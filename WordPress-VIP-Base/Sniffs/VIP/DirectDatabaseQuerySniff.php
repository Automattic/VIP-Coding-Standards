<?php
/**
 * WordPress Coding Standard.
 *
 * @package WPCS\WordPressCodingStandards
 * @link    https://github.com/WordPress-Coding-Standards/WordPress-Coding-Standards
 * @license https://opensource.org/licenses/MIT MIT
 */

namespace Automattic\phpcs\WordPressVIP\Sniffs\VIP;

use WordPress\Sniff;
use PHP_CodeSniffer_Tokens as Tokens;

/**
 * Flag Database direct queries.
 *
 * @link    https://vip.wordpress.com/documentation/vip/code-review-what-we-look-for/#direct-database-queries
 * @link    https://vip.wordpress.com/documentation/vip/code-review-what-we-look-for/#database-alteration
 *
 * @package WPCS\WordPressCodingStandards
 *
 * @since   0.3.0
 * @since   0.6.0  Removed the add_unique_message() function as it is no longer needed.
 * @since   0.11.0 This class now extends WordPress_Sniff.
 * @since   0.13.0 Class name changed: this class is now namespaced.
 *
 * @deprecated 1.0.0  This sniff has been moved to the `DB` category.
 *                    This file remains for now to prevent BC breaks.
 */
class DirectDatabaseQuerySniff extends \WordPress\Sniffs\DB\DirectDatabaseQuerySniff {

	/**
	 * Keep track of whether the warnings have been thrown to prevent
	 * the messages being thrown for every token triggering the sniff.
	 *
	 * @since 1.0.0
	 *
	 * @var array
	 */
	private $thrown = array(
		'DeprecatedSniff'                 => false,
		'FoundPropertyForDeprecatedSniff' => false,
	);

	/**
	 * Don't use.
	 *
	 * @deprecated 1.0.0
	 *
	 * @param int $stackPtr The position of the current token in the stack.
	 *
	 * @return void|int
	 */
	public function process_token( $stackPtr ) {
		if ( false === $this->thrown['DeprecatedSniff'] ) {
			$this->phpcsFile->addWarning(
				'The "WordPress.VIP.DirectDatabaseQuery" sniff has been renamed to "WordPress.DB.DirectDatabaseQuery". Please update your custom ruleset.',
				0,
				'DeprecatedSniff'
			);

			$this->thrown['DeprecatedSniff'] = true;
		}

		if ( ( $this->customCacheGetFunctions !== $this->addedCustomFunctions['cacheget']
			|| $this->customCacheSetFunctions !== $this->addedCustomFunctions['cacheset']
			|| $this->customCacheDeleteFunctions !== $this->addedCustomFunctions['cachedelete'] )
			&& false === $this->thrown['FoundPropertyForDeprecatedSniff']
		) {
			$this->phpcsFile->addWarning(
				'The "WordPress.VIP.DirectDatabaseQuery" sniff has been renamed to "WordPress.DB.DirectDatabaseQuery". Please update your custom ruleset.',
				0,
				'FoundPropertyForDeprecatedSniff'
			);

			$this->thrown['FoundPropertyForDeprecatedSniff'] = true;
		}

		return parent::process_token( $stackPtr );
	}

} // End class.
