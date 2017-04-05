<?php

/**
 * A function
 *
 * @return void
 */
function do_something_with_terms() {
	$terms = get_the_terms( get_post(), 'post_tag' );
	// VIP: hotfix, check return value.
	if ( true === is_array( $terms ) && false === empty( $terms ) ) {
		// Do something meaningful.
	}
}

// Some arbitrary comment.

/**
 * A function
 *
 * @uses wpcom_vip_get_category_by_slug
 */
fuction use_wpcom_vip_get_category_by_slug() {
	wpcom_vip_get_category_by_slug( 'my-slug' );
}

/*
 * VIP: Hotfixing something
 */
function vip_hotfix_something() {
	// do something
}