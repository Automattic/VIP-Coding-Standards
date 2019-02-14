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
 * Flag suspicious WP_Query and get_posts params.
 *
 *  @package VIPCS\WordPressVIPMinimum
 */
class WPQueryParamsSniff extends Sniff {

	/**
	 * Returns an array of tokens this test wants to listen for.
	 *
	 * @return array
	 */
	public function register() {
		return [
			T_CONSTANT_ENCAPSED_STRING,
		];
	}

	/**
	 * Process this test when one of its tokens is encountered
	 *
	 * @param int $stackPtr The position of the current token in the stack passed in $tokens.
	 *
	 * @return void
	 */
	public function process_token( $stackPtr ) {

		if ( 'suppress_filters' === trim( $this->tokens[ $stackPtr ]['content'], '\'' ) ) {

			$next_token = $this->phpcsFile->findNext( array_merge( Tokens::$emptyTokens, [ T_EQUAL, T_CLOSE_SQUARE_BRACKET, T_DOUBLE_ARROW ] ), $stackPtr + 1, null, true );

			if ( T_TRUE === $this->tokens[ $next_token ]['code'] ) {
				// WordPress.com: https://lobby.vip.wordpress.com/wordpress-com-documentation/uncached-functions/.
				// VIP Go: https://vip.wordpress.com/documentation/vip-go/uncached-functions/.
				$message = 'Setting `suppress_filters` to `true` is prohibited.';
				$this->phpcsFile->addError( $message, $stackPtr, 'SuppressFiltersTrue' );
			}
		}

		if ( 'post__not_in' === trim( $this->tokens[ $stackPtr ]['content'], '\'' ) ) {
			$message = 'Using `post__not_in` should be done with caution, see https://vip.wordpress.com/documentation/performance-improvements-by-removing-usage-of-post__not_in/ for more information.';
			$this->phpcsFile->addWarning( $message, $stackPtr, 'PostNotIn' );
		}
	}

}
