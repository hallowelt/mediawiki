<?php

use PHPUnit\Framework\Test;
use PHPUnit\Framework\TestFailure;
use PHPUnit\Util\TestDox\CliTestDoxPrinter;

/**
 * Custom result printer overriding the CliTestDoxPrinter and outputting logs for each test case.
 */
class MediaWikiPHPUnitResultPrinter extends CliTestDoxPrinter {

	/**
	 * @param TestFailure $defect
	 * @return void
	 */
	protected function printDefectTrace( TestFailure $defect ): void {
		parent::printDefectTrace( $defect );
		$test = $defect->getTestName();
		$log = MediaWikiLoggerPHPUnitExtension::$testsCollection[$test] ?? null;
		if ( $log ) {
			$this->write( "=== Logs generated by test case\n{$log}\n===\n" );
		}
	}

	/**
	 * Print the plain test name, not the prettified version from TestDox.
	 *
	 * @param Test $test
	 * @return string
	 */
	protected function formatTestName( Test $test ): string {
		return $test->getName();
	}

	/**
	 * Print the plain class name, not the prettified version from TestDox.
	 *
	 * @param Test $test
	 * @return string
	 */
	protected function formatClassName( Test $test ): string {
		return get_class( $test );
	}

}
