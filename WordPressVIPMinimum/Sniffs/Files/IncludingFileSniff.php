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
 * Checks file inclusion is correctly used.
 *
 * @package VIPCS\WordPressVIPMinimum
 */
class IncludingFileSniff extends AbstractFunctionRestrictionsSniff {

	/**
	 * List of function used for getting paths.
	 *
	 * @var array
	 */
	public $allowedPathFunctions = [
		'plugin_dir_path'            => true,
		'dirname'                    => true,
		'get_stylesheet_directory'   => true,
		'get_template_directory'     => true,
		'locate_template'            => true,
		'get_parent_theme_file_path' => true,
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
		'ABSPATH'        => true,
		'WP_CONTENT_DIR' => true,
		'WP_PLUGIN_DIR'  => true,
	];

	/**
	 * List of custom keywords for paths.
	 *
	 * @var array
	 */
	public $customPaths = [
		'path',
		'dir',
		'base',
	];

	/**
	 * Functions used for modify slashes.
	 *
	 * @var array
	 */
	public $slashingFunctions = [
		'trailingslashit'      => true,
		'user_trailingslashit' => true,
		'untrailingslashit'    => true,
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

		if ( $this->tokens[ $nextToken ]['code'] === T_OPEN_PARENTHESIS ) {
			// The construct is using parenthesis, grab the next non empty token.
			$nextToken = $this->phpcsFile->findNext( Tokens::$emptyTokens, $nextToken + 1, null, true, null, true );
		}

		if ( $this->tokens[ $nextToken ]['code'] === T_DIR || $this->tokens[ $nextToken ]['content'] === '__DIR__' ) {
			// The construct is using __DIR__ which is fine.
			return;
		}

		if ( isset( Tokens::$stringTokens[ $this->tokens[ $nextToken ]['code'] ] ) && filter_var( str_replace( [ '"', "'" ], '', $this->tokens[ $nextToken ]['content'] ), FILTER_VALIDATE_URL ) !== false ) {
			$message = 'Include path must be local file source, external URLs are prohibited on WordPress VIP.';
			$this->phpcsFile->addError( $message, $nextToken, 'ExternalURL' );
			return;
		}

		if ( $this->tokens[ $nextToken ]['code'] === T_VARIABLE ) {
			$message = 'File inclusion using variable (`%s`). Probably needs manual inspection.';
			$data    = [ $this->tokens[ $nextToken ]['content'] ];
			$this->phpcsFile->addWarning( $message, $nextToken, 'UsingVariable', $data );
			return;
		}

		if ( $this->tokens[ $nextToken ]['code'] === T_STRING ) {
			$is_function = $this->phpcsFile->findNext( Tokens::$emptyTokens, $nextToken + 1, null, true, null, true );
			if ( $this->tokens[ $is_function ]['code'] === T_OPEN_PARENTHESIS ) {
				if ( isset( $this->allowedPathFunctions[ $this->tokens[ $nextToken ]['content'] ] ) ) {
					// The construct is using one of the functions for getting correct path which is fine.
					return;
				}

				if ( isset( $this->slashingFunctions[ $this->tokens[ $nextToken ]['content'] ] ) ) {
					// The construct is using one of the slashing functions, it's probably correct.
					return;
				}

				if ( $this->is_targetted_token( $nextToken ) ) {
					$message = 'File inclusion using custom function ( `%s()` ). Must return local file source, as external URLs are prohibited on WordPress VIP. Needs manual inspection.';
					$data    = [ $this->tokens[ $nextToken ]['content'] ];
					$this->phpcsFile->addWarning( $message, $nextToken, 'UsingCustomFunction', $data );
					return;
				}
			} else { // We're in constant territory now.
				if ( isset( $this->restrictedConstants[ $this->tokens[ $nextToken ]['content'] ] ) ) {
					$message = '`%s` constant might not be defined or available. Use `%s()` instead.';
					$data    = [ $this->tokens[ $nextToken ]['content'], $this->restrictedConstants[ $this->tokens[ $nextToken ]['content'] ] ];
					$this->phpcsFile->addError( $message, $nextToken, 'RestrictedConstant', $data );
					return;
				}
				if ( isset( $this->allowedConstants[ $this->tokens[ $nextToken ]['content'] ] ) ) {
					// The construct is using one of the allowed constants which is fine.
					return;
				}
				if ( $this->has_custom_path( $this->tokens[ $nextToken ]['content'] ) === true ) {
					// The construct is using a constant with a custom keyword.
					return;
				}

				// The construct is using custom constant, which needs manual inspection.
				if ( preg_match( '/^[a-zA-Z_\x7f-\xff][a-zA-Z0-9_\x7f-\xff]*$/', $this->tokens[ $nextToken ]['content'] ) === 1 ) {
					$message = 'File inclusion using custom constant (`%s`). Probably needs manual inspection.';
					$data    = [ $this->tokens[ $nextToken ]['content'] ];
					$this->phpcsFile->addWarning( $message, $nextToken, 'UsingCustomConstant', $data );
					return;
				}
			}
		}

		$message = 'Absolute include path must be used. Use `get_template_directory()`, `get_stylesheet_directory()` or `plugin_dir_path()`.';
		$this->phpcsFile->addError( $message, $nextToken, 'NotAbsolutePath' );
	}

	/**
	 * Check if a content string contains wording found in custom paths.
	 *
	 * @param string $content  Optionally, the current content string, might be a
	 *                         substring of the original string.
	 *                         Defaults to `false` to distinguish between a passed
	 *                         empty string and not passing the $content string.
	 *
	 * @return bool True if the string contains a keyword in $customPaths, false otherwise.
	 */
	private function has_custom_path( $content ) {
		$content = strtolower( $content );

		foreach ( $this->customPaths as $path ) {
			if ( strpos( $content, $path ) !== false ) {
				return true;
			}
		}

		return false;
	}
}
