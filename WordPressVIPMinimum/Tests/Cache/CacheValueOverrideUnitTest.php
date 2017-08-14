<?php
/**
 * Unit test class for WordPressVIPMinimum Coding Standard.
 *
 * @package VIPCS\WordPressVIPMinimum
 */

namespace WordPressVIPMinimum\Tests\Cache;

use PHP_CodeSniffer\Tests\Standards\AbstractSniffUnitTest;

// Cross version compatibility for PHPCS 2.x and 3.x.
if ( ! class_exists( '\AbstractSniffUnitTest' ) ) {
	class_alias( '\PHP_CodeSniffer\Tests\Standards\AbstractSniffUnitTest', '\AbstractSniffUnitTest' );
}

/**
 * Unit test class for the CacheValueOverride sniff.
 *
 * @package VIPCS\WordPressVIPMinimum
 */
class CacheValueOverrideUnitTest extends AbstractSniffUnitTest {

	/**
	 * Returns the lines where errors should occur.
	 *
	 * @return array <int line number> => <int number of errors>
	 */
	public function getErrorList() {
		return array(
			5 => 1,
		);
	}

	/**
	 * Returns the lines where warnings should occur.
	 *
	 * @return array <int line number> => <int number of warnings>
	 */
	public function getWarningList() {
		return array();

	}

} // End class.
