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
use PHPCSUtils\Utils\PassedParameters;
use WordPressCS\WordPress\AbstractFunctionParameterSniff;
use WordPressCS\WordPress\Helpers\PrintingFunctionsTrait;

/**
 * Flag functions that don't return anything, yet are wrapped in an escaping function call.
 *
 * E.g. esc_html( _e( 'foo' ) );
 *
 * @uses \WordPressCS\WordPress\Helpers\PrintingFunctionsTrait::$customPrintingFunctions
 */
class EscapingVoidReturnFunctionsSniff extends AbstractFunctionParameterSniff {

	use PrintingFunctionsTrait;

	/**
	 * The group name for this group of functions.
	 *
	 * @var string
	 */
	protected $group_name = 'escaping_void';

	/**
	 * Functions this sniff is looking for.
	 *
	 * @var array<string, array{param_position: int, param_name: string}> Keys are the target functions,
	 *                                                                    value, the name and position of the target parameter.
	 */
	protected $target_functions = [
		'esc_attr' => [
			'param_position' => 1,
			'param_name'     => 'text',
		],
		'esc_attr__' => [
			'param_position' => 1,
			'param_name'     => 'text',
		],
		'esc_attr_e' => [
			'param_position' => 1,
			'param_name'     => 'text',
		],
		'esc_attr_x' => [
			'param_position' => 1,
			'param_name'     => 'text',
		],
		'esc_html' => [
			'param_position' => 1,
			'param_name'     => 'text',
		],
		'esc_html__' => [
			'param_position' => 1,
			'param_name'     => 'text',
		],
		'esc_html_e' => [
			'param_position' => 1,
			'param_name'     => 'text',
		],
		'esc_html_x' => [
			'param_position' => 1,
			'param_name'     => 'text',
		],
		'esc_js' => [
			'param_position' => 1,
			'param_name'     => 'text',
		],
		'esc_textarea' => [
			'param_position' => 1,
			'param_name'     => 'text',
		],
		'esc_url' => [
			'param_position' => 1,
			'param_name'     => 'url',
		],
		'esc_url_raw' => [
			'param_position' => 1,
			'param_name'     => 'url',
		],
		'esc_xml' => [
			'param_position' => 1,
			'param_name'     => 'text',
		],
		'tag_escape' => [
			'param_position' => 1,
			'param_name'     => 'tag_name',
		],
		'wp_kses' => [
			'param_position' => 1,
			'param_name'     => 'content',
		],
		'wp_kses_data' => [
			'param_position' => 1,
			'param_name'     => 'data',
		],
		'wp_kses_one_attr' => [
			'param_position' => 1,
			'param_name'     => 'attr',
		],
		'wp_kses_post' => [
			'param_position' => 1,
			'param_name'     => 'data',
		],
	];

	/**
	 * Process the parameters of a matched function.
	 *
	 * @param int    $stackPtr        The position of the current token in the stack.
	 * @param string $group_name      The name of the group which was matched.
	 * @param string $matched_content The token content (function name) which was matched
	 *                                in lowercase.
	 * @param array  $parameters      Array with information about the parameters.
	 *
	 * @return void
	 */
	public function process_parameters( $stackPtr, $group_name, $matched_content, $parameters ) {
		$param_position = $this->target_functions[ $matched_content ]['param_position'];
		$param_name     = $this->target_functions[ $matched_content ]['param_name'];

		$target_param = PassedParameters::getParameterFromStack( $parameters, $param_position, $param_name );
		if ( $target_param === false ) {
			// Missing (required) target parameter. Probably live coding, nothing to examine (yet). Bow out.
			return;
		}

		$ignore                   = Tokens::$emptyTokens;
		$ignore[ T_NS_SEPARATOR ] = T_NS_SEPARATOR;

		$next_token = $this->phpcsFile->findNext( $ignore, $target_param['start'], ( $target_param['end'] + 1 ), true );
		if ( $next_token === false || $this->tokens[ $next_token ]['code'] !== T_STRING ) {
			// Not what we are looking for.
			return;
		}

		$next_after = $this->phpcsFile->findNext( Tokens::$emptyTokens, $next_token + 1, ( $target_param['end'] + 1 ), true );
		if ( $next_after === false || $this->tokens[ $next_after ]['code'] !== T_OPEN_PARENTHESIS ) {
			// Not a function call inside the escaping function.
			return;
		}

		if ( $this->is_printing_function( $this->tokens[ $next_token ]['content'] ) ) {
			$message = 'Attempting to escape `%s()` which is printing its output.';
			$data    = [ $this->tokens[ $next_token ]['content'] ];
			$this->phpcsFile->addError( $message, $stackPtr, 'Found', $data );
			return;
		}
	}
}
