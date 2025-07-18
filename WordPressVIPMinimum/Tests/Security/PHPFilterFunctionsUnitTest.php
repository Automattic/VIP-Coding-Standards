<?php
/**
 * Unit test class for WordPressVIPMinimum Coding Standard.
 *
 * @package VIPCS\WordPressVIPMinimum
 */

namespace WordPressVIPMinimum\Tests\Security;

use PHP_CodeSniffer\Tests\Standards\AbstractSniffUnitTest;

/**
 * Unit test class for the WP_Query params sniff.
 *
 * @covers \WordPressVIPMinimum\Sniffs\Security\PHPFilterFunctionsSniff
 */
class PHPFilterFunctionsUnitTest extends AbstractSniffUnitTest {

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
			44 => 1,
			45 => 1,
			46 => 1,
			48 => 1,
			49 => 1,
			50 => 1,
			52 => 1,
			53 => 1,
			54 => 1,
			56 => 1,
			57 => 1,
			58 => 1,
			65 => 1,
			70 => 1,
			71 => 1,
			73 => 1,
			75 => 1,
		];
	}
}
