<?php

$my_theme_options = get_option( 'my_theme', false );

if ( array_key_exists( 'key', $my_theme_options ) ) { // Bad.
}

$theme_settings = get_option( 'my_theme_categories_settings' );
if ( ! array_key_exists( $key, $theme_settings ) || empty( $theme_settings[ $key ] ) ) { // Bad.
}

$my_url = wpcom_vip_get_term_link();

echo '<a href="' . esc_url( $my_url ) . '">My Link</a>'; // Okay, deprecated.

echo '<a href="' . esc_url( get_term_link( $link ) ) . '">My term link</a>'; // Bad.

$tags = get_the_tags( $post_id );
$tags = wp_list_pluck( $tags, 'term_id' ); // Bad.

$single_post_meta = get_post_meta( $post_id, 'my_meta', true );

foreach( $single_post_meta as $meta ) {} // Bad.

$array_post_metas = get_post_meta( $post_id, 'my_non_single_meta', false ); // We are obtaining an array here.

foreach( $array_post_metas as $meta ) {} // Ok.
