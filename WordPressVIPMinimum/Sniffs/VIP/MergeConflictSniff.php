<?php
/**
 * WordPressVIPMinimum_Sniffs_Files_IncludingFileSniff.
 *
 * @package VIPCS\WordPressVIPMinimum
 */

namespace WordPressVIPMinimum\Sniffs\VIP;

use PHP_CodeSniffer_File as File;
use PHP_CodeSniffer_Tokens as Tokens;

/**
 * WordPressVIPMinimum_Sniffs_Files_IncludingFileSniff.
 *
 * Looks for merge conflict residues in files.
 *
 * @package VIPCS\WordPressVIPMinimum
 */
class MergeConflictSniff implements \PHP_CodeSniffer_Sniff {

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
		return array(
			T_SL,
			T_ENCAPSED_AND_WHITESPACE,
			T_IS_IDENTICAL,
		);

	}//end register()


	/**
	 * Processes this test, when one of its tokens is encountered.
	 *
	 * @param \PHP_CodeSniffer\Files\File $phpcsFile The file being scanned.
	 * @param int                         $stackPtr  The position of the current token in the
	 *                                               stack passed in $tokens.
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
			$phpcsFile->addError( 'Merge conflict detected. Found "<<<<<<< HEAD" string.', $stackPtr, 'HEAD' );
			return;
		} elseif ( T_ENCAPSED_AND_WHITESPACE === $tokens[ $stackPtr ]['code'] ) {
			if ( '=======' === substr( $tokens[ $stackPtr ]['content'], 0, 7 ) ) {
				$phpcsFile->addError( 'Merge conflict detected. Found "=======" string.', $stackPtr, 'DELIMITER' );
				return;
			} elseif ( '>>>>>>>' === substr( $tokens[ $stackPtr ]['content'], 0, 7 ) ) {
				$phpcsFile->addError( sprintf( 'Merge conflict detected. Found "%s" string.', trim( $tokens[ $stackPtr ]['content'] ) ), $stackPtr, 'DELIMITER' );
				return;
			}
		} else if ( T_IS_IDENTICAL === $tokens[ $stackPtr ]['code'] && T_IS_IDENTICAL === $tokens[ ( $stackPtr + 1 ) ]['code'] ) {
			$phpcsFile->addError( 'Merge conflict detected. Found "=======" string.', $stackPtr, 'DELIMITER' );
			return;
		}

	}//end process()


}//end class
