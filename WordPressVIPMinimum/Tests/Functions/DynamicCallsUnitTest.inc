<?php

function my_test() {
	echo esc_html( "foo" );
}


$my_notokay_func = 'extract';
$my_notokay_func(); // Bad.

$my_okay_func = 'my_test';
$my_okay_func(); // OK.

$test_with_comment /*comment*/ = 'func_get_args';
$test_with_comment(); // Bad.
