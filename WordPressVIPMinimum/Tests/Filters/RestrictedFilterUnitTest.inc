<?php

add_filter( 'test_filter', 'good_example_function' ); // Ok.

// warnings.
add_filter( 'upload_mimes', 'bad_example_function' );
add_filter('upload_mimes' ,'bad_example_function');
add_filter(  'upload_mimes','bad_example_function');
add_filter( "upload_mimes" ,'bad_example_function');
add_filter( 'upLoad_mimeS' ,'bad_example_function');
add_filter( 'upload_' . 'mimes' ,'bad_example_function');
add_filter( 'upl' . 'oad_' . 'mimes' ,'bad_example_function');
add_filter( 'upload_mimes', function() {
	// do stuff.	
});
