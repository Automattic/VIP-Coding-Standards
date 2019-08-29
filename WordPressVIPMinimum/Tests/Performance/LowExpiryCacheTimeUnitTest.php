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
			32 => 1,
			33 => 1,
			34 => 1,
			35 => 1,
			37 => 1,
			38 => 1,
			39 => 1,
			40 => 1,
			42 => 1,
			43 => 1,
			44 => 1,
			45 => 1,
			47 => 1,
			48 => 1,
			49 => 1,
			50 => 1,
		];
	}
}
