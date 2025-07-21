<?php
/**
 * Unit test class for WordPressVIPMinimum Coding Standard.
 *
 * @package VIPCS\WordPressVIPMinimum
 */

namespace WordPressVIPMinimum\Tests\Performance;

use PHP_CodeSniffer\Tests\Standards\AbstractSniffUnitTest;

/**
 * Unit test class for the ExitAfterRedirect sniff.
 *
 * @covers \WordPressVIPMinimum\Sniffs\Performance\FetchingRemoteDataSniff
 */
class FetchingRemoteDataUnitTest extends AbstractSniffUnitTest {

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
			35 => 1,
			36 => 1,
			37 => 1,
			41 => 1,
			44 => 1,
			45 => 1,
			46 => 1,
			48 => 1,
			62 => 1,
			70 => 1,
			79 => 1,
			85 => 1,
			88 => 1,
		];
	}
}
