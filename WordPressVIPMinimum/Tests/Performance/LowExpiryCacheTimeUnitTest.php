<?php
/**
 * Unit test class for WordPressVIPMinimum Coding Standard.
 *
 * @package VIPCS\WordPressVIPMinimum
 */

namespace WordPressVIPMinimum\Tests\Performance;

use PHP_CodeSniffer\Tests\Standards\AbstractSniffUnitTest;

/**
 * Unit test class for the LowExpiryCacheTime sniff.
 *
 * @package VIPCS\WordPressVIPMinimum
 *
 * @covers \WordPressVIPMinimum\Sniffs\Performance\LowExpiryCacheTimeSniff
 */
class LowExpiryCacheTimeUnitTest extends AbstractSniffUnitTest {

	/**
	 * Returns the lines where errors should occur.
	 *
	 * @return array <int line number> => <int number of errors>
	 */
	public function getErrorList() {
		return [];
	}

	/**
	 * Returns the lines where warnings should occur.
	 *
	 * @return array <int line number> => <int number of warnings>
	 */
	public function getWarningList() {
		return [
			27  => 1,
			28  => 1,
			29  => 1,
			30  => 1,
			32  => 1,
			33  => 1,
			34  => 1,
			35  => 1,
			37  => 1,
			38  => 1,
			39  => 1,
			40  => 1,
			47  => 1,
			52  => 1,
			56  => 1,
			74  => 1,
			75  => 1,
			76  => 1,
			77  => 1,
			78  => 1,
			79  => 1,
			88  => 1,
			94  => 1,
			95  => 1,
			105 => 1,
			112 => 1,
			113 => 1,
			114 => 1,
			115 => 1,
			116 => 1,
			119 => 1,
			120 => 1,
			123 => 1,
		];
	}
}
