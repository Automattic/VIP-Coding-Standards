<?php

add_filter( 'test_filter', 'good_example_function' ); 	// this one shouldn't trigger anything

add_filter( 'upload_mimes', 'bad_example_function' );