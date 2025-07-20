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
 * Restricts usage of file_get_contents().
 */
class FetchingRemoteDataSniff extends AbstractFunctionParameterSniff {

	/**
	 * The group name for this group of functions.
	 *
	 * @var string
	 */
	protected $group_name = 'file_get_contents';

	/**
	 * Functions this sniff is looking for.
	 *
	 * @var array<string, bool> Key is the function name, value irrelevant.
	 */
	protected $target_functions = [
		'file_get_contents' => true,
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
		$filename_param = PassedParameters::getParameterFromStack( $parameters, 1, 'filename' );
		if ( $filename_param === false ) {
			// Missing required parameter. Probably live coding, nothing to examine (yet). Bow out.
			return;
		}

		$data = [ $matched_content ];

		$has_text_string = $this->phpcsFile->findNext( Tokens::$stringTokens, $filename_param['start'], ( $filename_param['end'] + 1 ) );
		if ( $has_text_string === false ) {
			$message = '`%s()` is highly discouraged for remote requests, please use `wpcom_vip_file_get_contents()` or `vip_safe_wp_remote_get()` instead. If it\'s for a local file please use WP_Filesystem instead.';
			$this->phpcsFile->addWarning( $message, $stackPtr, 'FileGetContentsUnknown', $data );
			return;
		}

		$fileName = $this->tokens[ $has_text_string ]['content'];

		$isRemoteFile = ( strpos( $fileName, '://' ) !== false );
		if ( $isRemoteFile === true ) {
			$message = '`%s()` is highly discouraged for remote requests, please use `wpcom_vip_file_get_contents()` or `vip_safe_wp_remote_get()` instead.';
			$this->phpcsFile->addWarning( $message, $stackPtr, 'FileGetContentsRemoteFile', $data );
		}
	}
}
