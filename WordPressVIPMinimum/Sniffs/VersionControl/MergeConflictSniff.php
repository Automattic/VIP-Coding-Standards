<?php
/**
 * WordPressVIPMinimum Coding Standard.
 *
 * @package VIPCS\WordPressVIPMinimum
 * @link https://github.com/Automattic/VIP-Coding-Standards
 */

namespace WordPressVIPMinimum\Sniffs\VersionControl;

use WordPressVIPMinimum\Sniffs\Sniff;
use PHP_CodeSniffer\Util\Tokens;

/**
 * Looks for merge conflict residues in files.
 *
 * @package VIPCS\WordPressVIPMinimum
 */
class MergeConflictSniff extends Sniff {

	/**
	 * A list of tokenizers this sniff supports.
	 *
	 * @var string[]
	 */
	public $supportedTokenizers = [ 'CSS', 'JS', 'PHP' ];

	/**
	 * Returns an array of tokens this test wants to listen for.
	 *
	 * @return array
	 */
	public function register() {
		return [
			T_SL,
			T_ENCAPSED_AND_WHITESPACE,
			T_IS_IDENTICAL,
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

		if ( T_SL === $this->tokens[ $stackPtr ]['code'] ) {
			$nextToken = $this->phpcsFile->findNext( Tokens::$emptyTokens, $stackPtr + 1, null, true, null, true );
			if ( T_SL !== $this->tokens[ $nextToken ]['code'] ) {
				return;
			}
			$nextToken = $this->phpcsFile->findNext( Tokens::$emptyTokens, $nextToken + 1, null, true, null, true );
			if ( T_STRING !== $this->tokens[ $nextToken ]['code'] || 0 !== strpos( $this->tokens[ $nextToken ]['content'], '<<< HEAD' ) ) {
				return;
			}

			$message = 'Merge conflict detected. Found "<<<<<<< HEAD" string.';
			$this->phpcsFile->addError( $message, $stackPtr, 'Start' );

			return;
		}

		if ( T_ENCAPSED_AND_WHITESPACE === $this->tokens[ $stackPtr ]['code'] ) {
			if ( 0 === strpos( $this->tokens[ $stackPtr ]['content'], '=======' ) ) {
				$this->addSeparatorError( $stackPtr );

				return;
			}

			if ( 0 === strpos( $this->tokens[ $stackPtr ]['content'], '>>>>>>>' ) ) {
				$message = 'Merge conflict detected. Found "%s" string.';
				$data    = [ trim( $this->tokens[ $stackPtr ]['content'] ) ];
				$this->phpcsFile->addError( $message, $stackPtr, 'End', $data );

				return;
			}
		} elseif ( T_IS_IDENTICAL === $this->tokens[ $stackPtr ]['code'] && T_IS_IDENTICAL === $this->tokens[ $stackPtr + 1 ]['code'] ) {
			$this->addSeparatorError( $stackPtr );

			return;
		}
	}

	/**
	 * Consolidated violation.
	 *
	 * @param int $stackPtr  The position of the current token in the stack passed in $tokens.
	 */
	private function addSeparatorError( $stackPtr ) {
		$message = 'Merge conflict detected. Found "=======" string.';
		$this->phpcsFile->addError( $message, $stackPtr, 'Separator' );
	}

}
