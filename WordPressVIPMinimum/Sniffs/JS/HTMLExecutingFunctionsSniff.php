<?php
/**
 * WordPressVIPMinimum_Sniffs_Files_IncludingFileSniff.
 *
 * @package VIPCS\WordPressVIPMinimum
 */

namespace WordPressVIPMinimum\Sniffs\JS;

use WordPressVIPMinimum\Sniffs\Sniff;
use PHP_CodeSniffer\Util\Tokens;

/**
 * WordPressVIPMinimum_Sniffs_JS_HTMLExecutingFunctions.
 *
 * Flags functions which are executing HTML passed to it.
 *
 * @package VIPCS\WordPressVIPMinimum
 */
class HTMLExecutingFunctionsSniff extends Sniff {

	/**
	 * List of HTML executing functions.
	 *
	 * Name of function => content or target.
	 * Value indicates whether the function's arg is the content to be inserted, or the target where the inserted
	 * content is to be inserted before/after/replaced. For the latter, the content is in the preceding method's arg.
	 *
	 * @var array
	 */
	public $HTMLExecutingFunctions = [
		'after'        => 'content', // jQuery.
		'append'       => 'content', // jQuery.
		'appendTo'     => 'target',  // jQuery.
		'before'       => 'content', // jQuery.
		'html'         => 'content', // jQuery.
		'insertAfter'  => 'target',  // jQuery.
		'insertBefore' => 'target',  // jQuery.
		'prepend'      => 'content', // jQuery.
		'prependTo'    => 'target',  // jQuery.
		'replaceAll'   => 'target',  // jQuery.
		'replaceWith'  => 'content', // jQuery.
		'write'        => 'content',
		'writeln'      => 'content',
	];

	/**
	 * A list of tokenizers this sniff supports.
	 *
	 * @var string[]
	 */
	public $supportedTokenizers = [ 'JS' ];

	/**
	 * Returns an array of tokens this test wants to listen for.
	 *
	 * @return array
	 */
	public function register() {
		return [
			T_STRING,
		];
	}

	/**
	 * Processes this test, when one of its tokens is encountered.
	 *
	 * @param int $stackPtr The position of the current token in the stack passed in $tokens.
	 *
	 * @return void
	 */
	public function process_token( $stackPtr ) {

		if ( ! isset( $this->HTMLExecutingFunctions[ $this->tokens[ $stackPtr ]['content'] ] ) ) {
			// Looking for specific functions only.
			return;
		}

		if ( 'content' === $this->HTMLExecutingFunctions[ $this->tokens[ $stackPtr ]['content'] ] ) {
			$nextToken = $this->phpcsFile->findNext( Tokens::$emptyTokens, $stackPtr + 1, null, true, null, true );

			if ( T_OPEN_PARENTHESIS !== $this->tokens[ $nextToken ]['code'] ) {
				// Not a function.
				return;
			}

			$parenthesis_closer = $this->tokens[ $nextToken ]['parenthesis_closer'];

			while ( $nextToken < $parenthesis_closer ) {
				$nextToken = $this->phpcsFile->findNext( Tokens::$emptyTokens, $nextToken + 1, null, true, null, true );
				if ( T_STRING === $this->tokens[ $nextToken ]['code'] ) { // Contains a variable, function call or something else dynamic.
					$message = 'Any HTML passed to `%s` gets executed. Make sure it\'s properly escaped.';
					$data    = [ $this->tokens[ $stackPtr ]['content'] ];
					$this->phpcsFile->addWarning( $message, $stackPtr, $this->tokens[ $stackPtr ]['content'], $data );

					return;
				}
			}
		} elseif ( 'target' === $this->HTMLExecutingFunctions[ $this->tokens[ $stackPtr ]['content'] ] ) {
			$prevToken = $this->phpcsFile->findPrevious( Tokens::$emptyTokens, $stackPtr - 1, null, true, null, true );

			if ( T_OBJECT_OPERATOR !== $this->tokens[ $prevToken ]['code'] ) {
				return;
			}

			$prevPrevToken = $this->phpcsFile->findPrevious( Tokens::$emptyTokens, $prevToken - 1, null, true, null, true );

			if ( T_CLOSE_PARENTHESIS !== $this->tokens[ $prevPrevToken ]['code'] ) {
				// Not a function call, but may be a variable containing an element reference, so just
				// flag all remaining instances of these target HTML executing functions.
				$message = 'Any HTML used with `%s` gets executed. Make sure it\'s properly escaped.';
				$data    = [ $this->tokens[ $stackPtr ]['content'] ];
				$this->phpcsFile->addWarning( $message, $stackPtr, $this->tokens[ $stackPtr ]['content'], $data );

				return;
			}

			// Check if it's a function call (typically $() ) that contains a dynamic part.
			$parenthesis_opener = $this->tokens[ $prevPrevToken ]['parenthesis_opener'];

			while ( $prevPrevToken > $parenthesis_opener ) {
				$prevPrevToken = $this->phpcsFile->findPrevious( Tokens::$emptyTokens, $prevPrevToken - 1, null, true, null, true );
				if ( T_STRING === $this->tokens[ $prevPrevToken ]['code'] ) { // Contains a variable, function call or something else dynamic.
					$message = 'Any HTML used with `%s` gets executed. Make sure it\'s properly escaped.';
					$data    = [ $this->tokens[ $stackPtr ]['content'] ];
					$this->phpcsFile->addWarning( $message, $stackPtr, $this->tokens[ $stackPtr ]['content'], $data );

					return;
				}
			}
		}
	}

}
