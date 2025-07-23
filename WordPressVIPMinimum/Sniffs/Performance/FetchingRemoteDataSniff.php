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

		$data        = [ $matched_content ];
		$param_start = $filename_param['start'];
		$search_end  = ( $filename_param['end'] + 1 );

		$has_magic_dir = $this->phpcsFile->findNext( T_DIR, $param_start, $search_end );
		if ( $has_magic_dir !== false ) {
			// In all likelyhood a local file (disregarding creative code).
			return;
		}

		$isRemoteFile = false;
		$search_start = $param_start;
		// phpcs:ignore Generic.CodeAnalysis.AssignmentInCondition.FoundInWhileCondition -- Valid usage.
		while ( ( $has_text_string = $this->phpcsFile->findNext( Tokens::$stringTokens, $search_start, $search_end ) ) !== false ) {
			if ( strpos( $this->tokens[ $has_text_string ]['content'], '://' ) !== false ) {
				$isRemoteFile = true;
				break;
			}

			$search_start = ( $has_text_string + 1 );
		}

		if ( $isRemoteFile === true ) {
			$message = '`%s()` is highly discouraged for remote requests, please use `wpcom_vip_file_get_contents()` or `vip_safe_wp_remote_get()` instead.';
			$this->phpcsFile->addWarning( $message, $stackPtr, 'FileGetContentsRemoteFile', $data );
			return;
		}

		/*
		 * Okay, so we haven't been able to determine for certain this is a remote file.
		 * Check for tokens which would make the parameter contents dynamic.
		 */
		$ignore  = Tokens::$emptyTokens;
		$ignore += Tokens::$stringTokens;
		$ignore += [ T_STRING_CONCAT => T_STRING_CONCAT ];

		$has_non_text_string = $this->phpcsFile->findNext( $ignore, $param_start, $search_end, true );
		if ( $has_non_text_string !== false ) {
			$this->add_contents_unknown_warning( $stackPtr, $data );
		}
	}

	/**
	 * Process the function if no parameters were found.
	 *
	 * {@internal This method is only needed for handling PHP 8.1+ first class callables on WPCS < 3.2.0.
	 * Once the minimum supported WPCS is 3.2.0 or higher, this method should be removed.}
	 *
	 * @param int    $stackPtr        The position of the current token in the stack.
	 * @param string $group_name      The name of the group which was matched.
	 * @param string $matched_content The token content (function name) which was matched
	 *                                in lowercase.
	 *
	 * @return void
	 */
	public function process_no_parameters( $stackPtr, $group_name, $matched_content ) {
		// Check if this is a first class callable.
		$next = $this->phpcsFile->findNext( Tokens::$emptyTokens, ( $stackPtr + 1 ), null, true );
		if ( $next === false
			|| $this->tokens[ $next ]['code'] !== T_OPEN_PARENTHESIS
			|| isset( $this->tokens[ $next ]['parenthesis_closer'] ) === false
		) {
			// Live coding/parse error. Ignore.
			return;
		}

		// First class callable.
		$firstNonEmpty = $this->phpcsFile->findNext( Tokens::$emptyTokens, ( $next + 1 ), null, true );
		if ( $this->tokens[ $firstNonEmpty ]['code'] === T_ELLIPSIS ) {
			$secondNonEmpty = $this->phpcsFile->findNext( Tokens::$emptyTokens, ( $firstNonEmpty + 1 ), null, true );
			if ( $this->tokens[ $secondNonEmpty ]['code'] === T_CLOSE_PARENTHESIS ) {
				$this->add_contents_unknown_warning( $stackPtr, [ $matched_content ] );
			}
		}
	}

	/**
	 * Process the function if it is used as a first class callable.
	 *
	 * @param int    $stackPtr        The position of the current token in the stack.
	 * @param string $group_name      The name of the group which was matched.
	 * @param string $matched_content The token content (function name) which was matched
	 *                                in lowercase.
	 *
	 * @return void
	 */
	public function process_first_class_callable( $stackPtr, $group_name, $matched_content ) {
		$this->add_contents_unknown_warning( $stackPtr, [ $matched_content ] );
	}

	/**
	 * Add a warning if the function is used with unknown parameter(s) or with a $filename parameter for which
	 * it could not be determined if it references a local file or a remote file.
	 *
	 * @param int           $stackPtr The position of the current token in the stack.
	 * @param array<string> $data     Data to use for string replacement in the error message.
	 *
	 * @return void
	 */
	private function add_contents_unknown_warning( $stackPtr, $data ) {
		$message = '`%s()` is highly discouraged for remote requests, please use `wpcom_vip_file_get_contents()` or `vip_safe_wp_remote_get()` instead. If it\'s for a local file please use WP_Filesystem instead.';
		$this->phpcsFile->addWarning( $message, $stackPtr, 'FileGetContentsUnknown', $data );
	}
}
