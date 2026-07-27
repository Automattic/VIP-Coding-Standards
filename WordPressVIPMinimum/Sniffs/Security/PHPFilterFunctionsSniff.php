<?php
/**
 * WordPressVIPMinimum Coding Standard.
 *
 * @package VIPCS\WordPressVIPMinimum
 * @link https://github.com/Automattic/VIP-Coding-Standards
 * @license https://opensource.org/license/gpl-2-0 GPL-2.0
 */

namespace WordPressVIPMinimum\Sniffs\Security;

use PHP_CodeSniffer\Util\Tokens;
use PHPCSUtils\Utils\PassedParameters;
use WordPressCS\WordPress\AbstractFunctionParameterSniff;

/**
 * This sniff ensures that proper sanitization is occurring when PHP's filter_* functions are used.
 *
 * {@internal The $options parameter for filter_var_array() and filter_input_array() can take either an
 * integer (filter constant) or an array with options, which could include an option setting the filter constant.
 * At this time, this sniff does not handle an array with options being passed.}
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
	 * Functions this sniff is looking for and information on which parameter to check for in those function calls.
	 *
	 * @var array<string, array{param_position: int, param_name: string}> Key is the function name.
	 */
	protected $target_functions = [
		'filter_var'         => [
			'param_position' => 2,
			'param_name'     => 'filter',
		],
		'filter_input'       => [
			'param_position' => 3,
			'param_name'     => 'filter',
		],
		'filter_var_array'   => [
			'param_position' => 2,
			'param_name'     => 'options',
		],
		'filter_input_array' => [
			'param_position' => 2,
			'param_name'     => 'options',
		],
	];

	/**
	 * List of restricted filter names.
	 *
	 * @var array<string, bool>
	 */
	private $restricted_filters = [
		'FILTER_DEFAULT'    => true,
		'FILTER_UNSAFE_RAW' => true,
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
	 * @return int|void Integer stack pointer to skip forward or void to continue
	 *                  normal file processing.
	 */
	public function process_parameters( $stackPtr, $group_name, $matched_content, $parameters ) {
		$param_position = $this->target_functions[ $matched_content ]['param_position'];
		$param_name     = $this->target_functions[ $matched_content ]['param_name'];

		$target_param = PassedParameters::getParameterFromStack( $parameters, $param_position, $param_name );
		if ( $target_param === false ) {
			/*
			 * Check for PHP 5.6+ argument unpacking.
			 *
			 * No need for extensive defensive coding, we already know this is syntactically a valid function call,
			 * otherwise this method would not have been reached.
			 */
			$tokens       = $this->phpcsFile->getTokens();
			$open_parens  = $this->phpcsFile->findNext( Tokens::$emptyTokens, ( $stackPtr + 1 ), null, true );
			$has_ellipses = $this->phpcsFile->findNext( T_ELLIPSIS, ( $open_parens + 1 ), $tokens[ $open_parens ]['parenthesis_closer'] );

			if ( $has_ellipses !== false ) {
				$target_nesting_level = 1;
				if ( isset( $tokens[ $open_parens ]['nested_parenthesis'] ) ) {
					$target_nesting_level = ( count( $tokens[ $open_parens ]['nested_parenthesis'] ) + 1 );
				}

				if ( $target_nesting_level === count( $tokens[ $has_ellipses ]['nested_parenthesis'] ) ) {
					// Bow out as undetermined.
					return;
				}
			}

			$message = 'Missing $%s parameter for "%s()".';
			$data    = [ $param_name, $matched_content ];

			// Error codes should probably be made more descriptive, but that would be a BC-break.
			$error_code = 'MissingSecondParameter';
			if ( $param_position === 3 ) {
				$error_code = 'MissingThirdParameter';
			}

			$this->phpcsFile->addWarning( $message, $stackPtr, $error_code, $data );
			return;
		}

		if ( isset( $this->restricted_filters[ $target_param['clean'] ] ) ) {
			$first_non_empty = $this->phpcsFile->findNext( Tokens::$emptyTokens, $target_param['start'], ( $target_param['end'] + 1 ), true );

			$message = 'Please use an appropriate filter to sanitize, as "%s" does no filtering, see: http://php.net/manual/en/filter.filters.sanitize.php.';
			$data    = [ $target_param['clean'] ];
			$this->phpcsFile->addWarning( $message, $first_non_empty, 'RestrictedFilter', $data );
		}
	}
}
