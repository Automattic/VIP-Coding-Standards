<?php
/**
 * WordPressVIPMinimum Coding Standard.
 *
 * @package VIPCS\WordPressVIPMinimum
 * @link https://github.com/Automattic/VIP-Coding-Standards
 */

namespace WordPressVIPMinimum\Sniffs\Performance;

use WordPressVIPMinimum\Sniffs\Sniff;
use PHP_CodeSniffer\Util\Tokens;

/**
 * Restricts usage of rewrite rules flushing
 *
 *  @package VIPCS\WordPressVIPMinimum
 */
class FetchingRemoteDataSniff extends Sniff {

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
	 * @param int $stackPtr The position of the current token in the stack passed in $tokens.
	 *
	 * @return void
	 */
	public function process_token( $stackPtr ) {

		$functionName = $this->tokens[ $stackPtr ]['content'];
		if ( 'file_get_contents' !== $functionName ) {
			return;
		}

		$data = [ $this->tokens[ $stackPtr ]['content'] ];

		$fileNameStackPtr = $this->phpcsFile->findNext( Tokens::$stringTokens, $stackPtr + 1, null, false, null, true );
		if ( false === $fileNameStackPtr ) {
			$message = '`%s()` is highly discouraged for remote requests, please use `wpcom_vip_file_get_contents()` or `vip_safe_wp_remote_get()` instead. If it\'s for a local file please use WP_Filesystem instead.';
			$this->phpcsFile->addWarning( $message, $stackPtr, 'FileGetContentsUnknown', $data );
		}

		$fileName = $this->tokens[ $fileNameStackPtr ]['content'];

		$isRemoteFile = ( false !== strpos( $fileName, '://' ) );
		if ( true === $isRemoteFile ) {
			$message = '`%s()` is highly discouraged for remote requests, please use `wpcom_vip_file_get_contents()` or `vip_safe_wp_remote_get()` instead.';
			$this->phpcsFile->addWarning( $message, $stackPtr, 'FileGetContentsRemoteFile', $data );
		}
	}

}
