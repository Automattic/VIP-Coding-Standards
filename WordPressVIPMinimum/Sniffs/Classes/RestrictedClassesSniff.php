<?php
/**
 * WordPressVIPMinimum Coding Standard.
 *
 * @package VIPCS\WordPressVIPMinimum
 */

namespace WordPressVIPMinimum\Sniffs\Classes;

use WordPress\AbstractClassRestrictionsSniff;

use PHP_CodeSniffer\Util\Tokens;

/**
 * WordPressVIPMinimum_Sniffs_Classes_RestrictedClassesSniff.
 *
 * @package VIPCS\WordPressVIPMinimum
 *
 * @since 0.4.0
 */
class RestrictedClassesSniff extends AbstractClassRestrictionsSniff {

	/**
	 * Groups of classes to restrict.
	 *
	 * @return array
	 */
	public function getGroups() {
		return array(
			'wp_cli' => array(
				'classes' => array(
					'WP_CLI_Command',
				),
			),
		);
	}

	/**
	 * Process a matched token.
	 *
	 * @param int    $stackPtr        The position of the current token in the stack.
	 * @param array  $group_name      The name of the group which was matched.
	 * @param string $matched_content The token content (class name) which was matched.
	 *
	 * @return void
	 */
	public function process_matched_token( $stackPtr, $group_name, $matched_content ) {
		$tokens = $this->phpcsFile->getTokens();

		if ( T_EXTENDS === $tokens[ $stackPtr ]['code'] ) {
			$this->phpcsFile->addWarning( 'We recommend extending `WPCOM_VIP_CLI_Command` instead of `WP_CLI_Command` and using the helper functions available in it (such as `stop_the_insanity()`), see https://vip.wordpress.com/documentation/writing-bin-scripts/ for more information.', $stackPtr, 'Extend_WP_CLI_Command' );
		}
	}
}
