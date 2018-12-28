<?php
/**
 * WordPressVIPMinimum Coding Standard.
 *
 * @package VIPCS\WordPressVIPMinimum
 * @link https://github.com/Automattic/VIP-Coding-Standards
 * @license https://opensource.org/licenses/MIT MIT
 */

namespace WordPressVIPMinimum\Sniffs\VIP;

use WordPress\Sniff;

/**
 * Discourages the use of the session variable.
 * Creating a session writes a file to the server and is unreliable in a multi-server environment.
 *
 * @link https://lobby.vip.wordpress.com/wordpress-com-documentation/code-review-what-we-look-for/#session-start-and-other-session-functions
 *
 * @package VIPCS\WordPressVIPMinimum
 *
 * @since   0.5.0
 */
class SessionVariableUsageSniff extends Sniff {

	/**
	 * Returns an array of tokens this test wants to listen for.
	 *
	 * @return array
	 */
	public function register() {
		return [
			\T_VARIABLE,
		];
	}

	/**
	 * Process the token and handle the deprecation notice.
	 *
	 * @param int $stackPtr The position of the current token in the stack.
	 *
	 * @return void
	 */
	public function process_token( $stackPtr ) {
		if ( '$_SESSION' === $this->tokens[ $stackPtr ]['content'] ) {
			$this->phpcsFile->addError(
				'Usage of $_SESSION variable is prohibited.',
				$stackPtr,
				'SessionVarsProhibited'
			);
		}
	}
}
