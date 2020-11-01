<?php
/**
 * Unit test class for WordPressVIPMinimum Coding Standard.
 *
 * @package VIPCS\WordPressVIPMinimum
 */

namespace WordPressVIPMinimum\Tests\Security;

use PHP_CodeSniffer\Tests\Standards\AbstractSniffUnitTest;

/**
 * Unit test class for the unescaped output in Underscore.js templating engine.
 *
 * @package VIPCS\WordPressVIPMinimum
 *
 * @covers \WordPressVIPMinimum\Sniffs\Security\UnderscorejsSniff
 */
class UnderscorejsUnitTest extends AbstractSniffUnitTest {

	/**
	 * Get a list of all test files to check.
	 *
	 * @param string $testFileBase The base path that the unit tests files will have.
	 *
	 * @return string[]
	 */
	protected function getTestFiles( $testFileBase ) {
		return [
			$testFileBase . 'inc',
			$testFileBase . 'js',
			__DIR__ . DIRECTORY_SEPARATOR . 'Gruntfile.js',
		];
	}

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
	 * @param string $testFile The name of the file being tested.
	 *
	 * @return array <int line number> => <int number of warnings>
	 */
	public function getWarningList( $testFile = '' ) {
		switch ( $testFile ) {
			case 'UnderscorejsUnitTest.inc':
				return [
					6   => 1,
					14  => 1,
					22  => 1,
					23  => 1,
					28  => 1,
					32  => 1,
					38  => 3,
					45  => 1,
					46  => 1,
					47  => 1,
					58  => 1,
					60  => 1,
					114 => 1,
					115 => 1,
				];

			case 'UnderscorejsUnitTest.js':
				return [
					4  => 1,
					5  => 1,
					7  => 1,
					9  => 1,
					12 => 1,
					44 => 1,
					45 => 1,
				];

			default:
				return [];
		}
	}

}
