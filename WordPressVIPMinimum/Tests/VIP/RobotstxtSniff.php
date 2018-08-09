<?php
/**
 * Unit test class for WordPressVIPMinimum Coding Standard.
 */

/**
 * Unit test class for the RobotstxtSniff sniff.
 */
class WordPressVIPMinimum_Sniffs_VIP_RobotstxtSniffUnitTest extends AbstractSniffUnitTest {

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
			9 => 1,
			7 => 1,
		);
	}
}
