<?php
/**
 * Unit test class for WordPressVIPMinimum Coding Standard.
 *
 * @package VIPCS\WordPressVIPMinimum
 */

namespace WordPressVIPMinimum\Tests\Functions;

use PHP_CodeSniffer\Tests\Standards\AbstractSniffUnitTest;

/**
 * Unit test class for the DynamicCalls sniff.
 *
 * @covers \WordPressVIPMinimum\Sniffs\Functions\DynamicCallsSniff
 */
class DynamicCallsUnitTest extends AbstractSniffUnitTest {

	/**
	 * Returns the lines where errors should occur.
	 *
	 * @param string $testFile The name of the file being tested.
	 *
	 * @return array<int, int> Key is the line number, value is the number of expected errors.
	 */
	public function getErrorList( $testFile = '' ) {

		switch ( $testFile ) {
			case 'DynamicCallsUnitTest.1.inc':
				return [
					9  => 1,
					15 => 1,
					35 => 1,
					40 => 1,
					45 => 1,
					47 => 1,
				];

			default:
				return [];
		}
	}

	/**
	 * Returns the lines where warnings should occur.
	 *
	 * @return array<int, int> Key is the line number, value is the number of expected warnings.
	 */
	public function getWarningList() {
		return [];
	}
}
