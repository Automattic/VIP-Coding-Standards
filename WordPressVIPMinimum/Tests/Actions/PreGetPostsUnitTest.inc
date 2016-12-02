<?php

function myprefix_function( WP_Query $wp_query ) {

	if ( ( true === $wp_query->is_main_query() ) ) {
		$wp_query->set( 'cat', '-5' );
	} else if ( $wp_query->is_search() ) {
		$wp_query->set( 'cat', '-5' );
	}

	$wp_query->set( 'cat', '-5' );

	if ( ! $wp_query->is_main_query() ) {
		return;
	}

	if ( ( true === $wp_query->is_main_query() ) ) {
		$wp_query->set( 'cat', '-5' );
	} else if ( $wp_query->is_search() ) {
		$wp_query->set( 'cat', '-5' );
	}
}

add_action( "pre_get_posts", 'myprefix_function' );

add_action( 'pre_get_posts', function( $wp_query ) {

	if ( ! $wp_query->is_search() ) {
		$wp_query->set( 'cat', '-5' );
	}

	$wp_query->set( 'cat', '-5' );

	if ( ( ! $wp_query->is_main_query() ) ) {
		return;
	}

	if ( ! $wp_query->is_search() ) {
		$wp_query->set( 'cat', '-5' );
	}

} );

class a {

	public function __construct() {
		add_action( 'pre_get_posts', array( self, 'pre_get_posts' ) );
	}

	public function pre_get_posts( $wp_query ) {

		$wp_query->set( 'cat', '-5' );

		if ( $wp_query->is_main_query() ) {
			$wp_query->set( 'cat', '-5' );
		} else if ( $wp_query->is_search() ) {
			$wp_query->set( 'cat', '-5' );
		}

		if ( ( ! $wp_query->is_main_query() ) ) {
			return;
		}

		$wp_query->set( 'cat', '-5' );

		if ( $wp_query->is_main_query() ) {
			$wp_query->set( 'cat', '-5' );
		} else if ( $wp_query->is_search() ) {
			$wp_query->set( 'cat', '-5' );
		}
	}
}

add_action( 'pre_get_posts', function( $wp_query ) {

	if ( $wp_query->is_main_query() ) {
		if ( 0 === 1) {
			if ( 1 === 0 ) {
				$wp_query->set( 'cat', '-5' ); // do not report on this
			}
		}
	}

	if ( $wp_query->is_search() ) {
		if ( 0 === 1) {
			if ( 1 === 0 ) {
				$wp_query->set( 'cat', '-5' ); //report on this
			}
		}
	}

} );
