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

		if ( false === in_array( $this->tokens[ $stackPtr ]['content'], $this->escaping_functions, true ) ) {
			return;
		}

		$function_name = $this->tokens[ $stackPtr ]['content'];

		$echo_or_string_concat = $this->phpcsFile->findPrevious( Tokens::$emptyTokens, $stackPtr - 1, null, true );

		if ( T_ECHO === $this->tokens[ $echo_or_string_concat ]['code'] ) {
			// Very likely inline HTML with <?php tag.
			$php_open = $this->phpcsFile->findPrevious( Tokens::$emptyTokens, $echo_or_string_concat - 1, null, true );

			if ( T_OPEN_TAG !== $this->tokens[ $php_open ]['code'] ) {
				return;
			}

			$html = $this->phpcsFile->findPrevious( Tokens::$emptyTokens, $php_open - 1, null, true );

			if ( T_INLINE_HTML !== $this->tokens[ $html ]['code'] ) {
				return;
			}
		} elseif ( T_STRING_CONCAT === $this->tokens[ $echo_or_string_concat ]['code'] ) {
			// Very likely string concatenation mixing strings and functions/variables.
			$html = $this->phpcsFile->findPrevious( Tokens::$emptyTokens, $echo_or_string_concat - 1, null, true );

			if ( T_CONSTANT_ENCAPSED_STRING !== $this->tokens[ $html ]['code'] ) {
				return;
			}
		} else {
			// Neither - bailing.
			return;
		}

		$data = [ $function_name ];

		if ( 'esc_url' !== $function_name && $this->is_href_or_src( $this->tokens[ $html ]['content'] ) ) {
			$message = 'Wrong escaping function. href and src attributes should be escaped by `esc_url()`, not by `%s()`.';
			$this->phpcsFile->addError( $message, $stackPtr, 'hrefSrcEscUrl', $data );
			return;
		}
		if ( 'esc_html' === $function_name && $this->is_html_attr( $this->tokens[ $html ]['content'] ) ) {
			$message = 'Wrong escaping function. HTML attributes should be escaped by `esc_attr()`, not by `%s()`.';
			$this->phpcsFile->addError( $message, $stackPtr, 'htmlAttrNotByEscHTML', $data );
			return;
		}
	}

	/**
	 * Tests whether provided string ends with open src or href attribute.
	 *
	 * @param string $content Haystack in which we look for an open src or href attribute.
	 *
	 * @return bool True if string ends with open src or href attribute.
	 */
	public function is_href_or_src( $content ) {
		$is_href_or_src = false;
		foreach ( [ 'href', 'src', 'url' ] as $attr ) {
			foreach ( [
				'="',
				"='",
				'=\'"', // The tokenizer does some fun stuff when it comes to mixing double and single quotes.
				'="\'', // The tokenizer does some fun stuff when it comes to mixing double and single quotes.
			] as $ending ) {
				if ( true === $this->endswith( $content, $attr . $ending ) ) {
					$is_href_or_src = true;
					break;
				}
			}
		}
		return $is_href_or_src;
	}

	/**
	 * Tests, whether provided string ends with open HMTL attribute.
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
			if ( true === $this->endswith( $content, $ending ) ) {
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
