<?php
/**
 * WordPressVIPMinimum Coding Standard.
 *
 * @package VIPCS\WordPressVIPMinimum
 * @link https://github.com/Automattic/VIP-Coding-Standards
 * @license https://opensource.org/license/gpl-2-0 GPL-2.0
 */

namespace WordPressVIPMinimum\Sniffs\Hooks;

use PHP_CodeSniffer\Util\Tokens;
use PHPCSUtils\Utils\MessageHelper;
use PHPCSUtils\Utils\PassedParameters;
use PHPCSUtils\Utils\TextStrings;
use WordPressCS\WordPress\AbstractFunctionParameterSniff;

/**
 * This sniff restricts usage of some action and filter hooks.
 *
 * @since 0.4.0
 */
class RestrictedHooksSniff extends AbstractFunctionParameterSniff {

	/**
	 * The group name for this group of functions.
	 *
	 * @var string
	 */
	protected $group_name = 'restricted_hooks';

	/**
	 * Functions this sniff is looking for.
	 *
	 * @var array<string, bool> Key is the function name, value irrelevant.
	 */
	protected $target_functions = [
		'add_filter' => true,
		'add_action' => true,
	];

	/**
	 * List of restricted filters by groups.
	 *
	 * @var array<string, array<string, string|array<string>>>
	 */
	private $restricted_hook_groups = [
		'upload_mimes' => [
			// TODO: This error message needs a link to the VIP Documentation, see https://github.com/Automattic/VIP-Coding-Standards/issues/235.
			'type'  => 'warning',
			'msg'   => 'Please ensure that the mimes being filtered do not include insecure types (i.e. SVG, SWF, etc.). Manual inspection required.',
			'hooks' => [
				'upload_mimes',
			],
		],
		'http_request' => [
			// https://docs.wpvip.com/technical-references/code-quality-and-best-practices/retrieving-remote-data/.
			'type'  => 'warning',
			'msg'   => 'Please ensure that the timeout being filtered is not greater than 3s since remote requests require the user to wait for completion before the rest of the page will load. Manual inspection required.',
			'hooks' => [
				'http_request_timeout',
				'http_request_args',
			],
		],
		'robotstxt' => [
			// https://docs.wpvip.com/how-tos/modify-the-robots-txt-file/.
			'type'  => 'warning',
			'msg'   => 'Don\'t forget to flush the robots.txt cache by going to Settings > Reading and toggling the privacy settings.',
			'hooks' => [
				'do_robotstxt',
				'robots_txt',
			],
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
	 * @return int|void Integer stack pointer to skip forward or void to continue
	 *                  normal file processing.
	 */
	public function process_parameters( $stackPtr, $group_name, $matched_content, $parameters ) {
		$hook_name_param = PassedParameters::getParameterFromStack( $parameters, 1, 'hook_name' );
		if ( $hook_name_param === false ) {
			// Missing required parameter. Nothing to examine. Bow out.
			return;
		}

		$normalized_hook_name = $this->normalize_hook_name_from_parameter( $hook_name_param );
		if ( $normalized_hook_name === '' ) {
			// Dynamic hook name. Cannot reliably determine if it's one of the targets. Bow out.
			return;
		}

		foreach ( $this->restricted_hook_groups as $group => $group_args ) {
			foreach ( $group_args['hooks'] as $hook ) {
				if ( $normalized_hook_name === $hook ) {
					$isError = ( $group_args['type'] === 'error' );
					MessageHelper::addMessage( $this->phpcsFile, $group_args['msg'], $stackPtr, $isError, $hook );
				}
			}
		}
	}

	/**
	 * Normalize hook name parameter.
	 *
	 * @param array $parameter Array with information about a parameter.
	 *
	 * @return string Normalized hook name or an empty string if the hook name could not be determined.
	 */
	private function normalize_hook_name_from_parameter( $parameter ) {
		$allowed_tokens  = Tokens::$emptyTokens;
		$allowed_tokens += [
			T_STRING_CONCAT            => T_STRING_CONCAT,
			T_CONSTANT_ENCAPSED_STRING => T_CONSTANT_ENCAPSED_STRING,
		];

		$has_disallowed_token = $this->phpcsFile->findNext( $allowed_tokens, $parameter['start'], ( $parameter['end'] + 1 ), true );
		if ( $has_disallowed_token !== false ) {
			return '';
		}

		$hook_name = '';
		for ( $i = $parameter['start']; $i <= $parameter['end']; $i++ ) {
			if ( $this->tokens[ $i ]['code'] === T_CONSTANT_ENCAPSED_STRING ) {
				$hook_name .= TextStrings::stripQuotes( $this->tokens[ $i ]['content'] );
			}
		}

		return $hook_name;
	}
}
