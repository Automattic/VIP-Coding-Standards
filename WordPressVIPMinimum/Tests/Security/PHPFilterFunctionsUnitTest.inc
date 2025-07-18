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
filter_input( INPUT_GET, 'foo' ); // Missing $filter parameter.
\filter_input( INPUT_GET, 'foo', FILTER_DEFAULT ); // This filter ID does nothing.
filter_input( INPUT_GET, "foo", FILTER_UNSAFE_RAW /* comment */ ,); // This filter ID does nothing.

filter_var( $url ); // Missing $filter parameter.
Filter_Var( $url, FILTER_DEFAULT ); // This filter ID does nothing.
filter_var( 'https://google.com', FILTER_UNSAFE_RAW ); // This filter ID does nothing.

filter_var_array( $array, ); // Missing $options parameter.
filter_var_array( $array, FILTER_DEFAULT ); // This filter ID does nothing.
filter_var_array( $array, FILTER_UNSAFE_RAW ); // This filter ID does nothing.

filter_input_array( $array ); // Missing $options parameter.
\FILTER_INPUT_ARRAY( $array, FILTER_DEFAULT ); // This filter ID does nothing.
filter_input_array( $array, FILTER_UNSAFE_RAW, ); // This filter ID does nothing.

// Safeguard handling of function calls using PHP 8.0+ named parameters.
filter_input(var_name: $var_name, filter: FILTER_SANITIZE_STRING, type: FILTER_DEFAULT); // OK, invalid input value for $type, but that's not our concern.
filter_input(var_name: $var_name, filter: $filter, type: $type); // Ignore, undetermined.
filter_input(
    var_name: $var_name,
    filter: FILTER_DEFAULT, // This filter ID does nothing.
    type: $type,
);

filter_var(filter: FILTER_SANITIZE_URL); // OK, well not really, missing required parameter, but that's not our concern.
filter_var($value, options: FILTER_NULL_ON_FAILURE); // Missing $filter parameter.
filter_var(value: $value, filters: FILTER_SANITIZE_URL); // Typo in parameter name, report as missing $filter parameter.

filter_var_array(options: FILTER_UNSAFE_RAW, array: $array); // This filter ID does nothing.

filter_input_array($type, add_empty: false, options: FILTER_DEFAULT,); // This filter ID does nothing.

// Ignore function calls using PHP 5.6 argument unpacking as we don't know what parameters were passed.
filter_input(INPUT_GET, ...$params);
trim(filter_input(INPUT_GET, ...$params));
// ... but only ignore unpacking if done at the correct nesting level.
filter_input(INPUT_GET, $obj->getVarname(...$params)); // Missing $filter parameter.
