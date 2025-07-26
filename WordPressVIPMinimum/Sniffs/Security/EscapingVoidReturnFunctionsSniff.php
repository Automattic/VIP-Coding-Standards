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
use WordPressCS\WordPress\Helpers\PrintingFunctionsTrait;

/**
 * Flag functions that don't return anything, yet are wrapped in an escaping function call.
 *
 * E.g. esc_html( _e( 'foo' ) );
 *
 * @uses \WordPressCS\WordPress\Helpers\PrintingFunctionsTrait::$customPrintingFunctions
 */
class EscapingVoidReturnFunctionsSniff extends AbstractFunctionParameterSniff {

	use PrintingFunctionsTrait;

	/**
	 * The group name for this group of functions.
	 *
	 * @var string
	 */
	protected $group_name = 'escaping_void';

	/**
	 * Functions this sniff is looking for.
	 *
	 * @var array<string, true> Keys are target functions, value irrelevant.
	 */
	protected $target_functions = [
		'esc_*'      => true,
		'tag_escape' => true,
		'wp_kses*'   => true,
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
		$next_token = $this->phpcsFile->findNext( Tokens::$emptyTokens, $stackPtr + 1, null, true );
		if ( $this->tokens[ $next_token ]['code'] !== T_OPEN_PARENTHESIS ) {
			// Not a function call.
			return;
		}

		$ignore                   = Tokens::$emptyTokens;
		$ignore[ T_NS_SEPARATOR ] = T_NS_SEPARATOR;

		$next_token = $this->phpcsFile->findNext( $ignore, $next_token + 1, null, true );
		if ( $this->tokens[ $next_token ]['code'] !== T_STRING ) {
			// Not what we are looking for.
			return;
		}

		$next_after = $this->phpcsFile->findNext(Tokens::$emptyTokens, $next_token + 1, null, true );
		if ( $this->tokens[ $next_after ]['code'] !== T_OPEN_PARENTHESIS ) {
			// Not a function call inside the escaping function.
			return;
		}

		if ( $this->is_printing_function( $this->tokens[ $next_token ]['content'] ) ) {
			$message = 'Attempting to escape `%s()` which is printing its output.';
			$data    = [ $this->tokens[ $next_token ]['content'] ];
			$this->phpcsFile->addError( $message, $stackPtr, 'Found', $data );
			return;
		}
	}
}
