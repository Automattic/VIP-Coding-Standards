<?php
/**
 * WordPress-VIP-Minimum Coding Standard.
 *
 *  @package VIPCS\WordPressVIPMinimum
 * @link https://github.com/Automattic/VIP-Coding-Standards
 */

namespace WordPressVIPMinimum\Sniffs\VIP;

use PHP_CodeSniffer\Files\File;
use PHP_CodeSniffer\Sniffs\Sniff;
use PHP_CodeSniffer\Util\Tokens;

/**
 * Flag suspicious WP_Query and get_posts params.
 *
 *  @package VIPCS\WordPressVIPMinimum
 */
class WPQueryParamsSniff implements Sniff {

	/**
	 * Returns an array of tokens this test wants to listen for.
	 *
	 * @return array
	 */
	public function register() {
		return array(
			T_CONSTANT_ENCAPSED_STRING,
		);
	}

	/**
	 * Process this test when one of its tokens is encountered
	 *
	 * @param \PHP_CodeSniffer\Files\File $phpcsFile The file being scanned.
	 * @param int                         $stackPtr  The position of the current token in the stack passed in $tokens.
	 *
	 * @return void
	 */
	public function process( File $phpcsFile, $stackPtr ) {

		$tokens = $phpcsFile->getTokens();

		if ( 'suppress_filters' === trim( $tokens[ $stackPtr ]['content'], '\'' ) ) {

			$next_token = $phpcsFile->findNext( array_merge( Tokens::$emptyTokens, array( T_EQUAL, T_CLOSE_SQUARE_BRACKET, T_DOUBLE_ARROW ) ), ( $stackPtr + 1 ), null, true );

			if ( T_TRUE === $tokens[ $next_token ]['code'] ) {
				$phpcsFile->addError( 'Setting `suppress_filters` to `true` is probihited.', $stackPtr, 'suppressFiltersTrue' );
			}
		}

		if ( 'post__not_in' === trim( $tokens[ $stackPtr ]['content'], '\'' ) ) {
			$phpcsFile->addWarning( 'Using `post__not_in` should be done with caution.', $stackPtr, 'post__not_in' );
		}
	}

}
