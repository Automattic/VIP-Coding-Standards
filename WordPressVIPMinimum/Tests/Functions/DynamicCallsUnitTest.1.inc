<?php

function my_test() {
	echo esc_html( "foo" );
}
$irrelevant = 10;

$my_notokay_func = 'extract';
$my_notokay_func(); // Bad.

$my_okay_func = 'my_test';
$my_okay_func(); // OK.

$test_with_comment /*comment*/ = 'func_get_args';
$test_with_comment /*comment*/ (); // Bad.

$test_getting_the_actual_value_1 = function_call( 'extract' );
$test_getting_the_actual_value_1(); // OK. Unclear what the actual variable value will be.

$test_getting_the_actual_value_2 = $array['compact'];
$test_getting_the_actual_value_2(); // OK. Unclear what the actual variable value will be.

$test_getting_the_actual_value_3 = 10 ?>
<div>html</div>
<?php
echo 'extract';
$test_getting_the_actual_value_3(); // OK. Broken function call, but not calling extract().

$test_getting_the_actual_value_4 = 'get_defined_vars' . $source;
$test_getting_the_actual_value_4(); // OK. Unclear what the actual variable value will be.

$ensure_no_notices_are_thrown_on_parse_error = /*comment*/ ;

$test_double_quoted_string = "assert";
$test_double_quoted_string(); // Bad.

function hasStaticVars() {
	static $staticvar_foo = 'irrelevant', $staticvar_bar = 'func_num_args', $staticvar_baz = 'nothing';
	$staticvar_foo(); // OK.
	$staticvar_bar(); // Bad.
	$staticvar_baz(); // OK.
}

function functionParams( $param_foo = 'func_get_arg', $param_bar = 'nothing', $param_baz = 'func_num_args') {
	$param_foo(); // Bad.
	$param_bar(); // OK.
	$param_baz(); // Bad.
}
