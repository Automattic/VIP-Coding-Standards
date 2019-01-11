<?php
/**
 * Unit test class for WordPressVIPMinimum Coding Standard.
 *
 * @package VIPCS\WordPressVIPMinimum
 */

namespace WordPressVIPMinimum\Tests\Hooks;

use PHP_CodeSniffer\Tests\Standards\AbstractSniffUnitTest;
/**
 * Unit test class for the Hooks/AlwaysReturn sniff.
 *
 * @package VIPCS\WordPressVIPMinimum
 */
class AlwaysReturnInFilterUnitTest extends AbstractSniffUnitTest {

	/**
	 * Returns the lines where errors should occur.
	 *
	 * @return array <int line number> => <int number of errors>
	 */
	public function getErrorList() {
		return [
			15  => 1,
			49  => 1,
			88  => 1,
			95  => 1,
			105 => 1,
			129 => 1,
		];
	}

	/**
	 * Returns the lines where warnings should occur.
	 *
	 * @return array <int line number> => <int number of warnings>
	 */
	public function getWarningList() {
		return [];
	}

}
