<?php
/**
 * Unit test class for WordPressVIPMinimum Coding Standard.
 *
 * @package VIPCS\WordPressVIPMinimum
 */

namespace WordPressVIPMinimum\Tests\VIP;

use PHP_CodeSniffer\Tests\Standards\AbstractSniffUnitTest;

/**
 * Unit test class for the FileSystemWritesDisallow sniff.
 *
 * @package VIPCS\WordPressVIPMinimum
 *
 * @since   0.5.0
 */
class FileSystemWritesDisallowUnitTest extends AbstractSniffUnitTest {

	/**
	 * Returns the lines where errors should occur.
	 *
	 * @return array <int line number> => <int number of errors>
	 */
	public function getErrorList() {
		return [
			3  => 1,
			9  => 1,
			10 => 1,
			12 => 1,
			17 => 1,
			18 => 1,
			19 => 1,
			20 => 1,
			21 => 1,
			22 => 1,
			23 => 1,
			24 => 1,
			25 => 1,
			26 => 1,
			27 => 1,
			28 => 1,
			30 => 1,
			31 => 1,
			33 => 1,
			34 => 1,
			35 => 1,
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
		return [];
	}

}
