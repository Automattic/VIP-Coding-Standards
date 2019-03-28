<?php
/**
 * WordPressVIPMinimum Coding Standard.
 *
 * @package VIPCS\WordPressVIPMinimum
 * @link https://github.com/Automattic/VIP-Coding-Standards
 * @license https://opensource.org/licenses/MIT MIT
 */

namespace WordPressVIPMinimum;

/**
 * Class for running ruleset tests.
 *
 * The test works as follows:
 *
 *  - Run PHP_CodeSniffer against the ruleset-test.inc file.
 *  - Store the results of the output into the $error, $warning and $messages fields.
 *  - Compare the expected errors, warnings, and messages against the actual errors,
 *    warnings, and messages, and see if there is any discrepancies on either side.
 *  - If there were discrepancies, it will list them.
 *  - Report a boolean back to the client code whether there was any discrepancies or not.
 */
class RulesetTest {

	/**
	 * Expected errors, warnings and messages.
	 *
	 * This is the giant array in the ruleset-test.php files.
	 *
	 * @var array
	 */
	public $expected = [];

	/**
	 * Numbers of Errors for each line, as reported by PHP_CodeSniffer.
	 *
	 * @var array
	 */
	private $errors = [];

	/**
	 * Numbers of Warnings for each line, as reported by PHP_CodeSniffer.
	 *
	 * @var array
	 */
	private $warnings = [];

	/**
	 * Messages, as reported by PHP_CodeSniffer.
	 *
	 * @var array
	 */
	private $messages = [];

	/**
	 * Whether any issues were found when comparing expected to output.
	 *
	 * @var bool
	 */
	private $found_issue = false;

	/**
	 * Name of the ruleset e.g. WordPressVIPMinimum or WordPress-VIP-Go.
	 *
	 * @var string
	 */
	private $ruleset;

	/**
	 * String returned by PHP_CodeSniffer report for an Error.
	 */
	const ERROR_TYPE = 'ERROR';

	/**
	 * Init the object by processing the test file.
	 *
	 * @param string $ruleset  Name of the ruleset e.g. WordPressVIPMinimum or WordPress-VIP-Go.
	 * @param array  $expected The array of expected errors, warnings and messages.
	 */
	public function __construct( $ruleset, $expected = [] ) {
		$this->ruleset  = $ruleset;
		$this->expected = $expected;

		// Travis support.
		if ( false === getenv( 'PHPCS_BIN' ) ) {
			// phpcs:ignore
			putenv( 'PHPCS_BIN=phpcs' );
		}

		$output = $this->collect_phpcs_result();

		if ( ! is_object( $output ) || empty( $output ) ) {
			// phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped
			printf( 'The PHPCS command checking the ruleset haven\'t returned any issues. Bailing ...' . PHP_EOL );
			exit( 1 ); // Die early, if we don't have any output.
		}

		$this->process_output( $output );
	}

	/**
	 * Run all the tests and return whether test was successful.
	 *
	 * @return bool
	 */
	public function passes() {
		$this->run();

		return ! $this->found_issue;
	}

	/**
	 * Run all the tests.
	 */
	private function run() {
		// Check for missing expected values.
		$this->check_missing_expected_values();
		// Check for extra values which were not expected.
		$this->check_unexpected_values();
		// Check for expected messages.
		$this->check_messages();
	}

	/**
	 * Collect the PHP_CodeSniffer result.
	 *
	 * @return array Returns an associative array with keys of `totals` and `files`.
	 */
	private function collect_phpcs_result() {
		$shell = sprintf( '$PHPCS_BIN --severity=1 --standard=%1$s --report=json ./%1$s/ruleset-test.inc', $this->ruleset );
		// phpcs:ignore
		$output = shell_exec( $shell );

		return json_decode( $output );
	}

	/**
	 * Process the Decoded JSON output from PHP_CodeSniffer.
	 *
	 * @param stdClass $output Deconded JSON output from PHP_CodeSniffer.
	 */
	private function process_output( $output ) {
		foreach ( $output->files as $file ) {
			$this->process_file( $file );
		}
	}

	/**
	 * Process single file of within PHP_CodeSniffer results.
	 *
	 * @param \stdClass $file File output.
	 */
	private function process_file( $file ) {
		foreach ( $file->messages as $violation ) {
			$this->process_violation( $violation );
		}
	}

	/**
	 * Process single violation within PHP_CodeSniffer results.
	 *
	 * @param \stdClass $violation Violation data.
	 */
	private function process_violation( $violation ) {
		if ( $this->violation_type_is_error( $violation ) ) {
			$this->add_error_for_line( $violation->line );
		} else {
			$this->add_warning_for_line( $violation->line );
		}

		$this->add_message_for_line( $violation->line, $violation->message );
	}

	/**
	 * Check if violation is an error.
	 *
	 * @param \stdClass $violation Violation data.
	 * @return bool True if string matches error type.
	 */
	private function violation_type_is_error( $violation ) {
		return self::ERROR_TYPE === $violation->type;
	}

	/**
	 * Add 1 to the number of errors for the given line.
	 *
	 * @param int $line Line number.
	 */
	private function add_error_for_line( $line ) {
		$this->errors[ $line ] = isset( $this->errors[ $line ] ) ? ++$this->errors[ $line ] : 1;
	}

	/**
	 * Add 1 to the number of errors for the given line.
	 *
	 * @param int $line Line number.
	 */
	private function add_warning_for_line( $line ) {
		$this->warnings[ $line ] = isset( $this->warnings[ $line ] ) ? ++$this->warnings[ $line ] : 1;
	}

	/**
	 * Add message for the given line.
	 *
	 * @param int    $line    Line number.
	 * @param string $message Message.
	 */
	private function add_message_for_line( $line, $message ) {
		$this->messages[ $line ] = ( ! isset( $this->messages[ $line ] ) || ! is_array( $this->messages[ $line ] ) ) ? [ $message ] : array_merge( $this->messages[ $line ], [ $message ] );
	}

	/**
	 * Check whether all expected numbers of errors and warnings are present in the output.
	 */
	private function check_missing_expected_values() {
		foreach ( $this->expected as $type => $lines ) {
			if ( 'messages' === $type ) {
				continue;
			}

			foreach ( $lines as $line_number => $expected_count_of_type_violations ) {
				if ( ! isset( $this->{$type}[ $line_number ] ) ) {
					$this->error_warning_message( $expected_count_of_type_violations, $type, 0, $line_number );
				} elseif ( $this->{$type}[ $line_number ] !== $expected_count_of_type_violations ) {
					$this->error_warning_message( $expected_count_of_type_violations, $type, $this->{$type}[ $line_number ], $line_number );
				}

				unset( $this->{$type}[ $line_number ] );
			}
		}
	}

	/**
	 * Check whether there are no unexpected numbers of errors and warnings.
	 */
	private function check_unexpected_values() {
		foreach ( [ 'errors', 'warnings' ] as $type ) {
			foreach ( $this->$type as $line_number => $actual_count_of_type_violations ) {
				if ( ! isset( $this->expected[ $type ][ $line_number ] ) ) {
					$this->error_warning_message( 0, $type, $actual_count_of_type_violations, $line_number );
				} elseif ( $actual_count_of_type_violations !== $this->expected[ $type ][ $line_number ] ) {
					$this->error_warning_message( $this->expected[ $type ][ $line_number ], $type, $actual_count_of_type_violations, $line_number );
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
		foreach ( $this->expected['messages'] as $line_number => $messages ) {
			foreach ( $messages as $message ) {
				if ( ! isset( $this->messages[ $line_number ] ) ) {
					// phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped
					printf( 'Expected "%s" but found no message for line %d' . PHP_EOL, $message, $line_number );
					$this->found_issue = true;
				} elseif ( ! in_array( $message, $this->messages[ $line_number ], true ) ) {
					// phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped
					printf( 'Expected message "%s" was not found for line %d.' . PHP_EOL, $message, $line_number );
					$this->found_issue = true;
				}
			}
		}
		foreach ( $this->messages as $line_number => $messages ) {
			foreach ( $messages as $message ) {
				if ( isset( $this->expected['messages'][ $line_number ] ) ) {
					if ( ! in_array( $message, $this->expected['messages'][ $line_number ], true ) ) {
						// phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped
						printf( 'Unexpected message "%s" was found for line %d.' . PHP_EOL, $message, $line_number );
						$this->found_issue = true;
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
		// phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped
		printf( 'Expected %d %s, found %d on line %d.' . PHP_EOL, $expected, $type, $number, $line );
		$this->found_issue = true;
	}
}
