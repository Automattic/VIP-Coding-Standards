<?php
/**
 * WordPress-VIP-Minimum Coding Standard.
 *
 * @package VIPCS\WordPressVIPMinimum
 * @link https://github.com/Automattic/VIP-Coding-Standards
 */

namespace WordPressVIPMinimum\Sniffs\VIP;

use PHP_CodeSniffer_File as File;
use PHP_CodeSniffer_Tokens as Tokens;

/**
 * Restricts usage of rewrite rules flushing
 *
 *  @package VIPCS\WordPressVIPMinimum
 */
class FetchingRemoteDataSniff implements \PHP_CodeSniffer_Sniff {

	/**
	 * Returns an array of tokens this test wants to listen for.
	 *
	 * @return array
	 */
	public function register() {
		return Tokens::$functionNameTokens;
	}

	/**
	 * Process this test when one of its tokens is encountered.
	 *
	 * @param \PHP_CodeSniffer\Files\File $phpcsFile  The file being scanned.
	 * @param int                         $stackPtr   The position of the current token in the stack passed in $tokens.
	 *
	 * @return void
	 */
	public function process( File $phpcsFile, $stackPtr ) {

		$tokens = $phpcsFile->getTokens();

		$functionName = $tokens[ $stackPtr ]['content'];
		if ( 'file_get_contents' !== $functionName ) {
			return;
		}

		$fileNameStackPtr = $phpcsFile->findNext( Tokens::$stringTokens, ( $stackPtr + 1 ), null, false, null, true );
		if ( false === $fileNameStackPtr ) {
			$phpcsFile->addWarning( sprintf( '`%s()` is highly discouraged for remote requests, please use `wpcom_vip_file_get_contents()` or `vip_safe_wp_remote_get()` instead.', $tokens[ $stackPtr ]['content'] ), $stackPtr, 'fileGetContentsUknown' );
		}

		$fileName = $tokens[ $fileNameStackPtr ]['content'];

		$isRemoteFile = ( false !== strpos( $fileName, '://' ) );
		if ( true === $isRemoteFile ) {
			$phpcsFile->addWarning( sprintf( '`%s()` is highly discouraged for remote requests, please use `wpcom_vip_file_get_contents()` or `vip_safe_wp_remote_get()` instead.', $tokens[ $stackPtr ]['content'] ), $stackPtr, 'fileGetContentsRemoteFile' );
		}
	}

}
