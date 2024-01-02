<?php

/**
 * HTML form generation and submission handling, OOUI style.
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

use MediaWiki\Html\Html;
use MediaWiki\Linker\Linker;
use MediaWiki\Parser\Sanitizer;
use MediaWiki\Status\Status;

/**
 * Compact stacked vertical format for forms, implemented using OOUI widgets.
 *
 * @stable to extend
 */
class OOUIHTMLForm extends HTMLForm {
	private $oouiErrors;
	private $oouiWarnings;

	protected $displayFormat = 'ooui';

	public static function loadInputFromParameters( $fieldname, $descriptor,
		HTMLForm $parent = null
	) {
		$field = parent::loadInputFromParameters( $fieldname, $descriptor, $parent );
		$field->setShowEmptyLabel( false );
		return $field;
	}

	public function getButtons() {
		$buttons = '';

		if ( $this->mShowSubmit ) {
			$attribs = [
				'infusable' => true,
				'classes' => [ 'mw-htmlform-submit' ],
				'type' => 'submit',
				'label' => $this->getSubmitText(),
				'value' => $this->getSubmitText(),
				'flags' => $this->mSubmitFlags,
			];

			if ( isset( $this->mSubmitID ) ) {
				$attribs['id'] = $this->mSubmitID;
			}

			if ( isset( $this->mSubmitName ) ) {
				$attribs['name'] = $this->mSubmitName;
			}

			if ( isset( $this->mSubmitTooltip ) ) {
				$attribs += [
					'title' => Linker::titleAttrib( $this->mSubmitTooltip ),
					'accessKey' => Linker::accesskey( $this->mSubmitTooltip ),
				];
			}

			$buttons .= new OOUI\ButtonInputWidget( $attribs );
		}

		if ( $this->mShowReset ) {
			$buttons .= new OOUI\ButtonInputWidget( [
				'type' => 'reset',
				'label' => $this->msg( 'htmlform-reset' )->text(),
			] );
		}

		if ( $this->mShowCancel ) {
			$buttons .= new OOUI\ButtonWidget( [
				'label' => $this->msg( 'cancel' )->text(),
				'href' => $this->getCancelTargetURL(),
			] );
		}

		foreach ( $this->mButtons as $button ) {
			$attrs = [];

			// @phan-suppress-next-line PhanTypePossiblyInvalidDimOffset Always set in HTMLForm::addButton
			if ( $button['attribs'] ) {
				// @phan-suppress-next-line PhanTypePossiblyInvalidDimOffset Always set in HTMLForm::addButton
				$attrs += $button['attribs'];
			}

			if ( isset( $button['id'] ) ) {
				$attrs['id'] = $button['id'];
			}

			if ( isset( $button['label-message'] ) ) {
				$label = new OOUI\HtmlSnippet( $this->getMessage( $button['label-message'] )->parse() );
			} elseif ( isset( $button['label'] ) ) {
				$label = $button['label'];
			} elseif ( isset( $button['label-raw'] ) ) {
				$label = new OOUI\HtmlSnippet( $button['label-raw'] );
			} else {
				$label = $button['value'];
			}

			$attrs['classes'] = isset( $attrs['class'] ) ? (array)$attrs['class'] : [];

			$buttons .= new OOUI\ButtonInputWidget( [
				'type' => 'submit',
				'name' => $button['name'],
				'value' => $button['value'],
				'label' => $label,
				// @phan-suppress-next-line PhanTypePossiblyInvalidDimOffset Always set in HTMLForm::addButton
				'flags' => $button['flags'],
				// @phan-suppress-next-line PhanTypePossiblyInvalidDimOffset Always set in HTMLForm::addButton
				'framed' => $button['framed'],
			] + $attrs );
		}

		if ( !$buttons ) {
			return '';
		}

		return Html::rawElement( 'div',
			[ 'class' => 'mw-htmlform-submit-buttons' ], "\n$buttons" ) . "\n";
	}

	/**
	 * @inheritDoc
	 * @return OOUI\PanelLayout
	 */
	protected function wrapFieldSetSection( $legend, $section, $attributes, $isRoot ) {
		// to get a user visible effect, wrap the fieldset into a framed panel layout
		$layout = new OOUI\PanelLayout( [
			'expanded' => false,
			'padded' => true,
			'framed' => true,
		] );

		$layout->appendContent(
			new OOUI\FieldsetLayout( [
				'label' => $legend,
				'items' => [
					new OOUI\Widget( [
						'content' => new OOUI\HtmlSnippet( $section )
					] ),
				],
			] + $attributes )
		);
		return $layout;
	}

	/**
	 * @inheritDoc
	 * @return OOUI\FieldLayout HTML
	 */
	protected function formatField( HTMLFormField $field, $value ) {
		return $field->getOOUI( $value );
	}

	/**
	 * Put a form section together from the individual fields' HTML, merging it and wrapping.
	 * @param OOUI\FieldLayout[] $fieldsHtml Array of outputs from formatField()
	 * @param string $sectionName
	 * @param bool $anyFieldHasLabel Unused
	 * @return string HTML
	 */
	protected function formatSection( array $fieldsHtml, $sectionName, $anyFieldHasLabel ) {
		if ( !$fieldsHtml ) {
			// Do not generate any wrappers for empty sections. Sections may be empty if they only have
			// subsections, but no fields. A legend will still be added in wrapFieldSetSection().
			return '';
		}

		$html = implode( '', $fieldsHtml );

		if ( $sectionName ) {
			return Html::rawElement(
				'div',
				[ 'id' => Sanitizer::escapeIdForAttribute( $sectionName ) ],
				$html
			);
		}
		return $html;
	}

	/**
	 * @param string|array|Status $elements
	 * @param string $elementsType
	 * @return string
	 */
	public function getErrorsOrWarnings( $elements, $elementsType ) {
		if ( $elements === '' ) {
			return '';
		}

		if ( !in_array( $elementsType, [ 'error', 'warning' ], true ) ) {
			throw new DomainException( $elementsType . ' is not a valid type.' );
		}
		$errors = [];
		if ( $elements instanceof Status ) {
			if ( !$elements->isGood() ) {
				$errors = $elements->getErrorsByType( $elementsType );
				foreach ( $errors as &$error ) {
					// Input:  [ 'message' => 'foo', 'params' => [ 'a', 'b', 'c' ] ]
					// Output: [ 'foo', 'a', 'b', 'c' ]
					$error = $this->getMessage(
						array_merge( [ $error['message'] ], $error['params'] ) )->parse();
				}
			}
		} elseif ( $elementsType === 'error' ) {
			if ( is_array( $elements ) ) {
				foreach ( $elements as $error ) {
					$errors[] = $this->getMessage( $error )->parse();
				}
			} elseif ( $elements && $elements !== true ) {
				$errors[] = (string)$elements;
			}
		}

		foreach ( $errors as &$error ) {
			$error = new OOUI\HtmlSnippet( $error );
		}

		// Used in formatFormHeader()
		if ( $elementsType === 'error' ) {
			$this->oouiErrors = $errors;
		} else {
			$this->oouiWarnings = $errors;
		}
		return '';
	}

	public function getHeaderHtml( $section = null ) {
		if ( $section === null ) {
			// We handle $this->mHeader elsewhere, in getBody()
			return '';
		} else {
			return parent::getHeaderHtml( $section );
		}
	}

	protected function formatFormHeader() {
		if ( !( $this->mHeader || $this->oouiErrors || $this->oouiWarnings ) ) {
			return '';
		}
		$classes = [
			'mw-htmlform-ooui-header',
			...$this->oouiErrors ? [ 'mw-htmlform-ooui-header-errors' ] : [],
			...$this->oouiWarnings ? [ 'mw-htmlform-ooui-header-warnings' ] : [],
		];
		// if there's no header, don't create an (empty) LabelWidget, simply use a placeholder
		if ( $this->mHeader ) {
			$element = new OOUI\LabelWidget( [ 'label' => new OOUI\HtmlSnippet( $this->mHeader ) ] );
		} else {
			$element = new OOUI\Widget( [] );
		}
		return new OOUI\FieldLayout(
			$element,
			[
				'align' => 'top',
				'errors' => $this->oouiErrors,
				'notices' => $this->oouiWarnings,
				'classes' => $classes,
			]
		);
	}

	/**
	 * @inheritDoc
	 */
	public function getHTML( $submitResult ) {
		// Enable OOUI only if the form is going to be displayed (T352592).
		$this->getOutput()->enableOOUI();
		$this->getOutput()->addModuleStyles( 'mediawiki.htmlform.ooui.styles' );

		return parent::getHTML( $submitResult );
	}

	public function getBody() {
		return $this->formatFormHeader() . parent::getBody();
	}

	public function wrapForm( $html ) {
		if ( is_string( $this->mWrapperLegend ) ) {
			$phpClass = $this->mCollapsible ? CollapsibleFieldsetLayout::class : OOUI\FieldsetLayout::class;
			$content = new $phpClass( [
				'label' => $this->mWrapperLegend,
				'collapsed' => $this->mCollapsed,
				'items' => [
					new OOUI\Widget( [
						'content' => new OOUI\HtmlSnippet( $html )
					] ),
				],
			] + OOUI\Element::configFromHtmlAttributes( $this->mWrapperAttributes ) );
		} else {
			$content = new OOUI\HtmlSnippet( $html );
		}

		$form = new OOUI\FormLayout( $this->getFormAttributes() + [
			'classes' => [ 'mw-htmlform', 'mw-htmlform-ooui' ],
			'content' => $content,
		] );

		// Include a wrapper for style, if requested.
		$form = new OOUI\PanelLayout( [
			'classes' => [ 'mw-htmlform-ooui-wrapper' ],
			'expanded' => false,
			'padded' => $this->mWrapperLegend !== false,
			'framed' => $this->mWrapperLegend !== false,
			'content' => $form,
		] );

		return $form;
	}
}
