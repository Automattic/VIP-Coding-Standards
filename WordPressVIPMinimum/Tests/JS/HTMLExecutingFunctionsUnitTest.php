<?php
/**
 * Unit test class for WordPressVIPMinimum Coding Standard.
 *
 * @package VIPCS\WordPressVIPMinimum
 */

namespace WordPressVIPMinimum\Tests\JS;

use PHP_CodeSniffer\Tests\Standards\AbstractSniffUnitTest;

/**
 * Unit test class for the HTML executing JS functions sniff.
 *
 * @package VIPCS\WordPressVIPMinimum
 *
 * @covers \WordPressVIPMinimum\Sniffs\JS\HTMLExecutingFunctionsSniff
 */
class HTMLExecutingFunctionsUnitTest extends AbstractSniffUnitTest {

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
			5  => 1,
			6  => 1,
			8  => 1,
			9  => 1,
			11 => 1,
			12 => 1,
			14 => 1,
			15 => 1,
			17 => 1,
			18 => 1,
			20 => 1,
			21 => 1,
			23 => 1,
			25 => 1,
			29 => 1,
			30 => 1,
			32 => 1,
			33 => 1,
			35 => 1,
			36 => 1,
			38 => 1,
			39 => 1,
			41 => 1,
			43 => 1,
			46 => 1,
			48 => 1,
		];
	}
}
