<?php

$query_args = array(
	'posts_per_page' => 10,
	'post_status' => 'publish',
	'meta_query' => array(
		array(
			'key' => 'my_post_meta_key',
			'value' => "(^|\n|\r\n)99999($|\n|\r\n)",
			'compare' => 'REGEXP', // Bad.
		),
		array(
			'key' => 'my_other_post_meta_key',
			'value' => "(^|\n|\r\n)99999($|\n|\r\n)",
			'compare' => 'NOT REGEXP', // Bad.
		),
		array(
			'key' => 'my_last_post_meta_key',
			'value' => '%word',
			'compare' => 'NOT LIKE', // OK.
		),
	),
);

$query_args = array(
	'posts_per_page' => 1,
	'post_status' => 'draft',
	'meta_key' => 'my_awesome_meta_key',
	'meta_value' => "(^|\n|\r\n)99999($|\n|\r\n)",
	'meta_compare' => 'REGEXP', // Bad.
);

$query_args = array(
	'meta_compare' => 'NOT REGEXP', // Bad.
	'meta_value' => '(^|\n|\r\n)99999($|\n|\r\n)',
	'meta_key' => 'my_awesome_meta_key'
);

$query_args = array(
	'meta_compare' => 'LIKE', // Ok.
	'meta_value' => '%word',
	'meta_key' => 'my_awesome_meta_key',
);
