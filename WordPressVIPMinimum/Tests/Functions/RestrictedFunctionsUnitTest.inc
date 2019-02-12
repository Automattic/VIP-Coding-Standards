<?php

$test_script = '$test_script.php';
$test_string = '<i>Random string</i>';
$test = 'Test123';
$int = 123;
$allowed_html = [
    'a' => [
        'href' => [],
        'title' => [],
    ],
    'br' => [],
    'em' => [],
    'strong' => [],
];
$url = 'http://www.google.ca';

wp_cache_get_mult(); // Ok - similarly-named function to wp_cache_get_multi().
wp_cache_get_multi(); // Error.

opcache_resets(); // Ok - similarly-named custom function to opcache_reset().
opcach_invalidate ( $test_script ); // Ok - similarly-named custom function to opcache_invalidate().
opcache_compil_file(); // Ok - similarly-named custom function to opcache_compile_file().
okcache_is_​script_​cached( 'test_script.php' ); // Ok - similarly-named custom function to opcache_is_script_cached().
foo_opcache_get_status( $test_script ); // Ok - similarly-named custom function to opcache_get_status().
opcache_get_config( $test_script ); // Ok - similary-named custom function to opcache_get_configuration().
opcache_reset(); // Error.
opcache_invalidate( 'test_script.php' ); // Error - one parameter.
opcache_invalidate( $test_script, true ); // Error - two parameters.
opcache_compile_file( $test_script ); // Error - one parameter.
opcache_​is_​script_​cached( 'test_script.php' ); // Error - one parameter.
opcache_​get_​status(); // Error.
opcache_​get_​status( false ); // Error.
opcache_​get_​configuration(); // Error.

get_super_admin(); // Ok - similarly-named function to get_super_admins().
get_super_admins(); // Error.

vip_irc(); // Ok - similarly-named function to wpcom_vip_irc().
wpcom_vip_irc(); // Error.

flush_rewrite_rule(); // Ok - similarly-named function to flush_rewrite_rules().
flush_rewrite_rules(); // Error.

wpcom_vip_attachment_url_to_postid( $url ); // Ok - VIP recommended version of attachment_url_to_postid().
attachment_url_to_postid( $url ); // Error.

flush_rules(); // Ok - not in global class.
$wp_write->flush_rules(); // Ok - not in global class $wp_rewrite.
$wp_rewrite->flush_rules(); // Error.

db_delta(); // Ok - similarly-named function to dbDelta().
dbDelta(); // Error.

switch_blog(); // Ok - similarly-named function to switch_to_blog().
switch_to_blog( $blogid ); // Error.

wpcom_vip_get_page_by_title(); // Ok - VIP recommended version of get_page_by_title().
get_page_by_title( $page_title ); // Error.

wpcom_vip_url_to_postid( $url ); // Ok - VIP recommended version of url_to_postid().
url_to_postid( $url ); // Error.

wpcom_vip_add_role(); // Ok - VIP recommended version of add_role().
class Foo {
	function add_role() {}
}
$x = new Foo();
$x->add_role(); // Ok - calling an instantiated method of another class and not actual WP add_role().
class Bar {
	static function add_role() {}
}
$y = Bar::add_role(); // Ok - calling static function of another class and not actual WP add_role().
\SomeNamespace\add_role(); // Ok - calling namespaced function and not actual WP add_role().
add_role( 'test' ); // Error.
\add_role(); // Error.

get_post_meta( 123, 'test' ); // Ok - not using get_user_meta().
update_post_meta( 1234, 'test', $test ); // Ok - not using update_user_meta().
delete_post_meta( $int, $test ); // Ok - not using delete_user_meta().
add_post_meta( $int, $test, $test ); // Ok - not using add_user_meta().
get_user_meta(); // Error.
update_user_meta(); // Error.
delete_user_meta(); // Error.
add_user_meta(); // Error.

wpcom_vip_term_exists(); // Ok - VIP recommended version of term_exists().
term_exists(); // Error.

wpcom_vip_count_user_posts(); // Ok - VIP recommended version of count_user_posts().
count_user_posts(); // Error.

wpcom_vip_old_slug_redirect(); // Ok - VIP recommended version of wp_old_slug_redirect().
wp_old_slug_redirect(); // Error.

wpcom_vip_get_adjacent_post(); // Ok - VIP recommended version of get_adjacent_post(), get_previous_post(), get_previous_post_link(), get_next_post() and get_next_post_link().
get_adjacent_post(); // Error.
get_previous_post(); // Error.
get_next_post(); // Error.
get_previous_post_link(); // Error.
get_next_post_link(); // Error.

get_intermediate_images(); // Ok - similarly-named function to get_intermediate_image_sizes().
get_intermediate_image_sizes(); // Error.

jetpack_is_mobile(); // Ok - VIP recommended version of wp_is_mobile().
wp_is_mobile(); // Error.

send_mail(); // Ok - similarly-named function to wp_mail() and mail().
wp_mail(); // Warning.
mail(); // Warning.

multi_author(); // Ok - similarly-named function to is_multi_author().
is_multi_author(); // Warning.

echo esc_html( get_the_field( 'field' ) ); // Ok - ACF echoing and escape via get_* equivalent.
echo '<img src="test.jpg" alt="' . esc_attr( get_sub_field( 'field' ) ) . '">'; // Ok - ACF echoing and escape via get_* equivalent.
the_field( 'field' ); // Warning - ACF unescaped template tag.
the_sub_field( 'field' ); // Warning - ACF unescaped template tag.

vip_safe_wp_remote_get(); // Ok - VIP recommended version of wp_remote_get().
wp_remote_get( $url ); // Warning.

cookie( $_GET['test'] ); // Ok - similarly-named function to setcookie().
setcookie( 'cookie[three]', 'cookiethree' ); // Warning.

get_post( 123 ); // Ok - not using get_posts().
wp_get_recent_post(); // Ok - similarly-named function to wp_get_recent_posts().
get_child(); // Ok - similarly-named function to get_children().
get_posts(); // Warning.
wp_get_recent_posts(); // Warning.
get_children(); // Warning.

get_term_link(); // Ok - non-deprecated version of wpcom_vip_get_term_link().
get_term_by(); // Ok - non-deprecated version of wpcom_vip_get_term_by().
get_category_by_slug(); // Ok - non-deprecated version of wpcom_vip_get_category_by_slug().
wpcom_vip_get_category_by_slug(); // Warning - deprecated.
wpcom_vip_get_term_by(); // Warning - deprecated.
wpcom_vip_get_term_link(); // Warning - deprecated.

session_abort(); // Error.
session_cache_expire(); // Error.
session_cache_limiter(); // Error.
session_commit(); // Error.
session_create_id(); // Error.
session_decode(); // Error.
session_destroy(); // Error.
session_encode(); // Error.
session_gc(); // Error.
session_get_cookie_params(); // Error.
session_id(); // Error.
session_is_registered(); // Error.
session_module_name(); // Error.
session_name(); // Error.
session_regenerate_id(); // Error.
session_register_shutdown(); // Error.
session_register(); // Error.
session_reset(); // Error.
session_save_path(); // Error.
session_set_cookie_params(); // Error.
session_set_save_handler(); // Error.
session_start(); // Error.
session_status(); // Error.
session_unregister(); // Error.
session_unset(); // Error.
session_write_close(); // Error.

file_put_contents( $file, $text, FILE_APPEND ); // Error.

$fp = fopen( $file, 'a+' ); // Ok.
stream_set_blocking( $fp, 0 ); // Ok.

while ( $count > $loop ) {
	if ( flock( $fp, LOCK_EX ) ) { // Error.
		fwrite( $fp, $text ); // Error.
	}
	flock( $fp, LOCK_UN ); // Error.
}

fclose( $fp ); // Ok.

delete(); // Error.
fputcsv(); // Error.
fputs(); // Error.
ftruncate(); // Error.
is_writable(); // Error.
is_writeable(); // Error.
link(); // Error.
rename(); // Error.
symlink(); // Error.
tempnam(); // Error.
touch(); // Error.
unlink(); // Error.
mkdir(); // Error.
rmdir(); // Error.
chgrp(); // Error.
chown(); // Error.
chmod(); // Error.
lchgrp(); // Error.
lchown(); // Error.

use Fieldmanager_Link as Link; // Ok.
namespace Fieldmanager\Link\Something; // Ok.
namespace Fieldmanager\Link; // Ok.

$a = time();
$b = time() * 2;
$wp_random_testing = create_function( '$a, $b', 'return ( $b / $a ); '); // Warning.

class Foo {
    public create_function = 'foo'; // Ok.
}
$c = new Foo();
$c->create_function = 'bar' . ( 1 === 1 ?? 'foo' ); // Ok.
$wp_random_testing = create_function2( '$a, $b', 'return ( $b / $a ); '); // Ok.
$wp_random_testing = create_function3( '$a, $b', 'return ( $b / $a ); '); // Ok.

add_site_option( $foo, 'bar' ); // Warning.
add_option( $foo, 'bar' ); // Ok.
update_site_option(  $bar, 'foo' ); // Warning.
update_option( 'foo', $bar ); // Ok.
delete_site_option( $foo   ); // Warning.
delete_option(  $bar ); // Ok.
