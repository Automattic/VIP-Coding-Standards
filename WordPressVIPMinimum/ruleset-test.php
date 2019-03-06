<?php
/**
 * Ruleset test for the WordPressVIPMinimum ruleset
 *
 * The expected errors, warnings, and messages listed here, should match what is expected to be reported
 * when ruleset-test.inc is run against PHP_CodeSniffer and the WordPressVIPMinimum standard.
 *
 * To run the test, see ../bin/ruleset-tests.
 *
 * @package VIPCS\WordPressVIPMinimum
 */

namespace WordPressVIPMinimum;

// Expected values.
$expected = [
	'errors'   => [
		4   => 1,
		8   => 1,
		15  => 1,
		18  => 1,
		23  => 1,
		39  => 1,
		42  => 1,
		47  => 1,
		48  => 1,
		52  => 1,
		53  => 1,
		60  => 1,
		75  => 1,
		79  => 1,
		88  => 1,
		92  => 1,
		96  => 1,
		104 => 1,
		110 => 1,
		118 => 1,
		121 => 1,
		123 => 1,
		125 => 1,
		127 => 1,
		151 => 1,
		153 => 1,
		155 => 1,
		164 => 1,
		168 => 1,
		172 => 1,
		180 => 1,
		183 => 1,
		186 => 1,
		189 => 1,
		192 => 1,
		193 => 1,
		194 => 1,
		195 => 1,
		196 => 1,
		197 => 1,
		198 => 1,
		199 => 1,
		200 => 1,
		201 => 1,
		202 => 1,
		203 => 1,
		204 => 1,
		205 => 1,
	],
	'warnings' => [
		18  => 1,
		22  => 1,
		23  => 1,
		27  => 1,
		35  => 1,
		43  => 1,
		44  => 1,
		64  => 1,
		69  => 1,
		84  => 1,
		96  => 1,
		102 => 1,
		103 => 1,
		129 => 1,
		131 => 1,
		133 => 1,
		147 => 1,
		149 => 1,
		158 => 1,
		160 => 1,
		162 => 1,
		166 => 1,
		170 => 1,
		177 => 1,
		201 => 1,
	],
	'messages' => [
		123 => [
			'`get_children()` performs a no-LIMIT query by default, make sure to set a reasonable `posts_per_page`. `get_children()` will do a -1 query by default, a maximum of 100 should be used.',
		],
	],
];

require __DIR__ . '/../tests/RulesetTest.php';

// Run the tests!
$test = new RulesetTest( 'WordPressVIPMinimum', $expected );
if ( $test->passes() ) {
	// phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped
	printf( 'All WordPressVIPMinimum tests passed!' . PHP_EOL );
	exit( 0 );
}

exit( 1 );
