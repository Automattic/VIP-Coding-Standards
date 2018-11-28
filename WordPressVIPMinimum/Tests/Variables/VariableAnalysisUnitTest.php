<?php
/**
 * Unit test class for WordPressVIPMinimum Coding Standard.
 *
 * @package VIPCS\WordPressVIPMinimum
 */

namespace WordPressVIPMinimum\Tests\Variables;

use PHP_CodeSniffer\Tests\Standards\AbstractSniffUnitTest;

/**
 * Unit test class for the Variable Analysis sniff.
 *
 * @package VIPCS\WordPressVIPMinimum
 */
class VariableAnalysisUnitTest extends AbstractSniffUnitTest {

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
		$warningList = [
			5 => 2,
		];

		// PHP prior to version 7.x does not properly process the $e.
		if ( true === version_compare( PHP_VERSION, '7.0.0', '>=' ) ) {
			$warningList[18] = 2;
		}

		return $warningList;
	}

}
