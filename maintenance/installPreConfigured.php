<?php

use MediaWiki\Installer\Installer;
use MediaWiki\Installer\Task\AddWikiTaskContext;
use MediaWiki\Installer\Task\CannedProvider;
use MediaWiki\Installer\Task\ITaskContext;
use MediaWiki\Installer\Task\Task;
use MediaWiki\Installer\Task\TaskFactory;
use MediaWiki\Installer\Task\TaskList;
use MediaWiki\Installer\Task\TaskRunner;
use MediaWiki\MainConfigNames;
use MediaWiki\Registration\ExtensionRegistry;
use MediaWiki\Settings\SettingsBuilder;
use Symfony\Component\Yaml\Yaml;

require_once __DIR__ . '/Maintenance.php';

class InstallPreConfigured extends Maintenance {
	public function __construct() {
		parent::__construct();
		$this->addDescription( 'Create the database and tables for a new wiki, ' .
			'using a pre-existing LocalSettings.php' );
		$this->addOption( 'task',
			'Execute only the specified task', false, true );
		$this->addOption( 'skip',
			'Skip the specified task', false, true, false, true );
		$this->addOption( 'override-config',
			'Specify a configuration variable with name=value. The value is in YAML format.',
			false, true, 'c', true );
		$this->addOption( 'override-option',
			'Specify an installer option with name=value. The value is in YAML format.',
			false, true, 'o', true );
		$this->addOption( 'show-tasks',
			'Show the list of tasks to be executed, do not actually install' );
	}

	public function finalSetup( SettingsBuilder $settingsBuilder ) {
		parent::finalSetup( $settingsBuilder );

		// Load installer i18n
		$settingsBuilder->putConfigValue( MainConfigNames::MessagesDirs,
			[ MW_INSTALL_PATH . '/includes/installer/i18n' ] );

		// Apply override-config options. Doing this here instead of in
		// AddWikiTaskContext::setConfigVar() allows the options to be available
		// globally for use in LoadExtensionSchemaUpdates.
		foreach ( $this->getOption( 'override-config' ) ?? [] as $str ) {
			[ $name, $value ] = $this->parseKeyValue( $str );
			if ( str_starts_with( $name, 'wg' ) ) {
				$name = substr( $name, 2 );
			}
			$settingsBuilder->putConfigValue( $name, $value );
		}
	}

	public function execute() {
		$context = $this->createTaskContext();
		$taskFactory = $this->createTaskFactory( $context );
		$taskList = $this->createTaskList( $taskFactory );
		$taskRunner = $this->createTaskRunner( $taskList, $taskFactory );

		if ( $this->hasOption( 'show-tasks' ) ) {
			$taskRunner->loadExtensions();
			echo $taskRunner->dumpTaskList();
			return true;
		}

		Installer::disableStorage( $this->getConfig(), 'en' );
		if ( $this->hasOption( 'task' ) ) {
			$status = $taskRunner->runNamedTask( $this->getOption( 'task' ) );
		} else {
			$status = $taskRunner->execute();
		}

		if ( $status->isOK() ) {
			return true;
		} else {
			$this->error( "Installation failed at task \"" .
				$taskRunner->getCurrentTaskName() . '"' );
			return false;
		}
	}

	/**
	 * Split a string into a key and a value, and parse the value as YAML.
	 *
	 * @param string $str
	 * @return array A list where the first element is the name, and the
	 *   second is the decoded value
	 */
	private function parseKeyValue( string $str ) {
		$parts = explode( '=', $str, 2 );
		if ( count( $parts ) !== 2 ) {
			$this->fatalError( "Invalid configuration variable \"$str\"" );
		}
		return [ $parts[0], Yaml::parse( $parts[1], Yaml::PARSE_EXCEPTION_ON_INVALID_TYPE ) ];
	}

	/**
	 * Get the context for running tasks, with config overrides from the
	 * command line.
	 *
	 * @return AddWikiTaskContext
	 */
	private function createTaskContext() {
		$context = new AddWikiTaskContext(
			$this->getConfig(),
			$this->getServiceContainer()->getDBLoadBalancerFactory()
		);

		// Make sure ExtensionTablesTask isn't skipped
		$context->setOption( 'Extensions',
			array_keys( ExtensionRegistry::getInstance()->getAllThings() ) );

		foreach ( $this->getOption( 'override-option' ) ?? [] as $str ) {
			[ $name, $value ] = $this->parseKeyValue( $str );
			$context->setOption( $name, $value );
		}
		return $context;
	}

	/**
	 * Get the full list of tasks, before skipping is applied.
	 *
	 * @param TaskFactory $taskFactory
	 * @return TaskList
	 */
	private function createTaskList( TaskFactory $taskFactory ) {
		$taskList = new TaskList;
		$taskFactory->registerMainTasks( $taskList, TaskFactory::PROFILE_ADD_WIKI );
		$reg = ExtensionRegistry::getInstance();
		$taskList->add( $taskFactory->create(
			[
				'class' => CannedProvider::class,
				'args' => [
					'extensions',
					[
						'HookContainer' => $this->getHookContainer(),
						'VirtualDomains' => $reg->getAttribute( 'DatabaseVirtualDomains' ),
						'ExtensionTaskSpecs' => $reg->getAttribute( 'InstallerTasks' ),
					]
				]
			]
		) );
		return $taskList;
	}

	/**
	 * Create and configure a TaskRunner
	 *
	 * @param TaskList $taskList
	 * @param TaskFactory $taskFactory
	 * @return TaskRunner
	 */
	private function createTaskRunner( TaskList $taskList, TaskFactory $taskFactory ) {
		$taskRunner = new TaskRunner( $taskList, $taskFactory, TaskFactory::PROFILE_ADD_WIKI );
		$taskRunner->setSkippedTasks( $this->getOption( 'skip' ) ?? [] );

		$taskRunner->addTaskStartListener( function ( Task $task ) {
			$name = $task->getName();
			$desc = $task->getDescriptionMessage()->plain();
			$this->output( "[$name] $desc... " );
		} );

		$taskRunner->addTaskEndListener( function ( $task, StatusValue $status ) {
			if ( $status->isOK() ) {
				$this->output( "done\n" );
			} else {
				$this->output( "\n" );
			}
			if ( !$status->isGood() ) {
				try {
					$this->error( $status );
				} catch ( InvalidArgumentException $e ) {
					$this->error( (string)$status );
				}
			}
		} );

		return $taskRunner;
	}

	/**
	 * Get the factory used to create tasks
	 *
	 * @param ITaskContext $context
	 * @return TaskFactory
	 */
	private function createTaskFactory( ITaskContext $context ) {
		return new TaskFactory(
			$this->getServiceContainer()->getObjectFactory(),
			$context
		);
	}
}

$maintClass = InstallPreConfigured::class;
require_once RUN_MAINTENANCE_IF_MAIN;
