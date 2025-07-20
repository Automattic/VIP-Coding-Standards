<?php

/*
 * Not the sniff target.
 */
use add_filter;

my\ns\add_filter($a, $b);
$this->add_action($a, $b);
$this?->add_filter($a, $b);
MyClass::add_action($a, $b);
echo ADD_FILTER;
namespace\add_action($a, $b);


/*
 * These should all be okay.
 */
add_filter( 'not_target_hook', 'good_example_function' );
\add_filter( 'upload_mimesX'  , 'good_example_function' );

add_action(...$params); // PHP 5.6 argument unpacking.

// Looks like a function call, but is a PHP 8.0+ class instantiation via an attribute.
#[ADD_FILTER('text')]
function foo() {}

// PHP 8.1 first class callable.
// As we have no knowledge about what parameters will be passed, we shouldn't flag this.
array_walk($filters, add_filter(...));

// Ignore as undetermined.
Add_Filter( $hook_name, 'undetermined' );
\add_action( $obj->get_filterName(), 'undetermined' );
add_filter( MyClass::FILTER_NAME, 'undetermined', );
\add_filter( "upload_$mimes", 'undetermined' );

// Incomplete function call, should be ignored by the sniff.
$incorrect_but_ok = add_filter();
$incorrect_but_ok = add_action();


/*
 * These should all be flagged with a warning.
 */
add_filter( 'do_robotstxt', 'bad_example_function' ); // Simple string.
add_action('upload_mimes' , [$obj, 'method']); // Incorrect spacing.
add_filter(  'robots_txt','bad_example_function'); // Incorrect spacing.
\add_filter( "http_request_timeout" , fn($param) => $param * 10); // Double quotes.

ADD_FILTER( 'upload_' . 'mimes','bad_example_function'); // Single concatenation.
add_filter( 'upl' . 'oad_' . 'mimes','bad_example_function'); // Multiple concatenation.
add_filter( "upload_" . 'mimes' , bad_example_function(...)); // Single concatenation with double and single quotes.
add_filter( 'upl' . "oad_" . "mimes",'bad_example_function'); // Multiple concatenation with double and single quotes.
\add_action( 'http_request_args', function() { // Anonymous callback.
	// Do stuff.
});
add_action( 'upload_mimes', 'bad_example_function' ); // Check `add_action()`, which is an alias for `add_filter()`.
add_filter( 'http_request_timeout', 'bad_example_function' ); // Simple string.
add_filter('http_request_args', 'bad_example_function' ); // Simple string + incorrect spacing.
add_action( /*comment*/ 'do_robotstxt', 'my_do_robotstxt'); // Simple string.
add_filter( 'robots_txt', function() { // Anonymous callback.
} );

// Safeguard correct handling of function calls using PHP 8.0+ named parameters.
add_action(callback: 'invalid', priority: 10); // OK, well, not really, missing required $hook_name param, but that's not the concern of this sniff.
add_action(callback: 'do_robotstxt', hook_name: 'not_our_target'); // OK.
add_action(hookName: 'not_our_target', callback: 'do_robotstxt',); // OK, well, not really, typo in param name, but that's not the concern of the sniff.

add_filter(priority: 10, hook_name: 'robots_txt', callback: some_function(...) ); // Warning.

// Hook names are case-sensitive.
add_filter( 'upLoad_mimeS' , $callback); // OK, not our target.

// Bug fix - spacing vs concatenation.
add_filter('do_' . 'robots' . 'txt', 'bad_example_function'); // Warning.
