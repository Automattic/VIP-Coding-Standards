<?php
/**
 * Unit test class for WordPressVIPMinimum Coding Standard.
 *
 * @package VIPCS\WordPressVIPMinimum
 */

namespace WordPressVIPMinimum\Tests\Files;

use PHP_CodeSniffer\Tests\Standards\AbstractSniffUnitTest;
/**
 * Unit test class for the IncludingFile sniff.
 *
 * @package VIPCS\WordPressVIPMinimum
 */
class IncludingNonPHPFileUnitTest extends AbstractSniffUnitTest {

	/**
	 * Returns the lines where errors should occur.
	 *
	 * @return array <int line number> => <int number of errors>
	 */
	public function getErrorList() {
		return [
			15 => 1,
			17 => 1,
			19 => 1,
			21 => 1,
			23 => 1,
			25 => 1,
			27 => 1,
			29 => 1,
			31 => 1,
			33 => 1,
			35 => 1,
			37 => 1,
			39 => 1,
			43 => 1,
			45 => 1,
			47 => 1,
			49 => 1,
		];
	}

	/**
	 * Returns the lines where warnings should occur.
	 *
	 * @return array <int line number> => <int number of warnings>
	 */
	public function getWarningList() {
		return [];
	}

}
