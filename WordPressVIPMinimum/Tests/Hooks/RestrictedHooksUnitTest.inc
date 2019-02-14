<?php

add_filter( 'upload_mime', 'good_example_function' ); // Ok.
add_filter( 'upload_mimesX', 'good_example_function' ); // Ok.

// Warnings.
add_filter( 'upload_mimes', 'bad_example_function' ); // Simple string.
add_filter('upload_mimes' ,'bad_example_function'); // Incorrect spacing.
add_filter(  'upload_mimes','bad_example_function'); // Incorrect spacing.
add_filter( "upload_mimes" ,'bad_example_function'); // Double quotes.
add_filter( 'upLoad_mimeS' ,'bad_example_function'); // Uppercase characters.
add_filter( 'upload_' . 'mimes' ,'bad_example_function'); // Single concatenation.
add_filter( 'upl' . 'oad_' . 'mimes' ,'bad_example_function'); // Multiple concatenation.
add_filter( "upload_" . 'mimes' ,'bad_example_function'); // Single concatenation with double and single quotes.
add_filter( 'upl' . "oad_" . "mimes" ,'bad_example_function'); // Multiple concatenation with double and single quotes.
add_filter( 'upload_mimes', function() { // Anonymous callback.
	// Do stuff.
});
add_action( 'upload_mimes', 'bad_example_function' ); // Check `add_action()`, which is an alias for `add_filter()`.
add_filter( 'http_request_timeout', 'bad_example_function' ); // Simple string.
add_filter('http_request_args', 'bad_example_function' ); // Simple string + incorrect spacing.
add_action( 'do_robotstxt', 'my_do_robotstxt'); // Simple string.
add_filter( 'robots_txt', function() { // Anonymous callback.
} );
