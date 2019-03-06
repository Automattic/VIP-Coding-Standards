<?php

// WordPress.XSS.EscapeOutput
echo $hello; // Error.

// WordPress.CSRF.NonceVerification
function bar() {
	if ( ! isset( $_POST['test'] ) ) { // Error.
		return;
	}
}

?>
<!-- WordPress.WP.EnqueuedResources -->
<script src="http://someurl/somefile.js"></script> <!-- Error. -->
<?php
// WordPress.WP.PreparedSQL
$wpdb->query( "SELECT * FROM $wpdb->posts WHERE post_title LIKE '" . $_GET['title'] . "';" ); // Error + Warning.

// WordPress.Variables.GlobalVariables
function foo() {
	global $page; // WordPressVIPMinimum.Variables.VariableAnalysis.UnusedVariable // Warning.
	$page = get_post( $post_id ); // WordPressVIPMinimum.Variables.VariableAnalysis.UndefinedVariable // Error + Warning.
}

// WordPress.PHP.StrictComparisons
if ( true == $true ) { // Warning.
}

// WordPress.PHP.YodaConditions
if ( $true === true ) { // Ok.
}

// WordPress.PHP.StrictInArray
if ( true === in_array( $foo, $bar ) ) { // Warning.
}

// WordPress.Functions.DontExtract
extract( $foobar ); // Error.

// WordPress.PHP.DevelopmentFunctions
error_log( 'Hey there!' ); // Error.
var_export( $foo ); // Warning.
var_dump( $bar ); // Warning.

// WordPress.PHP.DiscouragedPHPFunctions
error_reporting(); // Error.
ini_set(); // Error.

// Generic.NamingConventions.ConstructorName
class TestClass extends MyClass {
    function TestClass() { // Error.
        parent::MyClass(); // Error.
        parent::__construct();
    }
}

?>
<!-- Generic.PHP.DisallowShortOpenTag -->
<?= $foo ?> <!-- Error. -->

<?php
// WordPress.WP.AlternativeFunctions
curl_init(); // Warning.

// WordPressVIPMinimum.Hooks.PreGetPosts
add_action( 'pre_get_posts', function( $wp_query ) {
	if ( ! $wp_query->is_search() ) {
		$wp_query->set( 'cat', '-5' ); // Warning.
	}
} );

// WordPressVIPMinimum.Cache.CacheValueOverride
$bad_wp_users = wp_cache_get( md5( self::CACHE_KEY . '_wp_users'), self::CACHE_GROUP );
$bad_wp_users = false; // Error.

// WordPressVIPMinimum.Classes.DeclarationCompatibility
class MyWidget extends WP_Widget {
	function widget() {
	} // Error (line above), missing $args and $instance params.
}

// WordPressVIPMinimum.Constants.RestrictedConstants
if ( A8C_PROXIED_REQUEST === true ) { // Warning.
}

// WordPressVIPMinimum.Files.IncludingFile
require_once "my_file.php"; // Error.

// WordPressVIPMinimum.Functions.CheckReturnValue
$my_theme_options = get_option( 'my_theme', false );
if ( array_key_exists( 'key', $my_theme_options ) ) { // Error.
}

// Generic.PHP>NoSilencedErrors + WordPressVIPMinimum.Functions.RestrictedFunctions.file_get_contents_file_get_contents
@file_get_contents( $foo ); // Error + Warning.

// WordPressVIPMinimum.Performance.RegexpCompare + WordPress.DB.SlowDBQuery
$query_args = array(
	'posts_per_page' => 1,
	'post_status' => 'draft',
	'meta_key' => 'my_awesome_meta_key', // WordPress.DB.SlowDBQuery. // Warning.
	'meta_value' => "(^|\n|\r\n)99999($|\n|\r\n)", // WordPress.DB.SlowDBQuery. // Warning.
	'meta_compare' => 'REGEXP', // Error.
);

// WordPressVIPMinimum.Performance.RemoteRequestTimeout
wp_remote_post( $this->endpoint, array(
        'method'      => 'POST',
        'timeout'     => 45, // Error.
        'httpversion' => '1.1',
        'blocking'    => false,
        'body'        => wp_json_encode( $this->logs, JSON_UNESCAPED_SLASHES ),
    )
);

// Squiz.PHP.Eval
eval( ';' ); // Error.

// WordPressVIPMinimum.Functions.RestrictedFunctions
wpcom_vip_irc(); // Error.

get_children(); // Error + Message.

attachment_url_to_postid(); // Error.

str_replace( 'foo', 'bar', 'foobar' ); // Error.

wpcom_vip_get_term_link(); // Warning.

wpcom_vip_get_term_by(); // Warning.

wpcom_vip_get_category_by_slug(); // Warning.

get_cat_ID(); // Ok.

get_category_link(); // Ok.

get_tag_link(); // Ok.

get_category_by_slug(); // Ok.

get_term_by(); // Ok.

get_term_link(); // Ok.

wp_mail(); // Warning.

mail(); // Warning.

dbDelta(); // Error.

flush_rewrite_rules(); // Error.
global $wp_rewrite;
$wp_rewrite->flush_rules(); // Error.

// WordPress.CodeAnalysis.AssignmentInCondition.
if ( $a = 1 ) {} // Warning.

add_option( 'taxonomy_rating_' . $obj->term_id ); // Warning.

//wpcom_vip_load_plugin( 'disqus' ); // Warning.

echo "<a href='" . esc_attr( $some_var ) . "'></a>"; // Error.

$hello = true === isset( $_GET['utm_medium'] ) ? true : false; // Warning.

wp_safe_redirect( 'https.//vip.wordpress.com' ); // Error.

is_multi_author(); // Warning.

include( 'non-php-file.svg' ); // Error. Including non-php file.

echo file_get_contents( 'non-php-file.svg' ); // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped -- Preferred way of including SVG/CSS file.

// phpcs:set WordPressVIPMinimum.VIP.CronInterval min_interval 600
add_filter( 'cron_schedules', 'my_add_weekly' ); // Error. 6 min.
// phpcs:set WordPressVIPMinimum.VIP.CronInterval min_interval 900

thisisasyntaxerror! // Error.

// WordPressVIPMinimum.Functions.RestrictedFunctions.site_option_add_site_option
add_site_option( 'foo', $bar ); // Error.

// WordPressVIPMinimum.Functions.RestrictedFunctions.site_option_update_site_option
update_site_option( $bar, $foo, true ); // Error.

// WordPressVIPMinimum.Functions.RestrictedFunctions.site_option_delete_site_option
delete_site_option( $foo ); // Error.

// WordPressVIPMinimum.Security.EscapingVoidReturnFunctions.Found
esc_js( _deprecated_argument() ); // Error.
esc_js( _deprecated_constructor() ); // Error.
esc_js( _deprecated_file() ); // Error.
esc_js( _deprecated_function() ); // Error.
esc_js( _deprecated_hook() ); // Error.
esc_js( _doing_it_wrong() ); // Error.
esc_html( _e( 'foo', 'bar' ) ); // Error.
esc_html( _ex( 'foo', 'bar' ) ); // Error.
esc_attr( printf( 'foo', [] ) ); // Error.
esc_attr( trigger_error( 'foo' ) ); // Error (+ warning due to trigger_error() call).
esc_attr( user_error( 'foo', '' ) ); // Error.
esc_attr( vprintf( 'foo', [] ) ); // Error.
esc_attr( wp_die( 'foo' ) ); // Error.
esc_attr( wp_dropdown_pages() ); // Error.

?> <!-- closing PHP tag should be omitted -->
