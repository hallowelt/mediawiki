'use strict';

const fs = require( 'fs' ),
	path = require( 'path' ),
	logPath = process.env.LOG_DIR || './log/';

function relPath( foo ) {
	return path.resolve( __dirname, '../..', foo );
}

exports.config = {
	// ======
	// Custom WDIO config specific to MediaWiki
	// ======
	// Use in a test as `browser.options.<key>`.

	// Configure wiki admin user/pass via env
	// Defaults are for convenience with MediaWiki-Vagrant
	username: process.env.MEDIAWIKI_USER || 'Admin',
	password: process.env.MEDIAWIKI_PASSWORD || 'vagrant',

	// ======
	// Sauce Labs
	// ======
	services: [ 'sauce' ],
	user: process.env.SAUCE_USERNAME,
	key: process.env.SAUCE_ACCESS_KEY,

	// ==================
	// Specify Test Files
	// ==================
	// Define which test specs should run. The pattern is relative to the directory
	// from which `wdio` was called. Notice that, if you are calling `wdio` from an
	// NPM script (see https://docs.npmjs.com/cli/run-script) then the current working
	// directory is where your package.json resides, so `wdio` will be called from there.
	specs: [
		relPath( './tests/selenium/specs/**/*.js' ),
		relPath( './extensions/*/tests/selenium/specs/**/*.js' ),
		relPath( './extensions/VisualEditor/modules/ve-mw/tests/selenium/specs/**/*.js' ),
		relPath( './skins/*/tests/selenium/specs/**/*.js' )
	],
	// Patterns to exclude.
	exclude: [
		relPath( './extensions/CirrusSearch/tests/selenium/specs/**/*.js' )
	],

	// ============
	// Capabilities
	// ============
	// Define your capabilities here. WebdriverIO can run multiple capabilities at the same
	// time. Depending on the number of capabilities, WebdriverIO launches several test
	// sessions. Within your capabilities you can overwrite the spec and exclude options in
	// order to group specific specs to a specific capability.

	// First, you can define how many instances should be started at the same time. Let's
	// say you have 3 different capabilities (Chrome, Firefox, and Safari) and you have
	// set maxInstances to 1; wdio will spawn 3 processes. Therefore, if you have 10 spec
	// files and you set maxInstances to 10, all spec files will get tested at the same time
	// and 30 processes will get spawned. The property handles how many capabilities
	// from the same test should run tests.
	maxInstances: 1,

	// If you have trouble getting all important capabilities together, check out the
	// Sauce Labs platform configurator - a great tool to configure your capabilities:
	// https://docs.saucelabs.com/reference/platforms-configurator
	//
	// For Chrome/Chromium https://sites.google.com/a/chromium.org/chromedriver/capabilities
	capabilities: [ {
		// maxInstances can get overwritten per capability. So if you have an in-house Selenium
		// grid with only 5 firefox instances available you can make sure that not more than
		// 5 instances get started at a time.
		maxInstances: 1,
		browserName: 'chrome',
		chromeOptions: {
			// If DISPLAY is set, assume running from developer machine and/or with Xvfb.
			// Otherwise, use --headless (added in Chrome 59)
			// https://chromium.googlesource.com/chromium/src/+/59.0.3030.0/headless/README.md
			args: (
				process.env.DISPLAY ? [] : [ '--headless' ]
			).concat(
				// Chrome sandbox does not work in Docker
				fs.existsSync( '/.dockerenv' ) ? [ '--no-sandbox' ] : []
			)
		}
	} ],

	// ===================
	// Test Configurations
	// ===================
	// Define all options that are relevant for the WebdriverIO instance here
	//
	// By default WebdriverIO commands are executed in a synchronous way using
	// the wdio-sync package. If you still want to run your tests in an async way
	// e.g. using promises you can set the sync option to false.
	sync: true,

	// Level of logging verbosity: silent | verbose | command | data | result | error
	logLevel: 'error',

	// Enables colors for log output.
	coloredLogs: true,

	// Warns when a deprecated command is used
	deprecationWarnings: true,

	// If you only want to run your tests until a specific amount of tests have failed use
	// bail (default is 0 - don't bail, run all tests).
	bail: 0,

	// Saves a screenshot to a given path if a command fails.
	screenshotPath: logPath,

	// Set a base URL in order to shorten url command calls. If your `url` parameter starts
	// with `/`, the base url gets prepended, not including the path portion of your baseUrl.
	// If your `url` parameter starts without a scheme or `/` (like `some/path`), the base url
	// gets prepended directly.
	baseUrl: (
		process.env.MW_SERVER || 'http://127.0.0.1:8080'
	) + (
		process.env.MW_SCRIPT_PATH || '/w'
	),

	// Default timeout for all waitFor* commands.
	waitforTimeout: 10000,

	// Default timeout in milliseconds for request
	// if Selenium Grid doesn't send response
	connectionRetryTimeout: 90000,

	// Default request retries count
	connectionRetryCount: 3,

	// Initialize the browser instance with a WebdriverIO plugin. The object should have the
	// plugin name as key and the desired plugin options as properties. Make sure you have
	// the plugin installed before running any tests. The following plugins are currently
	// available:
	// WebdriverCSS: https://github.com/webdriverio/webdrivercss
	// WebdriverRTC: https://github.com/webdriverio/webdriverrtc
	// Browserevent: https://github.com/webdriverio/browserevent
	// plugins: {
	// 	webdrivercss: {
	// 		screenshotRoot: 'my-shots',
	// 		failedComparisonsRoot: 'diffs',
	// 		misMatchTolerance: 0.05,
	// 		screenWidth: [320,480,640,1024]
	// 	},
	// 	webdriverrtc: {},
	// 	browserevent: {}
	// },
	//
	// Test runner services
	// Services take over a specific job you don't want to take care of. They enhance
	// your test setup with almost no effort. Unlike plugins, they don't add new
	// commands. Instead, they hook themselves up into the test process.
	// services: [],//
	// Framework you want to run your specs with.
	// The following are supported: Mocha, Jasmine, and Cucumber
	// see also: http://webdriver.io/guide/testrunner/frameworks.html
	//
	// Make sure you have the wdio adapter package for the specific framework installed
	// before running any tests.
	framework: 'mocha',

	// Test reporter for stdout.
	// The only one supported by default is 'dot'
	// see also: http://webdriver.io/guide/testrunner/reporters.html
	reporters: [ 'spec', 'junit' ],
	reporterOptions: {
		junit: {
			outputDir: logPath
		}
	},

	// Options to be passed to Mocha.
	// See the full list at http://mochajs.org/
	mochaOpts: {
		ui: 'bdd',
		timeout: 60000
	},

	// =====
	// Hooks
	// =====
	// WebdriverIO provides several hooks you can use to interfere with the test process in order to enhance
	// it and to build services around it. You can either apply a single function or an array of
	// methods to it. If one of them returns with a promise, WebdriverIO will wait until that promise got
	// resolved to continue.

	/**
	 * Gets executed once before all workers get launched.
	 * @param {Object} config wdio configuration object
	 * @param {Array.<Object>} capabilities list of capabilities details
	 */
	// onPrepare: function (config, capabilities) {
	// },

	/**
	 * Gets executed just before initialising the webdriver session and test framework. It allows you
	 * to manipulate configurations depending on the capability or spec.
	 * @param {Object} config wdio configuration object
	 * @param {Array.<Object>} capabilities list of capabilities details
	 * @param {Array.<String>} specs List of spec file paths that are to be run
	 */
	// beforeSession: function (config, capabilities, specs) {
	// },

	/**
	 * Gets executed before test execution begins. At this point you can access to all global
	 * variables like `browser`. It is the perfect place to define custom commands.
	 * @param {Array.<Object>} capabilities list of capabilities details
	 * @param {Array.<String>} specs List of spec file paths that are to be run
	 */
	// before: function (capabilities, specs) {
	// },

	/**
	 * Runs before a WebdriverIO command gets executed.
	 * @param {String} commandName hook command name
	 * @param {Array} args arguments that command would receive
	 */
	// beforeCommand: function (commandName, args) {
	// },

	/**
	 * Hook that gets executed before the suite starts
	 * @param {Object} suite suite details
	 */
	// beforeSuite: function (suite) {
	// },

	/**
	 * Function to be executed before a test (in Mocha/Jasmine) or a step (in Cucumber) starts.
	 * @param {Object} test test details
	 */
	// beforeTest: function (test) {
	// },

	/**
	 * Hook that gets executed _before_ a hook within the suite starts (e.g. runs before calling
	 * beforeEach in Mocha)
	 */
	// beforeHook: function () {
	// },

	/**
	 * Hook that gets executed _after_ a hook within the suite ends (e.g. runs after calling
	 * afterEach in Mocha)
	 */
	// afterHook: function () {
	// },
	/**
	 * Function to be executed after a test (in Mocha/Jasmine) or a step (in Cucumber) ends.
	 * @param {Object} test test details
	 */
	// from https://github.com/webdriverio/webdriverio/issues/269#issuecomment-306342170
	afterTest: function ( test ) {
		var filename, filePath;
		// if test passed, ignore, else take and save screenshot
		if ( test.passed ) {
			return;
		}
		// get current test title and clean it, to use it as file name
		filename = encodeURIComponent( test.title.replace( /\s+/g, '-' ) );
		// build file path
		filePath = this.screenshotPath + filename + '.png';
		// save screenshot
		browser.saveScreenshot( filePath );
		console.log( '\n\tScreenshot location:', filePath, '\n' );
	}

	/**
	 * Hook that gets executed after the suite has ended
	 * @param {Object} suite suite details
	 */
	// afterSuite: function (suite) {
	// },

	/**
	 * Runs after a WebdriverIO command gets executed
	 * @param {String} commandName hook command name
	 * @param {Array} args arguments that command would receive
	 * @param {Number} result 0 - command success, 1 - command error
	 * @param {Object} error error object if any
	 */
	// afterCommand: function (commandName, args, result, error) {
	// },

	/**
	 * Gets executed after all tests are done. You still have access to all global variables from
	 * the test.
	 * @param {Number} result 0 - test pass, 1 - test fail
	 * @param {Array.<Object>} capabilities list of capabilities details
	 * @param {Array.<String>} specs List of spec file paths that ran
	 */
	// after: function (result, capabilities, specs) {
	// },

	/**
	 * Gets executed right after terminating the webdriver session.
	 * @param {Object} config wdio configuration object
	 * @param {Array.<Object>} capabilities list of capabilities details
	 * @param {Array.<String>} specs List of spec file paths that ran
	 */
	// afterSession: function (config, capabilities, specs) {
	// },

	/**
	 * Gets executed after all workers got shut down and the process is about to exit.
	 * @param {Object} exitCode 0 - success, 1 - fail
	 * @param {Object} config wdio configuration object
	 * @param {Array.<Object>} capabilities list of capabilities details
	 */
	// onComplete: function(exitCode, config, capabilities) {
	// }
};
