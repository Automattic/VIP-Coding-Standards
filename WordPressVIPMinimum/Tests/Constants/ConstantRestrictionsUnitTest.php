<?php
/**
 * Unit test class for WordPressVIPMinimum Coding Standard.
 */

/**
 * Unit test class for the AdminBarRemoval sniff.
 */
class WordPressVIPMinimum_Tests_Constants_ConstantRestrictionsUnitTest extends AbstractSniffUnitTest {

	/**
	 * Returns the lines where errors should occur.
	 *
	 * @return array <int line number> => <int number of errors>
	 */
	public function getErrorList() {
		return array(
			11 => 1,
		);
	}

	/**
	 * Returns the lines where warnings should occur.
	 *
	 * @return array <int line number> => <int number of warnings>
	 */
	public function getWarningList() {
		return array(
			3 => 1,
			7 => 2,
		);

	}

} // End class.
