<?php
/**
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
 * @author Ruben Vardanyan (Me@RubenVardanyan.com)
 */

use MediaWiki\Language\Language;
use MediaWiki\MainConfigNames;
use MediaWiki\MediaWikiServices;

/**
 * Armenian (Հայերեն)
 *
 * @ingroup Languages
 */
class LanguageHy extends Language {

	/** @inheritDoc */
	public function convertGrammar( $word, $case ) {
		$grammarForms =
			MediaWikiServices::getInstance()->getMainConfig()->get( MainConfigNames::GrammarForms );
		if ( isset( $grammarForms['hy'][$case][$word] ) ) {
			return $grammarForms['hy'][$case][$word];
		}

		# These rules are not perfect, but they are currently only used for site names so it doesn't
		# matter if they are wrong sometimes. Just add a special case for your site name if necessary.

		# join and array_slice instead mb_substr
		$ar = [];
		preg_match_all( '/./us', $word, $ar );
		if ( !preg_match( "/[a-zA-Z_]/u", $word ) ) {
			switch ( $case ) {
				case 'genitive': # սեռական հոլով
					if ( implode( '', array_slice( $ar[0], -1 ) ) == 'ա' ) {
						$word = implode( '', array_slice( $ar[0], 0, -1 ) ) . 'այի';
					} elseif ( implode( '', array_slice( $ar[0], -1 ) ) == 'ո' ) {
						$word = implode( '', array_slice( $ar[0], 0, -1 ) ) . 'ոյի';
					} elseif ( implode( '', array_slice( $ar[0], -4 ) ) == 'գիրք' ) {
						$word = implode( '', array_slice( $ar[0], 0, -4 ) ) . 'գրքի';
					} else {
						$word .= 'ի';
					}
					break;
				case 'dative':  # Տրական հոլով
					# stub
					break;
				case 'accusative': # Հայցական հոլով
					# stub
					break;
				case 'instrumental':
					# stub
					break;
				case 'prepositional':
					# stub
					break;
			}
		}
		return $word;
	}
}
