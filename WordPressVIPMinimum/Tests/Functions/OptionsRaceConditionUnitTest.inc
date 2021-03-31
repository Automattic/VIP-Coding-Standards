<?php

function test_with_function_between() {
	$defaults = get_default_settings();
	delete_option( 'my_settings' );

	do_stuff();

	add_option( 'my_settings', $defaults ); // OK - since there is stuff being done in between.
}

function test_different_option_name() {
	$defaults = get_default_settings();
	delete_option( 'my_settings' );
	add_option( 'settings', [] ); // OK - Not same key as delete_option().
}

function test_same_option_key_by_var_assignment() {
	$key = 'option_key';
	delete_option( $key );
	$add = add_option( $key, $key ); // Warning.
}

function test_same_option_key() {
	$key = 'option_key';
	delete_option( $key );
	add_option( $key, 'data' ); // Warning.
}

function test_same_option_key_var_assignment2() {
	$delete = delete_option( 'option_key' );
	add_option( 'option_key', [ 'stuff', '123' ] ); // Warning.
}

function test_same_option_key_string() {
	$delete = delete_option(  'option_key' );
	add_option( 'option_key', [] ); // Warning.
}

function test_line_breaks() {
	$delete = delete_option(  'option_key' );



	add_option( 'option_key', [] ); // Warning.
}

function test_bad_spacing() {
		$delete =    delete_option(    'option_key' );
	add_option('option_key', []); // Warning.
}

function test_empty_tokens_between() {
	delete_option( 'key' );

	// Random comment here.
	/* Random stuff. */
        
	add_option ( 'key' ); // Warning.
}

function test_first_if() {
	$delete = delete_option ( 'option_key' );
	if ( $delete ) {
		add_option( 'option_key', [] ); // Warning.
	}
}

function test_inside_first_if_but_different_key() {
	$delete = delete_option ( 'option_key' );
	if ( $delete ) {
		add_option( 'different_option_key', [] ); // OK.
	}
}

function test_second_if() {
	$delete = delete_option ( 'option_key' );
	if ( do_something() ) {
		// Stuff.
	}
	if ( $delete ) {
		add_option( 'option_key', [] ); // OK - Not on first IF.
	}
}

function concurrent_option_writes() {
	delete_option( 'test_option' );
	add_option( 'test_option', [ 1, 2, 3 ] ); // Warning.

	do_stuff();

	$delete = delete_option( 'another_test_option' );
	if ( $delete ) {
		add_option( 'another_test_option', $data ); // Warning.
	}
}

function concurrent_option_writes_with_one_if_different_key() {
	$test = delete_option( 'test_option' );
	if ( $test ) {
		add_option( 'option', [ 1, 2, 3 ] );
	}
	$test = add_option( 'test_option', [ 1, 2, 3 ] ); // Warning.
}

function concurrent_option_writes_with_one_if_same_key() {
	$test = delete_option( 'test_option' );
	if ( $test ) {
		add_option( 'test_option', [] ); // Warning.
	}
	$test = add_option( 'test_option', [ 1, 2, 3 ] ); // Warning.
}
