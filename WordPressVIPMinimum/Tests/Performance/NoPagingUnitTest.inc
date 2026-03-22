<?php

// === nopaging tests ===

$args = array(
	'nopaging'       => true, // Bad.
);

_query_posts( 'nopaging=true' ); // Bad.

$query_args['my_posts_per_page'] = -1; // OK - not a recognized key.

// Verify handling with no trailing comma at end of array.
$args = array(
	'nopaging' => true // Bad.
);
$args = [ 'nopaging' => true ]; // Bad.

// nopaging with integer 1.
$args = [ 'nopaging' => 1 ]; // Bad.

// nopaging with false - should not trigger.
$args = [ 'nopaging' => false ]; // OK.

// nopaging with integer 0 - should not trigger.
$args = [ 'nopaging' => 0 ]; // OK.

// nopaging via variable assignment.
$args['nopaging'] = true; // Bad.

// nopaging via variable assignment with false.
$args['nopaging'] = false; // OK.

// === posts_per_page tests ===

// posts_per_page set to -1 in long array syntax.
$args = array(
	'posts_per_page' => -1, // Bad.
);

// posts_per_page set to -1 in short array syntax.
$args = [ 'posts_per_page' => -1 ]; // Bad.

// posts_per_page set to -1 as string.
$args = [ 'posts_per_page' => '-1' ]; // Bad.

// posts_per_page set to a normal value - should not trigger.
$args = [ 'posts_per_page' => 50 ]; // OK.

// posts_per_page set to 1 - should not trigger.
$args = [ 'posts_per_page' => 1 ]; // OK.

// numberposts set to -1.
$args = [ 'numberposts' => -1 ]; // Bad.

// numberposts set to a normal value - should not trigger.
$args = [ 'numberposts' => 10 ]; // OK.

// posts_per_page via variable assignment.
$args['posts_per_page'] = -1; // Bad.

// posts_per_page via variable assignment with normal value.
$args['posts_per_page'] = 10; // OK.

// Query string with posts_per_page=-1.
_query_posts( 'posts_per_page=-1&orderby=date' ); // Bad.

// Query string with posts_per_page set to a normal value.
_query_posts( 'posts_per_page=50' ); // OK.

// Query string with numberposts=-1.
_query_posts( 'numberposts=-1' ); // Bad.

// === Ignoring with phpcs:ignore comments ===

// phpcs:ignore WordPressVIPMinimum.Performance.NoPaging.posts_per_page_posts_per_page -- Intentional: fetching all items for export.
$args = [ 'posts_per_page' => -1 ]; // OK - ignored.

$args = [
	// phpcs:ignore WordPressVIPMinimum.Performance.NoPaging.posts_per_page_numberposts -- Intentional: legacy API requires all results.
	'numberposts' => -1, // OK - ignored.
];

// phpcs:ignore WordPressVIPMinimum.Performance.NoPaging.nopaging_nopaging -- Intentional: sitemap generation.
$args = [ 'nopaging' => true ]; // OK - ignored.
