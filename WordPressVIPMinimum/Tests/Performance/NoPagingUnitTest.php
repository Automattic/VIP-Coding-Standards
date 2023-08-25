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
 * @package VIPCS\WordPressVIPMinimum
 *
 * @since   0.5.0
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
			4  => 1,
			7  => 1,
			13 => 1,
			15 => 1,
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
