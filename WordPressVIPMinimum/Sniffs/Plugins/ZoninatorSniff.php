<?php
/**
 * WordPressVIPMinimum Coding Standard.
 *
 * @package VIPCS\WordPressVIPMinimum
 */

namespace WordPressVIPMinimum\Sniffs\Plugins;

use PHP_CodeSniffer_File as File;
use PHP_CodeSniffer_Tokens as Tokens;

/**
 * This sniff reminds the developers to check whether the WordPress Core REST API is enabled
 * along with loading v0.8 and above.
 */
class ZoninatorSniff implements \PHP_CodeSniffer_Sniff {

	/**
	 * Returns the token types that this sniff is interested in.
	 *
	 * @return array(int)
	 */
	public function register() {
		return Tokens::$functionNameTokens;

	}//end register()


	/**
	 * Processes the tokens that this sniff is interested in.
	 *
	 * @param \PHP_CodeSniffer\Files\File $phpcsFile The file where the token was found.
	 * @param int                         $stackPtr  The position in the stack where
	 *                                               the token was found.
	 *
	 * @return void
	 */
	public function process( File $phpcsFile, $stackPtr ) {

		$tokens    = $phpcsFile->getTokens();
		$phpcsFile = $phpcsFile;

		if ( 'wpcom_vip_load_plugin' !== $tokens[ $stackPtr ]['content'] ) {
			return;
		}

		$openBracket = $phpcsFile->findNext( Tokens::$emptyTokens, ( $stackPtr + 1 ), null, true );

		if ( T_OPEN_PARENTHESIS !== $tokens[ $openBracket ]['code'] ) {
			// Not a function call.
			return ;
		}

		$plugin_name = $phpcsFile->findNext( Tokens::$emptyTokens, ( $openBracket + 1 ), null, true );

		if ( 'zoninator' !== $this->remove_wrapping_quotation_marks( $tokens[ $plugin_name ]['content'] ) ) {
			return;
		}

		$comma = $phpcsFile->findNext( Tokens::$emptyTokens, ( $plugin_name + 1 ), null, true );

		if ( ! $comma || 'PHPCS_T_COMMA' !== $tokens[ $comma ]['code'] ) {
			// We are loading the default version.
			return;
		}

		$folder = $phpcsFile->findNext( Tokens::$emptyTokens, ( $comma + 1 ), null, true );

		$comma = $phpcsFile->findNext( Tokens::$emptyTokens, ( $folder + 1 ), null, true );

		if ( ! $comma || 'PHPCS_T_COMMA' !== $tokens[ $comma ]['code'] ) {
			// We are loading the default version.
			return;
		}

		$version = $phpcsFile->findNext( Tokens::$emptyTokens, ( $comma + 1 ), null, true );
		$version = $this->remove_wrapping_quotation_marks( $tokens[ $version ]['content'] );

		if ( true === version_compare( $version, '0.8', '>=' ) ) {
			$phpcsFile->addWarning( 'Zoninator of version >= v0.8 requires WordPress core REST API. Please, make sure the `wpcom_vip_load_wp_rest_api()` is being called on all sites loading this file.', $stackPtr, 'Zoninator' );
		}

	}//end process()

	public function remove_wrapping_quotation_marks( $string ) {
		return trim( str_replace( '"', "'", $string ), "'" );
	}

}//end class


