<?php
/**
 * WordPress-VIP-Minimum Coding Standard.
 *
 * @package VIPCS\WordPressVIPMinimum
 * @link https://github.com/Automattic/VIP-Coding-Standards
 */

namespace WordPressVIPMinimum\Sniffs\Cache;

use PHP_CodeSniffer\Files\File;
use PHP_CodeSniffer\Sniffs\Sniff;
use PHP_CodeSniffer\Util\Tokens;

/**
 * Checks whether proper escaping function is used.
 *
 *  @package VIPCS\WordPressVIPMinimum
 */
class BatcacheWhitelistedParamsSniff implements Sniff {

	/**
	 * List of whitelisted batcache params.
	 *
	 * @var array
	 */
	public $whitelistes_batcache_params = array(
		'hpt',
		'eref',
		'iref',
		'fbid',
		'om_rid',
		'utm',
		'utm_source',
		'utm_content',
		'utm_medium',
		'utm_campaign',
		'utm_term',
		'fb_xd_bust',
		'fb_xd_fragment',
		'npt',
		'module',
		'iid',
		'cid',
		'icid',
		'ncid',
		'snapid',
		'_',
		'fb_ref',
		'fb_source',
		'omcamp',
		'affiliate',
		'utm_affiliate',
		'utm_subid',
		'utm_keyword',
		'migAgencyId',
		'migSource',
		'migTrackDataExt',
		'migRandom',
		'migTrackFmtExt',
		'bust',
		'linkId',
		'_ga',
		'xid',
		'hootPostID',
		'pretty',
		'__lsa',
		'rpx_response',
		'__rmid',
		'sr_share',
		'ia_share_url',
	);

	/**
	 * Returns an array of tokens this test wants to listen for.
	 *
	 * @return array
	 */
	public function register() {
		return array( T_VARIABLE );
	}

	/**
	 * Process this test when one of its tokens is encountered
	 *
	 * @param \PHP_CodeSniffer\Files\File $phpcsFile The file being scanned.
	 * @param int                         $stackPtr  The position of the current token in the stack passed in $tokens.
	 *
	 * @return void
	 */
	public function process( File $phpcsFile, $stackPtr ) {

		$tokens = $phpcsFile->getTokens();

		if ( '$_GET' !== $tokens[ $stackPtr ]['content'] ) {
			return;
		}

		$key = $phpcsFile->findNext( array_merge( Tokens::$emptyTokens, array( T_OPEN_SQUARE_BRACKET ) ), ( $stackPtr + 1 ), null, true );

		if ( T_CONSTANT_ENCAPSED_STRING !== $tokens[ $key ]['code'] ) {
			return;
		}

		$variable_name = $tokens[ $key ]['content'];

		$variable_name = substr( $variable_name, 1, -1 );

		if ( true === in_array( $variable_name, $this->whitelistes_batcache_params, true ) ) {
			$phpcsFile->addWarning( sprintf( 'Batcache whitelisted GET param, `%s`, found. Batcache whitelisted parameters get stripped and are not available in PHP.', $variable_name ), $stackPtr, 'strippedGetParam' );
			return;
		}
	}
}
