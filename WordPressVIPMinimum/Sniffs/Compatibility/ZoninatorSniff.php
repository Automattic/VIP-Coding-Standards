<?php
/**
 * WordPressVIPMinimum Coding Standard.
 *
 * @package VIPCS\WordPressVIPMinimum
 */

namespace WordPressVIPMinimum\Sniffs\Compatibility;

use WordPressVIPMinimum\Sniffs\Sniff;
use PHP_CodeSniffer\Util\Tokens;

/**
 * This sniff reminds the developers to check whether the WordPress Core REST API is enabled
 * along with loading v0.8 and above.
 */
class ZoninatorSniff extends Sniff {

	/**
	 * Returns the token types that this sniff is interested in.
	 *
	 * @return array(int)
	 */
	public function register() {
		return Tokens::$functionNameTokens;
	}


	/**
	 * Processes the tokens that this sniff is interested in.
	 *
	 * @param int $stackPtr The position in the stack where the token was found.
	 *
	 * @return void
	 */
	public function process_token( $stackPtr ) {

		if ( 'wpcom_vip_load_plugin' !== $this->tokens[ $stackPtr ]['content'] ) {
			return;
		}

		$openBracket = $this->phpcsFile->findNext( Tokens::$emptyTokens, $stackPtr + 1, null, true );

		if ( T_OPEN_PARENTHESIS !== $this->tokens[ $openBracket ]['code'] ) {
			// Not a function call.
			return;
		}

		$plugin_name = $this->phpcsFile->findNext( Tokens::$emptyTokens, $openBracket + 1, null, true );

		if ( 'zoninator' !== $this->remove_wrapping_quotation_marks( $this->tokens[ $plugin_name ]['content'] ) ) {
			return;
		}

		$comma = $this->phpcsFile->findNext( Tokens::$emptyTokens, $plugin_name + 1, null, true );

		if ( ! $comma || 'PHPCS_T_COMMA' !== $this->tokens[ $comma ]['code'] ) {
			// We are loading the default version.
			return;
		}

		$folder = $this->phpcsFile->findNext( Tokens::$emptyTokens, $comma + 1, null, true );

		$comma = $this->phpcsFile->findNext( Tokens::$emptyTokens, $folder + 1, null, true );

		if ( ! $comma || 'PHPCS_T_COMMA' !== $this->tokens[ $comma ]['code'] ) {
			// We are loading the default version.
			return;
		}

		$version = $this->phpcsFile->findNext( Tokens::$emptyTokens, $comma + 1, null, true );
		$version = $this->remove_wrapping_quotation_marks( $this->tokens[ $version ]['content'] );

		if ( true === version_compare( $version, '0.8', '>=' ) ) {
			$message = 'Zoninator of version >= v0.8 requires WordPress core REST API. Please, make sure the `wpcom_vip_load_wp_rest_api()` is being called on all sites loading this file.';
			$this->phpcsFile->addWarning( $message, $stackPtr, 'RequiresRESTAPI' );
		}
	}

	/**
	 * Removes the quotation marks around T_CONSTANT_ENCAPSED_STRING.
	 *
	 * @param string $string T_CONSTANT_ENCAPSED_STRING containing wrapping quotation marks.
	 *
	 * @return string String w/o wrapping quotation marks.
	 */
	public function remove_wrapping_quotation_marks( $string ) {
		return trim( str_replace( '"', "'", $string ), "'" );
	}
}
