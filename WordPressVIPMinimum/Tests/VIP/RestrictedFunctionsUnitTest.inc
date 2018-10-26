<?php

wpcom_vip_irc(); // Bad.

get_children(); // Bad. Warning.

wp_cache_get_multi(); // Bad.

get_super_admins(); // Bad.

flush_rewrite_rules(); // Bad.

attachment_url_to_postid(); // Bad.

wpcom_vip_attachment_url_to_postid(); // OK.

get_term_link(); // Ok.

get_term_by(); // Ok.

get_category_by_slug(); // Ok.

get_tag_link(); // Ok.

get_category_link(); // Ok.

get_cat_ID(); // Ok.

wpcom_vip_get_category_by_slug(); // Bad. Deprecated.

wpcom_vip_get_term_by(); // Bad. Deprecated.

wpcom_vip_get_term_link(); // Bad. Deprecated.

wp_mail(); // Bad. Warning.

mail(); // Bad. Warning.

dbDelta(); // Bad. Warning.

is_multi_author(); // Bad. Warning.

the_field( 'field' ); // Warning - ACF.
the_sub_field( 'field' ); // Warning - ACF.

echo esc_html( get_the_field( 'field' ) ); // Ok - ACF.
echo '<img src="test.jpg" alt="' . esc_attr( get_sub_field( 'field' ) ) . '">'; // Ok - ACF.

$test_script = '$test_script.php';

opcache_reset(); // Bad.
opcache_invalidate( 'test_script.php' ); // Bad.
opcache_invalidate( $test_script, true ); // Bad.
opcache_compile_file( $test_script ); // Bad.
opcache_​is_​script_​cached( 'test_script.php' ); // Bad.
opcache_​get_​status(); // Bad.
opcache_​get_​status( false ); // Bad.
opcache_​get_​configuration(); // Bad.

opcache_resets(); // Ok, similarly-named custom function.
foo_opcache_get_status( $test_script ); // Ok, similarly-named custom function.
opcach_invalidate ( $test_script ); // Ok, similarly-named custom function.
okcache_is_​script_​cached( 'test_script.php' ); // Ok, similarly-named custom function.
