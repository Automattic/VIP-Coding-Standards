<?php
/**
 * Unit test class for WordPressVIPMinimum Coding Standard.
 *
 * @package VIPCS\WordPressVIPMinimum
 */

namespace WordPress\Tests\Variables;

use PHP_CodeSniffer\Tests\Standards\AbstractSniffUnitTest;

/**
 * Unit test class for the VIP_RestrictedVariables sniff.
 *
 * @package WPCS\WordPressCodingStandards
 *
 * @since   0.3.0
 * @since   0.13.0 Class name changed: this class is now namespaced.
 */
class RestrictedVariablesUnitTest extends AbstractSniffUnitTest {

	/**
	 * Returns the lines where errors should occur.
	 *
	 * @return array <int line number> => <int number of errors>
	 */
	public function getErrorList() {
		return [
			3  => 1,
			5  => 1,
			7  => 1,
			9  => 1,
			23 => 1,
			36 => 1,
			37 => 1,
		];
	}

	/**
	 * Returns the lines where warnings should occur.
	 *
	 * @return array <int line number> => <int number of warnings>
	 */
	public function getWarningList() {
		return [
			13 => 1,
			14 => 1,
			17 => 1,
			28 => 1,
		];
	}

}
