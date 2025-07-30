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
 * @covers \WordPressVIPMinimum\Sniffs\Performance\TaxonomyMetaInOptionsSniff
 */
class TaxonomyMetaInOptionsUnitTest extends AbstractSniffUnitTest {

	/**
	 * Returns the lines where errors should occur.
	 *
	 * @return array<int, int> Key is the line number, value is the number of expected errors.
	 */
	public function getErrorList() {
		return [];
	}

	/**
	 * Returns the lines where warnings should occur.
	 *
	 * @return array<int, int> Key is the line number, value is the number of expected warnings.
	 */
	public function getWarningList() {
		return [
			50 => 1,
			51 => 1,
			52 => 1,
			53 => 1,
			54 => 1,
			55 => 1,
		];
	}
}
