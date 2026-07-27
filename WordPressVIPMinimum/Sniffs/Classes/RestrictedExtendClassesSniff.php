<?php
/**
 * WordPressVIPMinimum Coding Standard.
 *
 * @package VIPCS\WordPressVIPMinimum
 * @link https://github.com/Automattic/VIP-Coding-Standards
 * @license https://opensource.org/license/gpl-2-0 GPL-2.0
 */

namespace WordPressVIPMinimum\Sniffs\Classes;

use PHPCSUtils\Utils\MessageHelper;
use WordPressCS\WordPress\AbstractClassRestrictionsSniff;

/**
 * WordPressVIPMinimum_Sniffs_Classes_RestrictedExtendClassesSniff.
 *
 * @since 0.4.0
 */
class RestrictedExtendClassesSniff extends AbstractClassRestrictionsSniff {

	/**
	 * Groups of classes to restrict.
	 *
	 * @return array<string, array<string, string|array<string>>>
	 */
	public function getGroups() {
		return [
			'wp_cli' => [
				'type'    => 'warning',
				'message' => 'We recommend extending `WPCOM_VIP_CLI_Command` instead of `WP_CLI_Command` and using the helper functions available in it (such as `stop_the_insanity()`), see https://vip.wordpress.com/documentation/writing-bin-scripts/ for more information.',
				'classes' => [
					'WP_CLI_Command',
				],
			],
		];
	}

	/**
	 * Returns an array of tokens this test wants to listen for.
	 *
	 * @return array<int|string>
	 */
	public function register() {
		$targets = parent::register();
		if ( empty( $targets ) ) {
			return $targets;
		}

		return [ T_EXTENDS ];
	}

	/**
	 * Process a matched token.
	 *
	 * @param int    $stackPtr        The position of the current token in the stack.
	 * @param string $group_name      The name of the group which was matched.
	 * @param string $matched_content The token content (class name) which was matched
	 *                                in lowercase.
	 *
	 * @return void
	 */
	public function process_matched_token( $stackPtr, $group_name, $matched_content ) {
		foreach ( $this->getGroups() as $group => $group_args ) {
			$isError = ( $group_args['type'] === 'error' );
			MessageHelper::addMessage( $this->phpcsFile, $group_args['message'], $stackPtr, $isError, $group );
		}
	}
}
