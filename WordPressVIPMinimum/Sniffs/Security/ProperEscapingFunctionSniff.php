<?php
/**
 * WordPressVIPMinimum Coding Standard.
 *
 * @package VIPCS\WordPressVIPMinimum
 * @link https://github.com/Automattic/VIP-Coding-Standards
 */

namespace WordPressVIPMinimum\Sniffs\Security;

use WordPressVIPMinimum\Sniffs\Sniff;
use PHP_CodeSniffer\Util\Tokens;

/**
 * Checks whether proper escaping function is used.
 *
 *  @package VIPCS\WordPressVIPMinimum
 */
class ProperEscapingFunctionSniff extends Sniff {

	/**
	 * List of escaping functions which are being tested.
	 *
	 * @var array
	 */
	public $escaping_functions = [
		'esc_url',
		'esc_attr',
		'esc_html',
	];

	/**
	 * Returns an array of tokens this test wants to listen for.
	 *
	 * @return array
	 */
	public function register() {
		return Tokens::$functionNameTokens;
	}

	/**
	 * Process this test when one of its tokens is encountered
	 *
	 * @param int $stackPtr The position of the current token in the stack passed in $tokens.
	 *
	 * @return void
	 */
	public function process_token( $stackPtr ) {

		if ( in_array( $this->tokens[ $stackPtr ]['content'], $this->escaping_functions, true ) === false ) {
			return;
		}

		$function_name = $this->tokens[ $stackPtr ]['content'];

		$echo_or_string_concat = $this->phpcsFile->findPrevious( Tokens::$emptyTokens, $stackPtr - 1, null, true );

		if ( $this->tokens[ $echo_or_string_concat ]['code'] === T_ECHO ) {
			// Very likely inline HTML with <?php tag.
			$php_open = $this->phpcsFile->findPrevious( Tokens::$emptyTokens, $echo_or_string_concat - 1, null, true );

			if ( $this->tokens[ $php_open ]['code'] !== T_OPEN_TAG ) {
				return;
			}

			$html = $this->phpcsFile->findPrevious( Tokens::$emptyTokens, $php_open - 1, null, true );

			if ( $this->tokens[ $html ]['code'] !== T_INLINE_HTML ) {
				return;
			}
		} elseif ( $this->tokens[ $echo_or_string_concat ]['code'] === T_STRING_CONCAT ) {
			// Very likely string concatenation mixing strings and functions/variables.
			$html = $this->phpcsFile->findPrevious( Tokens::$emptyTokens, $echo_or_string_concat - 1, null, true );

			if ( $this->tokens[ $html ]['code'] !== T_CONSTANT_ENCAPSED_STRING ) {
				return;
			}
		} else {
			// Neither - bailing.
			return;
		}

		$data = [ $function_name ];

		if ( $function_name !== 'esc_url' && $this->attr_expects_url( $this->tokens[ $html ]['content'] ) ) {
			$message = 'Wrong escaping function. href, src, and action attributes should be escaped by `esc_url()`, not by `%s()`.';
			$this->phpcsFile->addError( $message, $stackPtr, 'hrefSrcEscUrl', $data );
			return;
		}
		if ( $function_name === 'esc_html' && $this->is_html_attr( $this->tokens[ $html ]['content'] ) ) {
			$message = 'Wrong escaping function. HTML attributes should be escaped by `esc_attr()`, not by `%s()`.';
			$this->phpcsFile->addError( $message, $stackPtr, 'htmlAttrNotByEscHTML', $data );
			return;
		}
	}

	/**
	 * Tests whether provided string ends with open attribute which expects a URL value.
	 *
	 * @param string $content Haystack in which we look for an open attribute which exects a URL value.
	 *
	 * @return bool True if string ends with open attribute which exects a URL value.
	 */
	public function attr_expects_url( $content ) {
		$attr_expects_url = false;
		foreach ( [ 'href', 'src', 'url', 'action' ] as $attr ) {
			foreach ( [
				'="',
				"='",
				'=\'"', // The tokenizer does some fun stuff when it comes to mixing double and single quotes.
				'="\'', // The tokenizer does some fun stuff when it comes to mixing double and single quotes.
			] as $ending ) {
				if ( $this->endswith( $content, $attr . $ending ) === true ) {
					$attr_expects_url = true;
					break;
				}
			}
		}
		return $attr_expects_url;
	}

	/**
	 * Tests whether provided string ends with open HMTL attribute.
	 *
	 * @param string $content Haystack in which we look for open HTML attribute.
	 *
	 * @return bool True if string ends with open HTML attribute.
	 */
	public function is_html_attr( $content ) {
		$is_html_attr = false;
		foreach ( [
			'="',
			"='",
			'=\'"', // The tokenizer does some fun stuff when it comes to mixing double and single quotes.
			'="\'', // The tokenizer does some fun stuff when it comes to mixing double and single quotes.
		] as $ending ) {
			if ( $this->endswith( $content, $ending ) === true ) {
				$is_html_attr = true;
				break;
			}
		}
		return $is_html_attr;
	}

	/**
	 * A helper function which tests whether string ends with some other.
	 *
	 * @param string $haystack String which is being tested.
	 * @param string $needle The substring, which we try to locate on the end of the $haystack.
	 *
	 * @return bool True if haystack ends with needle.
	 */
	public function endswith( $haystack, $needle ) {
		return substr( $haystack, -strlen( $needle ) ) === $needle;
	}
}
