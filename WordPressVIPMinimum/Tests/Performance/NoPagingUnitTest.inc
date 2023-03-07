<?php

$args = array(
	'nopaging'       => true, // Bad.
);

_query_posts( 'nopaging=true' ); // Bad.

$query_args['my_posts_per_page'] = -1; // OK.

// Verify handling with no trailing comma at end of array.
$args = array(
	'nopaging' => true // Bad.
);
$args = [ 'nopaging' => true ]; // Bad.
