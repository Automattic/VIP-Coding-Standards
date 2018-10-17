<?php
/**
 * Unit test class for WordPressVIPMinimum Coding Standard.
 *
 * @package VIPCS\WordPressVIPMinimum
 */

namespace WordPressVIPMinimum\Tests\Filters;

use PHP_CodeSniffer\Tests\Standards\AbstractSniffUnitTest;
/**
 * Unit test class for the Filters/RestrictedFilter sniff.
 *
 * @package VIPCS\WordPressVIPMinimum
 *
 * @since 0.4.0
 */
class RestrictedFilterUnitTest extends AbstractSniffUnitTest {

	/**
	 * Returns the lines where errors should occur.
	 *
	 * @return array <int line number> => <int number of errors>
	 */
	public function getErrorList() {
		return array();
	}

	/**
	 * Returns the lines where warnings should occur.
	 *
	 * @return array <int line number> => <int number of warnings>
	 */
	public function getWarningList() {
		return array(
			6 => 1,
			7 => 1,
			8 => 1,
			9 => 1,
			10 => 1,
			11 => 1,
			12 => 1,
			13 => 1,
		);
	}

}
