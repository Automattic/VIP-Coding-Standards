<?php
/**
 * WordPressVIPMinimum_Sniffs_Files_IncludingFileSniff.
 *
 * @package VIPCS\WordPressVIPMinimum
 */

namespace WordPressVIPMinimum\Sniffs\Files;

use WordPressCS\WordPress\AbstractFunctionRestrictionsSniff;
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
		$nextToken = $this->phpcsFile->findNext( Tokens::$emptyTokens, $stackPtr + 1, null, true, null, true );

		if ( T_OPEN_PARENTHESIS === $this->tokens[ $nextToken ]['code'] ) {
			// The construct is using parenthesis, grab the next non empty token.
			$nextToken = $this->phpcsFile->findNext( Tokens::$emptyTokens, $nextToken + 1, null, true, null, true );
		}

		if ( T_DIR === $this->tokens[ $nextToken ]['code'] || '__DIR__' === $this->tokens[ $nextToken ]['content'] ) {
			// The construct is using __DIR__ which is fine.
			return;
		}

		if ( T_VARIABLE === $this->tokens[ $nextToken ]['code'] ) {
			$message = 'File inclusion using variable (`%s`). Probably needs manual inspection.';
			$data    = [ $this->tokens[ $nextToken ]['content'] ];
			$this->phpcsFile->addWarning( $message, $nextToken, 'UsingVariable', $data );
			return;
		}

		if ( T_STRING === $this->tokens[ $nextToken ]['code'] ) {
			if ( true === in_array( $this->tokens[ $nextToken ]['content'], $this->getPathFunctions, true ) ) {
				// The construct is using one of the function for getting correct path which is fine.
				return;
			}

			if ( true === in_array( $this->tokens[ $nextToken ]['content'], $this->allowedConstants, true ) ) {
				// The construct is using one of the allowed constants which is fine.
				return;
			}

			if ( true === array_key_exists( $this->tokens[ $nextToken ]['content'], $this->restrictedConstants ) ) {
				// The construct is using one of the restricted constants.
				$message = '`%s` constant might not be defined or available. Use `%s()` instead.';
				$data    = [ $this->tokens[ $nextToken ]['content'], $this->restrictedConstants[ $this->tokens[ $nextToken ]['content'] ] ];
				$this->phpcsFile->addError( $message, $nextToken, 'RestrictedConstant', $data );
				return;
			}

			$nextNextToken = $this->phpcsFile->findNext( array_merge( Tokens::$emptyTokens, [ T_COMMENT ] ), $nextToken + 1, null, true, null, true );
			if ( 1 === preg_match( '/^[a-zA-Z_\x7f-\xff][a-zA-Z0-9_\x7f-\xff]*$/', $this->tokens[ $nextToken ]['content'] ) && T_OPEN_PARENTHESIS !== $this->tokens[ $nextNextToken ]['code'] ) {
				// The construct is using custom constant, which needs manual inspection.
				$message = 'File inclusion using custom constant (`%s`). Probably needs manual inspection.';
				$data    = [ $this->tokens[ $nextToken ]['content'] ];
				$this->phpcsFile->addWarning( $message, $nextToken, 'UsingCustomConstant', $data );
				return;
			}

			if ( 0 === strpos( $this->tokens[ $nextToken ]['content'], '$' ) ) {
				$message = 'File inclusion using variable (`%s`). Probably needs manual inspection.';
				$data    = [ $this->tokens[ $nextToken ]['content'] ];
				$this->phpcsFile->addWarning( $message, $nextToken, 'UsingVariable', $data );
				return;
			}

			if ( true === in_array( $this->tokens[ $nextToken ]['content'], $this->slashingFunctions, true ) ) {
				// The construct is using one of the slashing functions, it's probably correct.
				return;
			}

			if ( $this->is_targetted_token( $nextToken ) ) {
				$message = 'File inclusion using custom function ( `%s()` ). Must return local file source, as external URLs are prohibited on WordPress VIP. Probably needs manual inspection.';
				$data    = [ $this->tokens[ $nextToken ]['content'] ];
				$this->phpcsFile->addWarning( $message, $nextToken, 'UsingCustomFunction', $data );
				return;
			}

			$message = 'Absolute include path must be used. Use `get_template_directory()`, `get_stylesheet_directory()` or `plugin_dir_path()`.';
			$this->phpcsFile->addError( $message, $nextToken, 'NotAbsolutePath' );
			return;
		}

		if ( T_CONSTANT_ENCAPSED_STRING === $this->tokens[ $nextToken ]['code'] && filter_var( str_replace( [ '"', "'" ], '', $this->tokens[ $nextToken ]['content'] ), FILTER_VALIDATE_URL ) ) {
			$message = 'Include path must be local file source, external URLs are prohibited on WordPress VIP.';
			$this->phpcsFile->addError( $message, $nextToken, 'ExternalURL' );
			return;
		}

		$message = 'Absolute include path must be used. Use `get_template_directory()`, `get_stylesheet_directory()` or `plugin_dir_path()`.';
		$this->phpcsFile->addError( $message, $nextToken, 'NotAbsolutePath' );
	}
}
