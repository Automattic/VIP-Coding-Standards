<?php

// WordPress.XSS.EscapeOutput
echo $hello; // Bad.

// WordPress.CSRF.NonceVerification
// WordPress.VIP.SuperGlobalInputUsage.AccessDetected
function bar() {
	if ( ! isset( $_POST['test'] ) ) { // Bad. 1 error + 1 warning.
		return;
	}
}

?>
<!-- WordPress.WP.EnqueuedResources -->
<script src="http://someurl/somefile.js"></script> <!-- bad -->
<?php
// WordPress.WP.PreparedSQL
$wpdb->query( "SELECT * FROM $wpdb->posts WHERE post_title LIKE '" . $_GET['title'] . "';" ); // Bad. Error + Warning.

// WordPress.Variables.GlobalVariables
function foo() {
	global $page; // WordPressVIPMinimum.Variables.VariableAnalysis.UnusedVariable // Bad. Warning.
	$page = get_post( $post_id ); // Bad. // WordPressVIPMinimum.Variables.VariableAnalysis.UndefinedVariable Bad. Warning.
}

// WordPress.PHP.StrictComparisons
if ( true == $true ) { // Bad. Warning.
}

// WordPress.PHP.YodaConditions
if ( $true === true ) { // Ok.
}

// WordPress.PHP.StrictInArray
if ( true === in_array( $foo, $bar ) ) { // Bad. Warning.
}

// WordPress.Functions.DontExtract
extract( $foobar ); // Bad.

// WordPress.PHP.DevelopmentFunctions
error_log( 'Hey there!' ); // Bad. Error.
var_export( $foo ); // Bad. Warning.
var_dump( $bar ); // Bad.

// WordPress.PHP.DiscouragedPHPFunctions
error_reporting(); // Bad.
ini_set(); // Bad.

// Generic.NamingConventions.ConstructorName
class TestClass extends MyClass {
    function TestClass() { // Bad.
        parent::MyClass(); // Bad.
        parent::__construct();
    }
}

?>
<!-- Generic.PHP.DisallowShortOpenTag -->
<?= $foo ?> <!-- Bad. -->

<?php
// WordPress.WP.AlternativeFunctions
curl_init(); // Bad. Warning.

// WordPressVIPMinimum.Actions.PreGetPosts
add_action( 'pre_get_posts', function( $wp_query ) {
	if ( ! $wp_query->is_search() ) {
		$wp_query->set( 'cat', '-5' ); // Bad. Warning.
	}
} );

// WordPressVIPMinimum.Cache.CacheValueOverride
$bad_wp_users = wp_cache_get( md5( self::CACHE_KEY . '_wp_users'), self::CACHE_GROUP );
$bad_wp_users = false; // Bad.

// WordPressVIPMinimum.Classes.DeclarationCompatibility
class MyWidget extends WP_Widget {
	function widget() {
	} // Bad, missing $args and $instance params.
}

// WordPressVIPMinimum.Constants.ConstantRestictions
if ( A8C_PROXIED_REQUEST === true ) { // Bad. Warning.
}

// WordPressVIPMinimum.Files.IncludingFile
require_once "my_file.php"; // NOK.

// WordPressVIPMinimum.Functions.CheckReturnValue
$my_theme_options = get_option( 'my_theme', false );
if ( array_key_exists( 'key', $my_theme_options ) ) { // Bad.
}

// WordPressVIPMinimum.VIP.ErrorControl + WordPressVIPMinimum.VIP.RestrictedFunctions.file_get_contents_file_get_contents
@file_get_contents( $foo ); // Error + Warning.

// WordPressVIPMinimum.VIP.FlushRewriteRules
global $wp_rewrite;
$wp_rewrite->flush_rules(); // Bad.
flush_rewrite_rules(); // Bad.

// WordPressVIPMinimum.VIP.RegexpCompare + WordPress.VIP.SlowDBQuery
$query_args = array(
	'posts_per_page' => 1,
	'post_status' => 'draft',
	'meta_key' => 'my_awesome_meta_key', // Bad. WordPress.VIP.SlowDBQuery. Warning.
	'meta_value' => "(^|\n|\r\n)99999($|\n|\r\n)", // Bad. WordPress.VIP.SlowDBQuery. Warning.
	'meta_compare' => 'REGEXP', // Bad.
);

// WordPressVIPMinimum.VIP.RemoteRequestTimeout
wp_remote_post( $this->endpoint, array(
        'method'      => 'POST',
        'timeout'     => 45, // Bad.
        'httpversion' => '1.1',
        'blocking'    => false,
        'body'        => wp_json_encode( $this->logs, JSON_UNESCAPED_SLASHES ),
    )
);

// Squiz.PHP.Eval
eval( ';' ); // Bad. Error.

// WordPressVIPMinimum.VIP.RestrictedFunctions
wpcom_vip_irc(); // Bad. Error.

get_children(); // Bad. Warning. Message.

attachment_url_to_postid(); // Bad. Error.

str_replace( 'foo', 'bar', 'foobar' ); // Bad. Error.

wpcom_vip_get_term_link(); // Bad. Warning.

wpcom_vip_get_term_by(); // Bad. Warning.

wpcom_vip_get_category_by_slug(); // Bad. Warning.

get_cat_ID(); // Ok.

get_category_link(); // Ok.

get_tag_link(); // Ok.

get_category_by_slug(); // Ok.

get_term_by(); // Ok.

get_term_link(); // Ok.

wp_mail(); // Bad. Warning.

mail(); // Bad. Warning.

dbDelta(); // Bad. Error.

// WordPress.CodeAnalysis.AssignmentInCondition.
if ( $a = 1 ) {} // Bad. Warning.

add_option( 'taxonomy_rating_' . $obj->term_id ); // Bad. Warning.

//wpcom_vip_load_plugin( 'disqus' ); // Bad. Warning.

echo "<a href='" . esc_attr( $some_var ) . "'></a>"; // NOK. Error.

$hello = true === isset( $_GET['utm_medium'] ) ? true : false; // NOK. Warning 3 times.

?>

