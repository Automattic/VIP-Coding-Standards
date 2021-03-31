<?php
/**
 * Unit test class for WordPressVIPMinimum Coding Standard.
 *
 * @package VIPCS\WordPressVIPMinimum
 */

namespace WordPressVIPMinimum\Tests\Functions;

use PHP_CodeSniffer\Tests\Standards\AbstractSniffUnitTest;

/**
 * Unit test class for the OptionsRaceCondition sniff.
 *
 * @package VIPCS\WordPressVIPMinimum
 *
 * @covers \WordPressVIPMinimum\Sniffs\Functions\OptionsRaceConditionSniff
 */
class OptionsRaceConditionUnitTest extends AbstractSniffUnitTest {

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
			21  => 1,
			27  => 1,
			32  => 1,
			37  => 1,
			45  => 1,
			50  => 1,
			59  => 1,
			65  => 1,
			88  => 1,
			94  => 1,
			103 => 1,
			109 => 1,
			111 => 1,
		];
	}

}
