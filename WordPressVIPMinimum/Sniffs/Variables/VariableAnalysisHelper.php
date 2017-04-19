<?php
// @codingStandardsIgnoreStart
/**
 * This file is part of the VariableAnalysis addon for PHP_CodeSniffer.
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

/**
 * Holds details of a scope.
 *
 * @category  PHP
 * @package   PHP_CodeSniffer
 * @author    Sam Graham <php-codesniffer-variableanalysis BLAHBLAH illusori.co.uk>
 * @copyright 2011-2012 Sam Graham <php-codesniffer-plugins BLAHBLAH illusori.co.uk>
 * @link      http://pear.php.net/package/PHP_CodeSniffer
 */
class ScopeInfo {
    public $owner;
    public $opener;
    public $closer;
    public $variables = array();

    function __construct($currScope) {
        $this->owner = $currScope;
        // TODO: extract opener/closer
    }
}

/**
 * Holds details of a variable within a scope.
 *
 * @category  PHP
 * @package   PHP_CodeSniffer
 * @author    Sam Graham <php-codesniffer-variableanalysis BLAHBLAH illusori.co.uk>
 * @copyright 2011 Sam Graham <php-codesniffer-variableanalysis BLAHBLAH illusori.co.uk>
 * @link      http://pear.php.net/package/PHP_CodeSniffer
 */
class VariableInfo {
    public $name;
    /**
     * What scope the variable has: local, param, static, global, bound
     */
    public $scopeType;
    public $typeHint;
    public $passByReference = false;
    public $firstDeclared;
    public $firstInitialized;
    public $firstRead;
    public $ignoreUnused = false;

    static $scopeTypeDescriptions = array(
        'local'  => 'variable',
        'param'  => 'function parameter',
        'static' => 'static variable',
        'global' => 'global variable',
        'bound'  => 'bound variable',
        );

    function __construct($varName) {
        $this->name = $varName;
    }
}
// @codingStandardsIgnoreEnd
