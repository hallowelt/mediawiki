<?php

namespace MediaWiki\HTMLForm\Field;

use MediaWiki\Html\Html;
use MediaWiki\HTMLForm\HTMLFormField;
use MediaWiki\HTMLForm\HTMLFormFieldRequiredOptionsException;
use MediaWiki\HTMLForm\HTMLNestedFilterable;
use MediaWiki\Request\WebRequest;

/**
 * A checkbox matrix
 * Operates similarly to HTMLMultiSelectField, but instead of using an array of
 * options, uses an array of rows and an array of columns to dynamically
 * construct a matrix of options. The tags used to identify a particular cell
 * are of the form "columnName-rowName"
 *
 * Options:
 *   - columns
 *     - Required associative array mapping column labels (as HTML) to their tags.
 *   - rows
 *     - Required associative array mapping row labels (as HTML) to their tags.
 *   - force-options-on
 *     - Array of column-row tags to be displayed as enabled but unavailable to change.
 *   - force-options-off
 *     - Array of column-row tags to be displayed as disabled but unavailable to change.
 *   - tooltips
 *     - Optional associative array mapping row labels to tooltips (as text, will be escaped).
 *   - tooltips-html
 *     - Optional associative array mapping row labels to tooltips (as HTML).
 *       Only used by OOUI form fields. Takes precedence when supported, so to support both
 *       OOUI and non-OOUI forms, you can set both.
 *   - tooltip-class
 *     - Optional CSS class used on tooltip container span. Defaults to mw-icon-question.
 *       Not used by OOUI form fields.
 *
 * @stable to extend
 */
class HTMLCheckMatrix extends HTMLFormField implements HTMLNestedFilterable {
	private const REQUIRED_PARAMS = [
		// Required by underlying HTMLFormField
		'fieldname',
		// Required by HTMLCheckMatrix
		'rows',
		'columns'
	];

	/**
	 * @stable to call
	 * @inheritDoc
	 */
	public function __construct( $params ) {
		$missing = array_diff( self::REQUIRED_PARAMS, array_keys( $params ) );
		if ( $missing ) {
			throw new HTMLFormFieldRequiredOptionsException( $this, $missing );
		}

		// The label should always be on a separate line above the options
		$params['vertical-label'] = true;
		parent::__construct( $params );
	}

	/** @inheritDoc */
	public function validate( $value, $alldata ) {
		$rows = $this->mParams['rows'];
		$columns = $this->mParams['columns'];

		// Make sure user-defined validation callback is run
		$p = parent::validate( $value, $alldata );
		if ( $p !== true ) {
			return $p;
		}

		// Make sure submitted value is an array
		if ( !is_array( $value ) ) {
			return false;
		}

		// If all options are valid, array_intersect of the valid options
		// and the provided options will return the provided options.
		$validOptions = [];
		foreach ( $rows as $rowTag ) {
			foreach ( $columns as $columnTag ) {
				$validOptions[] = $columnTag . '-' . $rowTag;
			}
		}
		$validValues = array_intersect( $value, $validOptions );
		if ( count( $validValues ) == count( $value ) ) {
			return true;
		} else {
			return $this->msg( 'htmlform-select-badoption' );
		}
	}

	/**
	 * Build a table containing a matrix of checkbox options.
	 * The value of each option is a combination of the row tag and column tag.
	 * mParams['rows'] is an array with row labels as keys and row tags as values.
	 * mParams['columns'] is an array with column labels as keys and column tags as values.
	 *
	 * @param array $value Array of the options that should be checked
	 *
	 * @return string
	 */
	public function getInputHTML( $value ) {
		$html = '';
		$tableContents = '';
		$rows = $this->mParams['rows'];
		$columns = $this->mParams['columns'];

		$attribs = $this->getAttributes( [ 'disabled', 'tabindex' ] );

		// Build the column headers
		$headerContents = Html::rawElement( 'td', [], "\u{00A0}" );
		foreach ( $columns as $columnLabel => $columnTag ) {
			$headerContents .= Html::rawElement( 'th', [], $columnLabel );
		}
		$thead = Html::rawElement( 'tr', [], "\n$headerContents\n" );
		$tableContents .= Html::rawElement( 'thead', [], "\n$thead\n" );

		$tooltipClass = 'mw-icon-question';
		if ( isset( $this->mParams['tooltip-class'] ) ) {
			$tooltipClass = $this->mParams['tooltip-class'];
		}

		// Build the options matrix
		foreach ( $rows as $rowLabel => $rowTag ) {
			// Append tooltip if configured
			if ( isset( $this->mParams['tooltips'][$rowLabel] ) ) {
				$tooltipAttribs = [
					'class' => "mw-htmlform-tooltip $tooltipClass",
					'title' => $this->mParams['tooltips'][$rowLabel],
					'aria-label' => $this->mParams['tooltips'][$rowLabel]
				];
				$rowLabel .= ' ' . Html::element( 'span', $tooltipAttribs, '' );
			}
			$rowContents = Html::rawElement( 'td', [], $rowLabel );
			foreach ( $columns as $columnTag ) {
				$thisTag = "$columnTag-$rowTag";
				// Construct the checkbox
				$thisAttribs = [
					'id' => "{$this->mID}-$thisTag",
					'value' => $thisTag,
				];
				$checked = in_array( $thisTag, (array)$value, true );
				if ( $this->isTagForcedOff( $thisTag ) ) {
					$checked = false;
					$thisAttribs['disabled'] = 1;
					$thisAttribs['class'] = 'checkmatrix-forced checkmatrix-forced-off';
				} elseif ( $this->isTagForcedOn( $thisTag ) ) {
					$checked = true;
					$thisAttribs['disabled'] = 1;
					$thisAttribs['class'] = 'checkmatrix-forced checkmatrix-forced-on';
				}

				$checkbox = $this->getOneCheckboxHTML( $checked, $attribs + $thisAttribs );

				$rowContents .= Html::rawElement(
					'td',
					[],
					$checkbox
				);
			}
			$tableContents .= Html::rawElement( 'tr', [], "\n$rowContents\n" );
		}

		// Put it all in a table
		$html .= Html::rawElement( 'table',
				[ 'class' => 'mw-htmlform-matrix' ],
				Html::rawElement( 'tbody', [], "\n$tableContents\n" ) ) . "\n";

		return $html;
	}

	/** @inheritDoc */
	public function getInputOOUI( $value ) {
		$attribs = $this->getAttributes( [ 'disabled', 'tabindex' ] );

		return new \MediaWiki\Widget\CheckMatrixWidget(
			[
				'name' => $this->mName,
				'infusable' => true,
				'id' => $this->mID,
				'rows' => $this->mParams['rows'],
				'columns' => $this->mParams['columns'],
				'tooltips' => $this->mParams['tooltips'] ?? [],
				'tooltips-html' => $this->mParams['tooltips-html'] ?? [],
				'forcedOff' => $this->mParams['force-options-off'] ?? [],
				'forcedOn' => $this->mParams['force-options-on'] ?? [],
				'values' => $value,
			] + \OOUI\Element::configFromHtmlAttributes( $attribs )
		);
	}

	/**
	 * @param bool $checked
	 * @param array $attribs
	 * @return string
	 */
	protected function getOneCheckboxHTML( $checked, $attribs ) {
		return Html::check( "{$this->mName}[]", $checked, $attribs );
	}

	/**
	 * @param string $tag
	 * @return bool
	 */
	protected function isTagForcedOff( $tag ) {
		return isset( $this->mParams['force-options-off'] )
			&& in_array( $tag, $this->mParams['force-options-off'] );
	}

	/**
	 * @param string $tag
	 * @return bool
	 */
	protected function isTagForcedOn( $tag ) {
		return isset( $this->mParams['force-options-on'] )
			&& in_array( $tag, $this->mParams['force-options-on'] );
	}

	/**
	 * @param WebRequest $request
	 *
	 * @return array
	 */
	public function loadDataFromRequest( $request ) {
		if ( $this->isSubmitAttempt( $request ) ) {
			// Checkboxes are just not added to the request arrays if they're not checked,
			// so it's perfectly possible for there not to be an entry at all
			return $request->getArray( $this->mName, [] );
		} else {
			// That's ok, the user has not yet submitted the form, so show the defaults
			return $this->getDefault();
		}
	}

	/** @inheritDoc */
	public function getDefault() {
		return $this->mDefault ?? [];
	}

	/** @inheritDoc */
	public function filterDataForSubmit( $data ) {
		$columns = HTMLFormField::flattenOptions( $this->mParams['columns'] );
		$rows = HTMLFormField::flattenOptions( $this->mParams['rows'] );
		$res = [];
		foreach ( $columns as $column ) {
			foreach ( $rows as $row ) {
				// Make sure option hasn't been forced
				$thisTag = "$column-$row";
				if ( $this->isTagForcedOff( $thisTag ) ) {
					$res[$thisTag] = false;
				} elseif ( $this->isTagForcedOn( $thisTag ) ) {
					$res[$thisTag] = true;
				} else {
					$res[$thisTag] = in_array( $thisTag, $data );
				}
			}
		}

		return $res;
	}

	/** @inheritDoc */
	protected function getOOUIModules() {
		return [ 'mediawiki.widgets.CheckMatrixWidget' ];
	}

	/** @inheritDoc */
	protected function shouldInfuseOOUI() {
		return true;
	}
}

/** @deprecated class alias since 1.42 */
class_alias( HTMLCheckMatrix::class, 'HTMLCheckMatrix' );
