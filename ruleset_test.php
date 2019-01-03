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
		176 => 1,
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
	],
	'messages' => [
		123 => [
			'`get_children()` performs a no-LIMIT query by default, make sure to set a reasonable `posts_per_page`. `get_children()` will do a -1 query by default, a maximum of 100 should be used.',
		],
	],
];

/**
 * Class PHPCS_Ruleset_Test
 */
class PHPCS_Ruleset_Test {

	/**
	 * Numbers of errors for each line.
	 *
	 * @var array
	 */
	private $errors = [];

	/**
	 * Numbers of Warnings for each line.
	 *
	 * @var array
	 */
	private $warnings = [];

	/**
	 * Messages reported by PHPCS.
	 *
	 * @var array
	 */
	private $messages = [];

	/**
	 * Number of found issues.
	 *
	 * @var int
	 */
	private $total_issues = 0;

	/**
	 * Expected errors, warnings and messages.
	 *
	 * @var array
	 */
	public $expected = [];

	/**
	 * Init the object by processing the test file.
	 *
	 * @param array $expected The array of expected errors, warnings and messages.
	 */
	public function __construct( $expected = [] ) {
		$this->expected = $expected;

		// Travis support.
		if ( false === getenv( 'PHPCS_BIN' ) ) {
			// @codingStandardsIgnoreLine
			putenv( 'PHPCS_BIN=phpcs' );
		}

		// Collect the PHPCS result.
		// @codingStandardsIgnoreLine
		$output = shell_exec( '$PHPCS_BIN --standard=WordPressVIPMinimum --report=json ./ruleset_test.inc' );

		$output = json_decode( $output, true );

		if ( false === is_array( $output ) || true === empty( $output ) ) {
			printf( 'The PHPCS command checking the ruleset haven\'t returned any issues. Bailing ...' . PHP_EOL ); // XSS OK.
			exit( 1 ); // Die early, if we don't have any output.
		}

		foreach ( $output['files'] as $file => $issues ) {
			foreach ( $issues['messages'] as $issue ) {
				if ( 'ERROR' === $issue['type'] ) {
					$this->errors[ $issue['line'] ] = ( isset( $this->errors[ $issue['line'] ] ) ) ? $this->errors[ $issue['line'] ]++ : 1;
				} else {
					$this->warnings[ $issue['line'] ] = ( isset( $this->warnings[ $issue['line'] ] ) ) ? $this->warnings[ $issue['line'] ]++ : 1;
				}
				$this->messages[ $issue['line'] ] = ( false === isset( $this->messages[ $issue['line'] ] ) || false === is_array( $this->messages[ $issue['line'] ] ) ) ? [ $issue['message'] ] : array_merge( $this->messages[ $issue['line'] ], [ $issue['message'] ] );
			}
		}
	}

	/**
	 * Run all the tests.
	 *
	 * @return int
	 */
	public function run() {
		// Check for missing expected values.
		$this->check_missing_expected_values();
		// Check for extra values which were not expected.
		$this->check_unexpected_values();
		// Check for expected messages.
		$this->check_messages();

		return $this->total_issues;
	}

	/**
	 * Check whether all expected numbers of errors and warnings are present.
	 */
	private function check_missing_expected_values() {
		foreach ( $this->expected as $type => $lines ) {
			if ( 'messages' === $type ) {
				continue;
			}
			foreach ( $lines as $line => $number ) {
				if ( false === isset( $this->{$type}[ $line ] ) ) {
					$this->error_warning_message( $number, $type, 0, $line );
					$this->total_issues ++;
				} elseif ( $this->{$type}[ $line ] !== $number ) {
					$this->error_warning_message( $number, $type, $this->{$type}[ $line ], $line );
					$this->total_issues ++;
				}
				unset( $this->{$type}[ $line ] );
			}
		}
	}

	/**
	 * Check whether there are no unexpected numbers of errors and warnings.
	 */
	private function check_unexpected_values() {
		foreach ( [ 'errors', 'warnings' ] as $type ) {
			foreach ( $this->$type as $line => $number ) {
				if ( false === isset( $expected[ $type ][ $line ] ) ) {
					$this->error_warning_message( 0, $type, $number, $line );
					$this->total_issues ++;
				} elseif ( $number !== $expected[ $type ][ $line ] ) {
					$this->error_warning_message( $expected[ $type ][ $line ], $type, $number, $line );
					$this->total_issues ++;
				}
			}
		}
	}

	/**
	 * Check whether all expected messages are present and whether there are no unexpected messages.
	 *
	 * @return void
	 */
	private function check_messages() {
		foreach ( $this->expected['messages'] as $line => $messages ) {
			foreach ( $messages as $message ) {
				if ( false === isset( $this->messages[ $line ] ) ) {
					printf( 'Expected "%s" but found no message for line %d' . PHP_EOL, $message, $line ); // XSS OK.
					$this->total_issues ++;
				} elseif ( false === in_array( $message, $this->messages[ $line ], true ) ) {
					printf( 'Expected message "%s" was not found for line %d' . PHP_EOL, $message, $line ); // XSS OK.
					$this->total_issues ++;
				}
			}
		}
		foreach ( $this->messages as $line => $messages ) {
			foreach ( $messages as $message ) {
				if ( true === isset( $this->expected['messages'][ $line ] ) ) {
					if ( false === in_array( $message, $this->expected['messages'][ $line ], true ) ) {
						printf( 'Unexpected message "%s" was found for line %d' . PHP_EOL, $message, $line ); // XSS OK.
						$this->total_issues ++;
					}
				}
			}
		}
	}

	/**
	 * Print out the message reporting found issues.
	 *
	 * @param int    $expected Expected number of issues.
	 * @param string $type The type of the issue.
	 * @param int    $number Real number of issues.
	 * @param int    $line Line number.
	 */
	private function error_warning_message( $expected, $type, $number, $line ) {
		printf( 'Expected %d %s, found %d on line %d' . PHP_EOL, $expected, $type, $number, $line ); // XSS OK.
	}
}

// Run the tests!
$test = new PHPCS_Ruleset_Test( $expected );
if ( 0 === $test->run() ) {
	printf( 'No issues found. All tests passed!' . PHP_EOL ); // XSS OK.
	exit( 0 );
} else {
	exit( 1 );
}
