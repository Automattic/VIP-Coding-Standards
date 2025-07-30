<?php
/**
 * WordPressVIPMinimum Coding Standard.
 *
 * @package VIPCS\WordPressVIPMinimum
 * @link https://github.com/Automattic/VIP-Coding-Standards
 * @license https://opensource.org/license/gpl-2-0 GPL-2.0
 */

namespace WordPressVIPMinimum\Sniffs\Performance;

use PHP_CodeSniffer\Util\Tokens;
use PHPCSUtils\Utils\PassedParameters;
use WordPressCS\WordPress\AbstractFunctionParameterSniff;

/**
 * Restricts the implementation of taxonomy term meta via options.
 */
class TaxonomyMetaInOptionsSniff extends AbstractFunctionParameterSniff {

	/**
	 * List of options_ functions
	 *
	 * @deprecated 3.1.0 This property should never have been public.
	 *
	 * @var array<string>
	 */
	public $option_functions = [
		'get_option',
		'add_option',
		'update_option',
		'delete_option',
	];

	/**
	 * List of possible variable names holding term ID.
	 *
	 * @var array<string>
	 */
	public $taxonomy_term_patterns = [
		'category_id',
		'cat_id',
		'cat',
		'term_id',
		'term',
		'tag_id',
		'tag',
	];

	/**
	 * The group name for this group of functions.
	 *
	 * @var string
	 */
	protected $group_name = 'option_functions';

	/**
	 * Functions this sniff is looking for.
	 *
	 * @var array<string, true> Keys are the target functions, value irrelevant.
	 */
	protected $target_functions = [
		'get_option'    => true,
		'add_option'    => true,
		'update_option' => true,
		'delete_option' => true,
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
		$target_param = PassedParameters::getParameterFromStack( $parameters, 1, 'option' );
		if ( $target_param === false ) {
			// Missing (required) target parameter. Probably live coding, nothing to examine (yet). Bow out.
			return;
		}

		$param_start = $target_param['start'];
		$param_end   = ( $target_param['end'] + 1 ); // Add one to include the last token in the parameter in findNext searches.

		$param_ptr = $this->phpcsFile->findNext( Tokens::$emptyTokens, $param_start, $param_end, true );

		if ( $this->tokens[ $param_ptr ]['code'] === T_DOUBLE_QUOTED_STRING ) {
			foreach ( $this->taxonomy_term_patterns as $taxonomy_term_pattern ) {
				if ( strpos( $this->tokens[ $param_ptr ]['content'], $taxonomy_term_pattern ) !== false ) {
					$this->addPossibleTermMetaInOptionsWarning( $stackPtr );
					return;
				}
			}
		} elseif ( $this->tokens[ $param_ptr ]['code'] === T_CONSTANT_ENCAPSED_STRING ) {

			$string_concat = $this->phpcsFile->findNext( Tokens::$emptyTokens, $param_ptr + 1, null, true );
			if ( $this->tokens[ $string_concat ]['code'] !== T_STRING_CONCAT ) {
				return;
			}

			$variable_name = $this->phpcsFile->findNext( Tokens::$emptyTokens, $string_concat + 1, null, true );
			if ( $this->tokens[ $variable_name ]['code'] !== T_VARIABLE ) {
				return;
			}

			foreach ( $this->taxonomy_term_patterns as $taxonomy_term_pattern ) {
				if ( strpos( $this->tokens[ $variable_name ]['content'], $taxonomy_term_pattern ) !== false ) {
					$this->addPossibleTermMetaInOptionsWarning( $stackPtr );
					return;
				}
			}

			$object_operator = $this->phpcsFile->findNext( Tokens::$emptyTokens, $variable_name + 1, null, true );
			if ( $this->tokens[ $object_operator ]['code'] !== T_OBJECT_OPERATOR
				&& $this->tokens[ $object_operator ]['code'] !== T_NULLSAFE_OBJECT_OPERATOR
			) {
				return;
			}

			$object_property = $this->phpcsFile->findNext( Tokens::$emptyTokens, $object_operator + 1, null, true );
			if ( $this->tokens[ $object_property ]['code'] !== T_STRING ) {
				return;
			}

			foreach ( $this->taxonomy_term_patterns as $taxonomy_term_pattern ) {
				if ( strpos( $this->tokens[ $object_property ]['content'], $taxonomy_term_pattern ) !== false ) {
					$this->addPossibleTermMetaInOptionsWarning( $stackPtr );
					return;
				}
			}
		}
	}

	/**
	 * Helper method for composing the Warning for all possible cases.
	 *
	 * @param int $stackPtr The position of the current token in the stack passed in $tokens.
	 *
	 * @return void
	 */
	public function addPossibleTermMetaInOptionsWarning( $stackPtr ) {
		$message = 'Possible detection of storing taxonomy term meta in options table. Needs manual inspection. All such data should be stored in term_meta.';
		$this->phpcsFile->addWarning( $message, $stackPtr, 'PossibleTermMetaInOptions' );
	}
}
