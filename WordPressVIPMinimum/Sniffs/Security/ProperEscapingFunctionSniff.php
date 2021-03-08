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
		'esc_url'    => 'url',
		'esc_attr'   => 'attr',
		'esc_attr__' => 'attr',
		'esc_attr_x' => 'attr',
		'esc_attr_e' => 'attr',
		'esc_html'   => 'html',
		'esc_html__' => 'html',
		'esc_html_x' => 'html',
		'esc_html_e' => 'html',
	];

	/**
	 * List of tokens we can skip.
	 *
	 * @var array
	 */
	private $echo_or_concat_tokens =
	[
		T_ECHO               => T_ECHO,
		T_OPEN_TAG           => T_OPEN_TAG,
		T_OPEN_TAG_WITH_ECHO => T_OPEN_TAG_WITH_ECHO,
		T_STRING_CONCAT      => T_STRING_CONCAT,
		T_COMMA              => T_COMMA,
	];

	/**
	 * List of attributes associated with url outputs.
	 *
	 * @var array
	 */
	private $url_attrs = [
		'href',
		'src',
		'url',
		'action',
	];

	/**
	 * List of syntaxes for inside attribute detection.
	 *
	 * @var array
	 */
	private $attr_endings = [
		'="',
		"='",
		"=\\'",
		'=\\"',
	];

	/**
	 * Returns an array of tokens this test wants to listen for.
	 *
	 * @return array
	 */
	public function register() {
		$this->echo_or_concat_tokens += Tokens::$emptyTokens;

		return [ T_STRING ];
	}

	/**
	 * Process this test when one of its tokens is encountered
	 *
	 * @param int $stackPtr The position of the current token in the stack passed in $tokens.
	 *
	 * @return void
	 */
	public function process_token( $stackPtr ) {

		if ( isset( $this->escaping_functions[ $this->tokens[ $stackPtr ]['content'] ] ) === false ) {
			return;
		}

		$html = $this->phpcsFile->findPrevious( $this->echo_or_concat_tokens, $stackPtr - 1, null, true );

		// Use $textStringTokens b/c heredoc and nowdoc tokens shouldn't be matched anyways.
		if ( $html === false || isset( Tokens::$textStringTokens[ $this->tokens[ $html ]['code'] ] ) === false ) {
			return;
		}

		$function_name = $this->tokens[ $stackPtr ]['content'];

		$data = [ $function_name ];

		$content = $this->tokens[ $html ]['content'];

		if ( isset( Tokens::$stringTokens[ $this->tokens[ $html ]['code'] ] ) === true ) {
			$content = Sniff::strip_quotes( $content );
		}

		if ( $this->is_outside_html_attr_context( $function_name, $content ) ) {
			$message = 'Wrong escaping function, using `%s()` in a context outside of HTML attributes may not escape properly.';
			$this->phpcsFile->addError( $message, $html, 'notAttrEscAttr', $data );
			return;
		}

		if ( $function_name !== 'esc_url' && $this->attr_expects_url( $content ) ) {
			$message = 'Wrong escaping function. href, src, and action attributes should be escaped by `esc_url()`, not by `%s()`.';
			$this->phpcsFile->addError( $message, $stackPtr, 'hrefSrcEscUrl', $data );
			return;
		}

		if ( $function_name === 'esc_html' && $this->is_html_attr( $content ) ) {
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
		foreach ( $this->url_attrs as $attr ) {
			foreach ( $this->attr_endings as $ending ) {
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
		foreach ( $this->attr_endings as $ending ) {
			if ( $this->endswith( $content, $ending ) === true ) {
				$is_html_attr = true;
				break;
			}
		}
		return $is_html_attr;
	}

	/**
	 * Tests whether escaping function is being used outside of HTML tag.
	 *
	 * @param string $function_name Escaping function.
	 * @param string $content       Haystack where we look for the end of a HTML tag.
	 *
	 * @return bool True if escaping attribute function and string ends with a HTML tag.
	 */
	public function is_outside_html_attr_context( $function_name, $content ) {
		return $this->escaping_functions[ $function_name ] === 'attr' && $this->endswith( trim( $content ), '>' );
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
