<?php
/**
 * WordPressVIPMinimum Coding Standard.
 *
 * @package VIPCS\WordPressVIPMinimum
 */

namespace WordPressVIPMinimum\Sniffs\Performance;

use PHP_CodeSniffer\Util\Tokens;
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
		if ( isset( $parameters[4] ) === false ) {
			// If no cache expiry time, bail (i.e. we don't want to flag for something like feeds where it is cached indefinitely until a hook runs).
			return;
		}

		$param          = $parameters[4];
		$tokensAsString = '';

		for ( $i = $param['start']; $i <= $param['end']; $i++ ) {
			if ( isset( Tokens::$emptyTokens[ $this->tokens[ $i ]['code'] ] ) === true ) {
				$tokensAsString .= ' ';
				continue;
			}

			if ( $this->tokens[ $i ]['code'] === T_LNUMBER
				|| $this->tokens[ $i ]['code'] === T_DNUMBER
			) {
				// Integer or float.
				$tokensAsString .= $this->tokens[ $i ]['content'];
				continue;
			}

			if ( $this->tokens[ $i ]['code'] === T_MULTIPLY
				|| $this->tokens[ $i ]['code'] === T_DIVIDE
				|| $this->tokens[ $i ]['code'] === T_MINUS
			) {
				$tokensAsString .= $this->tokens[ $i ]['content'];
				continue;
			}

			// If using time constants, we need to convert to a number.
			if ( $this->tokens[ $i ]['code'] === T_STRING
				&& isset( $this->wp_time_constants[ $this->tokens[ $i ]['content'] ] ) === true
			) {
				$tokensAsString .= $this->wp_time_constants[ $this->tokens[ $i ]['content'] ];
				continue;
			}
		}

		if ( $tokensAsString === '' ) {
			// Nothing found to evaluate.
			return;
		}

		$tokensAsString = trim( $tokensAsString );
		$time           = eval( "return $tokensAsString;" ); // phpcs:ignore Squiz.PHP.Eval -- No harm here.

		if ( $time < 300 ) {
			$message = 'Low cache expiry time of %s seconds detected. It is recommended to have 300 seconds or more.';
			$data    = [ $time ];

			if ( (string) $time !== $tokensAsString ) {
				$message .= ' Found: "%s"';
				$data[]   = $tokensAsString;
			}

			$reportPtr = $this->phpcsFile->findNext( Tokens::$emptyTokens, $param['start'], $param['end'], true );

			$this->phpcsFile->addWarning( $message, $reportPtr, 'LowCacheTime', $data );
		}
	}
}
