<?php
/**
 * Unit test class for WordPressVIPMinimum Coding Standard.
 *
 * @package VIPCS\WordPressVIPMinimum
 */

namespace WordPressVIPMinimum\Tests\VIP;

use PHP_CodeSniffer\Tests\Standards\AbstractSniffUnitTest;

/**
 * Unit test class for the RestrictedFunctions sniff.
 *
 * @package VIPCS\WordPressVIPMinimum
 */
class RestrictedFunctionsUnitTest extends AbstractSniffUnitTest {

	/**
	 * Returns the lines where errors should occur.
	 *
	 * @return array <int line number> => <int number of errors>
	 */
	public function getErrorList() {
		return [
			19  => 1,
			27  => 1,
			28  => 1,
			29  => 1,
			30  => 1,
			31  => 1,
			32  => 1,
			33  => 1,
			34  => 1,
			37  => 1,
			40  => 1,
			43  => 1,
			46  => 1,
			50  => 1,
			53  => 1,
			56  => 1,
			59  => 1,
			62  => 1,
			75  => 1,
			76  => 1,
			82  => 1,
			83  => 1,
			84  => 1,
			85  => 1,
			88  => 1,
			91  => 1,
			94  => 1,
			97  => 1,
			98  => 1,
			99  => 1,
			100 => 1,
			101 => 1,
			104 => 1,
			107 => 1,
		];
	}

	/**
	 * Returns the lines where warnings should occur.
	 *
	 * @return array <int line number> => <int number of warnings>
	 */
	public function getWarningList() {
		return [
			110 => 1,
			111 => 1,
			114 => 1,
			118 => 1,
			119 => 1,
			122 => 1,
			125 => 1,
			130 => 1,
			131 => 1,
			132 => 1,
			137 => 1,
			138 => 1,
			139 => 1,
		];
	}

}
