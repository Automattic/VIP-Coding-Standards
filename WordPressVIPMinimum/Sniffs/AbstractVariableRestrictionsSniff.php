<?php
/**
 * WordPressVIPMinimum Coding Standard.
 *
 * @package VIPCS\WordPressVIPMinimum
 * @link https://github.com/Automattic/VIP-Coding-Standards
 * @license https://opensource.org/licenses/MIT MIT
 */

namespace WordPressVIPMinimum\Sniffs;

use WordPressVIPMinimum\Sniffs\Sniff;

/**
 * Restricts usage of some variables.
 *
 * Originally part of WordPress Coding Standards repo.
 *
 * @package VIPCS\WordPressVIPMinimum
 */
abstract class AbstractVariableRestrictionsSniff extends Sniff {

	/**
	 * Exclude groups.
	 *
	 * Example: 'foo,bar'
	 *
	 * @var array
	 */
	public $exclude = [];

	/**
	 * Groups of variable data to check against.
	 *
	 * Don't use this in extended classes, override getGroups() instead.
	 *
	 * This is only used for Unit tests.
	 *
	 * @var array
	 */
	public static $groups = [];

	/**
	 * Cache for the excluded groups information.
	 *
	 * @var array
	 */
	protected $excluded_groups = [];

	/**
	 * Cache for the group information.
	 *
	 * @var array
	 */
	protected $groups_cache = [];

	/**
	 * Returns an array of tokens this test wants to listen for.
	 *
	 * @return array
	 */
	public function register() {
		// Retrieve the groups only once and don't set up a listener if there are no groups.
		if ( false === $this->setup_groups() ) {
			return [];
		}

		return [
			\T_VARIABLE,
			\T_OBJECT_OPERATOR,
			\T_DOUBLE_COLON,
			\T_OPEN_SQUARE_BRACKET,
			\T_DOUBLE_QUOTED_STRING,
			\T_HEREDOC,
		];
	}

	/**
	 * Groups of variables to restrict.
	 *
	 * This method should be overridden in extending classes.
	 *
	 * Example: groups => array(
	 *  'wpdb' => array(
	 *      'type'          => 'error' | 'warning',
	 *      'message'       => 'Dont use this one please!',
	 *      'variables'     => array( '$val', '$var' ),
	 *      'object_vars'   => array( '$foo->bar', .. ),
	 *      'array_members' => array( '$foo['bar']', .. ),
	 *  )
	 * )
	 *
	 * @return array
	 */
	abstract public function getGroups();

	/**
	 * Cache the groups.
	 *
	 * @return bool True if the groups were setup. False if not.
	 */
	protected function setup_groups() {
		$this->groups_cache = $this->getGroups();

		if ( empty( $this->groups_cache ) && empty( self::$groups ) ) {
			return false;
		}

		// Allow for adding extra unit tests.
		if ( ! empty( self::$groups ) ) {
			$this->groups_cache = array_merge( $this->groups_cache, self::$groups );
		}

		return true;
	}

	/**
	 * Processes this test, when one of its tokens is encountered.
	 *
	 * @param int $stackPtr The position of the current token in the stack.
	 * @return int|void Integer stack pointer to skip forward or void to continue
	 *                  normal file processing.
	 *
	 * @throws \PHP_CodeSniffer\Exceptions\RuntimeException Exception.
	 */
	public function process_token( $stackPtr ) {

		$token = $this->tokens[ $stackPtr ];

		$this->excluded_groups = static::merge_custom_array( $this->exclude );
		if ( array_diff_key( $this->groups_cache, $this->excluded_groups ) === [] ) {
			// All groups have been excluded.
			// Don't remove the listener as the exclude property can be changed inline.
			return;
		}

		// Check if it is a function not a variable.
		if ( \in_array( $token['code'], [ \T_OBJECT_OPERATOR, \T_DOUBLE_COLON ], true ) ) { // This only works for object vars and array members.
			$method               = $this->phpcsFile->findNext( \T_WHITESPACE, $stackPtr + 1, null, true );
			$possible_parenthesis = $this->phpcsFile->findNext( \T_WHITESPACE, $method + 1, null, true );
			if ( \T_OPEN_PARENTHESIS === $this->tokens[ $possible_parenthesis ]['code'] ) {
				return; // So .. it is a function after all !
			}
		}

		foreach ( $this->groups_cache as $groupName => $group ) {

			if ( isset( $this->excluded_groups[ $groupName ] ) ) {
				continue;
			}

			$patterns = [];

			// Simple variable.
			if ( \in_array( $token['code'], [ \T_VARIABLE, \T_DOUBLE_QUOTED_STRING, \T_HEREDOC ], true ) && ! empty( $group['variables'] ) ) {
				$patterns = array_merge( $patterns, $group['variables'] );
				$var      = $token['content'];

			}

			if ( \in_array( $token['code'], [ \T_OBJECT_OPERATOR, \T_DOUBLE_COLON, \T_DOUBLE_QUOTED_STRING, \T_HEREDOC ], true ) && ! empty( $group['object_vars'] ) ) {
				// Object var, ex: $foo->bar / $foo::bar / Foo::bar / Foo::$bar .
				$patterns = array_merge( $patterns, $group['object_vars'] );

				$owner = $this->phpcsFile->findPrevious( [ \T_VARIABLE, \T_STRING ], $stackPtr );
				$child = $this->phpcsFile->findNext( [ \T_STRING, \T_VARIABLE ], $stackPtr );
				$var   = implode( '', [ $this->tokens[ $owner ]['content'], $token['content'], $this->tokens[ $child ]['content'] ] );

			}

			if ( \in_array( $token['code'], [ \T_OPEN_SQUARE_BRACKET, \T_DOUBLE_QUOTED_STRING, \T_HEREDOC ], true ) && ! empty( $group['array_members'] ) ) {
				// Array members.
				$patterns = array_merge( $patterns, $group['array_members'] );

				if ( isset( $token['bracket_closer'] ) ) {
					$owner  = $this->phpcsFile->findPrevious( \T_VARIABLE, $stackPtr );
					$inside = $this->phpcsFile->getTokensAsString( $stackPtr, $token['bracket_closer'] - $stackPtr + 1 );
					$var    = implode( '', [ $this->tokens[ $owner ]['content'], $inside ] );
				}
			}

			if ( empty( $patterns ) ) {
				continue;
			}

			$patterns = array_map( [ $this, 'test_patterns' ], $patterns );
			$pattern  = implode( '|', $patterns );
			$delim    = ( \T_OPEN_SQUARE_BRACKET !== $token['code'] && \T_HEREDOC !== $token['code'] ) ? '\b' : '';

			if ( \T_DOUBLE_QUOTED_STRING === $token['code'] || \T_HEREDOC === $token['code'] ) {
				$var = $token['content'];
			}

			if ( empty( $var ) || preg_match( '#(' . $pattern . ')' . $delim . '#', $var, $match ) !== 1 ) {
				continue;
			}

			$this->addMessage(
				$group['message'],
				$stackPtr,
				'error' === $group['type'],
				$this->string_to_errorcode( $groupName . '_' . $match[1] ),
				[ $var ]
			);

			return; // Show one error only.
		}
	}

	/**
	 * Transform a wildcard pattern to a usable regex pattern.
	 *
	 * @param string $pattern Pattern.
	 * @return string
	 */
	private function test_patterns( $pattern ) {
		$pattern = preg_quote( $pattern, '#' );
		$pattern = preg_replace(
			[ '#\\\\\*#', '[\'"]' ],
			[ '.*', '\'' ],
			$pattern
		);
		return $pattern;
	}

}
