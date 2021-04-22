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
	 * Regular expression to match the end of HTML attributes.
	 *
	 * @var string
	 */
	const ATTR_END_REGEX = '`(?<attrname>href|src|url|(^|\s+)action)?=(?:\\\\)?["\']*$`i';

	/**
	 * List of escaping functions which are being tested.
	 *
	 * @var array
	 */
	protected $escaping_functions = [
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
		T_NS_SEPARATOR       => T_NS_SEPARATOR,
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

		$function_name = strtolower( $this->tokens[ $stackPtr ]['content'] );

		if ( isset( $this->escaping_functions[ $function_name ] ) === false ) {
			return;
		}

		$next_non_empty = $this->phpcsFile->findNext( Tokens::$emptyTokens, ( $stackPtr + 1 ), null, true );
		if ( $next_non_empty === false || $this->tokens[ $next_non_empty ]['code'] !== T_OPEN_PARENTHESIS ) {
			// Not a function call.
			return;
		}

		$ignore             = $this->echo_or_concat_tokens;
		$start_of_statement = $this->phpcsFile->findStartOfStatement( $stackPtr, T_COMMA );
		if ( $this->tokens[ $start_of_statement ]['code'] === T_ECHO ) {
			$ignore[ T_COMMA ] = T_COMMA;
		}

		$html = $this->phpcsFile->findPrevious( $ignore, $stackPtr - 1, null, true );

		// Use $textStringTokens b/c heredoc and nowdoc tokens will never be encountered in this context anyways..
		if ( $html === false || isset( Tokens::$textStringTokens[ $this->tokens[ $html ]['code'] ] ) === false ) {
			return;
		}

		$data = [ $function_name ];

		$content = $this->tokens[ $html ]['content'];
		if ( isset( Tokens::$stringTokens[ $this->tokens[ $html ]['code'] ] ) === true ) {
			$content = Sniff::strip_quotes( $content );
		}

		$escaping_type = $this->escaping_functions[ $function_name ];

		if ( $escaping_type === 'attr' && $this->is_outside_html_attr_context( $content ) ) {
			$message = 'Wrong escaping function, using `%s()` in a context outside of HTML attributes may not escape properly.';
			$this->phpcsFile->addError( $message, $html, 'notAttrEscAttr', $data );
			return;
		}

		if ( preg_match( self::ATTR_END_REGEX, $content, $matches ) !== 1 ) {
			return;
		}

		if ( $escaping_type !== 'url' && empty( $matches['attrname'] ) === false ) {
			$message = 'Wrong escaping function. href, src, and action attributes should be escaped by `esc_url()`, not by `%s()`.';
			$this->phpcsFile->addError( $message, $stackPtr, 'hrefSrcEscUrl', $data );
			return;
		}

		if ( $escaping_type === 'html' ) {
			$message = 'Wrong escaping function. HTML attributes should be escaped by `esc_attr()`, not by `%s()`.';
			$this->phpcsFile->addError( $message, $stackPtr, 'htmlAttrNotByEscHTML', $data );
			return;
		}
	}

	/**
	 * Tests whether an attribute escaping function is being used outside of an HTML tag.
	 *
	 * @param string $content Haystack where we look for the end of a HTML tag.
	 *
	 * @return bool True if the passed string ends a HTML tag.
	 */
	public function is_outside_html_attr_context( $content ) {
		return $this->endswith( trim( $content ), '>' );
	}

	/**
	 * A helper function which tests whether string ends with some other.
	 *
	 * @param string $haystack String which is being tested.
	 * @param string $needle   The substring, which we try to locate on the end of the $haystack.
	 *
	 * @return bool True if haystack ends with needle.
	 */
	public function endswith( $haystack, $needle ) {
		return substr( $haystack, -strlen( $needle ) ) === $needle;
	}
}
