<?php

/*
 * Not the sniff target.
 */
use function wp_cache_get;

$partiallyQualifiedFunction = my\ns\wp_cache_get(); $partiallyQualifiedFunction = false;
$methodCall = $this->wp_cache_get(); $methodCall = false;
$nullsafeMethodCall = $this?->wp_cache_get(); $nullsafeMethodCall = false;
$staticMethodCall = MyClass::wp_cache_get(); $staticMethodCall = false;
echo WP_CACHE_GET;
$namespacedFunction = namespace\wp_cache_get(); $namespacedFunction = false;

function &wp_cache_get() {}

// Looks like a function call, but is a PHP 8.0+ class instantiation via an attribute.
#[WP_Cache_Get('text')]
function foo() {}


/*
 * These should all be okay.
 */
$good_wp_users = wp_cache_get( md5( self::CACHE_KEY . '_wp_users'), self::CACHE_GROUP );

if ( empty( $good_wp_users ) ) {
	$good_wp_users = false;
	$good_wp_users = get_users();
}

function NoAssignment() {
	$unrelated_var = false;
	\wp_cache_get( $key, $group ) === false && do_something_to_create_new_cache();
	$unrelated_var = false;
}

function functionCallsAreNotCaseSensitiveGood() {
	$retrieved = WP_Cache_Get( ...$params );
	if ( empty( $retrieved ) ) {
		$retrieved = false;
	}
}

function fqnFunctionCallGood() {
	$no_params_not_our_concern = \wp_cache_get();
	if ( empty( $no_params_not_our_concern ) ) {
		$no_params_not_our_concern = false;
	}
}

function cacheOverrideDoesntAssignFalse() {
	$not_false = wp_cache_get( ...$params );
	$not_false = true;
}


function cacheOverrideAssignsUnknownValue( $unknown ) {
	$not_false = wp_cache_get( ...$params );
	$not_false = $unknown;
}


/*
 * These should all be flagged.
 */
$bad_wp_users = wp_cache_get( md5( self::CACHE_KEY . '_wp_users'), self::CACHE_GROUP );
$bad_wp_users = false; // Bad.

if ( empty( $bad_wp_users ) ) {
	$bad_wp_users = get_users();
}

function functionCallsAreNotCaseSensitiveBad() {
	$cache_retrieved = WP_Cache_Get( ...$params );
	// Comment.
	do_something_else();
	$cache_retrieved = false; // Bad.
}

function fqnFunctionCallBad() {
	$shouldBeCaught = \wp_cache_get();
	$shouldBeCaught = false; // Bad.
}

// Ignore PHP 8.1 first class callable.
// Ignore as the assignment is not for the return value of the function, but for the callable.
function firstClassCallable() {
	$callable = wp_cache_get(...);
	$callable = false; // OK.
}

// Bug fix - the variable re-assignment must be in the same scope.
function controlStructuresAreNotClosedScopes() {
	if (false === ($cache = \wp_cache_get( $key, $group ))) {
		// Create new cache.
	}
	$cache = false; // Bad.
}


function closedScopeA() {
	$myCache = \wp_cache_get();
}

function closedScopeB($myCache = false) {} // OK.

function closedScopeC() {
	$yourCache = wp_cache_get();

	$closure = function () {
		$yourCache = false; // OK.
	};
}

$globalScope = wp_cache_get();
function closedScopeD() {
	$globalScope = false; // OK.
}

$globalScope = wp_cache_get();
function closedScopeE($globalScope = false;) {} // OK.

$globalScope = wp_cache_get();
class FooBar {
	function method($globalScope = false) {} // OK.
}
