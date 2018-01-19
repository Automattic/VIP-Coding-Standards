<?php

get_posts( [
	'post__not_in' => array( 1, 2, 3 ),
	'suppress_filters' => true,
] );

$posts_not_in = [ 3, 4, 5 ];

$query_args = array(
	'post__not_in' => $posts_not_in,
	'suppress_filters' => false,
);

$q = new WP_Query( $query_args );

$query_args[ 'suppress_filters' ] = true;

$q = new WP_query( $query_args );