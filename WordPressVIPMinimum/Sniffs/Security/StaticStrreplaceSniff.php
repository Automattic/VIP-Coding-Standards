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
use PHPCSUtils\Tokens\Collections;
use PHPCSUtils\Utils\Arrays;
use PHPCSUtils\Utils\PassedParameters;
use PHPCSUtils\Utils\TextStrings;
use WordPressCS\WordPress\AbstractFunctionParameterSniff;

/**
 * Restricts usage of str_replace with all 3 params being static.
 */
class StaticStrreplaceSniff extends AbstractFunctionParameterSniff {

	/**
	 * The group name for this group of functions.
	 *
	 * @var string
	 */
	protected $group_name = 'str_replace';

	/**
	 * Functions this sniff is looking for.
	 *
	 * @var array<string, bool> Key is the function name, value irrelevant.
	 */
	protected $target_functions = [
		'str_replace' => true,
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
		$search_param  = PassedParameters::getParameterFromStack( $parameters, 1, 'search' );
		$replace_param = PassedParameters::getParameterFromStack( $parameters, 2, 'replace' );
		$subject_param = PassedParameters::getParameterFromStack( $parameters, 3, 'subject' );

		if ( $search_param === false || $replace_param === false || $subject_param === false ) {
			/*
			 * Either an invalid function call (missing PHP required parameter); or function call
			 * with argument unpacking; or live coding.
			 * In all these cases, this is not the code pattern this sniff is looking for, so bow out.
			 */
			return;
		}

		foreach ( [ $search_param, $replace_param, $subject_param ] as $param ) {
			if ( $this->is_parameter_static_text( $param ) === false ) {
				// Non-static text token found. Not what we're looking for.
				return;
			}
		}

		$message = 'This code pattern is often used to run a very dangerous shell programs on your server. The code in these files needs to be reviewed, and possibly cleaned.';
		$this->phpcsFile->addError( $message, $stackPtr, 'StaticStrreplace' );
	}

	/**
	 * Check whether the current parameter, or array item, only contains tokens which should be regarded
	 * as a valid part of a static text string.
	 *
	 * @param array<string, int|string> $param_info Array with information about a single parameter or array item.
	 *                                              Must be an array as returned via the PassedParameters class.
	 *
	 * @return bool
	 */
	private function is_parameter_static_text( $param_info ) {
		// List of tokens which can be skipped over without further examination.
		$static_tokens  = [
			T_CONSTANT_ENCAPSED_STRING => T_CONSTANT_ENCAPSED_STRING,
			T_PLUS                     => T_PLUS,
			T_STRING_CONCAT            => T_STRING_CONCAT,
		];
		$static_tokens += Tokens::$emptyTokens;

		for ( $i = $param_info['start']; $i <= $param_info['end']; $i++ ) {
			$next_to_examine = $this->phpcsFile->findNext( $static_tokens, $i, ( $param_info['end'] + 1 ), true );
			if ( $next_to_examine === false ) {
				// The parameter contained only tokens which could be considered static text.
				return true;
			}

			if ( isset( Collections::arrayOpenTokensBC()[ $this->tokens[ $next_to_examine ]['code'] ] ) ) {
				$arrayOpenClose = Arrays::getOpenClose( $this->phpcsFile, $next_to_examine );
				if ( $arrayOpenClose === false ) {
					// Short list, parse error or live coding, bow out.
					return false;
				}

				$array_items = PassedParameters::getParameters( $this->phpcsFile, $next_to_examine );
				foreach ( $array_items as $array_item ) {
					if ( $this->is_parameter_static_text( $array_item ) === false ) {
						return false;
					}
				}

				// The array only contained items with tokens which could be considered static text.
				$i = $arrayOpenClose['closer'];
				continue;
			}

			if ( $this->tokens[ $next_to_examine ]['code'] === T_START_HEREDOC ) {
				$heredoc_text  = TextStrings::getCompleteTextString( $this->phpcsFile, $next_to_examine );
				$stripped_text = TextStrings::stripEmbeds( $heredoc_text );
				if ( $heredoc_text !== $stripped_text ) {
					// Heredoc with interpolated expression(s). Not a static text.
					return false;
				}
			}

			if ( ( $this->tokens[ $next_to_examine ]['code'] === T_START_HEREDOC
				|| $this->tokens[ $next_to_examine ]['code'] === T_START_NOWDOC )
				&& isset( $this->tokens[ $next_to_examine ]['scope_closer'] )
			) {
				// No interpolation. Skip to end of a heredoc/nowdoc.
				$i = $this->tokens[ $next_to_examine ]['scope_closer'];
				continue;
			}

			// Any other token means this parameter should be regarded as non-static text. Not what we're looking for.
			return false;
		}

		return true;
	}
}
