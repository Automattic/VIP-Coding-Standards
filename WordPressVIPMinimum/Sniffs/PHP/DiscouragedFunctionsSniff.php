<?php
/**
 * WordPressVIPMinimum Coding Standard.
 *
 * @link    https://github.com/Automattic/VIP-Coding-Standards
 * @license https://github.com/Automattic/VIP-Coding-Standards/blob/master/LICENSE.md GPL v2 or later.
 */

if ( ! class_exists( 'Generic_Sniffs_PHP_ForbiddenFunctionsSniff', true ) ) {
	throw new PHP_CodeSniffer_Exception( 'Class Generic_Sniffs_PHP_ForbiddenFunctionsSniff not found' );
}

/**
 * Discourages the use of various functions and suggests (WordPress) alternatives.
 *
 *
 * @since   0.1.0
 * @since   0.10.0 The checks for the POSIX functions have been replaced by the stand-alone
 *                 sniff WordPress_Sniffs_PHP_POSIXFunctionsSniff.
 */
class WordPressVIPMinimum_Sniffs_PHP_DiscouragedFunctionsSniff extends  WordPress_Sniffs_PHP_DiscouragedFunctionsSniff {

	/**
	 * A list of forbidden functions which are being
	 * reported by other Sniffs and thus produce
	 * duplicate error for the same function.
	 *
	 * Functions in this list won't be reported as
	 * discouraged.
	 */
	public $elsewhereHandledForbiddenFunctions = array(
		'print_r',
		'var_dump',
	);

	/**
	 * Returns an array of tokens this test wants to listen for.
	 *
	 * {@internal Temporarily overrule the parent register() method until bugfix has
	 * been merged into PHPCS upstream and WPCS minimum PHPCS version has caught up.
	 * {@link https://github.com/squizlabs/PHP_CodeSniffer/pull/1076} }}
	 *
	 * @return array
	 */
	public function register() {
		$register = parent::register();

		if ( true !== $this->patternMatch ) {
			$this->forbiddenFunctionNames = array_map( 'strtolower', $this->forbiddenFunctionNames );
			$this->forbiddenFunctions     = array_combine( $this->forbiddenFunctionNames, $this->forbiddenFunctions );
		}

		foreach( $this->elsewhereHandledForbiddenFunctions as $function ) {
			unset( $this->forbiddenFunctions[$function] );
		}
		
		$this->forbiddenFunctionNames = array_filter( $this->forbiddenFunctionNames, array( $this, 'filterFunctionNames' ) );

		var_dump( $this->forbiddenFunctions );
		var_dump( $this->forbiddenFunctionNames );

		return $register;
	}

	public function filterFunctionNames( $functionName ) {
		return ! ( in_array( $functionName, $this->elsewhereHandledForbiddenFunctions, true ) );
	}

} // End class.
<?php
/**
 * WordPressVIPMinimum Coding Standard.
 *
 * @link    https://github.com/Automattic/VIP-Coding-Standards
 * @license https://github.com/Automattic/VIP-Coding-Standards/blob/master/LICENSE.md GPL v2 or later.
 */

if ( ! class_exists( 'Generic_Sniffs_PHP_ForbiddenFunctionsSniff', true ) ) {
	throw new PHP_CodeSniffer_Exception( 'Class Generic_Sniffs_PHP_ForbiddenFunctionsSniff not found' );
}

/**
 * Discourages the use of various functions and suggests (WordPress) alternatives.
 *
 *
 * @since   0.1.0
 * @since   0.10.0 The checks for the POSIX functions have been replaced by the stand-alone
 *                 sniff WordPress_Sniffs_PHP_POSIXFunctionsSniff.
 */
class WordPressVIPMinimum_Sniffs_PHP_DiscouragedFunctionsSniff extends  WordPress_Sniffs_PHP_DiscouragedFunctionsSniff {

	/**
	 * A list of forbidden functions which are being
	 * reported by other Sniffs and thus produce
	 * duplicate error for the same function.
	 *
	 * Functions in this list won't be reported as
	 * discouraged.
	 */
	public $elsewhereHandledForbiddenFunctions = array(
		'print_r',
		'var_dump',
	);

	/**
	 * Returns an array of tokens this test wants to listen for.
	 *
	 * {@internal Temporarily overrule the parent register() method until bugfix has
	 * been merged into PHPCS upstream and WPCS minimum PHPCS version has caught up.
	 * {@link https://github.com/squizlabs/PHP_CodeSniffer/pull/1076} }}
	 *
	 * @return array
	 */
	public function register() {
		$register = parent::register();

		if ( true !== $this->patternMatch ) {
			$this->forbiddenFunctionNames = array_map( 'strtolower', $this->forbiddenFunctionNames );
			$this->forbiddenFunctions     = array_combine( $this->forbiddenFunctionNames, $this->forbiddenFunctions );
		}

		foreach( $this->elsewhereHandledForbiddenFunctions as $function ) {
			unset( $this->forbiddenFunctions[$function] );
		}
		
		$this->forbiddenFunctionNames = array_filter( $this->forbiddenFunctionNames, array( $this, 'filterFunctionNames' ) );

		return $register;
	}

	public function filterFunctionNames( $functionName ) {
		return ! ( in_array( $functionName, $this->elsewhereHandledForbiddenFunctions, true ) );
	}

} // End class.
