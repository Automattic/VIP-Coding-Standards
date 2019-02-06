<?php
/**
 * Minimum viable integration test for the WordPress-VIP-Go ruleset
 *
 * To run the test, make sure you have the PHPCS, including the WordPress-VIP-Go standard, installed and executable
 * using the `phpcs --standard=WordPress-VIP-Go` command.
 *
 * To run the integration test, simply execute this file using the PHP CLI and check the output:
 *
 * ```
 * $ php ruleset_test.php
 * No issues found. All tests passed!
 * ```
 *
 * @package VIPCS\WordPressVIPGo
 */

namespace WordPressVIPGo;

// Expected values.
$expected = [
	'errors'   => [
		4   => 1,
		7   => 1,
		10  => 1,
		14  => 1,
		17  => 1,
		20  => 1,
		23  => 1,
		26  => 1,
		29  => 1,
		32  => 1,
		35  => 1,
		38  => 1,
		41  => 1,
		44  => 1,
		47  => 1,
		50  => 1,
		53  => 1,
		56  => 1,
		62  => 1,
		68  => 1,
		70  => 1,
		75  => 1,
		76  => 1,
		80  => 1,
		83  => 1,
		86  => 1,
		185 => 1,
		200 => 1,
		201 => 1,
		207 => 1,
		208 => 1,
		241 => 1,
		243 => 1,
		245 => 1,
		248 => 1,
		249 => 1,
		250 => 1,
		255 => 1,
		256 => 1,
		257 => 1,
		265 => 1,
		266 => 1,
		267 => 1,
		272 => 1,
		275 => 1,
		276 => 1,
		278 => 1,
		279 => 1,
	],
	'warnings' => [
		92  => 1,
		105 => 1,
		110 => 1,
		114 => 1,
		115 => 1,
		119 => 1,
		122 => 1,
		123 => 1,
		124 => 1,
		126 => 1,
		128 => 1,
		129 => 1,
		132 => 1,
		135 => 1,
		138 => 1,
		139 => 1,
		143 => 1,
		147 => 1,
		148 => 1,
		149 => 1,
		150 => 1,
		151 => 1,
		155 => 1,
		159 => 1,
		162 => 1,
		166 => 1,
		170 => 1,
		174 => 1,
		177 => 1,
		181 => 1,
		189 => 1,
		194 => 1,
		195 => 1,
		196 => 1,
		197 => 1,
		211 => 1,
		212 => 1,
		215 => 1,
		216 => 1,
		219 => 1,
		220 => 1,
		221 => 1,
		224 => 1,
		225 => 1,
		226 => 1,
		227 => 1,
		228 => 1,
		232 => 1,
		237 => 1,
		285 => 1,
		289 => 1,
		293 => 1,
	],
	'messages' => [
		4   => [
			'File system writes only work in /tmp/ and inside the /uploads/ folder on VIP Go. To do filesystem writes you must use the WP_Filesystem class, using functions such as delete() won\'t work or will return unexpected results. Read more here: https://vip.wordpress.com/documentation/using-wp_filesystem-instead-of-direct-file-access-functions/',
		],
		7   => [
			'File system writes only work in /tmp/ and inside the /uploads/ folder on VIP Go. To do filesystem writes you must use the WP_Filesystem class, using functions such as file_put_contents() won\'t work or will return unexpected results. Read more here: https://vip.wordpress.com/documentation/using-wp_filesystem-instead-of-direct-file-access-functions/',
		],
		10  => [
			'File system writes only work in /tmp/ and inside the /uploads/ folder on VIP Go. To do filesystem writes you must use the WP_Filesystem class, using functions such as flock() won\'t work or will return unexpected results. Read more here: https://vip.wordpress.com/documentation/using-wp_filesystem-instead-of-direct-file-access-functions/',
		],
		14  => [
			'File system writes only work in /tmp/ and inside the /uploads/ folder on VIP Go. To do filesystem writes you must use the WP_Filesystem class, using functions such as fputcsv() won\'t work or will return unexpected results. Read more here: https://vip.wordpress.com/documentation/using-wp_filesystem-instead-of-direct-file-access-functions/',
		],
		17  => [
			'File system writes only work in /tmp/ and inside the /uploads/ folder on VIP Go. To do filesystem writes you must use the WP_Filesystem class, using functions such as fputs() won\'t work or will return unexpected results. Read more here: https://vip.wordpress.com/documentation/using-wp_filesystem-instead-of-direct-file-access-functions/',
		],
		20  => [
			'File system writes only work in /tmp/ and inside the /uploads/ folder on VIP Go. To do filesystem writes you must use the WP_Filesystem class, using functions such as fwrite() won\'t work or will return unexpected results. Read more here: https://vip.wordpress.com/documentation/using-wp_filesystem-instead-of-direct-file-access-functions/',
		],
		23  => [
			'File system writes only work in /tmp/ and inside the /uploads/ folder on VIP Go. To do filesystem writes you must use the WP_Filesystem class, using functions such as ftruncate() won\'t work or will return unexpected results. Read more here: https://vip.wordpress.com/documentation/using-wp_filesystem-instead-of-direct-file-access-functions/',
		],
		26  => [
			'File system writes only work in /tmp/ and inside the /uploads/ folder on VIP Go. To do filesystem writes you must use the WP_Filesystem class, using functions such as is_writable() won\'t work or will return unexpected results. Read more here: https://vip.wordpress.com/documentation/using-wp_filesystem-instead-of-direct-file-access-functions/',
		],
		29  => [
			'File system writes only work in /tmp/ and inside the /uploads/ folder on VIP Go. To do filesystem writes you must use the WP_Filesystem class, using functions such as is_writeable() won\'t work or will return unexpected results. Read more here: https://vip.wordpress.com/documentation/using-wp_filesystem-instead-of-direct-file-access-functions/',
		],
		32  => [
			'File system writes only work in /tmp/ and inside the /uploads/ folder on VIP Go. To do filesystem writes you must use the WP_Filesystem class, using functions such as link() won\'t work or will return unexpected results. Read more here: https://vip.wordpress.com/documentation/using-wp_filesystem-instead-of-direct-file-access-functions/',
		],
		35  => [
			'File system writes only work in /tmp/ and inside the /uploads/ folder on VIP Go. To do filesystem writes you must use the WP_Filesystem class, using functions such as rename() won\'t work or will return unexpected results. Read more here: https://vip.wordpress.com/documentation/using-wp_filesystem-instead-of-direct-file-access-functions/',
		],
		38  => [
			'File system writes only work in /tmp/ and inside the /uploads/ folder on VIP Go. To do filesystem writes you must use the WP_Filesystem class, using functions such as symlink() won\'t work or will return unexpected results. Read more here: https://vip.wordpress.com/documentation/using-wp_filesystem-instead-of-direct-file-access-functions/',
		],
		41  => [
			'File system writes only work in /tmp/ and inside the /uploads/ folder on VIP Go. To do filesystem writes you must use the WP_Filesystem class, using functions such as tempnam() won\'t work or will return unexpected results. Read more here: https://vip.wordpress.com/documentation/using-wp_filesystem-instead-of-direct-file-access-functions/',
		],
		44  => [
			'File system writes only work in /tmp/ and inside the /uploads/ folder on VIP Go. To do filesystem writes you must use the WP_Filesystem class, using functions such as touch() won\'t work or will return unexpected results. Read more here: https://vip.wordpress.com/documentation/using-wp_filesystem-instead-of-direct-file-access-functions/',
		],
		47  => [
			'File system writes only work in /tmp/ and inside the /uploads/ folder on VIP Go. To do filesystem writes you must use the WP_Filesystem class, using functions such as unlink() won\'t work or will return unexpected results. Read more here: https://vip.wordpress.com/documentation/using-wp_filesystem-instead-of-direct-file-access-functions/',
		],
		50  => [
			'Due to server-side caching, server-side based client related logic might not work. We recommend implementing client side logic in JavaScript instead.',
		],
		53  => [
			'Due to server-side caching, server-side based client related logic might not work. We recommend implementing client side logic in JavaScript instead.',
		],
		56  => [
			'Due to server-side caching, server-side based client related logic might not work. We recommend implementing client side logic in JavaScript instead.',
		],
		62  => [
			'This is currently deprecated in PHP 7.0 and will be removed in the future. This will cause a fatal error on newer versions of PHP and should be fixed.',
		],
		80  => [
			'File operations should use WP_Filesystem methods instead of direct PHP filesystem calls. Found: fclose(). Read more here: https://vip.wordpress.com/documentation/using-wp_filesystem-instead-of-direct-file-access-functions/',
		],
		83  => [
			'File operations should use WP_Filesystem methods instead of direct PHP filesystem calls. Found: fopen(). Read more here: https://vip.wordpress.com/documentation/using-wp_filesystem-instead-of-direct-file-access-functions/',
		],
		86  => [
			'file_get_contents() is uncached. If this is being used to query a remote file please use wpcom_vip_file_get_contents() instead. If it\'s used for a local file please use WP_Filesystem instead. Read more here: https://vip.wordpress.com/documentation/using-wp_filesystem-instead-of-direct-file-access-functions/',
		],
		110 => [
			'Having more than 100 posts returned per page may lead to severe performance problems.',
		],
		114 => [
			'Having more than 100 posts returned per page may lead to severe performance problems.',
		],
		115 => [
			'Having more than 100 posts returned per page may lead to severe performance problems.',
		],
		143 => [
			'attachment_url_to_postid() is uncached, please use wpcom_vip_attachment_url_to_postid() instead.',
		],
		155 => [
			'get_page_by_title() is uncached, please use wpcom_vip_get_page_by_title() instead.',
		],
		159 => [
			'get_children() is uncached and performs a no limit query. Please use get_posts or WP_Query instead. More Info: https://vip.wordpress.com/documentation/vip-go/uncached-functions/',
		],
		170 => [
			'url_to_postid() is uncached, please use wpcom_vip_url_to_postid() instead.',
		],
		211 => [
			'Scripts should be registered/enqueued via `wp_enqueue_script`. This can improve the site\'s performance due to script concatenation.',
		],
		212 => [
			'Scripts should be registered/enqueued via `wp_enqueue_script`. This can improve the site\'s performance due to script concatenation.',
		],
		215 => [
			'Stylesheets should be registered/enqueued via `wp_enqueue_style`. This can improve the site\'s performance due to styles concatenation.',
		],
		216 => [
			'Stylesheets should be registered/enqueued via `wp_enqueue_style`. This can improve the site\'s performance due to styles concatenation.',
		],
		289 => [
			'Switch to blog may not work as expected since it only changes the database context for the blog and does not load the plugins or theme of that site. This means that filters or hooks that the blog you are switching to uses will not run.',
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
		$output = shell_exec( '$PHPCS_BIN --severity=1 --standard=WordPress-VIP-Go --report=json ./WordPress-VIP-Go/ruleset-test.inc' );

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
