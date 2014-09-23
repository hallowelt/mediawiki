<?php
/**
 * Doxygen filter to show correct member variable types in documentation.
 *
 * Should be filled in doxygen INPUT_FILTER as "php mwdoc-filter.php"
 *
 * Original source code by Goran Rakic
 * http://blog.goranrakic.com/
 * http://stackoverflow.com/questions/4325224
 *
 * @file
 */

if ( PHP_SAPI != 'cli' ) {
	die( "This filter can only be run from the command line.\n" );
}

$source = file_get_contents( $argv[1] );
$regexp = '#'
	. '\@var'
	. '\s+'
	// Type hint
	. '([^\s]+)'
	// Any text or line(s) between type hint and '/' closing the comment
	// (includes the star of "*/")
	. '([^/]+)'
	. '/'
	. '\s+'
	. '(var|public|protected|private)'
	. '\s+'
	// Variable name
	. '(\$[^\s;=]+)'
	. '#';
$replac = '${2}/ ${3} ${1} ${4}';
$source = preg_replace( $regexp, $replac, $source );

echo $source;
