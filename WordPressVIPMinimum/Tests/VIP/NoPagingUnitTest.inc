<?php

$args = array(
	'nopaging'       => true, // Bad.
);

_query_posts( 'nopaging=true' ); // Bad.

$query_args['my_posts_per_page'] = -1; // OK.
