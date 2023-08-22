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

get_posts( [ 'exclude' => $post_ids ] ); // Warning.

$exclude = [ 1, 2, 3 ];

// Issue #672 / #729.
get_users( [ 'exclude' => $post_ids ] ); // OK.
get_users( My\get_args( [ 'exclude' => $post_ids ] ) ); // OK - arbitrary as the call to `My\get_args()` on its own would be flagged, but let's allow it.

$context_unknown = [ 'exclude' => $post_ids ]; // Warning.
other_fn_calls_still_throw_warning( [ 'exclude' => $post_ids ] ); // Warning.
get_users( [ 'suppress_filters' => true ] ); // Error - not necessarily valid, but the exception being made is specifically about `exclude`.
