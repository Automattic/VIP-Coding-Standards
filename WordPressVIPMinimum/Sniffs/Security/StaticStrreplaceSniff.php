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
use PHPCSUtils\Exceptions\UnexpectedTokenType;
use PHPCSUtils\Tokens\Collections;
use PHPCSUtils\Utils\PassedParameters;
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

		$static_text_tokens                               = Tokens::$emptyTokens;
		$static_text_tokens[ T_CONSTANT_ENCAPSED_STRING ] = T_CONSTANT_ENCAPSED_STRING;

		foreach ( [ $search_param, $replace_param, $subject_param ] as $param ) {
			$has_non_static_text = $this->phpcsFile->findNext( $static_text_tokens, $param['start'], ( $param['end'] + 1 ), true );
			if ( $has_non_static_text === false ) {
				// The parameter contained only tokens which could be considered static text.
				continue;
			}

			if ( isset( Collections::arrayOpenTokensBC()[ $this->tokens[ $has_non_static_text ]['code'] ] ) ) {
				try {
					$array_items = PassedParameters::getParameters( $this->phpcsFile, $has_non_static_text );
				} catch ( UnexpectedTokenType $e ) {
					// Short list, parse error or live coding, bow out.
					return;
				}

				foreach ( $array_items as $array_item ) {
					$has_non_static_text = $this->phpcsFile->findNext( $static_text_tokens, $array_item['start'], $array_item['end'], true );
					if ( $has_non_static_text !== false ) {
						return;
					}
				}

				// The array only contained items with tokens which could be considered static text.
				continue;
			}

			// Non-static text token found. Not what we're looking for.
			return;
		}

		$message = 'This code pattern is often used to run a very dangerous shell programs on your server. The code in these files needs to be reviewed, and possibly cleaned.';
		$this->phpcsFile->addError( $message, $stackPtr, 'StaticStrreplace' );
	}
}
