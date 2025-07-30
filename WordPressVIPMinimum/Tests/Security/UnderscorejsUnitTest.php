<?php
/**
 * Unit test class for WordPressVIPMinimum Coding Standard.
 *
 * @package VIPCS\WordPressVIPMinimum
 */

namespace WordPressVIPMinimum\Tests\Security;

use PHP_CodeSniffer\Tests\Standards\AbstractSniffUnitTest;

/**
 * Unit test class for the unescaped output in Underscore.js templating engine.
 *
 * @covers \WordPressVIPMinimum\Sniffs\Security\UnderscorejsSniff
 */
class UnderscorejsUnitTest extends AbstractSniffUnitTest {

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
			6   => 1,
			14  => 1,
			22  => 1,
			23  => 1,
			28  => 1,
			32  => 1,
			38  => 3,
			45  => 1,
			46  => 1,
			47  => 1,
			58  => 1,
			60  => 1,
			114 => 1,
			115 => 1,
		];
	}
}
