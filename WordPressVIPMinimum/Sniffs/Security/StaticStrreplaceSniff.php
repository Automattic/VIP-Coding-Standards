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
use WordPressCS\WordPress\AbstractFunctionParameterSniff;

/**
 * Restricts usage of str_replace with all 3 params being static.
 */
class StaticStrreplaceSniff extends AbstractFunctionParameterSniff {

	/**
	 * The group name for this group of functions.
	 *
	 * @var string
	 */
	protected $group_name = 'str_replace';

	/**
	 * Functions this sniff is looking for.
	 *
	 * @var array<string, bool> Key is the function name, value irrelevant.
	 */
	protected $target_functions = [
		'str_replace' => true,
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

		$openBracket = $this->phpcsFile->findNext( Tokens::$emptyTokens, $stackPtr + 1, null, true );

		if ( $this->tokens[ $openBracket ]['code'] !== T_OPEN_PARENTHESIS ) {
			return;
		}

		$next_start_ptr = $openBracket + 1;
		for ( $i = 0; $i < 3; $i++ ) {
			$param_ptr = $this->phpcsFile->findNext( array_merge( Tokens::$emptyTokens, [ T_COMMA ] ), $next_start_ptr, null, true );

			if ( $this->tokens[ $param_ptr ]['code'] === T_ARRAY ) {
				$openBracket = $this->phpcsFile->findNext( Tokens::$emptyTokens, $param_ptr + 1, null, true );
				if ( $this->tokens[ $openBracket ]['code'] !== T_OPEN_PARENTHESIS ) {
					return;
				}

				// Find the closing bracket.
				$closeBracket = $this->tokens[ $openBracket ]['parenthesis_closer'];

				$array_item_ptr = $this->phpcsFile->findNext( array_merge( Tokens::$emptyTokens, [ T_COMMA ] ), $openBracket + 1, $closeBracket, true );
				while ( $array_item_ptr !== false ) {

					if ( $this->tokens[ $array_item_ptr ]['code'] !== T_CONSTANT_ENCAPSED_STRING ) {
						return;
					}
					$array_item_ptr = $this->phpcsFile->findNext( array_merge( Tokens::$emptyTokens, [ T_COMMA ] ), $array_item_ptr + 1, $closeBracket, true );
				}

				$next_start_ptr = $closeBracket + 1;
				continue;

			}

			if ( $this->tokens[ $param_ptr ]['code'] !== T_CONSTANT_ENCAPSED_STRING ) {
				return;
			}

			$next_start_ptr = $param_ptr + 1;

		}

		$message = 'This code pattern is often used to run a very dangerous shell programs on your server. The code in these files needs to be reviewed, and possibly cleaned.';
		$this->phpcsFile->addError( $message, $stackPtr, 'StaticStrreplace' );
	}
}
