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
use WordPressVIPMinimum\Sniffs\Sniff;

/**
 * Looks for instances of unescaped output for Mustache templating engine and Handlebars.js.
 */
class MustacheSniff extends Sniff {

	/**
	 * A list of tokenizers this sniff supports.
	 *
	 * @var string[]
	 */
	public $supportedTokenizers = [ 'JS', 'PHP' ];

	/**
	 * Returns an array of tokens this test wants to listen for.
	 *
	 * @return array<int|string>
	 */
	public function register() {
		$targets             = Tokens::$textStringTokens;
		$targets[ T_STRING ] = T_STRING;

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

		if ( strpos( $this->tokens[ $stackPtr ]['content'], '{{{' ) !== false && strpos( $this->tokens[ $stackPtr ]['content'], '}}}' ) !== false ) {
			// Mustache unescaped output notation.
			$message = 'Found Mustache unescaped output notation: "{{{}}}".';
			$this->phpcsFile->addWarning( $message, $stackPtr, 'OutputNotation' );
		}

		if ( strpos( $this->tokens[ $stackPtr ]['content'], '{{&' ) !== false ) {
			// Mustache unescaped variable notation.
			$message = 'Found Mustache unescape variable notation: "{{&".';
			$this->phpcsFile->addWarning( $message, $stackPtr, 'VariableNotation' );
		}

		$start_delimiter_change = strpos( $this->tokens[ $stackPtr ]['content'], '{{=' );
		if ( $start_delimiter_change !== false ) {
			// Mustache delimiter change.
			$end_delimiter_change = strpos( $this->tokens[ $stackPtr ]['content'], '=}}' );
			if ( $end_delimiter_change !== false && $start_delimiter_change < $end_delimiter_change ) {
				$start_new_delimiter  = $start_delimiter_change + 3;
				$new_delimiter_length = $end_delimiter_change - ( $start_delimiter_change + 3 );
				$new_delimiter        = substr( $this->tokens[ $stackPtr ]['content'], $start_new_delimiter, $new_delimiter_length );

				$message = 'Found Mustache delimiter change notation. New delimiter is: %s.';
				$data    = [ $new_delimiter ];
				$this->phpcsFile->addWarning( $message, $stackPtr, 'DelimiterChange', $data );
			}
		}

		if ( strpos( $this->tokens[ $stackPtr ]['content'], '.SafeString' ) !== false ) {
			// Handlebars.js Handlebars.SafeString does not get escaped.
			$message = 'Found Handlebars.SafeString call which does not get escaped.';
			$this->phpcsFile->addWarning( $message, $stackPtr, 'SafeString' );
		}
	}
}
