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
$expected = array(
	'errors'   => array(
		4   => 1,
		9   => 1,
		16  => 1,
		19  => 1,
		24  => 1,
		40  => 1,
		43  => 1,
		45  => 1,
		48  => 1,
		49  => 1,
		53  => 1,
		54  => 1,
		61  => 1,
		76  => 1,
		80  => 1,
		89  => 1,
		93  => 1,
		97  => 1,
		101 => 1,
		102 => 1,
		110 => 1,
		116 => 1,
		124 => 1,
		127 => 1,
		129 => 1,
		131 => 1,
		133 => 1,
		157 => 1,
		162 => 1,
	),
	'warnings' => array(
		9   => 1,
		19  => 1,
		23  => 1,
		24  => 1,
		28  => 1,
		36  => 1,
		44  => 1,
		65  => 1,
		70  => 1,
		85  => 1,
		97  => 1,
		108 => 1,
		109 => 1,
		135 => 1,
		137 => 1,
		139 => 1,
		153 => 1,
		155 => 1,
		160 => 1,
	),
	'messages' => array(
		129 => array(
			'get_children() performs a no-LIMIT query by default, make sure to set a reasonable posts_per_page. get_children() will do a -1 query by default, a maximum of 100 should be used.',
		),
	),
);

/**
 * Class PHPCS_Ruleset_Test
 */
class PHPCS_Ruleset_Test {

	/**
	 * Numbers of errors for each line.
	 *
	 * @var array
	 */
	private $errors = array();

	/**
	 * Numbers of Warnings for each line.
	 *
	 * @var array
	 */
	private $warnings = array();

	/**
	 * Messages reported by PHPCS.
	 *
	 * @var array
	 */
	private $messages = array();

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
	public $expected = array();

	/**
	 * Init the object by processing the test file.
	 *
	 * @param array $expected The array of expected errors, warnings and messages.
	 */
	public function __construct( $expected = array() ) {
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
				$this->messages[ $issue['line'] ] = ( false === isset( $this->messages[ $issue['line'] ] ) || false === is_array( $this->messages[ $issue['line'] ] ) ) ? array( $issue['message'] ) : array_merge( $this->messages[ $issue['line'] ], array( $issue['message'] ) );
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
				if ( false === isset( $this->$type[ $line ] ) ) {
					$this->error_warning_message( $number, $type, 0, $line );
					$this->total_issues ++;
				} elseif ( $this->$type[ $line ] !== $number ) {
					$this->error_warning_message( $number, $type, $this->$type[ $line ], $line );
					$this->total_issues ++;
				}
				unset( $this->$type[ $line ] );
			}
		}
	}

	/**
	 * Check whether there are no unexpected numbers of errors and warnings.
	 */
	private function check_unexpected_values() {
		foreach ( array( 'errors', 'warnings' ) as $type ) {
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

