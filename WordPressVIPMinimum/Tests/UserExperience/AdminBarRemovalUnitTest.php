<?php
/**
 * Unit test class for WordPressVIPMinimum Coding Standard.
 *
 * @package VIPCS\WordPressVIPMinimum
 */

namespace WordPressVIPMinimum\Tests\UserExperience;

use PHP_CodeSniffer\Tests\Standards\AbstractSniffUnitTest;

/**
 * Unit test class for the AdminBarRemoval sniff.
 *
 * @covers \WordPressVIPMinimum\Sniffs\UserExperience\AdminBarRemovalSniff
 */
class AdminBarRemovalUnitTest extends AbstractSniffUnitTest {

	/**
	 * Returns the lines where errors should occur.
	 *
	 * @return array<int, int> Key is the line number, value is the number of expected errors.
	 */
	public function getErrorList() {

		return [
			3   => 1,
			6   => 1,
			9   => 1,
			12  => 1,
			13  => 1,
			19  => 1,
			20  => 1,
			21  => 1,
			26  => 1,
			32  => 1,
			56  => 1,
			57  => 1,
			58  => 1,
			68  => 1,
			69  => 1,
			70  => 1,
			81  => 1,
			82  => 1,
			83  => 1,
			92  => 1,
			103 => 1,
			104 => 1,
			105 => 1,
			135 => 1,
			136 => 1,
			137 => 1,
			141 => 1,
			144 => 1,
			145 => 1,
			149 => 1,
			163 => 1,
			169 => 1,
			172 => 1,
			177 => 1,
			178 => 1,
			179 => 1,
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
