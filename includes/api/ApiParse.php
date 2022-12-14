<?php
/**
 * Copyright © 2007 Yuri Astrakhan "<Firstname><Lastname>@gmail.com"
 *
 * This program is free software; you can redistribute it and/or modify
 * it under the terms of the GNU General Public License as published by
 * the Free Software Foundation; either version 2 of the License, or
 * (at your option) any later version.
 *
 * This program is distributed in the hope that it will be useful,
 * but WITHOUT ANY WARRANTY; without even the implied warranty of
 * MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE. See the
 * GNU General Public License for more details.
 *
 * You should have received a copy of the GNU General Public License along
 * with this program; if not, write to the Free Software Foundation, Inc.,
 * 51 Franklin Street, Fifth Floor, Boston, MA 02110-1301, USA.
 * http://www.gnu.org/copyleft/gpl.html
 *
 * @file
 */

use MediaWiki\Cache\LinkBatchFactory;
use MediaWiki\CommentFormatter\CommentFormatter;
use MediaWiki\Content\IContentHandlerFactory;
use MediaWiki\Content\Renderer\ContentRenderer;
use MediaWiki\Content\Transform\ContentTransformer;
use MediaWiki\Languages\LanguageNameUtils;
use MediaWiki\Page\PageReference;
use MediaWiki\Page\WikiPageFactory;
use MediaWiki\Parser\ParserOutputFlags;
use MediaWiki\Request\FauxRequest;
use MediaWiki\Revision\RevisionLookup;
use MediaWiki\Revision\RevisionRecord;
use MediaWiki\Revision\SlotRecord;
use Wikimedia\ParamValidator\ParamValidator;
use Wikimedia\ParamValidator\TypeDef\EnumDef;

/**
 * @ingroup API
 */
class ApiParse extends ApiBase {

	/** @var string|false|null */
	private $section = null;

	/** @var Content|null */
	private $content = null;

	/** @var Content|null */
	private $pstContent = null;

	/** @var bool */
	private $contentIsDeleted = false, $contentIsSuppressed = false;

	/** @var RevisionLookup */
	private $revisionLookup;

	/** @var SkinFactory */
	private $skinFactory;

	/** @var LanguageNameUtils */
	private $languageNameUtils;

	/** @var LinkBatchFactory */
	private $linkBatchFactory;

	/** @var LinkCache */
	private $linkCache;

	/** @var IContentHandlerFactory */
	private $contentHandlerFactory;

	/** @var Parser */
	private $parser;

	/** @var WikiPageFactory */
	private $wikiPageFactory;

	/** @var ContentTransformer */
	private $contentTransformer;

	/** @var CommentFormatter */
	private $commentFormatter;

	/** @var ContentRenderer */
	private $contentRenderer;

	/**
	 * @param ApiMain $main
	 * @param string $action
	 * @param RevisionLookup $revisionLookup
	 * @param SkinFactory $skinFactory
	 * @param LanguageNameUtils $languageNameUtils
	 * @param LinkBatchFactory $linkBatchFactory
	 * @param LinkCache $linkCache
	 * @param IContentHandlerFactory $contentHandlerFactory
	 * @param Parser $parser
	 * @param WikiPageFactory $wikiPageFactory
	 * @param ContentRenderer $contentRenderer
	 * @param ContentTransformer $contentTransformer
	 * @param CommentFormatter $commentFormatter
	 */
	public function __construct(
		ApiMain $main,
		$action,
		RevisionLookup $revisionLookup,
		SkinFactory $skinFactory,
		LanguageNameUtils $languageNameUtils,
		LinkBatchFactory $linkBatchFactory,
		LinkCache $linkCache,
		IContentHandlerFactory $contentHandlerFactory,
		Parser $parser,
		WikiPageFactory $wikiPageFactory,
		ContentRenderer $contentRenderer,
		ContentTransformer $contentTransformer,
		CommentFormatter $commentFormatter
	) {
		parent::__construct( $main, $action );
		$this->revisionLookup = $revisionLookup;
		$this->skinFactory = $skinFactory;
		$this->languageNameUtils = $languageNameUtils;
		$this->linkBatchFactory = $linkBatchFactory;
		$this->linkCache = $linkCache;
		$this->contentHandlerFactory = $contentHandlerFactory;
		$this->parser = $parser;
		$this->wikiPageFactory = $wikiPageFactory;
		$this->contentRenderer = $contentRenderer;
		$this->contentTransformer = $contentTransformer;
		$this->commentFormatter = $commentFormatter;
	}

	private function getPoolKey(): string {
		$poolKey = WikiMap::getCurrentWikiDbDomain() . ':ApiParse:';
		if ( !$this->getUser()->isRegistered() ) {
			$poolKey .= 'a:' . $this->getUser()->getName();
		} else {
			$poolKey .= 'u:' . $this->getUser()->getId();
		}
		return $poolKey;
	}

	private function getContentParserOutput(
		Content $content,
		PageReference $page,
		$revId,
		ParserOptions $popts
	) {
		$worker = new PoolCounterWorkViaCallback( 'ApiParser', $this->getPoolKey(),
			[
				'doWork' => function () use ( $content, $page, $revId, $popts ) {
					return $this->contentRenderer->getParserOutput( $content, $page, $revId, $popts );
				},
				'error' => function () {
					$this->dieWithError( 'apierror-concurrency-limit' );
				},
			]
		);
		return $worker->execute();
	}

	private function getPageParserOutput(
		WikiPage $page,
		$revId,
		ParserOptions $popts,
		bool $suppressCache
	) {
		$worker = new PoolCounterWorkViaCallback( 'ApiParser', $this->getPoolKey(),
			[
				'doWork' => static function () use ( $page, $revId, $popts, $suppressCache ) {
					return $page->getParserOutput( $popts, $revId, $suppressCache );
				},
				'error' => function () {
					$this->dieWithError( 'apierror-concurrency-limit' );
				},
			]
		);
		return $worker->execute();
	}

	public function execute() {
		// The data is hot but user-dependent, like page views, so we set vary cookies
		$this->getMain()->setCacheMode( 'anon-public-user-private' );

		// Get parameters
		$params = $this->extractRequestParams();

		// No easy way to say that text and title or revid are allowed together
		// while the rest aren't, so just do it in three calls.
		$this->requireMaxOneParameter( $params, 'page', 'pageid', 'oldid', 'text' );
		$this->requireMaxOneParameter( $params, 'page', 'pageid', 'oldid', 'title' );
		$this->requireMaxOneParameter( $params, 'page', 'pageid', 'oldid', 'revid' );

		$text = $params['text'];
		$title = $params['title'];
		if ( $title === null ) {
			$titleProvided = false;
			// A title is needed for parsing, so arbitrarily choose one
			$title = 'API';
		} else {
			$titleProvided = true;
		}

		$page = $params['page'];
		$pageid = $params['pageid'];
		$oldid = $params['oldid'];

		$prop = array_fill_keys( $params['prop'], true );

		if ( isset( $params['section'] ) ) {
			$this->section = $params['section'];
			if ( !preg_match( '/^((T-)?\d+|new)$/', $this->section ) ) {
				$this->dieWithError( 'apierror-invalidsection' );
			}
		} else {
			$this->section = false;
		}

		// The parser needs $wgTitle to be set, apparently the
		// $title parameter in Parser::parse isn't enough *sigh*
		// TODO: Does this still need $wgTitle?
		global $wgTitle;

		$format = null;
		$redirValues = null;

		$needContent = isset( $prop['wikitext'] ) ||
			isset( $prop['parsetree'] ) || $params['generatexml'];

		// Return result
		$result = $this->getResult();

		if ( $oldid !== null || $pageid !== null || $page !== null ) {
			if ( $this->section === 'new' ) {
				$this->dieWithError( 'apierror-invalidparammix-parse-new-section', 'invalidparammix' );
			}
			if ( $oldid !== null ) {
				// Don't use the parser cache
				$rev = $this->revisionLookup->getRevisionById( $oldid );
				if ( !$rev ) {
					$this->dieWithError( [ 'apierror-nosuchrevid', $oldid ] );
				}

				$this->checkTitleUserPermissions( $rev->getPage(), 'read' );

				if ( !$rev->userCan( RevisionRecord::DELETED_TEXT, $this->getAuthority() ) ) {
					$this->dieWithError(
						[ 'apierror-permissiondenied', $this->msg( 'action-deletedtext' ) ]
					);
				}

				$revLinkTarget = $rev->getPageAsLinkTarget();
				$titleObj = Title::newFromLinkTarget( $revLinkTarget );
				$wgTitle = $titleObj;
				$pageObj = $this->wikiPageFactory->newFromTitle( $titleObj );
				[ $popts, $reset, $suppressCache ] = $this->makeParserOptions( $pageObj, $params );
				$p_result = $this->getParsedContent(
					$pageObj, $popts, $suppressCache, $pageid, $rev, $needContent
				);
			} else { // Not $oldid, but $pageid or $page
				if ( $params['redirects'] ) {
					$reqParams = [
						'redirects' => '',
					];
					$pageParams = [];
					if ( $pageid !== null ) {
						$reqParams['pageids'] = $pageid;
						$pageParams['pageid'] = $pageid;
					} else { // $page
						$reqParams['titles'] = $page;
						$pageParams['title'] = $page;
					}
					$req = new FauxRequest( $reqParams );
					$main = new ApiMain( $req );
					$pageSet = new ApiPageSet( $main );
					$pageSet->execute();
					$redirValues = $pageSet->getRedirectTitlesAsResult( $this->getResult() );

					foreach ( $pageSet->getRedirectTitles() as $title ) {
						$pageParams = [ 'title' => $title->getFullText() ];
					}
				} elseif ( $pageid !== null ) {
					$pageParams = [ 'pageid' => $pageid ];
				} else { // $page
					$pageParams = [ 'title' => $page ];
				}

				$pageObj = $this->getTitleOrPageId( $pageParams, 'fromdb' );
				$titleObj = $pageObj->getTitle();
				if ( !$titleObj->exists() ) {
					$this->dieWithError( 'apierror-missingtitle' );
				}

				$this->checkTitleUserPermissions( $titleObj, 'read' );
				$wgTitle = $titleObj;

				if ( isset( $prop['revid'] ) ) {
					$oldid = $pageObj->getLatest();
				}

				[ $popts, $reset, $suppressCache ] = $this->makeParserOptions( $pageObj, $params );
				$p_result = $this->getParsedContent(
					$pageObj, $popts, $suppressCache, $pageid, null, $needContent
				);
			}
		} else { // Not $oldid, $pageid, $page. Hence based on $text
			$model = $params['contentmodel'];
			$format = $params['contentformat'];

			$titleObj = Title::newFromText( $title );
			if ( !$titleObj || $titleObj->isExternal() ) {
				$this->dieWithError( [ 'apierror-invalidtitle', wfEscapeWikiText( $title ) ] );
			}
			$revid = $params['revid'];
			if ( $revid !== null ) {
				$rev = $this->revisionLookup->getRevisionById( $revid );
				if ( !$rev ) {
					$this->dieWithError( [ 'apierror-nosuchrevid', $revid ] );
				}
				$pTitleObj = $titleObj;
				$titleObj = Title::newFromLinkTarget( $rev->getPageAsLinkTarget() );
				if ( $titleProvided ) {
					if ( !$titleObj->equals( $pTitleObj ) ) {
						$this->addWarning( [ 'apierror-revwrongpage', $rev->getId(),
							wfEscapeWikiText( $pTitleObj->getPrefixedText() ) ] );
					}
				} else {
					// Consider the title derived from the revid as having
					// been provided.
					$titleProvided = true;
				}
			}
			$wgTitle = $titleObj;
			if ( $titleObj->canExist() ) {
				$pageObj = $this->wikiPageFactory->newFromTitle( $titleObj );
				[ $popts, $reset ] = $this->makeParserOptions( $pageObj, $params );
			} else {
				// Allow parsing wikitext in the context of special pages (T51477)
				$pageObj = null;
				$popts = ParserOptions::newFromContext( $this->getContext() );
				[ $popts, $reset ] = $this->tweakParserOptions( $popts, $titleObj, $params );
			}

			$textProvided = $text !== null;

			if ( !$textProvided ) {
				if ( $titleProvided && ( $prop || $params['generatexml'] ) ) {
					if ( $revid !== null ) {
						$this->addWarning( 'apiwarn-parse-revidwithouttext' );
					} else {
						$this->addWarning( 'apiwarn-parse-titlewithouttext' );
					}
				}
				// Prevent warning from ContentHandler::makeContent()
				$text = '';
			}

			// If we are parsing text, do not use the content model of the default
			// API title, but default to wikitext to keep BC.
			if ( $textProvided && !$titleProvided && $model === null ) {
				$model = CONTENT_MODEL_WIKITEXT;
				$this->addWarning( [ 'apiwarn-parse-nocontentmodel', $model ] );
			} elseif ( $model === null ) {
				$model = $titleObj->getContentModel();
			}

			$contentHandler = $this->contentHandlerFactory->getContentHandler( $model );
			// Not in the default format, check supported or not
			if ( $format && !$contentHandler->isSupportedFormat( $format ) ) {
				$this->dieWithError( [ 'apierror-badformat-generic', $format, $model ] );
			}

			try {
				$this->content = $contentHandler->unserializeContent( $text, $format );
			} catch ( MWContentSerializationException $ex ) {
				$this->dieWithException( $ex, [
					'wrap' => ApiMessage::create( 'apierror-contentserializationexception', 'parseerror' )
				] );
			}

			if ( $this->section !== false ) {
				if ( $this->section === 'new' ) {
					// Insert the section title above the content.
					if ( $params['sectiontitle'] !== null ) {
						$this->content = $this->content->addSectionHeader( $params['sectiontitle'] );
					}
				} else {
					$this->content = $this->getSectionContent( $this->content, $titleObj->getPrefixedText() );
				}
			}

			if ( $params['pst'] || $params['onlypst'] ) {
				$this->pstContent = $this->contentTransformer->preSaveTransform(
					$this->content,
					$titleObj,
					$this->getUser(),
					$popts
				);
			}
			if ( $params['onlypst'] ) {
				// Build a result and bail out
				$result_array = [];
				$result_array['text'] = $this->pstContent->serialize( $format );
				$result_array[ApiResult::META_BC_SUBELEMENTS][] = 'text';
				if ( isset( $prop['wikitext'] ) ) {
					$result_array['wikitext'] = $this->content->serialize( $format );
					$result_array[ApiResult::META_BC_SUBELEMENTS][] = 'wikitext';
				}
				if ( $params['summary'] !== null ||
					( $params['sectiontitle'] !== null && $this->section === 'new' )
				) {
					$result_array['parsedsummary'] = $this->formatSummary( $titleObj, $params );
					$result_array[ApiResult::META_BC_SUBELEMENTS][] = 'parsedsummary';
				}

				$result->addValue( null, $this->getModuleName(), $result_array );

				return;
			}

			// Not cached (save or load)
			if ( $params['pst'] ) {
				$p_result = $this->getContentParserOutput( $this->pstContent, $titleObj, $revid, $popts );
			} else {
				$p_result = $this->getContentParserOutput( $this->content, $titleObj, $revid, $popts );
			}
		}

		$result_array = [];

		$result_array['title'] = $titleObj->getPrefixedText();
		$result_array['pageid'] = $pageid ?: $titleObj->getArticleID();
		if ( $this->contentIsDeleted ) {
			$result_array['textdeleted'] = true;
		}
		if ( $this->contentIsSuppressed ) {
			$result_array['textsuppressed'] = true;
		}

		if ( isset( $params['useskin'] ) ) {
			$skin = $this->skinFactory->makeSkin( Skin::normalizeKey( $params['useskin'] ) );
		} else {
			$skin = null;
		}

		$outputPage = null;
		$context = null;
		if (
			$skin || isset( $prop['subtitle'] ) || isset( $prop['headhtml'] ) || isset( $prop['categorieshtml'] ) ||
			isset( $params['mobileformat'] )
		) {
			// Enabling the skin via 'useskin', 'subtitle', 'headhtml', or 'categorieshtml'
			// gets OutputPage and Skin involved, which (among others) applies
			// these hooks:
			// - ParserOutputHooks
			// - Hook: LanguageLinks
			// - Hook: SkinSubPageSubtitle
			// - Hook: OutputPageParserOutput
			// - Hook: OutputPageMakeCategoryLinks
			// - Hook: OutputPageBeforeHTML
			// HACK Adding the 'mobileformat' parameter *also* enables the skin, for compatibility with legacy
			// apps. This behavior should be considered deprecated so new users should not rely on this and
			// always use the "useskin" parameter to enable "skin mode".
			// Ideally this would be done with another hook so that MobileFrontend could enable skin mode, but
			// as this is just for a deprecated feature, we are hard-coding this param into core.
			$context = new DerivativeContext( $this->getContext() );
			$context->setTitle( $titleObj );

			if ( $pageObj ) {
				$context->setWikiPage( $pageObj );
			}
			// Some hooks only apply to pages when action=view, which this API
			// call is simulating.
			$context->setRequest( new FauxRequest( [ 'action' => 'view' ] ) );

			if ( $skin ) {
				// Use the skin specified by 'useskin'
				$context->setSkin( $skin );
				// Context clones the skin, refetch to stay in sync. (T166022)
				$skin = $context->getSkin();
			} else {
				// Make sure the context's skin refers to the context. Without this,
				// $outputPage->getSkin()->getOutput() !== $outputPage which
				// confuses some of the output.
				$context->setSkin( $context->getSkin() );
			}

			$outputPage = new OutputPage( $context );
			// Required for subtitle to appear
			$outputPage->setArticleFlag( true );

			$outputPage->addParserOutputMetadata( $p_result );
			if ( $this->content ) {
				$outputPage->addContentOverride( $titleObj, $this->content );
			}
			$context->setOutput( $outputPage );

			if ( $skin ) {
				// Based on OutputPage::output()
				$outputPage->loadSkinModules( $skin );
			}

			$this->getHookRunner()->onApiParseMakeOutputPage( $this, $outputPage );
		}

		if ( $oldid !== null ) {
			$result_array['revid'] = (int)$oldid;
		}

		if ( $params['redirects'] && $redirValues !== null ) {
			$result_array['redirects'] = $redirValues;
		}

		if ( isset( $prop['text'] ) ) {
			$skin = $context ? $context->getSkin() : null;
			$skinOptions = $skin ? $skin->getOptions() : [
				'toc' => true,
			];
			$result_array['text'] = $p_result->getText( [
				'allowTOC' => !$params['disabletoc'],
				'injectTOC' => $skinOptions['toc'],
				'enableSectionEditLinks' => !$params['disableeditsection'],
				'wrapperDivClass' => $params['wrapoutputclass'],
				'deduplicateStyles' => !$params['disablestylededuplication'],
				'skin' => $skin,
				'includeDebugInfo' => !$params['disablepp'] && !$params['disablelimitreport']
			] );
			$result_array[ApiResult::META_BC_SUBELEMENTS][] = 'text';
			if ( $context ) {
				$this->getHookRunner()->onOutputPageBeforeHTML( $context->getOutput(), $result_array['text'] );
			}
		}

		if ( $params['summary'] !== null ||
			( $params['sectiontitle'] !== null && $this->section === 'new' )
		) {
			$result_array['parsedsummary'] = $this->formatSummary( $titleObj, $params );
			$result_array[ApiResult::META_BC_SUBELEMENTS][] = 'parsedsummary';
		}

		if ( isset( $prop['langlinks'] ) ) {
			if ( $skin ) {
				$langlinks = $outputPage->getLanguageLinks();
			} else {
				$langlinks = $p_result->getLanguageLinks();
				// The deprecated 'effectivelanglinks' option depredates OutputPage
				// support via 'useskin'. If not already applied, then run just this
				// one hook of OutputPage::addParserOutputMetadata here.
				if ( $params['effectivelanglinks'] ) {
					$linkFlags = [];
					$this->getHookRunner()->onLanguageLinks( $titleObj, $langlinks, $linkFlags );
				}
			}

			$result_array['langlinks'] = $this->formatLangLinks( $langlinks );
		}
		if ( isset( $prop['categories'] ) ) {
			$result_array['categories'] = $this->formatCategoryLinks( $p_result->getCategories() );
		}
		if ( isset( $prop['categorieshtml'] ) ) {
			$result_array['categorieshtml'] = $outputPage->getSkin()->getCategories();
			$result_array[ApiResult::META_BC_SUBELEMENTS][] = 'categorieshtml';
		}
		if ( isset( $prop['links'] ) ) {
			$result_array['links'] = $this->formatLinks( $p_result->getLinks() );
		}
		if ( isset( $prop['templates'] ) ) {
			$result_array['templates'] = $this->formatLinks( $p_result->getTemplates() );
		}
		if ( isset( $prop['images'] ) ) {
			$result_array['images'] = array_keys( $p_result->getImages() );
		}
		if ( isset( $prop['externallinks'] ) ) {
			$result_array['externallinks'] = array_keys( $p_result->getExternalLinks() );
		}
		if ( isset( $prop['sections'] ) ) {
			$result_array['sections'] = $p_result->getSections();
			$result_array['showtoc'] = $p_result->getOutputFlag( ParserOutputFlags::SHOW_TOC );
		}
		if ( isset( $prop['parsewarnings'] ) ) {
			$result_array['parsewarnings'] = $p_result->getWarnings();
		}
		if ( isset( $prop['parsewarningshtml'] ) ) {
			$warnings = $p_result->getWarnings();
			$warningsHtml = array_map( static function ( $warning ) {
				return ( new RawMessage( '$1', [ $warning ] ) )->parse();
			}, $warnings );
			$result_array['parsewarningshtml'] = $warningsHtml;
		}

		if ( isset( $prop['displaytitle'] ) ) {
			$result_array['displaytitle'] = $p_result->getDisplayTitle() !== false
				? $p_result->getDisplayTitle()
				: htmlspecialchars( $titleObj->getPrefixedText(), ENT_NOQUOTES );
		}

		if ( isset( $prop['subtitle'] ) ) {
			// Get the subtitle without its container element to support UI refreshing
			$result_array['subtitle'] = $context->getSkin()->prepareSubtitle( false );
		}

		if ( isset( $prop['headitems'] ) ) {
			if ( $skin ) {
				$result_array['headitems'] = $this->formatHeadItems( $outputPage->getHeadItemsArray() );
			} else {
				$result_array['headitems'] = $this->formatHeadItems( $p_result->getHeadItems() );
			}
		}

		if ( isset( $prop['headhtml'] ) ) {
			$result_array['headhtml'] = $outputPage->headElement( $context->getSkin() );
			$result_array[ApiResult::META_BC_SUBELEMENTS][] = 'headhtml';
		}

		if ( isset( $prop['modules'] ) ) {
			if ( $skin ) {
				$result_array['modules'] = $outputPage->getModules();
				// Deprecated since 1.32 (T188689)
				$result_array['modulescripts'] = [];
				$result_array['modulestyles'] = $outputPage->getModuleStyles();
			} else {
				$result_array['modules'] = array_values( array_unique( $p_result->getModules() ) );
				// Deprecated since 1.32 (T188689)
				$result_array['modulescripts'] = [];
				$result_array['modulestyles'] = array_values( array_unique( $p_result->getModuleStyles() ) );
			}
		}

		if ( isset( $prop['jsconfigvars'] ) ) {
			$showStrategyKeys = (bool)( $params['showstrategykeys'] );
			$jsconfigvars = $skin ? $outputPage->getJsConfigVars() : $p_result->getJsConfigVars( $showStrategyKeys );
			$result_array['jsconfigvars'] = ApiResult::addMetadataToResultVars( $jsconfigvars );
		}

		if ( isset( $prop['encodedjsconfigvars'] ) ) {
			$jsconfigvars = $skin ? $outputPage->getJsConfigVars() : $p_result->getJsConfigVars();
			$result_array['encodedjsconfigvars'] = FormatJson::encode(
				$jsconfigvars,
				false,
				FormatJson::ALL_OK
			);
			$result_array[ApiResult::META_SUBELEMENTS][] = 'encodedjsconfigvars';
		}

		if ( isset( $prop['modules'] ) &&
			!isset( $prop['jsconfigvars'] ) && !isset( $prop['encodedjsconfigvars'] ) ) {
			$this->addWarning( 'apiwarn-moduleswithoutvars' );
		}

		if ( isset( $prop['indicators'] ) ) {
			if ( $skin ) {
				$result_array['indicators'] = (array)$outputPage->getIndicators();
			} else {
				$result_array['indicators'] = (array)$p_result->getIndicators();
			}
			ApiResult::setArrayType( $result_array['indicators'], 'BCkvp', 'name' );
		}

		if ( isset( $prop['iwlinks'] ) ) {
			$result_array['iwlinks'] = $this->formatIWLinks( $p_result->getInterwikiLinks() );
		}

		if ( isset( $prop['wikitext'] ) ) {
			$result_array['wikitext'] = $this->content->serialize( $format );
			$result_array[ApiResult::META_BC_SUBELEMENTS][] = 'wikitext';
			// @phan-suppress-next-line PhanImpossibleTypeComparison
			if ( $this->pstContent !== null ) {
				$result_array['psttext'] = $this->pstContent->serialize( $format );
				$result_array[ApiResult::META_BC_SUBELEMENTS][] = 'psttext';
			}
		}
		if ( isset( $prop['properties'] ) ) {
			$result_array['properties'] = $p_result->getPageProperties();
			ApiResult::setArrayType( $result_array['properties'], 'BCkvp', 'name' );
		}

		if ( isset( $prop['limitreportdata'] ) ) {
			$result_array['limitreportdata'] =
				$this->formatLimitReportData( $p_result->getLimitReportData() );
		}
		if ( isset( $prop['limitreporthtml'] ) ) {
			$result_array['limitreporthtml'] = EditPage::getPreviewLimitReport( $p_result );
			$result_array[ApiResult::META_BC_SUBELEMENTS][] = 'limitreporthtml';
		}

		if ( isset( $prop['parsetree'] ) || $params['generatexml'] ) {
			if ( $this->content->getModel() != CONTENT_MODEL_WIKITEXT ) {
				$this->dieWithError( 'apierror-parsetree-notwikitext', 'notwikitext' );
			}

			$this->parser->startExternalParse( $titleObj, $popts, Parser::OT_PREPROCESS );
			// @phan-suppress-next-line PhanUndeclaredMethod
			$xml = $this->parser->preprocessToDom( $this->content->getText() )->__toString();
			$result_array['parsetree'] = $xml;
			$result_array[ApiResult::META_BC_SUBELEMENTS][] = 'parsetree';
		}

		$result_mapping = [
			'redirects' => 'r',
			'langlinks' => 'll',
			'categories' => 'cl',
			'links' => 'pl',
			'templates' => 'tl',
			'images' => 'img',
			'externallinks' => 'el',
			'iwlinks' => 'iw',
			'sections' => 's',
			'headitems' => 'hi',
			'modules' => 'm',
			'indicators' => 'ind',
			'modulescripts' => 'm',
			'modulestyles' => 'm',
			'properties' => 'pp',
			'limitreportdata' => 'lr',
			'parsewarnings' => 'pw',
			'parsewarningshtml' => 'pw',
		];
		$this->setIndexedTagNames( $result_array, $result_mapping );
		$result->addValue( null, $this->getModuleName(), $result_array );
	}

	/**
	 * Constructs a ParserOptions object
	 *
	 * @param WikiPage $pageObj
	 * @param array $params
	 *
	 * @return array [ ParserOptions, ScopedCallback, bool $suppressCache ]
	 */
	private function makeParserOptions( WikiPage $pageObj, array $params ) {
		$popts = $pageObj->makeParserOptions( $this->getContext() );
		$popts->setRenderReason( 'api-parse' );
		return $this->tweakParserOptions( $popts, $pageObj->getTitle(), $params );
	}

	/**
	 * Tweaks a ParserOptions object
	 *
	 * @param ParserOptions $popts
	 * @param Title $title
	 * @param array $params
	 *
	 * @return array [ ParserOptions, ScopedCallback, bool $suppressCache ]
	 */
	private function tweakParserOptions( ParserOptions $popts, Title $title, array $params ) {
		$popts->setIsPreview( $params['preview'] || $params['sectionpreview'] );
		$popts->setIsSectionPreview( $params['sectionpreview'] );

		if ( $params['wrapoutputclass'] !== '' ) {
			$popts->setWrapOutputClass( $params['wrapoutputclass'] );
		}

		$reset = null;
		$suppressCache = false;
		$this->getHookRunner()->onApiMakeParserOptions( $popts, $title,
			$params, $this, $reset, $suppressCache );

		return [ $popts, $reset, $suppressCache ];
	}

	/**
	 * @param WikiPage $page
	 * @param ParserOptions $popts
	 * @param bool $suppressCache
	 * @param int $pageId
	 * @param RevisionRecord|null $rev
	 * @param bool $getContent
	 * @return ParserOutput
	 */
	private function getParsedContent(
		WikiPage $page, $popts, $suppressCache, $pageId, $rev, $getContent
	) {
		$revId = $rev ? $rev->getId() : null;
		$isDeleted = $rev && $rev->isDeleted( RevisionRecord::DELETED_TEXT );

		if ( $getContent || $this->section !== false || $isDeleted ) {
			if ( $rev ) {
				$this->content = $rev->getContent(
					SlotRecord::MAIN, RevisionRecord::FOR_THIS_USER, $this->getAuthority()
				);
				if ( !$this->content ) {
					$this->dieWithError( [ 'apierror-missingcontent-revid', $revId ] );
				}
			} else {
				$this->content = $page->getContent( RevisionRecord::FOR_THIS_USER, $this->getAuthority() );
				if ( !$this->content ) {
					$this->dieWithError( [ 'apierror-missingcontent-pageid', $page->getId() ] );
				}
			}
			$this->contentIsDeleted = $isDeleted;
			$this->contentIsSuppressed = $rev &&
				$rev->isDeleted( RevisionRecord::DELETED_TEXT | RevisionRecord::DELETED_RESTRICTED );
		}

		if ( $this->section !== false ) {
			$this->content = $this->getSectionContent(
				$this->content,
				$pageId === null ? $page->getTitle()->getPrefixedText() : $this->msg( 'pageid', $pageId )
			);
			return $this->getContentParserOutput( $this->content, $page->getTitle(), $revId, $popts );
		}

		if ( $isDeleted ) {
			// getParserOutput can't do revdeled revisions

			$pout = $this->getContentParserOutput( $this->content, $page->getTitle(), $revId, $popts );
		} else {
			// getParserOutput will save to Parser cache if able
			$pout = $this->getPageParserOutput( $page, $revId, $popts, $suppressCache );
		}
		if ( !$pout ) {
			// @codeCoverageIgnoreStart
			$this->dieWithError( [ 'apierror-nosuchrevid', $revId ?: $page->getLatest() ] );
			// @codeCoverageIgnoreEnd
		}

		return $pout;
	}

	/**
	 * Extract the requested section from the given Content
	 *
	 * @param Content $content
	 * @param string|Message $what Identifies the content in error messages, e.g. page title.
	 * @return Content
	 */
	private function getSectionContent( Content $content, $what ) {
		// Not cached (save or load)
		$section = $content->getSection( $this->section );
		if ( $section === false ) {
			$this->dieWithError( [ 'apierror-nosuchsection-what', $this->section, $what ], 'nosuchsection' );
		}
		if ( $section === null ) {
			$this->dieWithError( [ 'apierror-sectionsnotsupported-what', $what ], 'nosuchsection' );
		}

		// @phan-suppress-next-line PhanTypeMismatchReturnNullable T240141
		return $section;
	}

	/**
	 * This mimics the behavior of EditPage in formatting a summary
	 *
	 * @param Title $title of the page being parsed
	 * @param array $params The API parameters of the request
	 * @return string HTML
	 */
	private function formatSummary( $title, $params ) {
		$summary = $params['summary'] ?? '';
		$sectionTitle = $params['sectiontitle'] ?? '';

		if ( $this->section === 'new' && ( $sectionTitle === '' || $summary === '' ) ) {
			if ( $sectionTitle !== '' ) {
				$summary = $params['sectiontitle'];
			}
			if ( $summary !== '' ) {
				$summary = $this->msg( 'newsectionsummary' )
					->rawParams( $this->parser->stripSectionName( $summary ) )
					->inContentLanguage()->text();
			}
		}
		return $this->commentFormatter->format( $summary, $title, $this->section === 'new' );
	}

	private function formatLangLinks( $links ) {
		$result = [];
		foreach ( $links as $link ) {
			$entry = [];
			$bits = explode( ':', $link, 2 );
			$title = Title::newFromText( $link );

			$entry['lang'] = $bits[0];
			if ( $title ) {
				$entry['url'] = wfExpandUrl( $title->getFullURL(), PROTO_CURRENT );
				// localised language name in 'uselang' language
				$entry['langname'] = $this->languageNameUtils->getLanguageName(
					$title->getInterwiki(),
					$this->getLanguage()->getCode()
				);

				// native language name
				$entry['autonym'] = $this->languageNameUtils->getLanguageName( $title->getInterwiki() );
			}
			ApiResult::setContentValue( $entry, 'title', $bits[1] );
			$result[] = $entry;
		}

		return $result;
	}

	private function formatCategoryLinks( $links ) {
		$result = [];

		if ( !$links ) {
			return $result;
		}

		// Fetch hiddencat property
		$lb = $this->linkBatchFactory->newLinkBatch();
		$lb->setArray( [ NS_CATEGORY => $links ] );
		$db = $this->getDB();
		$res = $db->newSelectQueryBuilder()
			->select( [ 'page_title', 'pp_propname' ] )
			->from( 'page' )
			->where( $lb->constructSet( 'page', $db ) )
			->leftJoin( 'page_props', null, [ 'pp_propname' => 'hiddencat', 'pp_page = page_id' ] )
			->caller( __METHOD__ )
			->fetchResultSet();
		$hiddencats = [];
		foreach ( $res as $row ) {
			$hiddencats[$row->page_title] = isset( $row->pp_propname );
		}

		foreach ( $links as $link => $sortkey ) {
			$entry = [];
			$entry['sortkey'] = $sortkey;
			// array keys will cast numeric category names to ints, so cast back to string
			ApiResult::setContentValue( $entry, 'category', (string)$link );
			if ( !isset( $hiddencats[$link] ) ) {
				$entry['missing'] = true;

				// We already know the link doesn't exist in the database, so
				// tell LinkCache that before calling $title->isKnown().
				$title = Title::makeTitle( NS_CATEGORY, $link );
				$this->linkCache->addBadLinkObj( $title );
				if ( $title->isKnown() ) {
					$entry['known'] = true;
				}
			} elseif ( $hiddencats[$link] ) {
				$entry['hidden'] = true;
			}
			$result[] = $entry;
		}

		return $result;
	}

	private function formatLinks( $links ) {
		$result = [];
		foreach ( $links as $ns => $nslinks ) {
			foreach ( $nslinks as $title => $id ) {
				$entry = [];
				$entry['ns'] = $ns;
				ApiResult::setContentValue( $entry, 'title', Title::makeTitle( $ns, $title )->getFullText() );
				$entry['exists'] = $id != 0;
				$result[] = $entry;
			}
		}

		return $result;
	}

	private function formatIWLinks( $iw ) {
		$result = [];
		foreach ( $iw as $prefix => $titles ) {
			foreach ( array_keys( $titles ) as $title ) {
				$entry = [];
				$entry['prefix'] = $prefix;

				$title = Title::newFromText( "{$prefix}:{$title}" );
				if ( $title ) {
					$entry['url'] = wfExpandUrl( $title->getFullURL(), PROTO_CURRENT );
				}

				ApiResult::setContentValue( $entry, 'title', $title->getFullText() );
				$result[] = $entry;
			}
		}

		return $result;
	}

	private function formatHeadItems( $headItems ) {
		$result = [];
		foreach ( $headItems as $tag => $content ) {
			$entry = [];
			$entry['tag'] = $tag;
			ApiResult::setContentValue( $entry, 'content', $content );
			$result[] = $entry;
		}

		return $result;
	}

	private function formatLimitReportData( $limitReportData ) {
		$result = [];

		foreach ( $limitReportData as $name => $value ) {
			$entry = [];
			$entry['name'] = $name;
			if ( !is_array( $value ) ) {
				$value = [ $value ];
			}
			ApiResult::setIndexedTagNameRecursive( $value, 'param' );
			$entry = array_merge( $entry, $value );
			$result[] = $entry;
		}

		return $result;
	}

	private function setIndexedTagNames( &$array, $mapping ) {
		foreach ( $mapping as $key => $name ) {
			if ( isset( $array[$key] ) ) {
				ApiResult::setIndexedTagName( $array[$key], $name );
			}
		}
	}

	public function getAllowedParams() {
		return [
			'title' => null,
			'text' => [
				ParamValidator::PARAM_TYPE => 'text',
			],
			'revid' => [
				ParamValidator::PARAM_TYPE => 'integer',
			],
			'summary' => null,
			'page' => null,
			'pageid' => [
				ParamValidator::PARAM_TYPE => 'integer',
			],
			'redirects' => false,
			'oldid' => [
				ParamValidator::PARAM_TYPE => 'integer',
			],
			'prop' => [
				ParamValidator::PARAM_DEFAULT => 'text|langlinks|categories|links|templates|' .
					'images|externallinks|sections|revid|displaytitle|iwlinks|' .
					'properties|parsewarnings',
				ParamValidator::PARAM_ISMULTI => true,
				ParamValidator::PARAM_TYPE => [
					'text',
					'langlinks',
					'categories',
					'categorieshtml',
					'links',
					'templates',
					'images',
					'externallinks',
					'sections',
					'revid',
					'displaytitle',
					'subtitle',
					'headhtml',
					'modules',
					'jsconfigvars',
					'encodedjsconfigvars',
					'indicators',
					'iwlinks',
					'wikitext',
					'properties',
					'limitreportdata',
					'limitreporthtml',
					'parsetree',
					'parsewarnings',
					'parsewarningshtml',
					'headitems',
				],
				ApiBase::PARAM_HELP_MSG_PER_VALUE => [
					'parsetree' => [ 'apihelp-parse-paramvalue-prop-parsetree', CONTENT_MODEL_WIKITEXT ],
				],
				EnumDef::PARAM_DEPRECATED_VALUES => [
					'headitems' => 'apiwarn-deprecation-parse-headitems',
				],
			],
			'wrapoutputclass' => 'mw-parser-output',
			'pst' => false,
			'onlypst' => false,
			'effectivelanglinks' => [
				ParamValidator::PARAM_DEFAULT => false,
				ParamValidator::PARAM_DEPRECATED => true,
			],
			'section' => null,
			'sectiontitle' => [
				ParamValidator::PARAM_TYPE => 'string',
			],
			'disablepp' => [
				ParamValidator::PARAM_DEFAULT => false,
				ParamValidator::PARAM_DEPRECATED => true,
			],
			'disablelimitreport' => false,
			'disableeditsection' => false,
			'disablestylededuplication' => false,
			'showstrategykeys' => false,
			'generatexml' => [
				ParamValidator::PARAM_DEFAULT => false,
				ApiBase::PARAM_HELP_MSG => [
					'apihelp-parse-param-generatexml', CONTENT_MODEL_WIKITEXT
				],
				ParamValidator::PARAM_DEPRECATED => true,
			],
			'preview' => false,
			'sectionpreview' => false,
			'disabletoc' => false,
			'useskin' => [
				// T237856; We use all installed skins here to allow hidden (but usable) skins
				// to continue working correctly with some features such as Live Preview
				ParamValidator::PARAM_TYPE => array_keys( $this->skinFactory->getInstalledSkins() ),
			],
			'contentformat' => [
				ParamValidator::PARAM_TYPE => $this->contentHandlerFactory->getAllContentFormats(),
			],
			'contentmodel' => [
				ParamValidator::PARAM_TYPE => $this->contentHandlerFactory->getContentModels(),
			],
		];
	}

	protected function getExamplesMessages() {
		return [
			'action=parse&page=Project:Sandbox'
				=> 'apihelp-parse-example-page',
			'action=parse&text={{Project:Sandbox}}&contentmodel=wikitext'
				=> 'apihelp-parse-example-text',
			'action=parse&text={{PAGENAME}}&title=Test'
				=> 'apihelp-parse-example-texttitle',
			'action=parse&summary=Some+[[link]]&prop='
				=> 'apihelp-parse-example-summary',
		];
	}

	public function getHelpUrls() {
		return 'https://www.mediawiki.org/wiki/Special:MyLanguage/API:Parsing_wikitext#parse';
	}
}
