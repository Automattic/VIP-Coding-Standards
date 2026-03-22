<?php
/**
 * Unit test class for WordPressVIPMinimum Coding Standard.
 *
 * @package VIPCS\WordPressVIPMinimum
 */

namespace WordPressVIPMinimum\Tests\Hooks;

use PHP_CodeSniffer\Tests\Standards\AbstractSniffUnitTest;

/**
 * Unit test class for the AlwaysReturnInFilter sniff.
 *
 * @covers \WordPressVIPMinimum\Sniffs\Hooks\AlwaysReturnInFilterSniff
 */
class AlwaysReturnInFilterUnitTest extends AbstractSniffUnitTest {

	/**
	 * Returns the lines where errors should occur.
	 *
	 * @return array<int, int> Key is the line number, value is the number of expected errors.
	 */
	public function getErrorList() {
		return [
			15  => 1,
			49  => 1,
			88  => 1,
			95  => 1,
			105 => 1,
			129 => 1,
			163 => 1,
			188 => 1,
			196 => 1,
		];
	}

	/**
	 * Returns the lines where warnings should occur.
	 *
	 * @return array<int, int> Key is the line number, value is the number of expected warnings.
	 */
	public function getWarningList() {
		return [
			180 => 1,
		];
	}
}
