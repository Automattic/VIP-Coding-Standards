<?php
/**
 * Unit test class for WordPressVIPMinimum Coding Standard.
 *
 * @package VIPCS\WordPressVIPMinimum
 */

namespace WordPressVIPMinimum\Tests\Classes;

use PHP_CodeSniffer\Tests\Standards\AbstractSniffUnitTest;

/**
 * Unit test class for the DeclarationCompatibility sniff.
 *
 * @covers \WordPressVIPMinimum\Sniffs\Classes\DeclarationCompatibilitySniff
 */
class DeclarationCompatibilityUnitTest extends AbstractSniffUnitTest {

	/**
	 * Returns the lines where errors should occur.
	 *
	 * @param string $testFile The name of the file being tested.
	 *
	 * @return array<int, int> Key is the line number, value is the number of expected errors.
	 */
	public function getErrorList( $testFile = '' ) {
		switch ( $testFile ) {
			case 'DeclarationCompatibilityUnitTest.1.inc':
				return [
					53  => 1,
					54  => 1,
					55  => 1,
					56  => 1,
					57  => 1,
					58  => 1,
					59  => 1,
					60  => 1,
					61  => 1,
					62  => 1,
					63  => 1,
					64  => 1,
					68  => 1,
					69  => 1,
					70  => 1,
					71  => 1,
					75  => 1,
					76  => 1,
					77  => 1,
					78  => 1,
					79  => 1,
					80  => 1,
					81  => 1,
					82  => 1,
					83  => 1,
					84  => 1,
					85  => 1,
					86  => 1,
					87  => 1,
					88  => 1,
					89  => 1,
					90  => 1,
					91  => 1,
					92  => 1,
					96  => 1,
					97  => 1,
					98  => 1,
					99  => 1,
					100 => 1,
					104 => 1,
					105 => 1,
					106 => 1,
					107 => 1,
					108 => 1,
					109 => 1,
					110 => 1,
					111 => 1,
					112 => 1,
					113 => 1,
					114 => 1,
					115 => 1,
					156 => 1,
					157 => 1,
					158 => 1,
					159 => 1,
					160 => 1,
					161 => 1,
					162 => 1,
					163 => 1,
					164 => 1,
					165 => 1,
					166 => 1,
					171 => 1,
					172 => 1,
				];

			case 'DeclarationCompatibilityUnitTest.2.inc':
				return [
					43  => 1,
					44  => 1,
					45  => 1,
					46  => 1,
					47  => 1,
					48  => 1,
					49  => 1,
					50  => 1,
					51  => 1,
					55  => 1,
					56  => 1,
					57  => 1,
					58  => 1,
					59  => 1,
					60  => 1,
					61  => 1,
					62  => 1,
					66  => 1,
					67  => 1,
					68  => 1,
					72  => 1,
					73  => 1,
					74  => 1,
					75  => 1,
					79  => 1,
					80  => 1,
					81  => 1,
					82  => 1,
					83  => 1,
					84  => 1,
					88  => 1,
					92  => 1,
					93  => 1,
					97  => 1,
					98  => 1,
					99  => 1,
					100 => 1,
					101 => 1,
					102 => 1,
					103 => 1,
					104 => 1,
					105 => 1,
					132 => 1,
					133 => 1,
					134 => 1,
					135 => 1,
					136 => 1,
					137 => 1,
					138 => 1,
					143 => 1,
					144 => 1,
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
