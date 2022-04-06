<?php

use MediaWiki\MediaWikiServices;
use PHPUnit\Framework\TestSuite;
use Wikimedia\Parsoid\ParserTests\Test as ParsoidTest;
use Wikimedia\ScopedCallback;

/**
 * This is the suite class for running tests with Parsoid in integrated
 * mode within a single .txt source file.
 * It is not invoked directly. Use --filter to select files, or
 * use parserTests.php.
 */
class ParsoidTestFileSuite extends TestSuite {
	use SuiteEventsTrait;

	public const VALID_TEST_MODES = [ 'wt2html', 'wt2html+integrated', 'wt2wt' ];

	private $ptRunner;
	private $ptFileName;
	private $ptFileInfo;

	/** @var ScopedCallback */
	private $ptTeardownScope;

	public function __construct( $runner, $name, $fileName ) {
		parent::__construct( $name );
		$this->ptRunner = $runner;
		$this->ptFileName = $fileName;
		$this->ptFileInfo = Wikimedia\Parsoid\ParserTests\TestFileReader::read( $fileName, static function ( $msg ) {
			wfDeprecatedMsg( $msg, '1.35', false, false );
		} );
		$fileOptions = $this->ptFileInfo->fileOptions;
		if ( !isset( $fileOptions['parsoid-compatible'] ) ) {
			// Running files in Parsoid integrated mode is opt-in for now.
			$skipMessage = 'not compatible with Parsoid integrated mode';
		} elseif ( !MediaWikiServices::getInstance()->hasService( 'ParsoidPageConfigFactory' ) ) {
			// Disable integrated mode if Parsoid's services aren't available
			// (Temporary measure until Parsoid is fully integrated in core.)
			$skipMessage = 'Parsoid not available';
		} elseif ( !$this->ptRunner->meetsRequirements( $fileOptions['requirements'] ?? [] ) ) {
			$skipMessage = 'required extension not enabled';
		} else {
			$skipMessage = null;
		}

		foreach ( $this->ptFileInfo->testCases as $t ) {
			$testModes = $t->computeTestModes( self::VALID_TEST_MODES );
			$runnerOpts = [ 'numchanges' => 20 ]; // the default in Parsoid
			$suite = $this;
			$t->testAllModes( $testModes, $runnerOpts,
				static function ( ParsoidTest $newTest, string $mode, array $options ) use ( $suite, $runner, $fileName, $t, $skipMessage ) {
					// $options is being ignored but it is identical to $runnerOpts
					$test = [
						'test' => $t->testName,
						'desc' => ( $t->comment ?? '' ) . $t->testName,
						'input' => $t->wikitext,
						'result' => $t->legacyHtml,
						'options' => $t->options,
						'config' => $t->config ?? '',
						'line' => $t->lineNumStart,
						'file' => $t->filename,
						'parsoid' => $newTest,
						'parsoidMode' => $mode
					];
					$pit = new ParserIntegrationTest( $runner, $fileName, $test, $skipMessage );
					$suite->addTest( $pit, [ 'Database', 'Parser', 'ParserTests' ] );
				}
			);

		}
	}

	protected function setUp(): void {
		$articles = [];
		foreach ( $this->ptFileInfo->articles as $a ) {
			$articles[] = [
				'name' => $a->title,
				'text' => $a->text,
				'line' => $a->lineNumStart,
				'file' => $a->filename,
			];
		}
		$this->ptTeardownScope = $this->ptRunner->addArticles(
			$articles
		);
	}

	protected function tearDown(): void {
		if ( $this->ptTeardownScope ) {
			ScopedCallback::consume( $this->ptTeardownScope );
		}
	}
}
