<?php
/**
 * WordPressVIPMinimum Coding Standard.
 *
 * @package VIPCS\WordPressVIPMinimum
 */

namespace WordPressVIPMinimum\Sniffs\Functions;

use WordPressCS\WordPress\AbstractFunctionParameterSniff;

/**
 * This sniff ensures that redirect functions are encouraged to include the third argument.
 *
 * @package VIPCS\WordPressVIPMinimum
 *
 * @since 2.1.0
 */
class RedirectParametersSniff extends AbstractFunctionParameterSniff {

	/**
	 * The group name for this group of functions.
	 *
	 * @var string
	 */
	protected $group_name = 'redirect_functions';

	/**
	 * Functions this sniff is looking for.
	 *
	 * @var array The only requirement for this array is that the top level
	 *            array keys are the names of the functions you're looking for.
	 *            Other than that, the array can have arbitrary content
	 *            depending on your needs.
	 */
	protected $target_functions = [
		'wp_redirect'      => true,
		'wp_safe_redirect' => true,
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
		if ( count( $parameters ) < 3 ) {
			// No third parameter.
			$message = 'Missing recommended third parameter for "%s". This helps to identify the source of the redirect which is useful for debugging.';
			$data    = [ $matched_content ];
			$this->phpcsFile->addWarning( $message, $stackPtr, 'MissingThirdParameter', $data );
		}
	}

}
