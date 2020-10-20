<?php
/**
 * WordPressVIPMinimum Coding Standard.
 *
 * @package VIPCS\WordPressVIPMinimum
 * @link https://github.com/Automattic/VIP-Coding-Standards
 */

namespace WordPressVIPMinimum\Sniffs\Security;

use PHP_CodeSniffer\Util\Tokens;
use WordPressVIPMinimum\Sniffs\Sniff;

/**
 * Looks for instances of unescaped output for Underscore.js templating engine.
 *
 * @package VIPCS\WordPressVIPMinimum
 */
class UnderscorejsSniff extends Sniff {

	/**
	 * Regex to match unescaped output notations containing variable interpolation
	 * and retrieve a code snippet.
	 *
	 * @var string
	 */
	const UNESCAPED_INTERPOLATE_REGEX = '`<%=\s*(?:.+?%>|$)`';

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
		$targets   = Tokens::$textStringTokens;
		$targets[] = T_PROPERTY;

		return $targets;
	}

	/**
	 * Processes this test, when one of its tokens is encountered.
	 *
	 * @param int $stackPtr The position of the current token in the stack passed in $tokens.
	 *
	 * @return void
	 */
	public function process_token( $stackPtr ) {

		$content     = $this->strip_quotes( $this->tokens[ $stackPtr ]['content'] );
		$match_count = preg_match_all( self::UNESCAPED_INTERPOLATE_REGEX, $content, $matches );
		if ( $match_count > 0 ) {
			foreach ( $matches[0] as $match ) {
				// Underscore.js unescaped output.
				$message = 'Found Underscore.js unescaped output notation: "%s".';
				$data    = [ $match ];
				$this->phpcsFile->addWarning( $message, $stackPtr, 'OutputNotation', $data );
			}
		}

		if ( strpos( $content, 'interpolate' ) !== false ) {
			// Underscore.js unescaped output.
			$message = 'Found Underscore.js delimiter change notation.';
			$this->phpcsFile->addWarning( $message, $stackPtr, 'InterpolateFound' );
		}
	}

}
