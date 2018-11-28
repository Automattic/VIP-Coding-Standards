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
			// TODO: This error message needs a link to the VIP Documentation, see https://github.com/Automattic/VIP-Coding-Standards/issues/235.
			'type' => 'Warning',
			'msg'  => 'Please ensure that the mimes being filtered do not include insecure types (i.e. SVG, SWF, etc.). Manual inspection required.',
			'code' => 'UploadMimes',
		],
		'http_request_timeout' => [
			// WordPress.com: https://lobby.vip.wordpress.com/wordpress-com-documentation/fetching-remote-data/.
			// VIP Go: https://vip.wordpress.com/documentation/vip-go/fetching-remote-data/.
			'type' => 'Warning',
			'msg'     => 'Please ensure that the timeout being filtered is not greater than 3s since remote requests require the user to wait for completion before the rest of the page will load. Manual inspection required.',
			'code'    => 'HighTimeout',
		],
		'http_request_args' => [
			// WordPress.com: https://lobby.vip.wordpress.com/wordpress-com-documentation/fetching-remote-data/.
			// VIP Go: https://vip.wordpress.com/documentation/vip-go/fetching-remote-data/.
			'type' => 'Warning',
			'msg'     => 'Please ensure that the timeout being filtered is not greater than 3s since remote requests require the user to wait for completion before the rest of the page will load. Manual inspection required.',
			'code'    => 'HighTimeout',
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
				$addMethod = 'add' . $hook_args['type'];
				$this->phpcsFile->{$addMethod}( $hook_args['msg'], $stackPtr, $hook_args['code'] );
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
