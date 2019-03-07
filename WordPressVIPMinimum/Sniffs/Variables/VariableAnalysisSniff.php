<?php
// @codingStandardsIgnoreStart
/**
 * This file is part of the VariableAnalysis add-on for PHP_CodeSniffer.
 *
 * PHP version 5
 *
 * @category  PHP
 * @package   PHP_CodeSniffer
 * @author    Sam Graham <php-codesniffer-variableanalysis BLAHBLAH illusori.co.uk>
 * @copyright 2011-2012 Sam Graham <php-codesniffer-variableanalysis BLAHBLAH illusori.co.uk>
 * @license   http://www.opensource.org/licenses/bsd-license.php BSD License
 * @link      http://pear.php.net/package/PHP_CodeSniffer
 */

namespace WordPressVIPMinimum\Sniffs\Variables;

use WordPressVIPMinimum\Sniffs\Sniff;

/**
 * Checks the for undefined function variables.
 *
 * This sniff checks that all function variables
 * are defined in the function body.
 *
 * @category  PHP
 * @package   PHP_CodeSniffer
 * @author    Sam Graham <php-codesniffer-variableanalysis BLAHBLAH illusori.co.uk>
 * @copyright 2011 Sam Graham <php-codesniffer-variableanalysis BLAHBLAH illusori.co.uk>
 * @link      http://pear.php.net/package/PHP_CodeSniffer
 */
class VariableAnalysisSniff extends Sniff {

	/**
	 * The PHP_CodeSniffer file where the token was found.
	 *
	 * Needs consolidating with the previous property.
	 *
	 * @var File
	 */
	protected $currentFile;

	/**
	 * A list of scopes encountered so far and the variables within them.
	 */
	private $_scopes = array();

	/**
	 * A regexp for matching variable names in double-quoted strings.
	 */
	private $_double_quoted_variable_regexp = '|(?<!\\\\)(?:\\\\{2})*\${?([a-zA-Z0-9_]+)}?|';

	/**
	 *  Array of known pass-by-reference functions and the argument(s) which are passed
	 *  by reference, the arguments are numbered starting from 1 and an ellipsis '...'
	 *  means all argument numbers after the previous should be considered pass-by-reference.
	 */
	private $_passByRefFunctions = array(
		'__soapCall' => array(5),
		'addFunction' => array(3),
		'addTask' => array(3),
		'addTaskBackground' => array(3),
		'addTaskHigh' => array(3),
		'addTaskHighBackground' => array(3),
		'addTaskLow' => array(3),
		'addTaskLowBackground' => array(3),
		'addTaskStatus' => array(2),
		'apc_dec' => array(3),
		'apc_fetch' => array(2),
		'apc_inc' => array(3),
		'areConfusable' => array(3),
		'array_multisort' => array(1),
		'array_pop' => array(1),
		'array_push' => array(1),
		'array_replace' => array(1),
		'array_replace_recursive' => array(1, 2, 3, '...' ),
		'array_shift' => array(1),
		'array_splice' => array(1),
		'array_unshift' => array(1),
		'array_walk' => array(1),
		'array_walk_recursive' => array(1),
		'arsort' => array(1),
		'asort' => array(1),
		'bindColumn' => array(2),
		'bindParam' => array(2),
		'bind_param' => array(2, 3, '...' ),
		'bind_result' => array(1, 2, '...' ),
		'call_user_method' => array(2),
		'call_user_method_array' => array(2),
		'curl_multi_exec' => array(2),
		'curl_multi_info_read' => array(2),
		'current' => array(1),
		'dbplus_curr' => array(2),
		'dbplus_first' => array(2),
		'dbplus_info' => array(3),
		'dbplus_last' => array(2),
		'dbplus_next' => array(2),
		'dbplus_prev' => array(2),
		'dbplus_tremove' => array(3),
		'dns_get_record' => array(3, 4),
		'domxml_open_file' => array(3),
		'domxml_open_mem' => array(3),
		'each' => array(1),
		'enchant_dict_quick_check' => array(3),
		'end' => array(1),
		'ereg' => array(3),
		'eregi' => array(3),
		'exec' => array(2, 3),
		'exif_thumbnail' => array(1, 2, 3),
		'expect_expectl' => array(3),
		'extract' => array(1),
		'filter' => array(3),
		'flock' => array(2,3),
		'fscanf' => array(2, 3, '...' ),
		'fsockopen' => array(3, 4),
		'ftp_alloc' => array(3),
		'get' => array(2, 3),
		'getByKey' => array(4),
		'getMulti' => array(2),
		'getMultiByKey' => array(3),
		'getimagesize' => array(2),
		'getmxrr' => array(2, 3),
		'gnupg_decryptverify' => array(3),
		'gnupg_verify' => array(4),
		'grapheme_extract' => array(5),
		'headers_sent' => array(1, 2),
		'http_build_url' => array(4),
		'http_get' => array(3),
		'http_head' => array(3),
		'http_negotiate_charset' => array(2),
		'http_negotiate_content_type' => array(2),
		'http_negotiate_language' => array(2),
		'http_post_data' => array(4),
		'http_post_fields' => array(5),
		'http_put_data' => array(4),
		'http_put_file' => array(4),
		'http_put_stream' => array(4),
		'http_request' => array(5),
		'isSuspicious' => array(2),
		'is_callable' => array(3),
		'key' => array(1),
		'krsort' => array(1),
		'ksort' => array(1),
		'ldap_get_option' => array(3),
		'ldap_parse_reference' => array(3),
		'ldap_parse_result' => array(3, 4, 5, 6),
		'localtime' => array(2),
		'm_completeauthorizations' => array(2),
		'maxdb_stmt_bind_param' => array(3, 4, '...' ),
		'maxdb_stmt_bind_result' => array(2, 3, '...' ),
		'mb_convert_variables' => array(3, 4, '...' ),
		'mb_parse_str' => array(2),
		'mqseries_back' => array(2, 3),
		'mqseries_begin' => array(3, 4),
		'mqseries_close' => array(4, 5),
		'mqseries_cmit' => array(2, 3),
		'mqseries_conn' => array(2, 3, 4),
		'mqseries_connx' => array(2, 3, 4, 5),
		'mqseries_disc' => array(2, 3),
		'mqseries_get' => array(3, 4, 5, 6, 7, 8, 9),
		'mqseries_inq' => array(6, 8, 9, 10),
		'mqseries_open' => array(2, 4, 5, 6),
		'mqseries_put' => array(3, 4, 6, 7),
		'mqseries_put1' => array(2, 3, 4, 6, 7),
		'mqseries_set' => array(9, 10),
		'msg_receive' => array(3, 5, 8),
		'msg_send' => array(6),
		'mssql_bind' => array(3),
		'natcasesort' => array(1),
		'natsort' => array(1),
		'ncurses_color_content' => array(2, 3, 4),
		'ncurses_getmaxyx' => array(2, 3),
		'ncurses_getmouse' => array(1),
		'ncurses_getyx' => array(2, 3),
		'ncurses_instr' => array(1),
		'ncurses_mouse_trafo' => array(1, 2),
		'ncurses_mousemask' => array(2),
		'ncurses_pair_content' => array(2, 3),
		'ncurses_wmouse_trafo' => array(2, 3),
		'newt_button_bar' => array(1),
		'newt_form_run' => array(2),
		'newt_get_screen_size' => array(1, 2),
		'newt_grid_get_size' => array(2, 3),
		'newt_reflow_text' => array(5, 6),
		'newt_win_entries' => array(7),
		'newt_win_menu' => array(8),
		'next' => array(1),
		'oci_bind_array_by_name' => array(3),
		'oci_bind_by_name' => array(3),
		'oci_define_by_name' => array(3),
		'oci_fetch_all' => array(2),
		'ocifetchinto' => array(2),
		'odbc_fetch_into' => array(2),
		'openssl_csr_export' => array(2),
		'openssl_csr_new' => array(2),
		'openssl_open' => array(2),
		'openssl_pkcs12_export' => array(2),
		'openssl_pkcs12_read' => array(2),
		'openssl_pkey_export' => array(2),
		'openssl_private_decrypt' => array(2),
		'openssl_private_encrypt' => array(2),
		'openssl_public_decrypt' => array(2),
		'openssl_public_encrypt' => array(2),
		'openssl_random_pseudo_bytes' => array(2),
		'openssl_seal' => array(2, 3),
		'openssl_sign' => array(2),
		'openssl_x509_export' => array(2),
		'ovrimos_fetch_into' => array(2),
		'parse' => array(2,3),
		'parseCurrency' => array(2, 3),
		'parse_str' => array(2),
		'parsekit_compile_file' => array(2),
		'parsekit_compile_string' => array(2),
		'passthru' => array(2),
		'pcntl_sigprocmask' => array(3),
		'pcntl_sigtimedwait' => array(2),
		'pcntl_sigwaitinfo' => array(2),
		'pcntl_wait' => array(1),
		'pcntl_waitpid' => array(2),
		'pfsockopen' => array(3, 4),
		'php_check_syntax' => array(2),
		'poll' => array(1, 2, 3),
		'preg_filter' => array(5),
		'preg_match' => array(3),
		'preg_match_all' => array(3),
		'preg_replace' => array(5),
		'preg_replace_callback' => array(5),
		'prev' => array(1),
		'proc_open' => array(3),
		'query' => array(3),
		'queryExec' => array(2),
		'reset' => array(1),
		'rsort' => array(1),
		'settype' => array(1),
		'shuffle' => array(1),
		'similar_text' => array(3),
		'socket_create_pair' => array(4),
		'socket_getpeername' => array(2, 3),
		'socket_getsockname' => array(2, 3),
		'socket_recv' => array(2),
		'socket_recvfrom' => array(2, 5, 6),
		'socket_select' => array(1, 2, 3),
		'sort' => array(1),
		'sortWithSortKeys' => array(1),
		'sqlite_exec' => array(3),
		'sqlite_factory' => array(3),
		'sqlite_open' => array(3),
		'sqlite_popen' => array(3),
		'sqlite_query' => array(4),
		'sqlite_unbuffered_query' => array(4),
		'sscanf' => array(3, '...' ),
		'str_ireplace' => array(4),
		'str_replace' => array(4),
		'stream_open' => array(4),
		'stream_select' => array(1, 2, 3),
		'stream_socket_accept' => array(3),
		'stream_socket_client' => array(2, 3),
		'stream_socket_recvfrom' => array(4),
		'stream_socket_server' => array(2, 3),
		'system' => array(2),
		'uasort' => array(1),
		'uksort' => array(1),
		'unbufferedQuery' => array(3),
		'usort' => array(1),
		'wincache_ucache_dec' => array(3),
		'wincache_ucache_get' => array(2),
		'wincache_ucache_inc' => array(3),
		'xdiff_string_merge3' => array(4),
		'xdiff_string_patch' => array(4),
		'xml_parse_into_struct' => array(3, 4),
		'xml_set_object' => array(2),
		'xmlrpc_decode_request' => array(2),
		'xmlrpc_set_type' => array(1),
		'xslt_set_object' => array(2),
		'yaml_parse' => array(3),
		'yaml_parse_file' => array(3),
		'yaml_parse_url' => array(3),
		'yaz_ccl_parse' => array(3),
		'yaz_hits' => array(2),
		'yaz_scan_result' => array(2),
		'yaz_wait' => array(1),
		);

	/**
	 *  Allows an install to extend the list of known pass-by-reference functions
	 *  by defining generic.codeanalysis.variableanalysis.sitePassByRefFunctions.
	 */
	public $sitePassByRefFunctions;

	/**
	 *  Allows exceptions in a catch block to be unused without provoking unused-var warning.
	 *  Set generic.codeanalysis.variableanalysis.allowUnusedCaughtExceptions to a true value.
	 */
	public $allowUnusedCaughtExceptions = false;

	/**
	 *  Allow function parameters to be unused without provoking unused-var warning.
	 *  Set generic.codeanalysis.variableanalysis.allowUnusedFunctionParameters to a true value.
	 */
	public $allowUnusedFunctionParameters = true;

	/**
	 *  A list of names of placeholder variables that you want to ignore from
	 *  unused variable warnings, ie things like $junk.
	 */
	public $validUnusedVariableNames;

	/**
	 * Returns an array of tokens this test wants to listen for.
	 *
	 * @return array
	 */
	public function register() {
		//  Magic to modify $_passByRefFunctions with any site-specific settings.
		if ( !empty( $this->sitePassByRefFunctions) ) {
			foreach (preg_split( '/\s+/', trim( $this->sitePassByRefFunctions) ) as $line ) {
				list ( $function, $args) = explode( ':', $line );
				$this->_passByRefFunctions[$function] = explode( ',', $args);
			}
		}
		if ( !empty( $this->validUnusedVariableNames) ) {
			$this->validUnusedVariableNames =
				preg_split( '/\s+/', trim( $this->validUnusedVariableNames) );
		}
		return array(
			T_VARIABLE,
			T_DOUBLE_QUOTED_STRING,
			T_HEREDOC,
			T_CLOSE_CURLY_BRACKET,
			T_STRING,
		);
	}

	/**
	 * Processes this test, when one of its tokens is encountered.
	 *
	 * @param int  $stackPtr  The position of the current token in the stack passed in $tokens.
	 *
	 * @return void
	 */
	public function process_token( $stackPtr ) {
		$token  = $this->tokens[$stackPtr];

		//if ( $token['content'] == '$param' ) {
		//echo "Found token on line {$token['line']}.\n" . print_r( $token, true );
		//}

		if ( $this->currentFile !== $this->phpcsFile ) {
			$this->currentFile = $this->phpcsFile;
		}

		if ( $token['code'] === T_VARIABLE ) {
			$this->processVariable( $stackPtr );
			return;
		}
		if ( ( $token['code'] === T_DOUBLE_QUOTED_STRING) ||
			( $token['code'] === T_HEREDOC) ) {
			$this->processVariableInString( $stackPtr );
			return;
		}
		if ( ( $token['code'] === T_STRING) && ( $token['content'] === 'compact' ) ) {
			$this->processCompact( $stackPtr );
			return;
		}
		if ( ( $token['code'] === T_CLOSE_CURLY_BRACKET) &&
			isset( $token['scope_condition'] ) ) {
			$this->processScopeClose( $token['scope_condition'] );
			return;
		}
	}

	public function normalizeVarName( $varName ) {
		$varName = preg_replace( '/[{}$]/', '', $varName );
		return $varName;
	}

	public function scopeKey( $currScope ) {
		if ( $currScope === false ) {
			$currScope = 'file';
		}
		return ( $this->currentFile ? $this->currentFile->getFilename() : 'unknown file' ) .
			':' . $currScope;
	}

	//  Warning: this is an autovivifying get
	public function getScopeInfo( $currScope, $autoCreate = true ) {
		require_once __DIR__ . '/VariableAnalysisHelper.php';
		$scopeKey = $this->scopeKey( $currScope );
		if ( !isset( $this->_scopes[$scopeKey] ) ) {
			if ( !$autoCreate ) {
				return null;
			}
			$this->_scopes[$scopeKey] = new ScopeInfo( $currScope );
		}
		return $this->_scopes[$scopeKey];
	}

	public function getVariableInfo( $varName, $currScope, $autoCreate = true ) {
		require_once __DIR__ . '/VariableAnalysisHelper.php';
		$scopeInfo = $this->getScopeInfo( $currScope, $autoCreate );
		if ( !isset( $scopeInfo->variables[$varName] ) ) {
			if ( !$autoCreate ) {
				return null;
			}
			$scopeInfo->variables[$varName] = new VariableInfo( $varName );
			if ( $this->validUnusedVariableNames &&
			     in_array( $varName, $this->validUnusedVariableNames, true ) ) {
				$scopeInfo->variables[$varName]->ignoreUnused = true;
			}
		}
		return $scopeInfo->variables[$varName];
	}

	public function markVariableAssignment( $varName, $stackPtr, $currScope ) {
		$varInfo = $this->getVariableInfo( $varName, $currScope );
		if ( !isset( $varInfo->scopeType ) ) {
			$varInfo->scopeType = 'local';
		}
		if ( isset( $varInfo->firstInitialized) && ( $varInfo->firstInitialized <= $stackPtr ) ) {
			return;
		}
		$varInfo->firstInitialized = $stackPtr;
	}

	public function markVariableDeclaration( $varName, $scopeType, $typeHint, $stackPtr, $currScope, $permitMatchingRedeclaration = false ) {
		$varInfo = $this->getVariableInfo( $varName, $currScope );
		if ( isset( $varInfo->scopeType ) ) {
			if ( ( $permitMatchingRedeclaration === false ) ||
				( $varInfo->scopeType !== $scopeType ) ) {
				//  Issue redeclaration/reuse warning
				//  Note: we check off scopeType not firstDeclared, this is so that
				//    we catch declarations that come after implicit declarations like
				//    use of a variable as a local.
				$message = 'Redeclaration of %s %s as %s.';
				$data    = [
					VariableInfo::$scopeTypeDescriptions[$varInfo->scopeType],
					"\${$varName}",
					VariableInfo::$scopeTypeDescriptions[$scopeType],
				];
				$this->currentFile->addWarning( $message, $stackPtr, 'VariableRedeclaration', $data );
			}
		}
		$varInfo->scopeType = $scopeType;
		if ( isset( $typeHint) ) {
			$varInfo->typeHint = $typeHint;
		}
		if ( isset( $varInfo->firstDeclared) && ( $varInfo->firstDeclared <= $stackPtr ) ) {
			return;
		}
		$varInfo->firstDeclared = $stackPtr;
	}

	public function markVariableRead( $varName, $stackPtr, $currScope ) {
		$varInfo = $this->getVariableInfo( $varName, $currScope );
		if ( isset( $varInfo->firstRead) && ( $varInfo->firstRead <= $stackPtr ) ) {
			return;
		}
		$varInfo->firstRead = $stackPtr;
	}

	public function isVariableInitialized( $varName, $stackPtr, $currScope ) {
		$varInfo = $this->getVariableInfo( $varName, $currScope );

		return isset( $varInfo->firstInitialized ) && $varInfo->firstInitialized <= $stackPtr;
	}

	public function isVariableUndefined( $varName, $stackPtr, $currScope ) {
		$varInfo = $this->getVariableInfo( $varName, $currScope, false );
		if ( isset( $varInfo->firstDeclared) && $varInfo->firstDeclared <= $stackPtr ) {
			// TODO: do we want to check scopeType here?
			return false;
		}
		if ( isset( $varInfo->firstInitialized) && $varInfo->firstInitialized <= $stackPtr ) {
			return false;
		}
		//VIP: do not report on undefined variables on file scope - it produces a ton of warnings for custom template files
		if ( 0 === $currScope ) {
			return false;
		}
		return true;
	}

	public function markVariableReadAndWarnIfUndefined( $varName, $stackPtr, $currScope ) {

		$this->markVariableRead( $varName, $stackPtr, $currScope );

		if ( $this->isVariableUndefined( $varName, $stackPtr, $currScope ) === true ) {
			// We haven't been defined by this point.
			$message = 'Variable %s is undefined.';
			$data    = [ "\${$varName}" ];
			$this->phpcsFile->addWarning( $message, $stackPtr, 'UndefinedVariable', $data );
		}
		return true;
	}

	public function findFunctionPrototype( $stackPtr ) {
		if ( ( $openPtr = $this->findContainingBrackets( $stackPtr ) ) === false ) {
			return false;
		}
		// Function names are T_STRING, and return-by-reference is T_BITWISE_AND,
		// so we look backwards from the opening bracket for the first thing that
		// isn't a function name, reference sigil or whitespace and check if
		// it's a function keyword.
		$functionPtr = $this->phpcsFile->findPrevious(array( T_STRING, T_WHITESPACE, T_BITWISE_AND ),
			$openPtr - 1, null, true, null, true );
		if ( ( $functionPtr !== false ) &&
			( $this->tokens[$functionPtr]['code'] === T_FUNCTION ) ) {
			return $functionPtr;
		}
		return false;
	}

	public function findVariableScope( $stackPtr ) {
		$token  = $this->tokens[$stackPtr];

		$in_class = false;
		if ( !empty( $token['conditions'] ) ) {
			foreach (array_reverse( $token['conditions'], true ) as $scopePtr => $scopeCode ) {
				if ( ( $scopeCode === T_FUNCTION ) || ( $scopeCode === T_CLOSURE ) ) {
					return $scopePtr;
				}
				if ( ( $scopeCode === T_CLASS) || ( $scopeCode === T_INTERFACE ) ) {
					$in_class = true;
				}
			}
		}

		if ( ( $scopePtr = $this->findFunctionPrototype( $stackPtr ) ) !== false ) {
			return $scopePtr;
		}

		if ( $in_class) {
			// Member var of a class, we don't care.
			return false;
		}

		// File scope, hmm, lets use first token of file?
		return 0;
	}

	public function isNextThingAnAssign( $stackPtr ) {

		// Is the next non-whitespace an assignment?
		$nextPtr = $this->phpcsFile->findNext( T_WHITESPACE, $stackPtr + 1, null, true, null, true );
		if ( ( $nextPtr !== false ) &&  $this->tokens[ $nextPtr ]['code'] === T_EQUAL ) {
			return $nextPtr;
		}
		return false;
	}

	public function findWhereAssignExecuted( $stackPtr ) {

		//  Write should be recorded at the next statement to ensure we treat
		//  the assign as happening after the RHS execution.
		//  eg: $var = $var + 1; -> RHS could still be undef.
		//  However, if we're within a bracketed expression, we take place at
		//  the closing bracket, if that's first.
		//  eg: echo ( ( $var = 12) && ( $var == 12) );
		$semicolonPtr = $this->phpcsFile->findNext( T_SEMICOLON, $stackPtr + 1, null, false, null, true );
		$closePtr = false;
		if ( ( ( $openPtr = $this->findContainingBrackets( $stackPtr ) ) !== false ) && isset( $this->tokens[ $openPtr ]['parenthesis_closer'] ) ) {
			$closePtr =  $this->tokens[$openPtr]['parenthesis_closer'];
		}

		if ( $semicolonPtr === false ) {
			if ( $closePtr === false ) {
				// TODO: panic
				return $stackPtr;
			}
			return $closePtr;
		}

		if ( $closePtr < $semicolonPtr ) {
			return $closePtr;
		}

		return $semicolonPtr;
	}

	public function findContainingBrackets( $stackPtr ) {

		if ( isset( $this->tokens[$stackPtr]['nested_parenthesis'] ) ) {
			$openPtrs = array_keys( $this->tokens[$stackPtr]['nested_parenthesis'] );
			return end( $openPtrs);
		}
		return false;
	}


	public function findFunctionCall( $stackPtr ) {

		$openPtr = $this->findContainingBrackets( $stackPtr );
		if ( $openPtr ) {
			// First non-whitespace thing and see if it's a T_STRING function name
			$functionPtr = $this->phpcsFile->findPrevious( T_WHITESPACE,
				$openPtr - 1, null, true, null, true );
			if (  $this->tokens[$functionPtr]['code'] === T_STRING) {
				return $functionPtr;
			}
		}
		return false;
	}

	public function findFunctionCallArguments( $stackPtr ) {

		// Slight hack: also allow this to find args for array constructor.
		// TODO: probably should refactor into three functions: arg-finding and bracket-finding
		if (  $this->tokens[ $stackPtr ]['code'] !== T_STRING &&  $this->tokens[ $stackPtr ]['code'] !== T_ARRAY && ( $stackPtr = $this->findFunctionCall( $stackPtr ) ) === false ) {
			return false;
		}

		// $stackPtr is the function name, find our brackets after it
		$openPtr = $this->phpcsFile->findNext( T_WHITESPACE,
			$stackPtr + 1, null, true, null, true );
		if ( ( $openPtr === false ) || ( $this->tokens[$openPtr]['code'] !== T_OPEN_PARENTHESIS ) ) {
			return false;
		}

		if ( !isset( $this->tokens[$openPtr]['parenthesis_closer'] ) ) {
			return false;
		}
		$closePtr =  $this->tokens[$openPtr]['parenthesis_closer'];

		$argPtrs = array();
		$lastPtr = $openPtr;
		$lastArgComma = $openPtr;
		while ( ( $nextPtr = $this->phpcsFile->findNext( T_COMMA, $lastPtr + 1, $closePtr ) ) !== false ) {
			if ( $this->findContainingBrackets( $nextPtr ) === $openPtr ) {
				// Comma is at our level of brackets, it's an argument delimiter.
				$argPtrs[]    = range( $lastArgComma + 1, $nextPtr - 1 );
				$lastArgComma = $nextPtr;
			}
			$lastPtr = $nextPtr;
		}
		$argPtrs[] = range( $lastArgComma + 1, $closePtr - 1 );

		return $argPtrs;
	}

	protected function checkForFunctionPrototype( $stackPtr, $varName, $currScope ) {

		// Are we a function or closure parameter?
		// It would be nice to get the list of function parameters from watching for
		// T_FUNCTION, but AbstractVariableSniff and AbstractScopeSniff define everything
		// we need to do that as private or final, so we have to do it this hackish way.
		if ( ( $openPtr = $this->findContainingBrackets( $stackPtr ) ) === false ) {
			return false;
		}

		// Function names are T_STRING, and return-by-reference is T_BITWISE_AND,
		// so we look backwards from the opening bracket for the first thing that
		// isn't a function name, reference sigil or whitespace and check if
		// it's a function keyword.
		$functionPtr = $this->phpcsFile->findPrevious(array( T_STRING, T_WHITESPACE, T_BITWISE_AND ),
			$openPtr - 1, null, true, null, true );
		if ( ( $functionPtr !== false ) &&
			( (  $this->tokens[$functionPtr]['code'] === T_FUNCTION) ||
			 (  $this->tokens[$functionPtr]['code'] === T_CLOSURE ) )) {
			// TODO: typeHint
			$this->markVariableDeclaration( $varName, 'param', null, $stackPtr, $functionPtr );
			// Are we pass-by-reference?
			$referencePtr = $this->phpcsFile->findPrevious( T_WHITESPACE,
				$stackPtr - 1, null, true, null, true );
			if ( ( $referencePtr !== false ) && ( $this->tokens[$referencePtr]['code'] === T_BITWISE_AND ) ) {
				$varInfo = $this->getVariableInfo( $varName, $functionPtr );
				$varInfo->passByReference = true;
			}
			//  Are we optional with a default?
			if ( $this->isNextThingAnAssign( $stackPtr ) !== false ) {
				$this->markVariableAssignment( $varName, $stackPtr, $functionPtr );
			}
			return true;
		}

		// Is it a use keyword?  Use is both a read and a define, fun!
		if ( ( $functionPtr !== false ) && ( $this->tokens[$functionPtr]['code'] === T_USE ) ) {
			$this->markVariableRead( $varName, $stackPtr, $currScope );
			if ( $this->isVariableUndefined( $varName, $stackPtr, $currScope ) === true ) {
				// We haven't been defined by this point.
				$message = 'Variable `%s` is undefined.';
				$data    = [ "\${$varName}" ];
				$this->phpcsFile->addWarning( $message, $stackPtr, 'UndefinedVariable', $data );
				return true;
			}
			// $functionPtr is at the use, we need the function keyword for start of scope.
			$functionPtr = $this->phpcsFile->findPrevious( T_CLOSURE,
				$functionPtr - 1, $currScope + 1, false, null, true );
			if ( $functionPtr !== false ) {
				// TODO: typeHints in use?
				$this->markVariableDeclaration( $varName, 'bound', null, $stackPtr, $functionPtr );
				$this->markVariableAssignment( $varName, $stackPtr, $functionPtr );
				return true;
			}
		}
		return false;
	}

	protected function checkForCatchBlock( $stackPtr, $varName, $currScope ) {

		// Are we a catch block parameter?
		if ( ( $openPtr = $this->findContainingBrackets( $stackPtr ) ) === false ) {
			return false;
		}

		// Function names are T_STRING, and return-by-reference is T_BITWISE_AND,
		// so we look backwards from the opening bracket for the first thing that
		// isn't a function name, reference sigil or whitespace and check if
		// it's a function keyword.
		$catchPtr = $this->phpcsFile->findPrevious( T_WHITESPACE,
			$openPtr - 1, null, true, null, true );
		if ( ( $catchPtr !== false ) &&
			( $this->tokens[$catchPtr]['code'] === T_CATCH) ) {
			// Scope of the exception var is actually the function, not just the catch block.
			// TODO: typeHint
			$this->markVariableDeclaration( $varName, 'local', null, $stackPtr, $currScope, true );
			$this->markVariableAssignment( $varName, $stackPtr, $currScope );
			if ( $this->allowUnusedCaughtExceptions) {
				$varInfo = $this->getVariableInfo( $varName, $currScope );
				$varInfo->ignoreUnused = true;
			}
			return true;
		}
		return false;
	}

	protected function checkForThisWithinClass( $stackPtr, $varName ) {
		$token  = $this->tokens[$stackPtr];

		// Are we $this within a class?
		if ( ( $varName !== 'this' ) || empty( $token['conditions'] ) ) {
			return false;
		}

		foreach (array_reverse( $token['conditions'], true ) as $scopePtr => $scopeCode ) {
			//  $this within a closure is invalid
			//  Note: have to fetch code from $tokens, T_CLOSURE isn't set for conditions codes.
			if ( $this->tokens[$scopePtr]['code'] === T_CLOSURE ) {
				return false;
			}
			if ( $scopeCode === T_CLASS || $scopeCode === T_TRAIT ) {
				return true;
			}
		}

		return false;
	}

	protected function checkForSuperGlobal( $varName ) {
		// Are we a superglobal variable?
		if ( in_array( $varName, array(
			'GLOBALS',
			'_SERVER',
			'_GET',
			'_POST',
			'_FILES',
			'_COOKIE',
			'_SESSION',
			'_REQUEST',
			'_ENV',
			'argv',
			'argc',
			) ) ) {
			return true;
		}

		return false;
	}

	protected function checkForStaticMember( $stackPtr, $varName ) {
		$token  = $this->tokens[$stackPtr];

		// Are we a static member?
		$doubleColonPtr = $stackPtr - 1;
		if ( $this->tokens[$doubleColonPtr]['code'] !== T_DOUBLE_COLON ) {
			return false;
		}
		$classNamePtr   = $stackPtr - 2;
		if ( ( $this->tokens[$classNamePtr]['code'] !== T_STRING ) &&
			( $this->tokens[$classNamePtr]['code'] !== T_SELF ) &&
			( $this->tokens[$classNamePtr]['code'] !== T_STATIC ) ) {
			return false;
		}

		// Are we refering to self:: outside a class?
		// TODO: not sure this is our business or should be some other sniff.
		if ( ( $this->tokens[$classNamePtr]['code'] === T_SELF ) ||
			( $this->tokens[$classNamePtr]['code'] === T_STATIC ) ) {
			if ( $this->tokens[$classNamePtr]['code'] === T_SELF ) {
				$err_prefix = 'Self';
				$err_desc  = 'self::';
			} else {
				$err_prefix = 'Static';
				$err_desc  = 'static::';
			}
			if ( !empty( $token['conditions'] ) ) {
				foreach (array_reverse( $token['conditions'], true ) as $scopePtr => $scopeCode ) {
					//  self within a closure is invalid
					//  Note: have to fetch code from $tokens, T_CLOSURE isn't set for conditions codes.
					if ( $this->tokens[$scopePtr]['code'] === T_CLOSURE ) {
						$message = "Use of `{$err_desc}%s` inside closure.";
						$data    = [ "\${$varName}" ];
						$this->phpcsFile->addError( $message, $stackPtr,$err_prefix . 'InsideClosure', $data );
						return true;
					}
					if ( $scopeCode === T_CLASS ) {
						return true;
					}
				}
			}

			$message = "Use of `{$err_desc}%s` outside class definition.";
			$data    = [ "\${$varName}" ];
			$this->phpcsFile->addError( $message, $stackPtr,$err_prefix . 'OutsideClass', $data );
			return true;
		}

		return true;
	}

	protected function checkForAssignment( $stackPtr, $varName, $currScope ) {
		// Is the next non-whitespace an assignment?
		if ( ( $assignPtr = $this->isNextThingAnAssign( $stackPtr ) ) === false ) {
			return false;
		}

		// Plain ol' assignment. Simple-ish.
		if ( ( $writtenPtr = $this->findWhereAssignExecuted( $assignPtr ) ) === false ) {
			$writtenPtr = $stackPtr;  // I dunno
		}
		$this->markVariableAssignment( $varName, $writtenPtr, $currScope );
		return true;
	}

	protected function checkForListAssignment( $stackPtr, $varName, $currScope ) {

		// OK, are we within a list (...) construct?
		if ( ( $openPtr = $this->findContainingBrackets( $stackPtr ) ) === false ) {
			return false;
		}

		$prevPtr = $this->phpcsFile->findPrevious( T_WHITESPACE, $openPtr - 1, null, true, null, true );
		if ( ( $prevPtr === false ) || ( $this->tokens[$prevPtr]['code'] !== T_LIST) ) {
			return false;
		}

		// OK, we're a list (...) construct... are we being assigned to?
		$closePtr = $this->tokens[$openPtr]['parenthesis_closer'];
		if ( ( $assignPtr = $this->isNextThingAnAssign( $closePtr ) ) === false ) {
			return false;
		}

		// Yes, we're being assigned.
		$writtenPtr = $this->findWhereAssignExecuted( $assignPtr );
		$this->markVariableAssignment( $varName, $writtenPtr, $currScope );
		return true;
	}

	protected function checkForGlobalDeclaration( $stackPtr, $varName, $currScope ) {

		// Are we a global declaration?
		// Search backwards for first token that isn't whitespace, comma or variable.
		$globalPtr = $this->phpcsFile->findPrevious(
			array( T_WHITESPACE, T_VARIABLE, T_COMMA ),
			$stackPtr - 1, null, true, null, true );
		if ( ( $globalPtr === false ) || ( $this->tokens[$globalPtr]['code'] !== T_GLOBAL ) ) {
			return false;
		}

		// It's a global declaration.
		$this->markVariableDeclaration( $varName, 'global', null, $stackPtr, $currScope );
		return true;
	}

	protected function checkForStaticDeclaration( $stackPtr, $varName, $currScope ) {

		// Are we a static declaration?
		// Static declarations are a bit more complicated than globals, since they
		// can contain assignments. The assignment is compile-time however so can
		// only be constant values, which makes life manageable.
		//
		// Just to complicate matters further, late static binding constants
		// take the form static::CONSTANT and are invalid within static variable
		// assignments, but we don't want to accidentally match their use of the
		// static keyword.
		//
		// Valid values are:
		//   number         T_MINUS T_LNUMBER T_DNUMBER
		//   string         T_CONSTANT_ENCAPSED_STRING
		//   heredoc        T_START_HEREDOC T_HEREDOC T_END_HEREDOC
		//   nowdoc         T_START_NOWDOC T_NOWDOC T_END_NOWDOC
		//   define         T_STRING
		//   class constant T_STRING T_DOUBLE_COLON T_STRING
		// Search backwards for first token that isn't whitespace, comma, variable,
		// equals, or on the list of assignable constant values above.
		$staticPtr = $this->phpcsFile->findPrevious(
			array(
				T_WHITESPACE, T_VARIABLE, T_COMMA, T_EQUAL,
				T_MINUS, T_LNUMBER, T_DNUMBER,
				T_CONSTANT_ENCAPSED_STRING,
				T_STRING,
				T_DOUBLE_COLON,
				T_START_HEREDOC, T_HEREDOC, T_END_HEREDOC,
				T_START_NOWDOC, T_NOWDOC, T_END_NOWDOC,
				),
			$stackPtr - 1, null, true, null, true );
		if ( ( $staticPtr === false ) || ( $this->tokens[$staticPtr]['code'] !== T_STATIC) ) {
			//if ( $varName == 'static4' ) {
			//    echo "Failing token:\n" . print_r( $tokens[$staticPtr], true );
			//}
			return false;
		}

		// Is it a late static binding static::?
		// If so, this isn't the static keyword we're looking for, but since
		// static:: isn't allowed in a compile-time constant, we also know
		// we can't be part of a static declaration anyway, so there's no
		// need to look any further.
		$lateStaticBindingPtr = $this->phpcsFile->findNext( T_WHITESPACE, $staticPtr + 1, null, true, null, true );
		if ( ( $lateStaticBindingPtr !== false ) && ( $this->tokens[$lateStaticBindingPtr]['code'] === T_DOUBLE_COLON ) ) {
			return false;
		}

		// It's a static declaration.
		$this->markVariableDeclaration( $varName, 'static', null, $stackPtr, $currScope );
		if ( $this->isNextThingAnAssign( $stackPtr ) !== false ) {
			$this->markVariableAssignment( $varName, $stackPtr, $currScope );
		}
		return true;
	}

	protected function checkForForeachLoopVar( $stackPtr, $varName, $currScope ) {
		// Are we a foreach loop var?
		if ( ( $openPtr = $this->findContainingBrackets( $stackPtr ) ) === false ) {
			return false;
		}

		// Is there an 'as' token between us and the opening bracket?
		if ( $this->phpcsFile->findPrevious( T_AS, $stackPtr - 1, $openPtr ) === false ) {
			return false;
		}

		$this->markVariableAssignment( $varName, $stackPtr, $currScope );
		return true;
	}

	protected function checkForPassByReferenceFunctionCall( $stackPtr, $varName, $currScope ) {

		// Are we pass-by-reference to known pass-by-reference function?
		if ( ( $functionPtr = $this->findFunctionCall( $stackPtr ) ) === false ) {
			return false;
		}

		// Is our function a known pass-by-reference function?
		$functionName = $this->tokens[$functionPtr]['content'];
		if ( !isset( $this->_passByRefFunctions[$functionName] ) ) {
			return false;
		}

		$refArgs = $this->_passByRefFunctions[$functionName];

		if ( ( $argPtrs = $this->findFunctionCallArguments( $stackPtr ) ) === false ) {
			return false;
		}

		// We're within a function call arguments list, find which arg we are.
		$argPos = false;
		foreach ( $argPtrs as $idx => $ptrs) {
			if ( in_array( $stackPtr, $ptrs, true ) ) {
				$argPos = $idx + 1;
				break;
			}
		}
		if ( $argPos === false ) {
			return false;
		}
		if ( ! in_array( $argPos, $refArgs, true ) ) {
			// Our arg wasn't mentioned explicitly, are we after an ellipsis catch-all?
			if ( ( $elipsis = array_search( '...', $refArgs, true ) ) === false ) {
				return false;
			}
			if ( $argPos < $refArgs[$elipsis - 1] ) {
				return false;
			}
		}

		// Our argument position matches that of a pass-by-ref argument,
		// check that we're the only part of the argument expression.
		foreach ( $argPtrs[$argPos - 1] as $ptr ) {
			if ( $ptr === $stackPtr ) {
				continue;
			}
			if ( $this->tokens[$ptr]['code'] !== T_WHITESPACE ) {
				return false;
			}
		}

		// Just us, we can mark it as a write.
		$this->markVariableAssignment( $varName, $stackPtr, $currScope );
		// It's a read as well for purposes of used-variables.
		$this->markVariableRead( $varName, $stackPtr, $currScope );
		return true;
	}

	protected function checkForSymbolicObjectProperty( $stackPtr, $varName, $currScope ) {

		// Are we a symbolic object property/function dereference?
		// Search backwards for first token that isn't whitespace, is it a "->" operator?
		$objectOperatorPtr = $this->phpcsFile->findPrevious(
			T_WHITESPACE,
			$stackPtr - 1, null, true, null, true );
		if ( ( $objectOperatorPtr === false ) || ( $this->tokens[$objectOperatorPtr]['code'] !== T_OBJECT_OPERATOR ) ) {
			return false;
		}

		$this->markVariableReadAndWarnIfUndefined( $varName, $stackPtr, $currScope );
		return true;
	}

	/**
	 * Called to process class member vars.
	 *
	 * @param File $phpcsFile The PHP_CodeSniffer file where the token was found.
	 * @param int  $stackPtr  The position where the token was found.
	 *
	 * @return void
	 */
	protected function processMemberVar( $stackPtr ) {
		// TODO: don't care for now
	}

	/**
	 * Called to process normal member vars.
	 *
	 * @param File $phpcsFile The PHP_CodeSniffer file where the token was found.
	 * @param int  $stackPtr  The position where the token was found.
	 *
	 * @return void
	 */
	protected function processVariable( $stackPtr ) {
		$token  = $this->tokens[$stackPtr];

		$varName = $this->normalizeVarName( $token['content'] );
		if ( ( $currScope = $this->findVariableScope( $stackPtr ) ) === false ) {
			return;
		}

		//static $dump_token = false;
		//if ( $varName == 'property' ) {
		//    $dump_token = true;
		//}
		//if ( $dump_token) {
		//    echo "Found variable {$varName} on line {$token['line']} in scope {$currScope}.\n" . print_r( $token, true );
		//    echo "Prev:\n" . print_r( $tokens[$stackPtr - 1], true );
		//}

		// Determine if variable is being assigned or read.

		// Read methods that preempt assignment:
		//   Are we a $object->$property type symbolic reference?

		// Possible assignment methods:
		//   Is a mandatory function/closure parameter
		//   Is an optional function/closure parameter with non-null value
		//   Is closure use declaration of a variable defined within containing scope
		//   catch (...) block start
		//   $this within a class (but not within a closure ).
		//   $GLOBALS, $_REQUEST, etc superglobals.
		//   $var part of class::$var static member
		//   Assignment via =
		//   Assignment via list (...) =
		//   Declares as a global
		//   Declares as a static
		//   Assignment via foreach (... as ...) { }
		//   Pass-by-reference to known pass-by-reference function

		// Are we a $object->$property type symbolic reference?
		if ( $this->checkForSymbolicObjectProperty( $stackPtr, $varName, $currScope ) ) {
			return;
		}

		// Are we a function or closure parameter?
		if ( $this->checkForFunctionPrototype( $stackPtr, $varName, $currScope ) ) {
			return;
		}

		// Are we a catch parameter?
		if ( $this->checkForCatchBlock( $stackPtr, $varName, $currScope ) ) {
			return;
		}

		// Are we $this within a class?
		if ( $this->checkForThisWithinClass( $stackPtr, $varName ) ) {
			return;
		}

		// Are we a $GLOBALS, $_REQUEST, etc superglobal?
		if ( $this->checkForSuperGlobal( $varName ) ) {
			return;
		}

		// $var part of class::$var static member
		if ( $this->checkForStaticMember( $stackPtr, $varName ) ) {
			return;
		}

		// Is the next non-whitespace an assignment?
		if ( $this->checkForAssignment( $stackPtr, $varName, $currScope ) ) {
			return;
		}

		// OK, are we within a list (...) = construct?
		if ( $this->checkForListAssignment( $stackPtr, $varName, $currScope ) ) {
			return;
		}

		// Are we a global declaration?
		if ( $this->checkForGlobalDeclaration( $stackPtr, $varName, $currScope ) ) {
			return;
		}

		// Are we a static declaration?
		if ( $this->checkForStaticDeclaration( $stackPtr, $varName, $currScope ) ) {
			return;
		}

		// Are we a foreach loop var?
		if ( $this->checkForForeachLoopVar( $stackPtr, $varName, $currScope ) ) {
			return;
		}

		// Are we pass-by-reference to known pass-by-reference function?
		if ( $this->checkForPassByReferenceFunctionCall( $stackPtr, $varName, $currScope ) ) {
			return;
		}

		// OK, we don't appear to be a write to the var, assume we're a read.
		$this->markVariableReadAndWarnIfUndefined( $varName, $stackPtr, $currScope );
	}

	/**
	 * Called to process variables found in double quoted strings.
	 *
	 * Note that there may be more than one variable in the string, which will
	 * result only in one call for the string.
	 *
	 * @param File $phpcsFile The PHP_CodeSniffer file where the token was found.
	 * @param int  $stackPtr  The position where the double quoted string was found.
	 *
	 * @return void
	 */
	protected function processVariableInString( $stackPtr ) {
		$token  = $this->tokens[$stackPtr];

		if ( !preg_match_all( $this->_double_quoted_variable_regexp, $token['content'], $matches) ) {
			return;
		}

		$currScope = $this->findVariableScope( $stackPtr );
		foreach ( $matches[1] as $varName ) {
			$varName = $this->normalizeVarName( $varName );
			// Are we $this within a class?
			if ( $this->checkForThisWithinClass( $stackPtr, $varName ) ) {
				continue;
			}
			if ( $this->checkForSuperGlobal( $varName ) ) {
				continue;
			}
			$this->markVariableReadAndWarnIfUndefined( $varName, $stackPtr, $currScope );
		}
	}

	protected function processCompactArguments( $stackPtr, $arguments, $currScope ) {

		foreach ( $arguments as $argumentPtrs ) {
			$argumentPtrs = array_values( array_filter( $argumentPtrs, array( $this, 'filter_non_whitespace_tokens' ) ) );
			if (empty( $argumentPtrs) ) {
				continue;
			}
			if ( !isset( $this->tokens[$argumentPtrs[0]] ) ) {
				continue;
			}
			$argument_first_token = $this->tokens[$argumentPtrs[0]];
			if ( $argument_first_token['code'] === T_ARRAY ) {
				// It's an array argument, recurse.
				if ( ( $array_arguments = $this->findFunctionCallArguments( $argumentPtrs[0] ) ) !== false ) {
					$this->processCompactArguments( $stackPtr, $array_arguments, $currScope );
				}
				continue;
			}
			if (count( $argumentPtrs) > 1) {
				// Complex argument, we can't handle it, ignore.
				continue;
			}
			if ( $argument_first_token['code'] === T_CONSTANT_ENCAPSED_STRING ) {
				// Single-quoted string literal, ie compact( 'whatever' ).
				// Substr is to strip the enclosing single-quotes.
				$varName = substr( $argument_first_token['content'], 1, -1);
				$this->markVariableReadAndWarnIfUndefined( $varName, $argumentPtrs[0], $currScope );
				continue;
			}
			if ( $argument_first_token['code'] === T_DOUBLE_QUOTED_STRING) {
				// Double-quoted string literal.
				if (preg_match( $this->_double_quoted_variable_regexp, $argument_first_token['content'] ) ) {
					// Bail if the string needs variable expansion, that's runtime stuff.
					continue;
				}
				// Substr is to strip the enclosing double-quotes.
				$varName = substr( $argument_first_token['content'], 1, -1);
				$this->markVariableReadAndWarnIfUndefined( $varName, $argumentPtrs[0], $currScope );
			}
		}
	}

	/**
	 * Called to process variables named in a call to compact().
	 *
	 * @param File $phpcsFile The PHP_CodeSniffer file where the token was found.
	 * @param int  $stackPtr  The position where the call to compact() was found.
	 *
	 * @return void
	 */
	protected function processCompact( $stackPtr ) {
		$currScope = $this->findVariableScope( $stackPtr );

		if ( ( $arguments = $this->findFunctionCallArguments( $stackPtr ) ) !== false ) {
			$this->processCompactArguments( $stackPtr, $arguments, $currScope );
		}
	}

	/**
	 * Called to process the end of a scope.
	 *
	 * Note that although triggered by the closing curly brace of the scope, $stackPtr is
	 * the scope conditional, not the closing curly brace.
	 *
	 * @param File $phpcsFile The PHP_CodeSniffer file where the token was found..
	 * @param int  $stackPtr  The position of the scope conditional.
	 *
	 * @return void
	 */
	protected function processScopeClose ( $stackPtr ) {
		$scopeInfo = $this->getScopeInfo( $stackPtr, false );
		if ( null === $scopeInfo ) {
			return;
		}
		foreach ( $scopeInfo->variables as $varInfo ) {
			if ( $varInfo->ignoreUnused || isset( $varInfo->firstRead ) ) {
				continue;
			}
			if ( $this->allowUnusedFunctionParameters && $varInfo->scopeType === 'param' ) {
				continue;
			}
			if ( $varInfo->passByReference && isset( $varInfo->firstInitialized ) ) {
				// If we're pass-by-reference then it's a common pattern to
				// use the variable to return data to the caller, so any
				// assignment also counts as "variable use" for the purposes
				// of "unused variable" warnings.
				continue;
			}

			$message = 'Unused %s `%s`.';
			$data    = [
				VariableInfo::$scopeTypeDescriptions[$varInfo->scopeType],
				"\${$varInfo->name}",
			];

			if ( isset( $varInfo->firstDeclared ) ) {
				$this->phpcsFile->addWarning( $message, $varInfo->firstDeclared, 'UnusedVariable', $data );
			}
			if ( isset( $varInfo->firstInitialized ) ) {
				$this->phpcsFile->addWarning( $message, $varInfo->firstInitialized, 'UnusedVariable', $data );
			}
		}
	}

	public function filter_non_whitespace_tokens( $argumentPtr ) {
		return $this->tokens[$argumentPtr]['code'] !== T_WHITESPACE;
	}
}
// @codingStandardsIgnoreEnd
