<?php
/**
 * WordPressVIPMinimum_Sniffs_Files_IncludingFileSniff.
 *
 * @package VIPCS\WordPressVIPMinimum
 */

namespace WordPressVIPMinimum\Sniffs\Files;

use WordPress\AbstractFunctionRestrictionsSniff;
use PHP_CodeSniffer\Util\Tokens;

/**
 * WordPressVIPMinimum_Sniffs_Files_IncludingFileSniff.
 *
 * Checks that __DIR__, dirname( __FILE__ ) or plugin_dir_path( __FILE__ )
 * is used when including or requiring files.
 *
 * @package VIPCS\WordPressVIPMinimum
 */
class IncludingFileSniff extends AbstractFunctionRestrictionsSniff {

	/**
	 * List of function used for getting paths.
	 *
	 * @var array
	 */
	public $getPathFunctions = [
		'plugin_dir_path',
		'dirname',
		'get_stylesheet_directory',
		'get_template_directory',
		'locate_template',
	];

	/**
	 * List of restricted constants.
	 *
	 * @var array
	 */
	public $restrictedConstants = [
		'TEMPLATEPATH'   => 'get_template_directory',
		'STYLESHEETPATH' => 'get_stylesheet_directory',
	];

	/**
	 * List of allowed constants.
	 *
	 * @var array
	 */
	public $allowedConstants = [
		'ABSPATH',
		'WP_CONTENT_DIR',
		'WP_PLUGIN_DIR',
	];

	/**
	 * Functions used for modify slashes.
	 *
	 * @var array
	 */
	public $slashingFunctions = [
		'trailingslashit',
		'user_trailingslashit',
		'untrailingslashit',
	];

	/**
	 * Groups of functions to restrict.
	 *
	 * @return array
	 */
	public function getGroups() {
		return [];
	}

	/**
	 * Returns an array of tokens this test wants to listen for.
	 *
	 * @return array
	 */
	public function register() {
		return Tokens::$includeTokens;
	}

	/**
	 * Processes this test, when one of its tokens is encountered.
	 *
	 * @param int $stackPtr The position of the current token in the stack.
	 *
	 * @return void
	 */
	public function process_token( $stackPtr ) {
		$tokens = $this->phpcsFile->getTokens();

		$nextToken = $this->phpcsFile->findNext( Tokens::$emptyTokens, ( $stackPtr + 1 ), null, true, null, true );

		if ( T_OPEN_PARENTHESIS === $tokens[ $nextToken ]['code'] ) {
			// The construct is using parenthesis, grab the next non empty token.
			$nextToken = $this->phpcsFile->findNext( Tokens::$emptyTokens, ( $nextToken + 1 ), null, true, null, true );
		}

		if ( T_DIR === $tokens[ $nextToken ]['code'] || '__DIR__' === $tokens[ $nextToken ]['content'] ) {
			// The construct is using __DIR__ which is fine.
			return;
		}

		if ( T_VARIABLE === $tokens[ $nextToken ]['code'] ) {
			$this->phpcsFile->addWarning( sprintf( 'File inclusion using variable (`%s`). Probably needs manual inspection.', $tokens[ $nextToken ]['content'] ), $nextToken, 'UsingVariable' );
			return;
		}

		if ( T_STRING === $tokens[ $nextToken ]['code'] ) {
			if ( true === in_array( $tokens[ $nextToken ]['content'], $this->getPathFunctions, true ) ) {
				// The construct is using one of the function for getting correct path which is fine.
				return;
			}

			if ( true === in_array( $tokens[ $nextToken ]['content'], $this->allowedConstants, true ) ) {
				// The construct is using one of the allowed constants which is fine.
				return;
			}

			if ( true === in_array( $tokens[ $nextToken ]['content'], array_keys( $this->restrictedConstants ), true ) ) {
				// The construct is using one of the restricted constants.
				$this->phpcsFile->addError( sprintf( '`%s` constant might not be defined or available. Use `%s()` instead.', $tokens[ $nextToken ]['content'], $this->restrictedConstants[ $tokens[ $nextToken ]['content'] ] ), $nextToken, 'RestrictedConstant' );
				return;
			}

			$nextNextToken = $this->phpcsFile->findNext( array_merge( Tokens::$emptyTokens, array( T_COMMENT ) ), ( $nextToken + 1 ), null, true, null, true );
			if ( 1 === preg_match( '/^[a-zA-Z_\x7f-\xff][a-zA-Z0-9_\x7f-\xff]*$/', $tokens[ $nextToken ]['content'] ) && T_OPEN_PARENTHESIS !== $tokens[ $nextNextToken ]['code'] ) {
				// The construct is using custom constant, which needs manual inspection.
				$this->phpcsFile->addWarning( sprintf( 'File inclusion using custom constant (`%s`). Probably needs manual inspection.', $tokens[ $nextToken ]['content'] ), $nextToken, 'UsingCustomConstant' );
				return;
			}

			if ( 0 === strpos( $tokens[ $nextToken ]['content'], '$' ) ) {
				$this->phpcsFile->addWarning( sprintf( 'File inclusion using variable (`%s`). Probably needs manual inspection.', $tokens[ $nextToken ]['content'] ), $nextToken, 'UsingVariable' );
				return;
			}

			if ( true === in_array( $tokens[ $nextToken ]['content'], $this->slashingFunctions, true ) ) {
				// The construct is using one of the slashing functions, it's probably correct.
				return;
			}

			if ( $this->is_targetted_token( $nextToken ) ) {
				$this->phpcsFile->addWarning( sprintf( 'File inclusion using custom function ( `%s()` ). Must return local file source, as external URLs are prohibited on WordPress VIP. Probably needs manual inspection.', $tokens[ $nextToken ]['content'] ), $nextToken, 'UsingCustomFunction' );
				return;
			}

			$this->phpcsFile->addError( 'Absolute include path must be used. Use `get_template_directory()`, `get_stylesheet_directory()` or `plugin_dir_path()`.', $nextToken, 'NotAbsolutePath' );
			return;
		} else {
			if ( T_CONSTANT_ENCAPSED_STRING === $tokens[ $nextToken ]['code'] && filter_var( str_replace( [ '"', "'" ], '', $tokens[ $nextToken ]['content'] ), FILTER_VALIDATE_URL ) ) {
				$this->phpcsFile->addError( 'Include path must be local file source, external URLs are prohibited on WordPress VIP.', $nextToken, 'ExternalURL' );
				return;
			}

			$this->phpcsFile->addError( 'Absolute include path must be used. Use `get_template_directory()`, `get_stylesheet_directory()` or `plugin_dir_path()`.', $nextToken, 'NotAbsolutePath' );
			return;
		}
	}
}
