<?php
/**
 * Statistic output classes.
 *
 * @license GPL-2.0-or-later
 * @file
 * @ingroup MaintenanceLanguage
 * @author Ævar Arnfjörð Bjarmason <avarab@gmail.com>
 * @author Antoine Musso <hashar at free dot fr>
 */

use MediaWiki\Specials\SpecialVersion;
use Wikimedia\AtEase\AtEase;

/**
 * A general output object. Need to be overridden
 */
class StatsOutput {
	/**
	 * @param int|float $subset
	 * @param int|float $total
	 * @param bool $revert
	 * @param int|float $accuracy
	 * @return string
	 */
	public function formatPercent( $subset, $total, $revert = false, $accuracy = 2 ) {
		AtEase::suppressWarnings();
		$return = sprintf( '%.' . $accuracy . 'f%%', 100 * $subset / $total );
		AtEase::restoreWarnings();

		return $return;
	}

	public function heading() {
	}

	public function footer() {
	}

	public function blockstart() {
	}

	public function blockend() {
	}

	/**
	 * @param string|float|int $in
	 * @param bool $heading
	 */
	public function element( $in, $heading = false ) {
	}
}

/** Outputs WikiText */
class WikiStatsOutput extends StatsOutput {
	public function heading() {
		global $wgDummyLanguageCodes;
		$version = SpecialVersion::getVersion( 'nodb' );
		echo "'''Statistics are based on:''' <code>" . $version . "</code>\n\n";
		echo 'English (en) is excluded because it is the default localization';
		if ( is_array( $wgDummyLanguageCodes ) ) {
			$dummyCodes = [];
			foreach ( $wgDummyLanguageCodes as $dummyCode => $correctCode ) {
				$dummyCodes[] = $this->getServiceContainer()
					->getLanguageNameUtils()
					->getLanguageName( $dummyCode ) . ' (' . $dummyCode . ')';
			}
			echo ', as well as the following languages that are not intended for ' .
				'system message translations, usually because they redirect to other ' .
				'language codes: ' . implode( ', ', $dummyCodes );
		}
		# dot to end sentence
		echo ".\n\n";
		echo '{| class="sortable wikitable" border="2" style="background-color: #F9F9F9; ' .
			'border: 1px #AAAAAA solid; border-collapse: collapse; clear:both; width:100%;"' . "\n";
	}

	public function footer() {
		echo "|}\n";
	}

	public function blockstart() {
		echo "|-\n";
	}

	public function blockend() {
		echo '';
	}

	/** @inheritDoc */
	public function element( $in, $heading = false ) {
		echo ( $heading ? '!' : '|' ) . "$in\n";
	}

	/** @inheritDoc */
	public function formatPercent( $subset, $total, $revert = false, $accuracy = 2 ) {
		AtEase::suppressWarnings();
		$v = round( 255 * $subset / $total );
		AtEase::restoreWarnings();

		if ( $revert ) {
			# Weigh reverse with factor 20 so coloring takes effect more quickly as
			# this option is used solely for reporting 'bad' percentages.
			$v = $v * 20;
			if ( $v > 255 ) {
				$v = 255;
			}
			$v = 255 - $v;
		}
		if ( $v < 128 ) {
			# Red to Yellow
			$red = 'FF';
			$green = sprintf( '%02X', 2 * $v );
		} else {
			# Yellow to Green
			$red = sprintf( '%02X', 2 * ( 255 - $v ) );
			$green = 'FF';
		}
		$blue = '00';
		$color = $red . $green . $blue;

		$percent = parent::formatPercent( $subset, $total, $revert, $accuracy );

		return 'style="background-color:#' . $color . ';"|' . $percent;
	}
}

/** Output text. To be used on a terminal for example. */
class TextStatsOutput extends StatsOutput {
	/** @inheritDoc */
	public function element( $in, $heading = false ) {
		echo $in . "\t";
	}

	public function blockend() {
		echo "\n";
	}
}

/** csv output. Some people love excel */
class CsvStatsOutput extends StatsOutput {
	/** @inheritDoc */
	public function element( $in, $heading = false ) {
		echo $in . ";";
	}

	public function blockend() {
		echo "\n";
	}
}
