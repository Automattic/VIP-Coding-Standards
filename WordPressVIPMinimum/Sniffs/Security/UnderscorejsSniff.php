<?php
/**
 * WordPressVIPMinimum Coding Standard.
 *
 * @package VIPCS\WordPressVIPMinimum
 * @link https://github.com/Automattic/VIP-Coding-Standards
 * @license https://opensource.org/license/gpl-2-0 GPL-2.0
 */

namespace WordPressVIPMinimum\Sniffs\Security;

use PHP_CodeSniffer\Util\Tokens;
use PHPCSUtils\Utils\TextStrings;
use WordPressVIPMinimum\Sniffs\Sniff;

/**
 * Looks for instances of unescaped output for Underscore.js templating engine within PHP code.
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
	 * Regex to match execute notations containing a print command
	 * and retrieve a code snippet.
	 *
	 * @var string
	 */
	const UNESCAPED_PRINT_REGEX = '`<%\s*(?:print\s*\(.+?\)\s*;|__p\s*\+=.+?)\s*%>`';

	/**
	 * Regex to match the "interpolate" keyword when used to overrule the ERB-style delimiters.
	 *
	 * @var string
	 */
	const INTERPOLATE_KEYWORD_REGEX = '`(?:templateSettings\.interpolate|\.interpolate\s*=\s*/|interpolate\s*:\s*/)`';

	/**
	 * Returns an array of tokens this test wants to listen for.
	 *
	 * @return array<int|string>
	 */
	public function register() {
		return Tokens::$textStringTokens;
	}

	/**
	 * Processes this test, when one of its tokens is encountered.
	 *
	 * @param int $stackPtr The position of the current token in the stack passed in $tokens.
	 *
	 * @return void
	 */
	public function process_token( $stackPtr ) {
		$content = TextStrings::stripQuotes( $this->tokens[ $stackPtr ]['content'] );

		$match_count = preg_match_all( self::UNESCAPED_INTERPOLATE_REGEX, $content, $matches );
		if ( $match_count > 0 ) {
			foreach ( $matches[0] as $match ) {
				if ( strpos( $match, '_.escape(' ) !== false ) {
					continue;
				}

				// Underscore.js unescaped output.
				$message = 'Found Underscore.js unescaped output notation: "%s".';
				$data    = [ $match ];
				$this->phpcsFile->addWarning( $message, $stackPtr, 'OutputNotation', $data );
			}
		}

		$match_count = preg_match_all( self::UNESCAPED_PRINT_REGEX, $content, $matches );
		if ( $match_count > 0 ) {
			foreach ( $matches[0] as $match ) {
				if ( strpos( $match, '_.escape(' ) !== false ) {
					continue;
				}

				// Underscore.js unescaped output.
				$message = 'Found Underscore.js unescaped print execution: "%s".';
				$data    = [ $match ];
				$this->phpcsFile->addWarning( $message, $stackPtr, 'PrintExecution', $data );
			}
		}

		if ( preg_match( self::INTERPOLATE_KEYWORD_REGEX, $content ) > 0 ) {
			// Underscore.js delimiter change.
			$message = 'Found Underscore.js delimiter change notation.';
			$this->phpcsFile->addWarning( $message, $stackPtr, 'InterpolateFound' );
		}
	}
}
