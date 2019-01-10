<?php
/**
 * WordPressVIPMinimum Coding Standard.
 *
 * @package VIPCS\WordPressVIPMinimum
 * @link https://github.com/Automattic/VIP-Coding-Standards
 */

namespace WordPressVIPMinimum\Sniffs\VIP;

use PHP_CodeSniffer\Files\File;
use PHP_CodeSniffer\Sniffs\Sniff;
use PHP_CodeSniffer\Util\Tokens;

/**
 * Looks for merge conflict residues in files.
 *
 * @package VIPCS\WordPressVIPMinimum
 */
class MergeConflictSniff implements Sniff {

	/**
	 * A list of tokenizers this sniff supports.
	 *
	 * @var array
	 */
	public $supportedTokenizers = [
		'PHP',
		'JS',
		'CSS',
	];

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
	 * @param File $phpcsFile The PHP_CodeSniffer file where the token was found.
	 * @param int  $stackPtr  The position of the current token in the stack passed in $tokens.
	 *
	 * @return void
	 */
	public function process( File $phpcsFile, $stackPtr ) {
		$tokens = $phpcsFile->getTokens();

		if ( T_SL === $tokens[ $stackPtr ]['code'] ) {
			$nextToken = $phpcsFile->findNext( Tokens::$emptyTokens, ( $stackPtr + 1 ), null, true, null, true );
			if ( T_SL !== $tokens[ $nextToken ]['code'] ) {
				return;
			}
			$nextToken = $phpcsFile->findNext( Tokens::$emptyTokens, ( $nextToken + 1 ), null, true, null, true );
			if ( T_STRING !== $tokens[ $nextToken ]['code'] || '<<< HEAD' !== substr( $tokens[ $nextToken ]['content'], 0, 8 ) ) {
				return;
			}

			$message = 'Merge conflict detected. Found "<<<<<<< HEAD" string.';
			$phpcsFile->addError( $message, $stackPtr, 'Start' );

			return;
		} elseif ( T_ENCAPSED_AND_WHITESPACE === $tokens[ $stackPtr ]['code'] ) {
			if ( '=======' === substr( $tokens[ $stackPtr ]['content'], 0, 7 ) ) {
				$this->addSeparatorError( $phpcsFile, $stackPtr );

				return;
			} elseif ( '>>>>>>>' === substr( $tokens[ $stackPtr ]['content'], 0, 7 ) ) {
				$message = 'Merge conflict detected. Found "%s" string.';
				$data    = [ trim( $tokens[ $stackPtr ]['content'] ) ];
				$phpcsFile->addError( $message, $stackPtr, 'End', $data );

				return;
			}
		} elseif ( T_IS_IDENTICAL === $tokens[ $stackPtr ]['code'] && T_IS_IDENTICAL === $tokens[ ( $stackPtr + 1 ) ]['code'] ) {
			$this->addSeparatorError( $phpcsFile, $stackPtr );

			return;
		}
	}

	/**
	 * Consolidated violation.
	 *
	 * @param File $phpcsFile The PHP_CodeSniffer file where the token was found.
	 * @param int  $stackPtr  The position of the current token in the stack passed in $tokens.
	 */
	private function addSeparatorError( File $phpcsFile, $stackPtr ) {
		$message = 'Merge conflict detected. Found "=======" string.';
		$phpcsFile->addError( $message, $stackPtr, 'Separator' );
	}

}
