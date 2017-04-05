<?php
/**
 * Unit test class for WordPressVIPMinimum Coding Standard.
 */

/**
 * Unit test class for the AdminBarRemoval sniff.
 */
class WordPressVIPMinimum_Tests_VIP_CommentUnitTest extends AbstractSniffUnitTest {

	/**
	 * Returns the lines where errors should occur.
	 *
	 * @return array <int line number> => <int number of errors>
	 */
	public function getErrorList() {
		return array(
		);
	}

	/**
	 * Returns the lines where warnings should occur.
	 *
	 * @return array <int line number> => <int number of warnings>
	 */
	public function getWarningList() {
		return array(
			10 => 1,
			28 => 1,
		);

	}

} // End class.