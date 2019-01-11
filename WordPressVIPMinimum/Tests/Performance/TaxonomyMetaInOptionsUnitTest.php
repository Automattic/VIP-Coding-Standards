<?php
/**
 * Unit test class for WordPressVIPMinimum Coding Standard.
 *
 * @package VIPCS\WordPressVIPMinimum
 */

namespace WordPressVIPMinimum\Tests\Performance;

use PHP_CodeSniffer\Tests\Standards\AbstractSniffUnitTest;

/**
 * Unit test class for the TaxonomyMetaInOptions sniff.
 *
 * @package VIPCS\WordPressVIPMinimum
 */
class TaxonomyMetaInOptionsUnitTest extends AbstractSniffUnitTest {

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
			3 => 1,
			4 => 1,
			5 => 1,
			6 => 1,
			7 => 1,
			8 => 1,
		];
	}

}
