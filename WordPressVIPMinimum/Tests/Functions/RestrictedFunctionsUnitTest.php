<?php
/**
 * Unit test class for WordPressVIPMinimum Coding Standard.
 *
 * @package VIPCS\WordPressVIPMinimum
 */

namespace WordPressVIPMinimum\Tests\Functions;

use PHP_CodeSniffer\Tests\Standards\AbstractSniffUnitTest;

/**
 * Unit test class for the RestrictedFunctions sniff.
 *
 * @package VIPCS\WordPressVIPMinimum
 */
class RestrictedFunctionsUnitTest extends AbstractSniffUnitTest {

	/**
	 * Returns the lines where errors should occur.
	 *
	 * @return array <int line number> => <int number of errors>
	 */
	public function getErrorList() {
		return [
			19  => 1,
			27  => 1,
			28  => 1,
			29  => 1,
			30  => 1,
			31  => 1,
			32  => 1,
			33  => 1,
			34  => 1,
			37  => 1,
			40  => 1,
			43  => 1,
			46  => 1,
			50  => 1,
			53  => 1,
			56  => 1,
			59  => 1,
			62  => 1,
			75  => 1,
			76  => 1,
			82  => 1,
			83  => 1,
			84  => 1,
			85  => 1,
			88  => 1,
			91  => 1,
			94  => 1,
			97  => 1,
			98  => 1,
			99  => 1,
			100 => 1,
			101 => 1,
			104 => 1,
			107 => 1,
			141 => 1,
			142 => 1,
			143 => 1,
			144 => 1,
			145 => 1,
			146 => 1,
			147 => 1,
			148 => 1,
			149 => 1,
			150 => 1,
			151 => 1,
			152 => 1,
			153 => 1,
			154 => 1,
			155 => 1,
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
			168 => 1,
			174 => 1,
			175 => 1,
			177 => 1,
			182 => 1,
			183 => 1,
			184 => 1,
			185 => 1,
			186 => 1,
			187 => 1,
			188 => 1,
			189 => 1,
			190 => 1,
			191 => 1,
			192 => 1,
			193 => 1,
			194 => 1,
			195 => 1,
			196 => 1,
			197 => 1,
			198 => 1,
			199 => 1,
			200 => 1,
			218 => 1,
			220 => 1,
			222 => 1,
		];
	}

	/**
	 * Returns the lines where warnings should occur.
	 *
	 * @return array <int line number> => <int number of warnings>
	 */
	public function getWarningList() {
		return [
			110 => 1,
			111 => 1,
			114 => 1,
			118 => 1,
			119 => 1,
			122 => 1,
			125 => 1,
			130 => 1,
			131 => 1,
			132 => 1,
			137 => 1,
			138 => 1,
			139 => 1,
			208 => 1,
		];
	}

}
