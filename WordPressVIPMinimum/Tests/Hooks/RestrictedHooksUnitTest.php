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
			46 => 1,
			47 => 1,
			48 => 1,
			49 => 1,
			51 => 1,
			52 => 1,
			53 => 1,
			54 => 1,
			55 => 1,
			58 => 1,
			59 => 1,
			60 => 1,
			61 => 1,
			62 => 1,
			70 => 1,
		];
	}
}
