<?php
/**
 * Unit test class for WordPressVIPMinimum Coding Standard.
 *
 * @package VIPCS\WordPressVIPMinimum
 */

// Cross version compatibility for PHPCS 2.x and 3.x.
if ( ! class_exists( '\AbstractSniffUnitTest' ) ) {
	class_alias( '\PHP_CodeSniffer\Tests\Standards\AbstractSniffUnitTest', '\AbstractSniffUnitTest' );
}

/**
 * Unit test class for the StaticStrreplace sniff.
 *
 * @package VIPCS\WordPressVIPMinimum
 */
class WordPressVIPMinimum_Tests_VIP_StaticStrreplaceUnitTest extends AbstractSniffUnitTest {

	/**
	 * Returns the lines where errors should occur.
	 *
	 * @return array <int line number> => <int number of errors>
	 */
	public function getErrorList() {
		return array(
			3 => 1,
			7 => 1,
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
