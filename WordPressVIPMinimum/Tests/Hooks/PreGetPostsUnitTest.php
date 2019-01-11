<?php
/**
 * Unit test class for WordPressVIPMinimum Coding Standard.
 *
 * @package VIPCS\WordPressVIPMinimum
 */

namespace WordPressVIPMinimum\Tests\Hooks;

use PHP_CodeSniffer\Tests\Standards\AbstractSniffUnitTest;

/**
 * Unit test class for the PreGetPosts sniff.
 *
 * @package VIPCS\WordPressVIPMinimum
 */
class PreGetPostsUnitTest extends AbstractSniffUnitTest {

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
			8  => 1,
			11 => 1,
			29 => 1,
			32 => 1,
			52 => 1,
			57 => 1,
			87 => 1,
		];
	}

}
