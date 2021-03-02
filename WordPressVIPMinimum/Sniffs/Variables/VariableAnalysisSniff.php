<?php
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

use PHP_CodeSniffer\Files\File;

/**
 * Checks for undefined function variables.
 *
 * This sniff checks that all function variables
 * are defined in the function body.
 *
 * @category  PHP
 * @package   PHP_CodeSniffer
 * @author    Sam Graham <php-codesniffer-variableanalysis BLAHBLAH illusori.co.uk>
 * @copyright 2011 Sam Graham <php-codesniffer-variableanalysis BLAHBLAH illusori.co.uk>
 * @link      http://pear.php.net/package/PHP_CodeSniffer
 *
 * @deprecated 2.2.0 Use the `VariableAnalysis.CodeAnalysis.VariableAnalysis` sniff instead.
 *                   This `WordPressVIPMinimum.Variables.VariableAnalysis sniff will be removed in VIPCS 3.0.0.
 */
class VariableAnalysisSniff extends \VariableAnalysis\Sniffs\CodeAnalysis\VariableAnalysisSniff {

	/**
	 * Keep track of whether the warnings have been thrown to prevent
	 * the messages being thrown for every token triggering the sniff.
	 *
	 * @since 2.2.0
	 *
	 * @var array
	 */
	private $thrown = [
		'DeprecatedSniff'                 => false,
		'FoundPropertyForDeprecatedSniff' => false,
	];

	/**
	 * Don't use.
	 *
	 * @since      2.2.0 Added to allow for throwing the deprecation notices.
	 * @deprecated 2.2.0
	 *
	 * @param \PHP_CodeSniffer\Files\File $phpcsFile The file being scanned.
	 * @param int                         $stackPtr  The position of the current token
	 *                                               in the stack passed in $tokens.
	 *
	 * @return int|void Integer stack pointer to skip forward or void to continue
	 *                  normal file processing.
	 */
	public function process( File $phpcsFile, $stackPtr ) {

		if ( $this->thrown['DeprecatedSniff'] === false ) {
			$this->thrown['DeprecatedSniff'] = $phpcsFile->addWarning(
				'The "WordPressVIPMinimum.Variables.VariableAnalysis" sniff has been deprecated. Use the "VariableAnalysis.CodeAnalysis.VariableAnalysis" sniff instead. Please update your custom ruleset.',
				0,
				'DeprecatedSniff'
			);
		}
		if ( ! empty( $this->exclude ) && $this->thrown['FoundPropertyForDeprecatedSniff'] === false ) {
			$this->thrown['FoundPropertyForDeprecatedSniff'] = $phpcsFile->addWarning(
				'The "WordPressVIPMinimum.Variables.VariableAnalysis" sniff has been deprecated. Use the "CodeAnalysis.VariableAnalysis" sniff instead. "exclude" property setting found. Please update your custom ruleset.',
				0,
				'FoundPropertyForDeprecatedSniff'
			);
		}

		parent::process( $phpcsFile, $stackPtr );
	}
}
