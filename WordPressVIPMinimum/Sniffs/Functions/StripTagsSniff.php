<?php
/**
 * WordPressVIPMinimum Coding Standard.
 *
 * @package VIPCS\WordPressVIPMinimum
 * @link https://github.com/Automattic/VIP-Coding-Standards
 * @license https://opensource.org/license/gpl-2-0 GPL-2.0
 */

namespace WordPressVIPMinimum\Sniffs\Functions;

use PHPCSUtils\Utils\PassedParameters;
use WordPressCS\WordPress\AbstractFunctionParameterSniff;

/**
 * This sniff ensures proper tag stripping.
 *
 * @since 0.4.0
 */
class StripTagsSniff extends AbstractFunctionParameterSniff {

	/**
	 * The group name for this group of functions.
	 *
	 * @var string
	 */
	protected $group_name = 'strip_functions';

	/**
	 * Functions this sniff is looking for.
	 *
	 * @var array<string, bool> Key is the function name, value irrelevant.
	 */
	protected $target_functions = [
		'strip_tags' => true,
	];

	/**
	 * Process the parameters of a matched function.
	 *
	 * @param int    $stackPtr        The position of the current token in the stack.
	 * @param string $group_name      The name of the group which was matched.
	 * @param string $matched_content The token content (function name) which was matched
	 *                                in lowercase.
	 * @param array  $parameters      Array with information about the parameters.
	 *
	 * @return void
	 */
	public function process_parameters( $stackPtr, $group_name, $matched_content, $parameters ) {
		$string_param       = PassedParameters::getParameterFromStack( $parameters, 1, 'string' );
		$allowed_tags_param = PassedParameters::getParameterFromStack( $parameters, 2, 'allowed_tags' );

		if ( $string_param !== false && $allowed_tags_param === false ) {
			$this->add_warning( $stackPtr, 'StripTagsOneParameter' );
		} elseif ( $allowed_tags_param !== false ) {
			$message = '`strip_tags()` does not strip CSS and JS in between the script and style tags. Use `wp_kses()` instead to allow only the HTML you need.';
			$this->phpcsFile->addWarning( $message, $stackPtr, 'StripTagsTwoParameters' );
		} else {
			$this->add_warning( $stackPtr );
		}
	}

	/**
	 * Process the function if no parameters were found.
	 *
	 * @param int    $stackPtr        The position of the current token in the stack.
	 * @param string $group_name      The name of the group which was matched.
	 * @param string $matched_content The token content (function name) which was matched
	 *                                in lowercase.
	 *
	 * @return void
	 */
	public function process_no_parameters( $stackPtr, $group_name, $matched_content ) {
		$this->add_warning( $stackPtr );
	}

	/**
	 * Process the function if it is used as a first class callable.
	 *
	 * @param int    $stackPtr        The position of the current token in the stack.
	 * @param string $group_name      The name of the group which was matched.
	 * @param string $matched_content The token content (function name) which was matched
	 *                                in lowercase.
	 *
	 * @return void
	 */
	public function process_first_class_callable( $stackPtr, $group_name, $matched_content ) {
		$this->add_warning( $stackPtr );
	}

	/**
	 * Add a warning if the function is used at all.
	 *
	 * @param int    $stackPtr   The position of the current token in the stack.
	 * @param string $error_code Error code to use for the warning.
	 *
	 * @return void
	 */
	private function add_warning( $stackPtr, $error_code = 'Used' ) {
		$message = '`strip_tags()` does not strip CSS and JS in between the script and style tags. Use `wp_strip_all_tags()` to strip all tags.';
		$this->phpcsFile->addWarning( $message, $stackPtr, $error_code );
	}
}
