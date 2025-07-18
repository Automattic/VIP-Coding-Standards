<?php

/*
 * Not the sniff target.
 */
use filter_var;

my\ns\filter_input($a, $b);
$this->filter_var_array($a, $b);
$this?->filter_input_array($a, $b);
MyClass::filter_var($a, $b);
echo FILTER_INPUT;
namespace\filter_var_array($a, $b);

// Looks like a function call, but is a PHP 8.0+ class instantiation via an attribute.
#[Filter_Input('text')]
function foo() {}

// PHP 8.1 first class callable.
// As we have no knowledge about what parameters will be passed, we shouldn't flag this.
add_filter('my_filter', filter_var(...));


/*
 * These should all be okay.
 */
filter_var( $url, FILTER_SANITIZE_URL );
\filter_var( 'test', FILTER_SANITIZE_STRING );
FILTER_INPUT( INPUT_GET, 'foo', FILTER_SANITIZE_STRING, );
filter_input( INPUT_GET,  "foo"   , FILTER_SANITIZE_STRING );
filter_input_array( $array, filter_default ); // Constants are case-sensitive, so this is not the FILTER_DEFAULT constant.

// Ignore as undetermined.
filter_var(  "test", get_filter() );
\Filter_Var_Array( $array, $filterName );
filter_input_array( $array,$obj->get_filter()     , );

// Incomplete function call, should be ignored by the sniff.
$incorrect_but_ok = filter_input();

/*
 * These should all be flagged with a warning.
 */
filter_input( INPUT_GET, 'foo' ); // Missing third parameter.
\filter_input( INPUT_GET, 'foo', FILTER_DEFAULT ); // This filter ID does nothing.
filter_input( INPUT_GET, "foo", FILTER_UNSAFE_RAW  ,); // This filter ID does nothing.

filter_var( $url ); // Missing second parameter.
Filter_Var( $url, FILTER_DEFAULT ); // This filter ID does nothing.
filter_var( 'https://google.com', FILTER_UNSAFE_RAW ); // This filter ID does nothing.

filter_var_array( $array, ); // Missing second parameter.
filter_var_array( $array, FILTER_DEFAULT ); // This filter ID does nothing.
filter_var_array( $array, FILTER_UNSAFE_RAW ); // This filter ID does nothing.

filter_input_array( $array ); // Missing second parameter.
\FILTER_INPUT_ARRAY( $array, FILTER_DEFAULT ); // This filter ID does nothing.
filter_input_array( $array, FILTER_UNSAFE_RAW, ); // This filter ID does nothing.
