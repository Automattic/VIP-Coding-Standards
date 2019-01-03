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
 * Restricts usage of rewrite rules flushing
 *
 *  @package VIPCS\WordPressVIPMinimum
 */
class FetchingRemoteDataSniff implements Sniff {

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
			$phpcsFile->addWarning(
				'`%s()` is highly discouraged for remote requests, please use `wpcom_vip_file_get_contents()` or `vip_safe_wp_remote_get()` instead. If it\'s for a local file please use WP_Filesystem instead.',
				$stackPtr,
				'FileGetContentsUnknown',
				[ $tokens[ $stackPtr ]['content'] ]
			);
		}

		$fileName = $tokens[ $fileNameStackPtr ]['content'];

		$isRemoteFile = ( false !== strpos( $fileName, '://' ) );
		if ( true === $isRemoteFile ) {
			$phpcsFile->addWarning(
				'`%s()` is highly discouraged for remote requests, please use `wpcom_vip_file_get_contents()` or `vip_safe_wp_remote_get()` instead.',
				$stackPtr,
				'FileGetContentsRemoteFile',
				[ $tokens[ $stackPtr ]['content'] ]
			);
		}
	}

}
