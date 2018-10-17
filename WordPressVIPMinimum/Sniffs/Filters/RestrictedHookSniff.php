<?php
/**
 * WordPressVIPMinimum Coding Standard.
 *
 * @package VIPCS\WordPressVIPMinimum
 */

namespace WordPressVIPMinimum\Sniffs\Filters;

use WordPress\AbstractFunctionParameterSniff;

/**
 * This sniff restricts usage of some action and filter hooks.
 *
 * @package VIPCS\WordPressVIPMinimum
 *
 * @since 0.4.0
 */
class RestrictedHookSniff extends AbstractFunctionParameterSniff {

	/**
	 * The group name for this group of functions.
	 *
	 * @var string
	 */
	protected $group_name = 'restricted_hooks';

	/**
	 * Functions this sniff is looking for.
	 *
	 * @var array The only requirement for this array is that the top level
	 *            array keys are the names of the functions you're looking for.
	 *            Other than that, the array can have arbitrary content
	 *            depending on your needs.
	 */
	protected $target_functions = [
		'add_filter' => true,
		'add_action' => true,
	];

	/**
	 * List of restricted filter names.
	 *
	 * @var array
	 */
	private $restricted_hooks = [
		'upload_mimes' => [
			'error'     => 'Please ensure that the mimes being filtered do not include insecure types (e.g. SVG). Manual inspection required.',
			'error_code' => 'UploadMimes',
		],
	];

	/**
	 * Process the parameters of a matched function.
	 *
	 * @param int    $stackPtr        The position of the current token in the stack.
	 * @param array  $group_name      The name of the group which was matched.
	 * @param string $matched_content The token content (function name) which was matched.
	 * @param array  $parameters      Array with information about the parameters.
	 * @return int|void Integer stack pointer to skip forward or void to continue
	 *                  normal file processing.
	 */
	public function process_parameters( $stackPtr, $group_name, $matched_content, $parameters ) {
		foreach ( $this->restricted_hooks as $restricted_hook => $hook_args ) {
			if ( $this->normalize_hook_name_from_parameter( $parameters[1] ) === $restricted_hook ) {
				$this->phpcsFile->addWarning( $hook_args['error'], $stackPtr, $hook_args['error_code'] );
			}
		}
	}

	/**
	 * Normalize hook name parameter.
	 *
	 * @param array $parameter Array with information about a parameter.
	 * @return string Normalized hook name.
	 */
	private function normalize_hook_name_from_parameter( $parameter ) {
		// If concatenation is found, build hook name.
		$concat_ptr = $this->phpcsFile->findNext(
			T_STRING_CONCAT,
			$parameter['start'],
			$parameter['end'],
			false,
			null,
			true
		);

		if ( $concat_ptr ) {
			$hook_name = '';
			for ( $i = $parameter['start'] + 1; $i < $parameter['end']; $i++ ) {
				if ( T_CONSTANT_ENCAPSED_STRING === $this->tokens[ $i ]['code'] ) {
					$hook_name .= str_replace( [ "'", '"' ], '', $this->tokens[ $i ]['content'] );
				}
			}
		} else {
			$hook_name = $parameter['raw'];
		}

		// Remove quotes (double and single), and use lowercase.
		return strtolower( str_replace( [ "'", '"' ], '', $hook_name ) );
	}
}
