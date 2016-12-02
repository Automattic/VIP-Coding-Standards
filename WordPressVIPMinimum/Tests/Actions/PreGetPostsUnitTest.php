<?php
/**
 * Unit test class for WordPressVIPMinimum Coding Standard.
 */

/**
 * Unit test class for the AdminBarRemoval sniff.
 */
class WordPressVIPMinimum_Tests_Actions_PreGetPostsUnitTest extends AbstractSniffUnitTest {

	/**
	 * Returns the lines where errors should occur.
	 *
	 * @return array <int line number> => <int number of errors>
	 */
	public function getErrorList() {
		return array();
	}

	/**
	 * Returns the lines where warnings should occur.
	 *
	 * @return array <int line number> => <int number of warnings>
	 */
	public function getWarningList() {
		return array(
			8 => 1,
			11 => 1,
			29 => 1,
			32 => 1,
			52 => 1,
			57 => 1,
			87 => 1,
		);

	}

} // End class.
