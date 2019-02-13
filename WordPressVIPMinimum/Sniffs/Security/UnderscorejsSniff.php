<?php
/**
 * WordPressVIPMinimum Coding Standard.
 *
 * @package VIPCS\WordPressVIPMinimum
 * @link https://github.com/Automattic/VIP-Coding-Standards
 */

namespace WordPressVIPMinimum\Sniffs\Security;

use WordPressVIPMinimum\Sniffs\Sniff;

/**
 * Looks for instances of unescaped output for Underscore.js templating engine.
 *
 * @package VIPCS\WordPressVIPMinimum
 */
class UnderscorejsSniff extends Sniff {

	/**
	 * A list of tokenizers this sniff supports.
	 *
	 * @var string[]
	 */
	public $supportedTokenizers = [ 'JS', 'PHP' ];

	/**
	 * Returns an array of tokens this test wants to listen for.
	 *
	 * @return array
	 */
	public function register() {
		return [
			T_CONSTANT_ENCAPSED_STRING,
			T_PROPERTY,
			T_INLINE_HTML,
			T_HEREDOC,
		];
	}

	/**
	 * Processes this test, when one of its tokens is encountered.
	 *
	 * @param int $stackPtr The position of the current token in the stack passed in $tokens.
	 *
	 * @return void
	 */
	public function process_token( $stackPtr ) {

		if ( false !== strpos( $this->tokens[ $stackPtr ]['content'], '<%=' ) ) {
			// Underscore.js unescaped output.
			$message = 'Found Underscore.js unescaped output notation: "<%=".';
			$this->phpcsFile->addWarning( $message, $stackPtr, 'OutputNotation' );
		}

		if ( false !== strpos( $this->tokens[ $stackPtr ]['content'], 'interpolate' ) ) {
			// Underscore.js unescaped output.
			$message = 'Found Underscore.js delimiter change notation.';
			$this->phpcsFile->addWarning( $message, $stackPtr, 'InterpolateFound' );
		}
	}

}
