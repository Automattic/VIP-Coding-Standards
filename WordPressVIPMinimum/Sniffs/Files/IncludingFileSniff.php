<?php
/**
 * WordPressVIPMinimum_Sniffs_Files_IncludingFileSniff.
 */

/**
 * WordPressVIPMinimum_Sniffs_Files_IncludingFileSniff.
 *
 * Checks that __DIR__, dirname( __FILE__ ) or plugin_dir_path( __FILE__ )
 * is used when including or requiring files
 */
class WordPressVIPMinimum_Sniffs_Files_IncludingFileSniff implements PHP_CodeSniffer_Sniff {

	public $getPathFuncitons = array(
		'plugin_dir_path',
		'dirname',
		'get_stylesheet_directory',
		'get_template_directory',
	);

	public $restrictedConstants = array(
		'TEMPLATEPATH' => 'get_template_directory',
		'STYLESHEETPATH' => 'get_stylesheet_directory',
	);

	public $allowedConstants = array(
		'ABSPATH',
		'WP_CONTENT_DIR',
		'WP_PLUGIN_DIR',
	);

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
		return PHP_CodeSniffer_Tokens::$includeTokens;

	}//end register()


	/**
	 * Processes this test, when one of its tokens is encountered.
	 *
	 * @param PHP_CodeSniffer_File $phpcsFile The file being scanned.
	 * @param int				  $stackPtr  The position of the current token in the
	 *										stack passed in $tokens.
	 *
	 * @return void
	 */
	public function process( PHP_CodeSniffer_File $phpcsFile, $stackPtr ) {
		$tokens = $phpcsFile->getTokens();

		$nextToken = $phpcsFile->findNext( PHP_CodeSniffer_Tokens::$emptyTokens, ($stackPtr + 1), null, true, null, true );
		
		if ( $tokens[$nextToken]['code'] === T_OPEN_PARENTHESIS ) {
			// The construct is using parenthesis, grab the next non empty token
			$nextToken = $phpcsFile->findNext( PHP_CodeSniffer_Tokens::$emptyTokens, ($nextToken + 1), null, true, null, true );
		}

		if ( $tokens[$nextToken]['code'] === T_DIR ) {
			// The construct is using __DIR__ which is fine
			return;
		}

		if ( $tokens[$nextToken]['code'] === T_VARIABLE ) {
			$phpcsFile->addWarning( sprintf( 'File inclusion using variable (%s). Probably needs manual inspection.', $tokens[$nextToken]['content'] ), $nextToken );
			return;
		}

		if ( $tokens[$nextToken]['code'] === T_STRING ) {

			if ( true === in_array( $tokens[$nextToken]['content'], $this->getPathFuncitons, true ) ) {
				// The construct is using one of the function for getting correct path which is fine.
				return;
			}

			if ( true === in_array( $tokens[$nextToken]['content'], $this->allowedConstants, true ) ) {
				//The construct is using one of the allowed constants which is fine
				return;
			}

			if ( true === in_array( $tokens[$nextToken]['content'], array_keys( $this->restrictedConstants ), true ) ) {
				// The construct is using one of the restricted constants.
				$phpcsFile->addError( sprintf( '%s constant might not be defined or available. Use %s instead.', $tokens[$nextToken]['content'], $this->restrictedConstants[$tokens[$nextToken]['content']] ), $nextToken );
				return;
			}

			if ( 1 === preg_match( '/^[a-zA-Z_\x7f-\xff][a-zA-Z0-9_\x7f-\xff]*$/', $tokens[$nextToken]['content'] ) ) {
				// The construct is using custom constant, which needs manula inspection
				$phpcsFile->addWarning( sprintf( 'File inclusion using custom constant (%s). Probably needs manual inspection.', $tokens[$nextToken]['content'] ), $nextToken );
				return;
			}

			if ( 0 === strpos( $tokens[$nextToken]['content'], '$' ) ) {
				$phpcsFile->addWarning( sprintf( 'File inclusion using variable (%s). Probably needs manual inspection.', $tokens[$nextToken]['content'] ), $nextToken );
				return;
			}

			if ( true === in_array( $tokens[$nextToken]['content'], $this->slashingFunctions, true ) ) {
				// The construct is using one fo the slashing functions, it's probably correct
				return;
			}

			$nextNextToken = $phpcsFile->findNext( PHP_CodeSniffer_Tokens::$emptyTokens, ($nextToken + 1), null, true, null, true );
			if ( $tokens[$nextNextToken]['code'] === T_OPEN_PARENTHESIS ) {
				$phpcsFile->addWarning( sprintf( 'File inclusion using custom function ( %s() ). Probably needs manual inspection.', $tokens[$nextToken]['content'] ), $nextToken );
				return;
			}


			$phpcsFile->addError( 'Absolute include path must be used. Use get_template_directory, get_stylesheet_directory or plugin_dir_path.', $nextToken );
			return;

		} else {
			$phpcsFile->addError( 'Absolute include path must be used. Use get_template_directory, get_stylesheet_directory or plugin_dir_path.', $nextToken );
			return;
		}

	}//end process()


}//end class
