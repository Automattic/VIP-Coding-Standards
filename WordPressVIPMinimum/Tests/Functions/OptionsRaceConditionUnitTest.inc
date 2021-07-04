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

function test_same_option_key_double_quoted_string() {
	$delete = delete_option( "option_{$id}" );
	add_option( "option_{$id}", [ 'stuff', '123' ] ); // Warning.
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

function test_concurrent_option_writes_with_if() {
	$test = delete_option( 'test_option' );
	if ( $test ) {
		add_option( 'test_option', [] ); // Warning.
	}
	$test = add_option( 'test_option', [ 1, 2, 3 ] ); // Warning.
}

function test_different_quotes() {
	delete_option( 'foobar' );
	add_option( "foobar", 'baz' ); // Warning.
}

function test_spacing_inside_option_name() {
	delete_option( 'foobar' );
	add_option( '     foobar   ', 'baz' ); // Warning.
}

function test_concat_same_string_var() {
	delete_option( 'foo_' . $bar );
	add_option( 'foo_' . $bar, 'baz' ); // Warning.
}

function test_concat_different_string_var() {
	delete_option( 'foo_' . $ba );
	add_option( 'foo_' . $bar, 'baz' ); // OK - Not same option name.
}

function test_inside_if_concat_option_name( $test ) {
	$test = delete_option( "foo_" . $bar );
	if ( $test ) {
		add_option( 'foo_' . $bar, [ 1, 2, 3 ] ); // Warning.
	}
}

function test_long_concat() {
	delete_option( 'foo_' . $bar . '_' . $id );
	add_option( "foo_" . $bar . '_' . $id, 'baz' ); // Warning.
}

function test_long_concat_inside_if() {
	$test = delete_option( 'foo_' . $bar . '_' . $id );
	if ( $test ) {
		$option_added = add_option( "foo_" . $bar . '_' . $id, 'baz' ); // Warning.
	}
}
