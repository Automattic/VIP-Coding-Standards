<?php
/**
 * Unit test class for WordPressVIPMinimum Coding Standard.
 *
 * @package VIPCS\WordPressVIPMinimum
 */

namespace WordPressVIPMinimum\Tests\Classes;

use PHP_CodeSniffer\Tests\Standards\AbstractSniffUnitTest;

/**
 * Unit test class for the DeclarationCompatibility sniff.
 *
 * @covers \WordPressVIPMinimum\Sniffs\Classes\DeclarationCompatibilitySniff
 */
class DeclarationCompatibilityUnitTest extends AbstractSniffUnitTest {

	/**
	 * Returns the lines where errors should occur.
	 *
	 * @return array<int, int> Key is the line number, value is the number of expected errors.
	 */
	public function getErrorList() {
		return [
			4   => 1,
			7   => 1,
			10  => 1,
			13  => 1,
			16  => 1,
			19  => 1,
			25  => 1,
			40  => 1,
			43  => 1,
			46  => 1,
			49  => 1,
			52  => 1,
			61  => 1,
			67  => 1,
			70  => 1,
			76  => 1,
			79  => 1,
			88  => 1,
			106 => 1,
			112 => 1,
			119 => 1,
			128 => 1,
			134 => 1,
			137 => 1,
			140 => 1,
		];
	}

	/**
	 * Returns the lines where warnings should occur.
	 *
	 * @return array<int, int> Key is the line number, value is the number of expected warnings.
	 */
	public function getWarningList() {
		return [];
	}
}
