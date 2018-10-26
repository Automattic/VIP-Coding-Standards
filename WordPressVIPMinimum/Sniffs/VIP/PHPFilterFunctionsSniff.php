<?php
/**
 * WordPressVIPMinimum Coding Standard.
 *
 * @package VIPCS\WordPressVIPMinimum
 */

namespace WordPressVIPMinimum\Sniffs\VIP;

use WordPress\AbstractFunctionParameterSniff;

/**
 * This sniff ensures that proper sanitization is occurring when PHP's filter_* functions are used.
 *
 * @package VIPCS\WordPressVIPMinimum
 *
 * @since 0.4.0
 */
class PHPFilterFunctionsSniff extends AbstractFunctionParameterSniff {

	/**
	 * The group name for this group of functions.
	 *
	 * @var string
	 */
	protected $group_name = 'php_filter_functions';

	/**
	 * Functions this sniff is looking for.
	 *
	 * @var array The only requirement for this array is that the top level
	 *            array keys are the names of the functions you're looking for.
	 *            Other than that, the array can have arbitrary content
	 *            depending on your needs.
	 */
	protected $target_functions = [
		'filter_var'         => true,
		'filter_input'       => true,
		'filter_var_array'   => true,
		'filter_input_array' => true,
	];

	/**
	 * List of restricted filter names.
	 *
	 * @var array
	 */
	private $restricted_filters = [
		'FILTER_DEFAULT'    => true,
		'FILTER_UNSAFE_RAW' => true,
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
		if ( 'filter_input' === $matched_content ) {
			if ( 2 === count( $parameters ) ) {
				$this->phpcsFile->addWarning(
					sprintf( 'Missing third parameter for "%s".', $matched_content ),
					$stackPtr,
					'MissingThirdParameter'
				);
			}

			if ( isset( $parameters[3] ) && isset( $this->restricted_filters[ $parameters[3]['raw'] ] ) ) {
				$this->phpcsFile->addWarning(
					sprintf( 'Please use an appropriate filter to sanitize, as "%s" does no filtering, see: http://php.net/manual/en/filter.filters.sanitize.php.', strtoupper( $parameters[3]['raw'] ) ),
					$stackPtr,
					'RestrictedFilter'
				);
			}
		} else {
			if ( 1 === count( $parameters ) ) {
				$this->phpcsFile->addWarning(
					sprintf( 'Missing second parameter for "%s".', $matched_content ),
					$stackPtr,
					'MissingSecondParameter'
				);
			}

			if ( isset( $parameters[2] ) && isset( $this->restricted_filters[ $parameters[2]['raw'] ] ) ) {
				$this->phpcsFile->addWarning(
					sprintf( 'Please use an appropriate filter to sanitize, as "%s" does no filtering, see http://php.net/manual/en/filter.filters.sanitize.php.', strtoupper( $parameters[2]['raw'] ) ),
					$stackPtr,
					'RestrictedFilter'
				);
			}
		}
	}
}
