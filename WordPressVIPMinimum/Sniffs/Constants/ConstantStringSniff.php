<?php
/**
 * WordPressVIPMinimum Coding Standard.
 *
 * @package VIPCS\WordPressVIPMinimum
 * @link https://github.com/Automattic/VIP-Coding-Standards
 * @license https://opensource.org/license/gpl-2-0 GPL-2.0
 */

namespace WordPressVIPMinimum\Sniffs\Constants;

use PHP_CodeSniffer\Util\Tokens;
use PHPCSUtils\Utils\PassedParameters;
use WordPressCS\WordPress\AbstractFunctionParameterSniff;

/**
 * Sniff for properly using constant name when checking whether a constant is defined.
 */
class ConstantStringSniff extends AbstractFunctionParameterSniff {

	/**
	 * The group name for this group of functions.
	 *
	 * @var string
	 */
	protected $group_name = 'constant_functions';

	/**
	 * Functions this sniff is looking for.
	 *
	 * @var array<string, bool> Key is the function name, value irrelevant.
	 */
	protected $target_functions = [
		'define'  => true,
		'defined' => true,
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
		$param = PassedParameters::getParameterFromStack( $parameters, 1, 'constant_name' );
		if ( $param === false ) {
			// Target parameter not found.
			return;
		}

		$search             = Tokens::$emptyTokens;
		$search[ T_STRING ] = T_STRING;

		$has_only_tstring = $this->phpcsFile->findNext( $search, $param['start'], $param['end'] + 1, true );
		if ( $has_only_tstring !== false ) {
			// Came across something other than a T_STRING token. Ignore.
			return;
		}

		$tstring_token = $this->phpcsFile->findNext( T_STRING, $param['start'], $param['end'] + 1 );

		$message = 'The `%s()` function expects to be passed the constant name as a text string.';
		$data    = [ $this->tokens[ $stackPtr ]['content'] ];
		$this->phpcsFile->addError( $message, $tstring_token, 'NotCheckingConstantName', $data );
	}
}
