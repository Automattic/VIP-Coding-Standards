<?php
/**
 * Unit test class for WordPressVIPMinimum Coding Standard.
 *
 * @package VIPCS\WordPressVIPMinimum
 */

namespace WordPressVIPMinimum\Tests\Performance;

use PHP_CodeSniffer\Tests\Standards\AbstractSniffUnitTest;

/**
 * Unit test class for the NoPaging sniff.
 *
 * @covers \WordPressVIPMinimum\Sniffs\Performance\NoPagingSniff
 */
class NoPagingUnitTest extends AbstractSniffUnitTest {

	/**
	 * Returns the lines where errors should occur.
	 *
	 * @return array<int, int> Key is the line number, value is the number of expected errors.
	 */
	public function getErrorList() {
		return [
			6  => 1,
			9  => 1,
			15 => 1,
			17 => 1,
			20 => 1,
			29 => 1,
			38 => 1,
			42 => 1,
			45 => 1,
			54 => 1,
			60 => 1,
			66 => 1,
			72 => 1,
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
