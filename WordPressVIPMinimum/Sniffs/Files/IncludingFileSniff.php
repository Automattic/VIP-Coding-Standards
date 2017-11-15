<?php
/**
 * WordPressVIPMinimum_Sniffs_Files_IncludingFileSniff.
 *
 * @package VIPCS\WordPressVIPMinimum
 */

namespace WordPressVIPMinimum\Sniffs\Files;

use PHP_CodeSniffer_File as File;
use PHP_CodeSniffer_Tokens as Tokens;

/**
 * WordPressVIPMinimum_Sniffs_Files_IncludingFileSniff.
 *
 * Checks that __DIR__, dirname( __FILE__ ) or plugin_dir_path( __FILE__ )
 * is used when including or requiring files.
 *
 * @package VIPCS\WordPressVIPMinimum
 */
class IncludingFileSniff implements \PHP_CodeSniffer_Sniff {

	/**
	 * List of function used for getting paths.
	 *
	 * @var array
	 */
	public $getPathFuncitons = array(
		'plugin_dir_path',
		'dirname',
		'get_stylesheet_directory',
		'get_template_directory',
	);

	/**
	 * List of restricted constants.
	 *
	 * @var array
	 */
	public $restrictedConstants = array(
		'TEMPLATEPATH'   => 'get_template_directory',
		'STYLESHEETPATH' => 'get_stylesheet_directory',
	);

	/**
	 * List of allowed constants.
	 *
	 * @var array
	 */
	public $allowedConstants = array(
		'ABSPATH',
		'WP_CONTENT_DIR',
		'WP_PLUGIN_DIR',
	);

	/**
	 * Functions used for modify slashes.
	 *
	 * @var array
	 */
	public $slashingFunctions = array(
		'trailingslashit',
		'user_trailingslashit',
		'untrailingslashit',
	);

	/**
	 * Returns an array of tokens this test wants to listen for.
	 *
	 * @return array
	 */
	public function register() {
		return Tokens::$includeTokens;

	}//end register()


	/**
	 * Processes this test, when one of its tokens is encountered.
	 *
	 * @param \PHP_CodeSniffer\Files\File $phpcsFile The file being scanned.
	 * @param int                         $stackPtr  The position of the current token in the
	 *                                               stack passed in $tokens.
	 *
	 * @return void
	 */
	public function process( File $phpcsFile, $stackPtr ) {
		$tokens = $phpcsFile->getTokens();

		$nextToken = $phpcsFile->findNext( Tokens::$emptyTokens, ( $stackPtr + 1 ), null, true, null, true );

		if ( T_OPEN_PARENTHESIS === $tokens[ $nextToken ]['code'] ) {
			// The construct is using parenthesis, grab the next non empty token.
			$nextToken = $phpcsFile->findNext( Tokens::$emptyTokens, ( $nextToken + 1 ), null, true, null, true );
		}

		if ( T_DIR === $tokens[ $nextToken ]['code'] || '__DIR__' === $tokens[ $nextToken ]['content'] ) {
			// The construct is using __DIR__ which is fine.
			return;
		}

		if ( T_VARIABLE === $tokens[ $nextToken ]['code'] ) {
			$phpcsFile->addWarning( sprintf( 'File inclusion using variable (%s). Probably needs manual inspection.', $tokens[ $nextToken ]['content'] ), $nextToken, 'IncludingFile' );
			return;
		}

		if ( T_STRING === $tokens[ $nextToken ]['code'] ) {

			if ( true === in_array( $tokens[ $nextToken ]['content'], $this->getPathFuncitons, true ) ) {
				// The construct is using one of the function for getting correct path which is fine.
				return;
			}

			if ( true === in_array( $tokens[ $nextToken ]['content'], $this->allowedConstants, true ) ) {
				// The construct is using one of the allowed constants which is fine.
				return;
			}

			if ( true === in_array( $tokens[ $nextToken ]['content'], array_keys( $this->restrictedConstants ), true ) ) {
				// The construct is using one of the restricted constants.
				$phpcsFile->addError( sprintf( '%s constant might not be defined or available. Use %s instead.', $tokens[ $nextToken ]['content'], $this->restrictedConstants[ $tokens[ $nextToken ]['content'] ] ), $nextToken );
				return;
			}

			if ( 1 === preg_match( '/^[a-zA-Z_\x7f-\xff][a-zA-Z0-9_\x7f-\xff]*$/', $tokens[ $nextToken ]['content'] ) ) {
				// The construct is using custom constant, which needs manula inspection.
				$phpcsFile->addWarning( sprintf( 'File inclusion using custom constant (%s). Probably needs manual inspection.', $tokens[ $nextToken ]['content'] ), $nextToken, 'IncludingFile' );
				return;
			}

			if ( 0 === strpos( $tokens[ $nextToken ]['content'], '$' ) ) {
				$phpcsFile->addWarning( sprintf( 'File inclusion using variable (%s). Probably needs manual inspection.', $tokens[ $nextToken ]['content'] ), $nextToken, 'IncludingFile' );
				return;
			}

			if ( true === in_array( $tokens[ $nextToken ]['content'], $this->slashingFunctions, true ) ) {
				// The construct is using one fo the slashing functions, it's probably correct.
				return;
			}

			$nextNextToken = $phpcsFile->findNext( Tokens::$emptyTokens, ( $nextToken + 1 ), null, true, null, true );
			if ( T_OPEN_PARENTHESIS === $tokens[ $nextNextToken ]['code'] ) {
				$phpcsFile->addWarning( sprintf( 'File inclusion using custom function ( %s() ). Probably needs manual inspection.', $tokens[ $nextToken ]['content'] ), $nextToken, 'IncludingFile' );
				return;
			}

			$phpcsFile->addError( 'Absolute include path must be used. Use get_template_directory, get_stylesheet_directory or plugin_dir_path.', $nextToken, 'IncludingFile' );
			return;
		} else {
			$phpcsFile->addError( 'Absolute include path must be used. Use get_template_directory, get_stylesheet_directory or plugin_dir_path.', $nextToken, 'IncludingFile' );
			return;
		}// End if().

	}//end process()


}//end class
