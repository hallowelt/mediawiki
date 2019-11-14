<?php

use PHPUnit\Framework\TestFailure;
use PHPUnit\TextUI\ResultPrinter;

class MediaWikiPHPUnitResultPrinter extends ResultPrinter {
	protected function printDefectTrace( TestFailure $defect ) : void {
		$test = $defect->failedTest();
		if ( $test !== null && isset( $test->_formattedMediaWikiLogs ) ) {
			$log = $test->_formattedMediaWikiLogs;
			if ( $log ) {
				$this->write( "=== Logs generated by test case\n{$log}\n===\n" );
			}
		}
		parent::printDefectTrace( $defect );
	}
}
