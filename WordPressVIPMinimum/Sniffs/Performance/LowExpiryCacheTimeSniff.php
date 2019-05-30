<?php
/**
 * WordPressVIPMinimum Coding Standard.
 *
 * @package VIPCS\WordPressVIPMinimum
 */

namespace WordPressVIPMinimum\Sniffs\Performance;

use WordPressCS\WordPress\AbstractFunctionParameterSniff;

/**
 * This sniff throws a warning when low cache times are set.
 *
 * @package VIPCS\WordPressVIPMinimum
 *
 * @since 0.4.0
 */
class LowExpiryCacheTimeSniff extends AbstractFunctionParameterSniff {

	/**
	 * The group name for this group of functions.
	 *
	 * @var string
	 */
	protected $group_name = 'cache_functions';

	/**
	 * Functions this sniff is looking for.
	 *
	 * @var array The only requirement for this array is that the top level
	 *            array keys are the names of the functions you're looking for.
	 *            Other than that, the array can have arbitrary content
	 *            depending on your needs.
	 */
	protected $target_functions = [
		'wp_cache_set'     => true,
		'wp_cache_add'     => true,
		'wp_cache_replace' => true,
	];

	/**
	 * List of WP time constants, see https://codex.wordpress.org/Easier_Expression_of_Time_Constants.
	 *
	 * @var array
	 */
	protected $wp_time_constants = [
		'MINUTE_IN_SECONDS' => 60,
		'HOUR_IN_SECONDS'   => 3600,
		'DAY_IN_SECONDS'    => 86400,
		'WEEK_IN_SECONDS'   => 604800,
		'MONTH_IN_SECONDS'  => 2592000,
		'YEAR_IN_SECONDS'   => 31536000,
	];

	/**
	 * Process the parameters of a matched function.
	 *
	 * @param int    $stackPtr        The position of the current token in the stack.
	 * @param array  $group_name      The name of the group which was matched.
	 * @param string $matched_content The token content (function name) which was matched.
	 * @param array  $parameters      Array with information about the parameters.
	 * @return int|void Integer stack pointer to skip forward or void to continue
	 *                  normal file processing.
	 */
	public function process_parameters( $stackPtr, $group_name, $matched_content, $parameters ) {
		if ( false === isset( $parameters[4] ) ) {
			// If no cache expiry time, bail (i.e. we don't want to flag for something like feeds where it is cached indefinitely until a hook runs).
			return;
		}

		$time = $parameters[4]['raw'];

		if ( false === is_numeric( $time ) ) {
			// If using time constants, we need to convert to a number.
			$time = str_replace( array_keys( $this->wp_time_constants ), $this->wp_time_constants, $time );

			if ( preg_match( '#^[\s\d+*/-]+$#', $time ) > 0 ) {
				$time = eval( "return $time;" ); // phpcs:ignore Squiz.PHP.Eval -- No harm here.
			}
		}

		if ( $time < 300 ) {
			$message = 'Low cache expiry time of "%s", it is recommended to have 300 seconds or more.';
			$data    = [ $parameters[4]['raw'] ];
			$this->phpcsFile->addWarning( $message, $stackPtr, 'LowCacheTime', $data );
		}
	}
}
