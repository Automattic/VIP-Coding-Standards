<?php
/**
 * Unit test class for WordPressVIPMinimum Coding Standard.
 *
 * @package VIPCS\WordPressVIPMinimum
 * @link    https://github.com/Automattic/VIP-Coding-Standards
 * @license https://github.com/Automattic/VIP-Coding-Standards/blob/master/LICENSE.md GPL v2 or later.
 */

namespace WordPressVIPMinimum\Sniffs\Compatibility;

use PHP_CodeSniffer\Tests\Standards\AbstractSniffUnitTest;

/**
 * Unit test class for the RobotstxtSniff sniff.
 */
class RobotstxtUnitTest extends AbstractSniffUnitTest {

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
			9 => 1,
			7 => 1,
		];
	}
}
