<?php

/**
 * Minimum viable integration test for the WordPressVIPMinimum ruleset
 *
 * To run the test, make sure you have the PHPCS, including the WordPressVIPMinimum standard, installed and executable
 * using the `phpcs --standard=WordPressVIPMinimum` command.
 *
 * To run the integration test, simply execute this file using the PHP CLI and check the output:
 *
 * ```
 * $ php ruleset_test.php
 * No issues found. All tests passed!
 * ```
 */

$expected = array(
	'errors' => array(
		4 => 1,
		9 => 1,
		16 => 1,
		19 => 1,
		24 => 1,
		40 => 1,
		43 => 1,
		45 => 1,
		48 => 1,
		49 => 1,
		53 => 1,
		54 => 1,
		61 => 1,
		76 => 1,
		80 => 1,
		89 => 1,
		93 => 1,
		97 => 1,
		101 => 1,
		102 => 1,
		110 => 1,
		116 => 1,
		124 => 1,
		127 => 1,
	),
	'warnings' => array(
		9 => 1,
		19 => 1,
		23 => 1,
		24 => 1,
		28 => 1,
		32 => 1,
		36 => 1,
		44 => 1,
		65 => 1,
		70 => 1,
		85 => 1,
		97 => 1,
		108 => 1,
		109 => 1,
	),
);

$errors = $warnings = array();
$total_issues = 0;

// Collect the PHPCS result
$output = shell_exec( 'phpcs --standard=WordPressVIPMinimum --report=json ./ruleset_test.inc' );

$output = json_decode( $output, true );

foreach( $output['files'] as $file => $issues ) {
	foreach( $issues['messages'] as $issue ) {
		if ( 'ERROR' === $issue['type'] ) {
			$errors[$issue['line']] = ( isset( $errors[$issue['line']] ) ) ? $errors[$issue['line']]++ : 1;
		} else {
			$warnings[$issue['line']] = ( isset( $warnings[$issue['line']] ) ) ? $warnings[$issue['line']]++ : 1;
		}
	}
}

// Check for missing expected values
foreach ( $expected as $type => $lines ) {
	if ( 'errors' === $type ) {
		foreach( $lines as $line => $number ) {
			if ( false === isset( $errors[$line] ) ) {
				printf( 'Expected %d errors, found %d on line %d' . PHP_EOL, $number, 0, $line );
				$total_issues++;
			} else if ( $errors[$line] !== $number ) {
				printf( 'Expected %d errors, found %d on line %d' . PHP_EOL, $number, $errors[$line], $line );
				$total_issues++;
			}
			unset( $errors[$line] );
		}
	} else {
		foreach( $lines as $line => $number ) {
			if ( false === isset( $warnings[$line] ) ) {
				printf( 'Expected %d warnings, found %d on line %d' . PHP_EOL, $number, 0, $line );
				$total_issues++;
			} else if ( $warnings[$line] !== $number ) {
				printf( 'Expected %d warnings, found %d on line %d' . PHP_EOL, $number, $warnings[$line], $line );
				$total_issues++;
			}
			unset( $warnings[$line] );
			
		}
	}
}

// Check for extra values which were not expected
foreach( $errors as $line => $number ) {
	if ( false === isset( $expected['errors'][$line] ) ) {
		printf( 'Expected %d errors, found %d on line %d' . PHP_EOL, 0, $number, $line );
		$total_issues++;
	} else if ( $number !== $expected['errors'][$line] ) {
		printf( 'Expected %d errors, found %d on line %d' . PHP_EOL, $expected['errors'][$line], $number, $line );
		$total_issues++;
	}
}

foreach( $warnings as $line => $number ) {
	if ( false === isset( $expected['warnings'][$line] ) ) {
		printf( 'Expected %d warnings, found %d on line %d' . PHP_EOL, 0, $number, $line );
		$total_issues++;
	} else if ( $number !== $expected['warnings'][$line] ) {
		printf( 'Expected %d warnings, found %d on line %d' . PHP_EOL, $expected['warnings'][$line], $number, $line );
		$total_issues++;
	}
}

if ( 0 === $total_issues ) {
	printf( 'No issues found. All tests passed!' . PHP_EOL );
}