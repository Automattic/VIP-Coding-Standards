<?php
/**
 * Unit test class for WordPressVIPMinimum Coding Standard.
 *
 * @package VIPCS\WordPressVIPMinimum
 */

namespace WordPressVIPMinimum\Tests\Hooks;

use PHP_CodeSniffer\Tests\Standards\AbstractSniffUnitTest;
/**
 * Unit test class for the Filters/RestrictedHooks sniff.
 *
 * @since 0.4.0
 *
 * @covers \WordPressVIPMinimum\Sniffs\Hooks\RestrictedHooksSniff
 */
class RestrictedHooksUnitTest extends AbstractSniffUnitTest {

	/**
	 * Returns the lines where errors should occur.
	 *
	 * @return array<int, int> Key is the line number, value is the number of expected errors.
	 */
	public function getErrorList() {
		return [];
	}

	/**
	 * Returns the lines where warnings should occur.
	 *
	 * @return array<int, int> Key is the line number, value is the number of expected warnings.
	 */
	public function getWarningList() {
		return [
			7  => 1,
			8  => 1,
			9  => 1,
			10 => 1,
			11 => 1,
			12 => 1,
			13 => 1,
			14 => 1,
			15 => 1,
			16 => 1,
			19 => 1,
			20 => 1,
			21 => 1,
			22 => 1,
			23 => 1,
		];
	}
}
