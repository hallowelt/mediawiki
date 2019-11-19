<?php
/**
 * Internationalisation code.
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
 * @ingroup Language
 */

/**
 * A fake language variant converter. Languages which do not implement variant
 * conversion, for example, English, should return a FakeConverter rather than a
 * LanguageConverter when asked for their converter. The fake converter just
 * returns text unchanged, i.e. it doesn't do any conversion.
 *
 * See https://www.mediawiki.org/wiki/Writing_systems#LanguageConverter.
 *
 * @ingroup Language
 */
class FakeConverter {
	/**
	 * @var Language
	 */
	public $mLang;

	public function __construct( Language $langobj ) {
		$this->mLang = $langobj;
	}

	public function autoConvert( $text, $variant = false ) {
		return $text;
	}

	public function autoConvertToAllVariants( $text ) {
		return [ $this->mLang->getCode() => $text ];
	}

	public function convert( $t ) {
		return $t;
	}

	public function convertTo( $text, $variant ) {
		return $text;
	}

	/**
	 * @param Title $t
	 * @return mixed
	 */
	public function convertTitle( $t ) {
		return $t->getPrefixedText();
	}

	public function convertNamespace( $ns ) {
		return $this->mLang->getFormattedNsText( $ns );
	}

	/**
	 * @return string[]
	 */
	public function getVariants() {
		return [ $this->mLang->getCode() ];
	}

	public function getVariantFallbacks( $variant ) {
		return $this->mLang->getCode();
	}

	public function getPreferredVariant() {
		return $this->mLang->getCode();
	}

	public function getDefaultVariant() {
		return $this->mLang->getCode();
	}

	public function getURLVariant() {
		return '';
	}

	public function getConvRuleTitle() {
		return false;
	}

	public function findVariantLink( &$l, &$n, $ignoreOtherCond = false ) {
	}

	public function getExtraHashOptions() {
		return '';
	}

	public function markNoConversion( $text, $noParse = false ) {
		return $text;
	}

	public function convertCategoryKey( $key ) {
		return $key;
	}

	public function validateVariant( $variant = null ) {
		if ( $variant === null ) {
			return null;
		}
		$variant = strtolower( $variant );
		return $variant === $this->mLang->getCode() ? $variant : null;
	}

	public function translate( $text, $variant ) {
		return $text;
	}

	public function updateConversionTable( Title $title ) {
	}

	/**
	 * Used by test suites which need to reset the converter state.
	 *
	 * @private
	 */
	private function reloadTables() {
	}
}
