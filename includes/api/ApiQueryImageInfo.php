<?php
/**
 * Copyright © 2006 Yuri Astrakhan "<Firstname><Lastname>@gmail.com"
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

namespace MediaWiki\Api;

use FormatMetadata;
use MediaTransformError;
use MediaWiki\FileRepo\File\File;
use MediaWiki\FileRepo\File\OldLocalFile;
use MediaWiki\FileRepo\RepoGroup;
use MediaWiki\Language\Language;
use MediaWiki\Linker\Linker;
use MediaWiki\MainConfigNames;
use MediaWiki\MediaWikiServices;
use MediaWiki\Page\File\BadFileLookup;
use MediaWiki\Specials\SpecialUpload;
use MediaWiki\Title\Title;
use UploadBase;
use Wikimedia\ParamValidator\ParamValidator;
use Wikimedia\ParamValidator\TypeDef\IntegerDef;

/**
 * A query action to get image information and upload history.
 *
 * @ingroup API
 */
class ApiQueryImageInfo extends ApiQueryBase {
	public const TRANSFORM_LIMIT = 50;
	/** @var int */
	private static $transformCount = 0;

	private RepoGroup $repoGroup;
	private Language $contentLanguage;
	private BadFileLookup $badFileLookup;

	/**
	 * @param ApiQuery $query
	 * @param string $moduleName
	 * @param string|RepoGroup|null $prefixOrRepoGroup
	 * @param RepoGroup|Language|null $repoGroupOrContentLanguage
	 * @param Language|BadFileLookup|null $contentLanguageOrBadFileLookup
	 * @param BadFileLookup|null $badFileLookupOrUnused
	 */
	public function __construct(
		ApiQuery $query,
		string $moduleName,
		$prefixOrRepoGroup = null,
		$repoGroupOrContentLanguage = null,
		$contentLanguageOrBadFileLookup = null,
		$badFileLookupOrUnused = null
	) {
		// We allow a subclass to override the prefix, to create a related API module.
		// The ObjectFactory is injecting the services without the prefix.
		if ( !is_string( $prefixOrRepoGroup ) ) {
			$prefix = 'ii';
			$repoGroup = $prefixOrRepoGroup;
			$contentLanguage = $repoGroupOrContentLanguage;
			$badFileLookup = $contentLanguageOrBadFileLookup;
			// $badFileLookupOrUnused is null in this case
		} else {
			$prefix = $prefixOrRepoGroup;
			$repoGroup = $repoGroupOrContentLanguage;
			$contentLanguage = $contentLanguageOrBadFileLookup;
			$badFileLookup = $badFileLookupOrUnused;
		}
		parent::__construct( $query, $moduleName, $prefix );
		// This class is extended and therefor fallback to global state - T259960
		$services = MediaWikiServices::getInstance();
		$this->repoGroup = $repoGroup ?? $services->getRepoGroup();
		$this->contentLanguage = $contentLanguage ?? $services->getContentLanguage();
		$this->badFileLookup = $badFileLookup ?? $services->getBadFileLookup();
	}

	public function execute() {
		$params = $this->extractRequestParams();

		$prop = array_fill_keys( $params['prop'], true );

		$scale = $this->getScale( $params );

		$opts = [
			'version' => $params['metadataversion'],
			'language' => $params['extmetadatalanguage'],
			'multilang' => $params['extmetadatamultilang'],
			'extmetadatafilter' => $params['extmetadatafilter'],
			'revdelUser' => $this->getAuthority(),
		];

		if ( isset( $params['badfilecontexttitle'] ) ) {
			$badFileContextTitle = Title::newFromText( $params['badfilecontexttitle'] );
			if ( !$badFileContextTitle || $badFileContextTitle->isExternal() ) {
				$p = $this->getModulePrefix();
				$this->dieWithError( [ 'apierror-bad-badfilecontexttitle', $p ], 'invalid-title' );
			}
		} else {
			$badFileContextTitle = null;
		}

		$pageIds = $this->getPageSet()->getGoodAndMissingTitlesByNamespace();
		if ( !empty( $pageIds[NS_FILE] ) ) {
			$titles = array_keys( $pageIds[NS_FILE] );
			asort( $titles ); // Ensure the order is always the same

			$fromTitle = null;
			if ( $params['continue'] !== null ) {
				$cont = $this->parseContinueParamOrDie( $params['continue'], [ 'string', 'string' ] );
				$fromTitle = $cont[0];
				$fromTimestamp = $cont[1];
				// Filter out any titles before $fromTitle
				foreach ( $titles as $key => $title ) {
					if ( $title < $fromTitle ) {
						unset( $titles[$key] );
					} else {
						break;
					}
				}
			}

			$performer = $this->getAuthority();
			$findTitles = array_map( static function ( $title ) use ( $performer ) {
				return [
					'title' => $title,
					'private' => $performer,
				];
			}, $titles );

			if ( $params['localonly'] ) {
				$images = $this->repoGroup->getLocalRepo()->findFiles( $findTitles );
			} else {
				$images = $this->repoGroup->findFiles( $findTitles );
			}

			$result = $this->getResult();
			foreach ( $titles as $title ) {
				$info = [];
				$pageId = $pageIds[NS_FILE][$title];
				// @phan-suppress-next-next-line PhanPossiblyUndeclaredVariable
				// $fromTimestamp declared when $fromTitle notnull
				$start = $title === $fromTitle ? $fromTimestamp : $params['start'];

				if ( !isset( $images[$title] ) ) {
					if ( isset( $prop['uploadwarning'] ) || isset( $prop['badfile'] ) ) {
						// uploadwarning and badfile need info about non-existing files
						$images[$title] = $this->repoGroup->getLocalRepo()->newFile( $title );
						// Doesn't exist, so set an empty image repository
						$info['imagerepository'] = '';
					} else {
						$result->addValue(
							[ 'query', 'pages', (int)$pageId ],
							'imagerepository', ''
						);
						// The above can't fail because it doesn't increase the result size
						continue;
					}
				}

				/** @var File $img */
				$img = $images[$title];

				if ( self::getTransformCount() >= self::TRANSFORM_LIMIT ) {
					if ( count( $pageIds[NS_FILE] ) == 1 ) {
						// See the 'the user is screwed' comment below
						$this->setContinueEnumParameter( 'start',
							$start ?? wfTimestamp( TS_ISO_8601, $img->getTimestamp() )
						);
					} else {
						$this->setContinueEnumParameter( 'continue',
							$this->getContinueStr( $img, $start ) );
					}
					break;
				}

				if ( !isset( $info['imagerepository'] ) ) {
					$info['imagerepository'] = $img->getRepoName();
				}
				if ( isset( $prop['badfile'] ) ) {
					$info['badfile'] = (bool)$this->badFileLookup->isBadFile( $title, $badFileContextTitle );
				}

				$fit = $result->addValue( [ 'query', 'pages' ], (int)$pageId, $info );
				if ( !$fit ) {
					if ( count( $pageIds[NS_FILE] ) == 1 ) {
						// The user is screwed. imageinfo can't be solely
						// responsible for exceeding the limit in this case,
						// so set a query-continue that just returns the same
						// thing again. When the violating queries have been
						// out-continued, the result will get through
						$this->setContinueEnumParameter( 'start',
							$start ?? wfTimestamp( TS_ISO_8601, $img->getTimestamp() )
						);
					} else {
						$this->setContinueEnumParameter( 'continue',
							$this->getContinueStr( $img, $start ) );
					}
					break;
				}

				// Check if we can make the requested thumbnail, and get transform parameters.
				$finalThumbParams = $this->mergeThumbParams( $img, $scale, $params['urlparam'] );

				// Parser::makeImage always sets a targetlang, usually based on the language
				// the content is in.  To support Parsoid's standalone mode, overload the badfilecontexttitle
				// to also set the targetlang based on the page language.  Don't add this unless we're
				// already scaling since a set $finalThumbParams usually expects a width.
				if ( $badFileContextTitle && $finalThumbParams ) {
					$finalThumbParams['targetlang'] = $badFileContextTitle->getPageLanguage()->getCode();
				}

				// Get information about the current version first
				// Check that the current version is within the start-end boundaries
				$gotOne = false;
				if (
					( $start === null || $img->getTimestamp() <= $start ) &&
					( $params['end'] === null || $img->getTimestamp() >= $params['end'] )
				) {
					$gotOne = true;

					$fit = $this->addPageSubItem( $pageId,
						static::getInfo( $img, $prop, $result,
							$finalThumbParams, $opts
						)
					);
					if ( !$fit ) {
						if ( count( $pageIds[NS_FILE] ) == 1 ) {
							// See the 'the user is screwed' comment above
							$this->setContinueEnumParameter( 'start',
								wfTimestamp( TS_ISO_8601, $img->getTimestamp() ) );
						} else {
							$this->setContinueEnumParameter( 'continue',
								$this->getContinueStr( $img ) );
						}
						break;
					}
				}

				// Now get the old revisions
				// Get one more to facilitate query-continue functionality
				$count = ( $gotOne ? 1 : 0 );
				$oldies = $img->getHistory( $params['limit'] - $count + 1, $start, $params['end'] );
				/** @var File $oldie */
				foreach ( $oldies as $oldie ) {
					if ( ++$count > $params['limit'] ) {
						// We've reached the extra one which shows that there are
						// additional pages to be had. Stop here...
						// Only set a query-continue if there was only one title
						if ( count( $pageIds[NS_FILE] ) == 1 ) {
							$this->setContinueEnumParameter( 'start',
								wfTimestamp( TS_ISO_8601, $oldie->getTimestamp() ) );
						}
						break;
					}
					$fit = self::getTransformCount() < self::TRANSFORM_LIMIT &&
						$this->addPageSubItem( $pageId,
							static::getInfo( $oldie, $prop, $result,
								$finalThumbParams, $opts
							)
						);
					if ( !$fit ) {
						if ( count( $pageIds[NS_FILE] ) == 1 ) {
							$this->setContinueEnumParameter( 'start',
								wfTimestamp( TS_ISO_8601, $oldie->getTimestamp() ) );
						} else {
							$this->setContinueEnumParameter( 'continue',
								$this->getContinueStr( $oldie ) );
						}
						break;
					}
				}
				if ( !$fit ) {
					break;
				}
			}
		}
	}

	/**
	 * From parameters, construct a 'scale' array
	 * @param array $params Parameters passed to api.
	 * @return array|null Key-val array of 'width' and 'height', or null
	 */
	public function getScale( $params ) {
		if ( $params['urlwidth'] != -1 ) {
			$scale = [];
			$scale['width'] = $params['urlwidth'];
			$scale['height'] = $params['urlheight'];
		} elseif ( $params['urlheight'] != -1 ) {
			// Height is specified but width isn't
			// Don't set $scale['width']; this signals mergeThumbParams() to fill it with the image's width
			$scale = [];
			$scale['height'] = $params['urlheight'];
		} elseif ( $params['urlparam'] ) {
			// Audio files might not have a width/height.
			$scale = [];
		} else {
			$scale = null;
		}

		return $scale;
	}

	/** Validate and merge scale parameters with handler thumb parameters, give error if invalid.
	 *
	 * We do this later than getScale, since we need the image
	 * to know which handler, since handlers can make their own parameters.
	 * @param File $image Image that params are for.
	 * @param array|null $thumbParams Thumbnail parameters from getScale
	 * @param string $otherParams String of otherParams (iiurlparam).
	 * @return array|null Array of parameters for transform.
	 */
	protected function mergeThumbParams( $image, $thumbParams, $otherParams ) {
		if ( $thumbParams === null ) {
			// No scaling requested
			return null;
		}
		if ( !isset( $thumbParams['width'] ) && isset( $thumbParams['height'] ) ) {
			// We want to limit only by height in this situation, so pass the
			// image's full width as the limiting width. But some file types
			// don't have a width of their own, so pick something arbitrary so
			// thumbnailing the default icon works.
			if ( $image->getWidth() <= 0 ) {
				$thumbParams['width'] =
					max( $this->getConfig()->get( MainConfigNames::ThumbLimits ) );
			} else {
				$thumbParams['width'] = $image->getWidth();
			}
		}

		if ( !$otherParams ) {
			$this->checkParameterNormalise( $image, $thumbParams );
			return $thumbParams;
		}
		$p = $this->getModulePrefix();

		$h = $image->getHandler();
		if ( !$h ) {
			$this->addWarning( [ 'apiwarn-nothumb-noimagehandler', wfEscapeWikiText( $image->getName() ) ] );

			return $thumbParams;
		}

		$paramList = $h->parseParamString( $otherParams );
		if ( !$paramList ) {
			// Just set a warning (instead of dieWithError), as in many cases
			// we could still render the image using width and height parameters,
			// and this type of thing could happen between different versions of
			// handlers.
			$this->addWarning( [ 'apiwarn-badurlparam', $p, wfEscapeWikiText( $image->getName() ) ] );
			$this->checkParameterNormalise( $image, $thumbParams );
			return $thumbParams;
		}

		if (
			isset( $paramList['width'] ) && isset( $thumbParams['width'] ) &&
			(int)$paramList['width'] != (int)$thumbParams['width']
		) {
			$this->addWarning(
				[ 'apiwarn-urlparamwidth', $p, $paramList['width'], $thumbParams['width'] ]
			);
		}

		foreach ( $paramList as $name => $value ) {
			if ( !$h->validateParam( $name, $value ) ) {
				$this->dieWithError(
					[ 'apierror-invalidurlparam', $p, wfEscapeWikiText( $name ), wfEscapeWikiText( $value ) ]
				);
			}
		}

		$finalParams = $thumbParams + $paramList;
		$this->checkParameterNormalise( $image, $finalParams );
		return $finalParams;
	}

	/**
	 * Verify that the final image parameters can be normalised.
	 *
	 * This doesn't use the normalised parameters, since $file->transform
	 * expects the pre-normalised parameters, but doing the normalisation
	 * allows us to catch certain error conditions early (such as missing
	 * required parameter).
	 *
	 * @param File $image
	 * @param array $finalParams List of parameters to transform image with
	 */
	protected function checkParameterNormalise( $image, $finalParams ) {
		$h = $image->getHandler();
		if ( !$h ) {
			return;
		}
		// Note: normaliseParams modifies the array in place, but we aren't interested
		// in the actual normalised version, only if we can actually normalise them,
		// so we use the functions scope to throw away the normalisations.
		if ( !$h->normaliseParams( $image, $finalParams ) ) {
			$this->dieWithError( [ 'apierror-urlparamnormal', wfEscapeWikiText( $image->getName() ) ] );
		}
	}

	/**
	 * Get result information for an image revision
	 *
	 * @param File $file
	 * @param array $prop Array of properties to get (in the keys)
	 * @param ApiResult $result
	 * @param array|null $thumbParams Containing 'width' and 'height' items, or null
	 * @param array|false|string $opts Options for data fetching.
	 *   This is an array consisting of the keys:
	 *    'version': The metadata version for the metadata option
	 *    'language': The language for extmetadata property
	 *    'multilang': Return all translations in extmetadata property
	 *    'revdelUser': Authority to use when checking whether to show revision-deleted fields.
	 * @return array
	 */
	public static function getInfo( $file, $prop, $result, $thumbParams = null, $opts = false ) {
		$anyHidden = false;

		$services = MediaWikiServices::getInstance();

		if ( !$opts || is_string( $opts ) ) {
			$opts = [
				'version' => $opts ?: 'latest',
				'language' => $services->getContentLanguage(),
				'multilang' => false,
				'extmetadatafilter' => [],
				'revdelUser' => null,
			];
		}
		$version = $opts['version'];
		$vals = [
			ApiResult::META_TYPE => 'assoc',
		];

		// Some information will be unavailable if the file does not exist. T221812
		$exists = $file->exists();

		// Timestamp is shown even if the file is revdelete'd in interface
		// so do same here.
		if ( isset( $prop['timestamp'] ) && $exists ) {
			$vals['timestamp'] = wfTimestamp( TS_ISO_8601, $file->getTimestamp() );
		}

		// Handle external callers who don't pass revdelUser
		if ( isset( $opts['revdelUser'] ) && $opts['revdelUser'] ) {
			$revdelUser = $opts['revdelUser'];
			$canShowField = static function ( $field ) use ( $file, $revdelUser ) {
				return $file->userCan( $field, $revdelUser );
			};
		} else {
			$canShowField = static function ( $field ) use ( $file ) {
				return !$file->isDeleted( $field );
			};
		}

		$user = isset( $prop['user'] );
		$userid = isset( $prop['userid'] );

		if ( ( $user || $userid ) && $exists ) {
			if ( $file->isDeleted( File::DELETED_USER ) ) {
				$vals['userhidden'] = true;
				$anyHidden = true;
			}
			if ( $canShowField( File::DELETED_USER ) ) {
				// Already checked if the field can be show
				$uploader = $file->getUploader( File::RAW );
				if ( $user ) {
					$vals['user'] = $uploader ? $uploader->getName() : '';
				}
				if ( $userid ) {
					$vals['userid'] = $uploader ? $uploader->getId() : 0;
				}
				if ( $uploader && $services->getUserNameUtils()->isTemp( $uploader->getName() ) ) {
					$vals['temp'] = true;
				}
				if ( $uploader && !$uploader->isRegistered() ) {
					$vals['anon'] = true;
				}
			}
		}

		// This is shown even if the file is revdelete'd in interface
		// so do same here.
		if ( ( isset( $prop['size'] ) || isset( $prop['dimensions'] ) ) && $exists ) {
			$vals['size'] = (int)$file->getSize();
			$vals['width'] = (int)$file->getWidth();
			$vals['height'] = (int)$file->getHeight();

			$pageCount = $file->pageCount();
			if ( $pageCount !== false ) {
				$vals['pagecount'] = $pageCount;
			}

			// length as in how many seconds long a video is.
			$length = $file->getLength();
			if ( $length ) {
				// Call it duration, because "length" can be ambiguous.
				$vals['duration'] = (float)$length;
			}
		}

		$pcomment = isset( $prop['parsedcomment'] );
		$comment = isset( $prop['comment'] );

		if ( ( $pcomment || $comment ) && $exists ) {
			if ( $file->isDeleted( File::DELETED_COMMENT ) ) {
				$vals['commenthidden'] = true;
				$anyHidden = true;
			}
			if ( $canShowField( File::DELETED_COMMENT ) ) {
				if ( $pcomment ) {
					$vals['parsedcomment'] = $services->getCommentFormatter()->format(
						$file->getDescription( File::RAW ), $file->getTitle() );
				}
				if ( $comment ) {
					$vals['comment'] = $file->getDescription( File::RAW );
				}
			}
		}

		$canonicaltitle = isset( $prop['canonicaltitle'] );
		$url = isset( $prop['url'] );
		$sha1 = isset( $prop['sha1'] );
		$meta = isset( $prop['metadata'] );
		$extmetadata = isset( $prop['extmetadata'] );
		$commonmeta = isset( $prop['commonmetadata'] );
		$mime = isset( $prop['mime'] );
		$mediatype = isset( $prop['mediatype'] );
		$archive = isset( $prop['archivename'] );
		$bitdepth = isset( $prop['bitdepth'] );
		$uploadwarning = isset( $prop['uploadwarning'] );

		if ( $uploadwarning ) {
			$vals['html'] = SpecialUpload::getExistsWarning( UploadBase::getExistsWarning( $file ) );
		}

		if ( $file->isDeleted( File::DELETED_FILE ) ) {
			$vals['filehidden'] = true;
			$anyHidden = true;
		}

		if ( $anyHidden && $file->isDeleted( File::DELETED_RESTRICTED ) ) {
			$vals['suppressed'] = true;
		}

		// Early return, tidier than indenting all following things one level
		if ( isset( $opts['revdelUser'] ) && $opts['revdelUser']
			&& !$file->userCan( File::DELETED_FILE, $opts['revdelUser'] )
		) {
			return $vals;
		} elseif ( $file->isDeleted( File::DELETED_FILE ) ) {
			return $vals;
		}

		if ( $canonicaltitle ) {
			$vals['canonicaltitle'] = $file->getTitle()->getPrefixedText();
		}

		if ( $url ) {
			$urlUtils = $services->getUrlUtils();

			if ( $exists ) {
				if ( $thumbParams !== null ) {
					$mto = $file->transform( $thumbParams );
					self::$transformCount++;
					if ( $mto && !$mto->isError() ) {
						$vals['thumburl'] = (string)$urlUtils->expand( $mto->getUrl(), PROTO_CURRENT );

						// T25834 - If the URLs are the same, we haven't resized it, so shouldn't give the wanted
						// thumbnail sizes for the thumbnail actual size
						if ( $mto->getUrl() !== $file->getUrl() ) {
							$vals['thumbwidth'] = (int)$mto->getWidth();
							$vals['thumbheight'] = (int)$mto->getHeight();
						} else {
							$vals['thumbwidth'] = (int)$file->getWidth();
							$vals['thumbheight'] = (int)$file->getHeight();
						}

						if ( isset( $prop['thumbmime'] ) && $file->getHandler() ) {
							[ , $mime ] = $file->getHandler()->getThumbType(
								$mto->getExtension(), $file->getMimeType(), $thumbParams );
							$vals['thumbmime'] = $mime;
						}
						// Report srcset parameters
						Linker::processResponsiveImages( $file, $mto, [
							'width' => $vals['thumbwidth'],
							'height' => $vals['thumbheight']
						] + $thumbParams );
						foreach ( $mto->responsiveUrls as $density => $url ) {
							$vals['responsiveUrls'][$density] = (string)$urlUtils->expand( $url, PROTO_CURRENT );
						}
					} elseif ( $mto && $mto->isError() ) {
						/** @var MediaTransformError $mto */
						'@phan-var MediaTransformError $mto';
						$vals['thumberror'] = $mto->toText();
					}
				}
				$vals['url'] = (string)$urlUtils->expand( $file->getFullUrl(), PROTO_CURRENT );
			}
			$vals['descriptionurl'] = (string)$urlUtils->expand( $file->getDescriptionUrl(), PROTO_CURRENT );

			$shortDescriptionUrl = $file->getDescriptionShortUrl();
			if ( $shortDescriptionUrl !== null ) {
				$vals['descriptionshorturl'] = (string)$urlUtils->expand( $shortDescriptionUrl, PROTO_CURRENT );
			}
		}

		if ( !$exists ) {
			$vals['filemissing'] = true;
		}

		if ( $sha1 && $exists ) {
			$vals['sha1'] = \Wikimedia\base_convert( $file->getSha1(), 36, 16, 40 );
		}

		if ( $meta && $exists ) {
			$metadata = $file->getMetadataArray();
			if ( $metadata && $version !== 'latest' ) {
				$metadata = $file->convertMetadataVersion( $metadata, $version );
			}
			$vals['metadata'] = $metadata ? static::processMetaData( $metadata, $result ) : null;
		}
		if ( $commonmeta && $exists ) {
			$metaArray = $file->getCommonMetaArray();
			$vals['commonmetadata'] = $metaArray ? static::processMetaData( $metaArray, $result ) : [];
		}

		if ( $extmetadata && $exists ) {
			// Note, this should return an array where all the keys
			// start with a letter, and all the values are strings.
			// Thus there should be no issue with format=xml.
			$format = new FormatMetadata;
			$format->setSingleLanguage( !$opts['multilang'] );
			// @phan-suppress-next-line PhanUndeclaredMethod
			$format->getContext()->setLanguage( $opts['language'] );
			$extmetaArray = $format->fetchExtendedMetadata( $file );
			if ( $opts['extmetadatafilter'] ) {
				$extmetaArray = array_intersect_key(
					$extmetaArray, array_fill_keys( $opts['extmetadatafilter'], true )
				);
			}
			$vals['extmetadata'] = $extmetaArray;
		}

		if ( $mime && $exists ) {
			$vals['mime'] = $file->getMimeType();
		}

		if ( $mediatype && $exists ) {
			$vals['mediatype'] = $file->getMediaType();
		}

		if ( $archive && $file->isOld() ) {
			/** @var OldLocalFile $file */
			'@phan-var OldLocalFile $file';
			$vals['archivename'] = $file->getArchiveName();
		}

		if ( $bitdepth && $exists ) {
			$vals['bitdepth'] = $file->getBitDepth();
		}

		return $vals;
	}

	/**
	 * Get the count of image transformations performed
	 *
	 * If this is >= TRANSFORM_LIMIT, you should probably stop processing images.
	 *
	 * @return int Count
	 */
	protected static function getTransformCount() {
		return self::$transformCount;
	}

	/**
	 * @param array $metadata
	 * @param ApiResult $result
	 * @return array
	 */
	public static function processMetaData( $metadata, $result ) {
		$retval = [];
		if ( is_array( $metadata ) ) {
			foreach ( $metadata as $key => $value ) {
				$r = [
					'name' => $key,
					ApiResult::META_BC_BOOLS => [ 'value' ],
				];
				if ( is_array( $value ) ) {
					$r['value'] = static::processMetaData( $value, $result );
				} else {
					$r['value'] = $value;
				}
				$retval[] = $r;
			}
		}
		ApiResult::setIndexedTagName( $retval, 'metadata' );

		return $retval;
	}

	/** @inheritDoc */
	public function getCacheMode( $params ) {
		if ( $this->userCanSeeRevDel() ) {
			return 'private';
		}

		return 'public';
	}

	/**
	 * @param File $img
	 * @param string|null $start
	 * @return string
	 */
	protected function getContinueStr( $img, $start = null ) {
		return $img->getOriginalTitle()->getDBkey() . '|' . ( $start ?? $img->getTimestamp() );
	}

	/** @inheritDoc */
	public function getAllowedParams() {
		return [
			'prop' => [
				ParamValidator::PARAM_ISMULTI => true,
				ParamValidator::PARAM_DEFAULT => 'timestamp|user',
				ParamValidator::PARAM_TYPE => static::getPropertyNames(),
				ApiBase::PARAM_HELP_MSG_PER_VALUE => static::getPropertyMessages(),
			],
			'limit' => [
				ParamValidator::PARAM_TYPE => 'limit',
				ParamValidator::PARAM_DEFAULT => 1,
				IntegerDef::PARAM_MIN => 1,
				IntegerDef::PARAM_MAX => ApiBase::LIMIT_BIG1,
				IntegerDef::PARAM_MAX2 => ApiBase::LIMIT_BIG2
			],
			'start' => [
				ParamValidator::PARAM_TYPE => 'timestamp'
			],
			'end' => [
				ParamValidator::PARAM_TYPE => 'timestamp'
			],
			'urlwidth' => [
				ParamValidator::PARAM_TYPE => 'integer',
				ParamValidator::PARAM_DEFAULT => -1,
				ApiBase::PARAM_HELP_MSG => [
					'apihelp-query+imageinfo-param-urlwidth',
					self::TRANSFORM_LIMIT,
				],
			],
			'urlheight' => [
				ParamValidator::PARAM_TYPE => 'integer',
				ParamValidator::PARAM_DEFAULT => -1
			],
			'metadataversion' => [
				ParamValidator::PARAM_TYPE => 'string',
				ParamValidator::PARAM_DEFAULT => '1',
			],
			'extmetadatalanguage' => [
				ParamValidator::PARAM_TYPE => 'string',
				ParamValidator::PARAM_DEFAULT =>
					$this->contentLanguage->getCode(),
			],
			'extmetadatamultilang' => [
				ParamValidator::PARAM_TYPE => 'boolean',
				ParamValidator::PARAM_DEFAULT => false,
			],
			'extmetadatafilter' => [
				ParamValidator::PARAM_TYPE => 'string',
				ParamValidator::PARAM_ISMULTI => true,
			],
			'urlparam' => [
				ParamValidator::PARAM_DEFAULT => '',
				ParamValidator::PARAM_TYPE => 'string',
			],
			'badfilecontexttitle' => [
				ParamValidator::PARAM_TYPE => 'string',
			],
			'continue' => [
				ApiBase::PARAM_HELP_MSG => 'api-help-param-continue',
			],
			'localonly' => [
				ParamValidator::PARAM_TYPE => 'boolean',
				ParamValidator::PARAM_DEFAULT => false,
			],
		];
	}

	/**
	 * Returns all possible parameters to iiprop
	 *
	 * @param array $filter List of properties to filter out
	 * @return array
	 */
	public static function getPropertyNames( $filter = [] ) {
		return array_keys( static::getPropertyMessages( $filter ) );
	}

	/**
	 * Returns messages for all possible parameters to iiprop
	 *
	 * @param array $filter List of properties to filter out
	 * @return array
	 */
	public static function getPropertyMessages( $filter = [] ) {
		return array_diff_key(
			[
				'timestamp' => 'apihelp-query+imageinfo-paramvalue-prop-timestamp',
				'user' => 'apihelp-query+imageinfo-paramvalue-prop-user',
				'userid' => 'apihelp-query+imageinfo-paramvalue-prop-userid',
				'comment' => 'apihelp-query+imageinfo-paramvalue-prop-comment',
				'parsedcomment' => 'apihelp-query+imageinfo-paramvalue-prop-parsedcomment',
				'canonicaltitle' => 'apihelp-query+imageinfo-paramvalue-prop-canonicaltitle',
				'url' => 'apihelp-query+imageinfo-paramvalue-prop-url',
				'size' => 'apihelp-query+imageinfo-paramvalue-prop-size',
				'dimensions' => 'apihelp-query+imageinfo-paramvalue-prop-dimensions',
				'sha1' => 'apihelp-query+imageinfo-paramvalue-prop-sha1',
				'mime' => 'apihelp-query+imageinfo-paramvalue-prop-mime',
				'thumbmime' => 'apihelp-query+imageinfo-paramvalue-prop-thumbmime',
				'mediatype' => 'apihelp-query+imageinfo-paramvalue-prop-mediatype',
				'metadata' => 'apihelp-query+imageinfo-paramvalue-prop-metadata',
				'commonmetadata' => 'apihelp-query+imageinfo-paramvalue-prop-commonmetadata',
				'extmetadata' => 'apihelp-query+imageinfo-paramvalue-prop-extmetadata',
				'archivename' => 'apihelp-query+imageinfo-paramvalue-prop-archivename',
				'bitdepth' => 'apihelp-query+imageinfo-paramvalue-prop-bitdepth',
				'uploadwarning' => 'apihelp-query+imageinfo-paramvalue-prop-uploadwarning',
				'badfile' => 'apihelp-query+imageinfo-paramvalue-prop-badfile',
			],
			array_fill_keys( $filter, true )
		);
	}

	/** @inheritDoc */
	protected function getExamplesMessages() {
		return [
			'action=query&titles=File:Albert%20Einstein%20Head.jpg&prop=imageinfo'
				=> 'apihelp-query+imageinfo-example-simple',
			'action=query&titles=File:Test.jpg&prop=imageinfo&iilimit=50&' .
				'iiend=2007-12-31T23:59:59Z&iiprop=timestamp|user|url'
				=> 'apihelp-query+imageinfo-example-dated',
		];
	}

	/** @inheritDoc */
	public function getHelpUrls() {
		return 'https://www.mediawiki.org/wiki/Special:MyLanguage/API:Imageinfo';
	}
}

/** @deprecated class alias since 1.43 */
class_alias( ApiQueryImageInfo::class, 'ApiQueryImageInfo' );
