


<?php // Error - Squiz.WhiteSpace.SuperfluousWhitespace.

// Generic.PHP.Syntax
while { // Error.
}

// Generic.PHP.NoSilencedErrors
if (@in_array( $array, $needle, true )) { // Error.
    echo '@';
}

// WordPress.Security.EscapeOutput
echo $hello; // Error.
echo apply_filters( 'the_title', $title ); // Error.

// WordPress.Security.NonceVerification
function bar() {
	if ( ! isset( $_POST['test'] ) ) { // Error.
	}
}

// WordPress.Security.ValidatedSanitizedInput
if ( isset( $_POST['nonce'] ) && wp_verify_nonce( sanitize_text_field( $_POST['nonce'] ) ) ) {
	bar( $_POST['foo2'] ); // Error x 2.
	$foo2 = isset( $_POST['foo2'] ) ?? foo( sanitize_text_field( $_POST['foo2'] ) ); // Ok - exclude WordPress.Security.ValidatedSanitizedInput.MissingUnslash.
}

// WordPress.Security.PluginMenuSlug
add_menu_page( $page_title, $menu_title, $capability, __FILE__, $function, $icon_url, $position ); // Warning.

// WordPress.WP.EnqueuedResources
?> <script src="http://someurl/somefile.js"></script> <?php // Error.

// WordPress.WP.PostsPerPage
$args = array(
	'posts_per_page' => 999, // Warning.
);
_query_posts( 'posts_per_page=999' ); // Warning.
$query_args['posts_per_page'] = 999; // Warning.

// WordPress.WP.TimezoneChange
date_default_timezone_set( 'FooBar' ); // Error.

// WordPress.DB.PreparedSQL
$b = function () {
	global $wpdb;
	$listofthings = wp_cache_get( $listofthings );
	if ( ! $listofthings ) {
		$foo = "column = 'test'";
		
		$listofthings = $wpdb->query( 'SELECT something FROM somewhere WHERE ' . $foo ); // phpcs:ignore WordPress.DB.DirectDatabaseQuery.DirectQuery -- Error.
		wp_cache_set( 'foo', $listofthings );
	} 
};

// WordPress.DB.DirectDatabaseQuery
$baz = $wpdb->get_results( $wpdb->prepare( 'SELECT X FROM Y ' ) ); // Warning x 2.

// WordPress.DB.SlowDBQuery
$test = [
	'tax_query' => [], // Warning.
];
new WP_Query( array(
	'meta_query' => [], // Warning.
	'meta_key' => 'foo', // Warning.
	'meta_value' => 'bar', // Warning.
) );

// WordPress.WP.GlobalVariablesOverride
$GLOBALS['wpdb'] = 'test'; // Error.

// WordPress.PHP.StrictComparisons
if ( true == $true ) { // Warning.
}

// WordPress.CodeAnalysis.AssignmentInCondition
if ( $test = get_post( $post ) ) { // Warning.
}

// WordPress.PHP.StrictInArray
if ( true === in_array( $foo, $bar ) ) { // Warning.
}

// WordPress.Functions.DontExtract
extract( $foobar ); // Error.

// WordPress.WP.CronInterval
function my_add_weekly( $schedules ) {
	$schedules['every_6_mins'] = array(
		'interval' => 360,
		'display' => __( 'Once every 6 minutes' )
	);
	return $schedules;
}
add_filter( 'cron_schedules', 'my_add_weekly'); // Warning.

// Generic.NamingConventions.ConstructorName
class TestClass extends MyClass
{
    function __construct() {
        parent::MYCLASS(); // Error.
        parent::__construct();
    }
}
class OldClass
{
    function OldClass() // Error.
    {
    }
}

// Generic.NamingConventions.ConstructorName
class TestClass extends MyClass {
    function TestClass() { // Error.
        parent::MyClass(); // Error.
        parent::__construct();
    }
}

// Generic.PHP.DisallowShortOpenTag
?> <?= esc_html( $var ); // Error.

// Squiz.PHP.CommentedOutCode <!-- Warning. -->
// if (empty($this)) {echo 'This is will not work';} 

// Squiz.PHP.Eval
eval('$var = 4;'); // Error + Message.

// WordPress.PHP.DiscouragedPHPFunctions
base64_decode( 'VGhpcyBpcyBhbiBlbmNvZGVkIHN0cmluZw=='); // Ok - exclude obfuscation group.
base64_encode( 'This is an encoded string' ); // Ok - exclude obfuscation group.
convert_uudecode( "+22!L;W9E(%!(4\"$`\n`" ); // Ok - exclude obfuscation group.
convert_uuencode( "test\ntext text\r\n" ); // Ok - exclude obfuscation group.
str_rot13( 'The quick brown fox jumps over the lazy dog.' ); // Ok - exclude obfuscation group.
serialize(); // Warning.
unserialize(); // Warning.
urlencode(); // Warning.
passthru( 'cat myfile.zip', $err ); // Warning.
$process = proc_open( 'php', $descriptorspec, $pipes, $cwd, $env ); // Warning.
$last_line = system( 'ls', $retval ); // Warning.
$handle = popen( '/bin/ls', 'r' ); // Warning.

// WordPress.PHP.DiscouragedPHPFunctions.runtime_configuration_error_reporting
error_reporting(); // Error.

// WordPress.PHP.DiscouragedPHPFunctions.runtime_configuration_ini_set
ini_set(); // Error.

// WordPress.PHP.DiscouragedPHPFunctions.runtime_configuration_ini_alter
ini_alter(); // Error.

// WordPress.PHP.DiscouragedPHPFunctions.runtime_configuration_ini_restore
ini_restore(); // Error.

// WordPress.PHP.DiscouragedPHPFunctions.runtime_configuration_apache_setenv
apache_setenv(); // Error.

// WordPress.PHP.DiscouragedPHPFunctions.runtime_configuration_putenv
putenv(); // Error.

// WordPress.PHP.DiscouragedPHPFunctions.runtime_configuration_set_include_path
set_include_path(); // Error.

// WordPress.PHP.DiscouragedPHPFunctions.runtime_configuration_restore_include_path
restore_include_path(); // Error.

// WordPress.PHP.DiscouragedPHPFunctions.runtime_configuration_magic_quotes_runtime
magic_quotes_runtime(); // Error.

// WordPress.PHP.DiscouragedPHPFunctions.runtime_configuration_set_magic_quotes_runtime
set_magic_quotes_runtime(); // Error.

// WordPress.PHP.DiscouragedPHPFunctions.runtime_configuration_dl
dl(); // Error.

// WordPress.PHP.DiscouragedPHPFunctions.system_calls_exec
exec( 'whoami' ); // Error.

// WordPress.PHP.DiscouragedPHPFunctions.system_calls_shell_exec
$output = shell_exec( 'ls -lart' ); // Error.

// WordPress.PHP.DevelopmentFunctions
var_dump(); // Warning.
var_export(); // Warning.
print_r(); // Warning.
trigger_error(); // Warning.
set_error_handler(); // Warning.
debug_backtrace(); // Warning.
debug_print_backtrace(); // Warning.
wp_debug_backtrace_summary(); // Warning.

// WordPress.PHP.DevelopmentFunctions.prevent_path_disclosure_phpinfo
phpinfo(); // Error.

// WordPress.PHP.DevelopmentFunctions.error_log_error_log
error_log(); // Error.

// WordPress.WP.AlternativeFunctions
curl_init(); // Warning + Message.
curl_close( $ch ); // Warning + Message.
CURL_getinfo(); // Warning + Message.
parse_url( 'http://example.com/' ); // Warning + Message.
$json = json_encode( $thing ); // Warning + Message.
readfile(); // Warning.
fclose(); // Warning.
fopen(); // Warning.
fread(); // Warning.
fsockopen(); // Warning.
pfsockopen(); // Warning.
srand(); // Warning.
mt_srand(); // Warning.
rand(); // Warning.
mt_rand(); // Warning.

// WordPressVIPMinimum.Functions.RestrictedFunctions.get_posts_get_children
get_children(); // Error + Message.

// WordPressVIPMinimum.Variables.VariableAnalysis
function foo() {
	$a = 'Hello';
	$c = compact( $a, $b ); // Warning x 2.
	try {
		do_something_silly();
	} catch ( Exception $e ) {} // Ok.
}

/* The below rules are implicitly included via WordPressVIPMinimum */

// WordPressVIPMinimum.Classes.DeclarationCompatibility
class MyWidget extends WP_Widget {
	function widget() { // Error.
	}
}

// WordPressVIPMinimum.Classes.RestrictedExtendClasses
class BadTestClass extends WP_CLI_Command { } // Warning.

// WordPressVIPMinimum.Compatibility.ZoninatorSniff
wpcom_vip_load_plugin( 'zoninator', 'plugins', '0.8' ); // Warning.

// WordPressVIPMinimum.Constants.ConstantString
define( WPCOM_VIP ); // Error.

// WordPressVIPMinimum.Constants.RestrictedConstants
if ( A8C_PROXIED_REQUEST === true ) { // Warning.
}
define( 'JETPACK_DEV_DEBUG', true ); // Error.

// WordPressVIPMinimum.Files.IncludingFile
include ( MY_CONSTANT . "my_file.php" ); // Warning.
require_once( custom_function( 'test_file.php' ) ); // Warning.
require '../my_file.php'; // Error.
include_once("http://www.google.com/bad_file.php"); // Error.

// WordPressVIPMinimum.Files.IncludingNonPHPFile
require_once __DIR__ . "/my_file.svg"; // Error.

// WordPressVIPMinimum.Functions.CheckReturnValue
$my_theme_options = get_option( 'my_theme', false );
if ( array_key_exists( 'key', $my_theme_options ) ) { } // Error.
echo '<a href="' . esc_url( get_term_link( $link ) ) . '">My term link</a>'; // Error.

// WordPressVIPMinimum.Functions.DynamicCalls
$my_notokay_func = 'extract';
$my_notokay_func(); // Error.

// WordPressVIPMinimum.Functions.RestrictedFunctions
wp_cache_get_multi(); // Error.
opcache_reset(); // Error.
opcache_invalidate( 'test_script.php' ); // Error.
opcache_compile_file( $test_script ); // Error.
opcache_​is_​script_​cached( 'test_script.php' ); // Error.
opcache_​get_​status(); // Error.
opcache_​get_​configuration(); // Error.
get_super_admins(); // Error.
wpcom_vip_irc(); // Error.
flush_rewrite_rules(); // Error.
$wp_rewrite->flush_rules(); // Error.
attachment_url_to_postid( $url ); // Error.
dbDelta(); // Error.
switch_to_blog( $blogid ); // Error.
get_page_by_title( $page_title ); // Error.
url_to_postid( $url ); // Error.
\add_role(); // Error.
get_user_meta(); // Error.
update_user_meta(); // Error.
delete_user_meta(); // Error.
add_user_meta(); // Error.
term_exists(); // Error.
count_user_posts(); // Error.
wp_old_slug_redirect(); // Error.
get_adjacent_post(); // Error.
get_previous_post(); // Error.
get_previous_post_link(); // Error.
get_next_post(); // Error.
get_next_post_link(); // Error.
get_intermediate_image_sizes(); // Error.
wp_is_mobile(); // Error.
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
delete(); // Error.
file_put_contents( $file, $text, FILE_APPEND ); // Error.
while ( $count > $loop ) {
	if ( flock( $fp, LOCK_EX ) ) { // Error.
		fwrite( $fp, $text ); // Error.
	}
}
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
add_site_option( 'foo', $bar ); // Error.
update_site_option( $bar, $foo, true ); // Error.
delete_site_option( $foo ); // Error.
wp_mail(); // Warning.
mail(); // Warning.
is_multi_author(); // Warning.
the_sub_field( 'field' ); // Warning.
the_field( 'field' ); // Warning.
wp_remote_get( $url ); // Warning.
setcookie( 'cookie[three]', 'cookiethree' ); // Warning.
get_posts(); // Warning.
wp_get_recent_posts(); // Warning.
$wp_random_testing = create_function( '$a, $b', 'return ( $b / $a ); '); // Warning.
wpcom_vip_get_term_link(); // Warning.
wpcom_vip_get_term_by(); // Warning.
wpcom_vip_get_category_by_slug(); // Warning.

// WordPressVIPMinimum.Functions.StripTagsSniff
strip_tags( 'Testing' ); // Warning.
strip_tags( 'Test', $html ); // Warning.

// WordPressVIPMinimum.Hooks.AlwaysReturnInFilter
function bad_example_function_thing() { // Error.
	if ( 1 === 0  ) {
		if ( 1 === 1 ) {
			return 'ahoj';
		} else {
			return 'hello';
		}
	}
}
add_filter( 'bad_example_function_filter', 'bad_example_function_thing' );
add_filter( 'another_bad_example_closure', function() { // Error.
	return;
} );

// WordPressVIPMinimum.Hooks.PreGetPosts
add_action( 'pre_get_posts', function( $wp_query ) {
	if ( ! $wp_query->is_search() ) {
		$wp_query->set( 'cat', '-5' ); // Warning.
	}
} );

// WordPressVIPMinimum.Hooks.RestrictedHooks
add_filter( 'upload_mimes', 'bad_example_function' ); // Warning.
add_action( 'http_request_timeout', 'bad_example_function' ); // Warning.
add_filter('http_request_args', 'bad_example_function' ); // Warning.
add_action( 'do_robotstxt', 'my_do_robotstxt'); // Warning.
add_filter( 'robots_txt', function() { // Warning.
	return 'test';
} );

// WordPressVIPMinimum.Performance.BatcacheWhitelistedParams
// phpcs:ignore WordPress.Security.NonceVerification.NoNonceVerification,WordPress.Security.ValidatedSanitizedInput.InputNotValidated
$test = sanitize_text_field( $_GET["utm_medium"] ); // Warning.

// WordPressVIPMinimum.Performance.CacheValueOverride
$bad_wp_users = wp_cache_get( md5( self::CACHE_KEY . '_wp_users'), self::CACHE_GROUP );
$bad_wp_users = false; // Error.

// WordPressVIPMinimum.Performance.FetchingRemoteData
$external_resource = file_get_contents( 'https://example.com' ); // Warning.

// WordPressVIPMinimum.Performance.LowExpiryCacheTime
wp_cache_set( 'test', $data, $group, 100 ); // Warning.
wp_cache_add( 123, $data, null, 1.5 * MINUTE_IN_SECONDS ); // Warning.
wp_cache_replace( 'test', $data, $group,  2*MINUTE_IN_SECONDS  ); // Warning.

// WordPressVIPMinimum.Performance.NoPaging
$args = array(
	'nopaging'       => true, // Error.
);
_query_posts( 'nopaging=true' ); // Error.

// WordPressVIPMinimum.Performance.OrderByRand
$args = array(
	"orderby" => "RAND", // Error.
);
$query_args['orderby'] = 'rand'; // Error.

// WordPressVIPMinimum.Performance.RegexpCompare 
$query_args = array(
	'posts_per_page' => 1,
	'post_status' => 'draft',
	'meta_compare' => 'REGEXP', // Error.
);
$query_args = [
	'post_status' => 'publish',
	'meta_query' => [ // phpcs:ignore WordPress.DB.SlowDBQuery.slow_db_query_meta_query
		[
			'compare' => 'REGEXP', // Error.
		]
	]
];

// WordPressVIPMinimum.Performance.RemoteRequestTimeout
wp_remote_post( $this->endpoint, array(
        'method'      => 'POST',
        'timeout'     => 45, // Error.
        'httpversion' => '1.1',
        'blocking'    => false,
        'body'        => wp_json_encode( $this->logs, JSON_UNESCAPED_SLASHES ),
    )
);

// WordPressVIPMinimum.Performance.TaxonomyMetaInOptions
get_option( "taxonomy_rating_$obj->term_id" ); // Warning.
update_option( 'taxonomy_rating_' . $category_id ); // Warning.

// WordPressVIPMinimum.Performance.WPQueryParams
$query_args = array(
	'post__not_in' => $posts_not_in, // Warning.
	'suppress_filters' => true, // Error.
);

// WordPressVIPMinimum.Security.EscapingVoidReturnFunctions.Found
esc_js( _deprecated_argument() ); // Error.
esc_js( _deprecated_constructor() ); // Error.
esc_js( _deprecated_file() ); // Error.
esc_js( _deprecated_function() ); // Error.
esc_js( _deprecated_hook() ); // Error.
esc_js( _doing_it_wrong() ); // Error.
esc_html( printf( 'foo', [] ) ); // Error.
esc_attr( user_error( 'foo', '' ) ); // Error.
esc_attr( vprintf( 'foo', [] ) ); // Error.
esc_attr( wp_die( 'foo' ) ); // Error.
esc_attr( wp_dropdown_pages() ); // Error.

// WordPressVIPMinimum.Security.ExitAfterRedirect
function redirect_test() {
	wp_safe_redirect( 'https.//vip.wordpress.com' ); // Error.
}
wp_redirect( 'https://vip.wordpress.com' ); // Error.

// WordPressVIPMinimum.Security.PHPFilterFunctions
filter_input( INPUT_GET, 'foo' ); // Warning.
filter_input( INPUT_GET, "foo", FILTER_UNSAFE_RAW  ); // Warning.
filter_var( $url, FILTER_DEFAULT ); // Warning.
filter_var_array( $array ); // Warning.
filter_input_array( $array ); // Warning.

// WordPressVIPMinimum.Security.Mustache
echo '<a href="{{href}}">{{{data}}}</div></a>'; // Warning.
?>
<script type="text/html" id="tmpl-example">
{{=<% %>=}} <!-- Warning. -->
</script>
<script>
	andlebars.registerHelper('link', function(text, url) {
		text = 'test';
		url  = Handlebars.Utils.escapeExpression(url);
		var result = '<a href="' + url + '">' + text + '</a>';
		return new Handlebars.SafeString(result); // Warning.
	});
</script> <?php

// WordPressVIPMinimum.Security.ProperEscapingFunction
echo '<a href="' . esc_attr( $some_var ) . '"></a>'; // Error.
echo '<a title="' . esc_html( $some_var ) . '"></a>'; // Error.

// WordPressVIPMinimum.Security.StaticStrreplace
str_replace( 'foo', array( 'bar', 'foo' ), 'foobar' ); // Error.

// WordPressVIPMinimum.Security.Underscorejs
echo "<script>
 _.templateSettings = { 
	interpolate: /\{\{(.+?)\}\}/g" . // Warning.
"};
 </script>";

// WordPressVIPMinimum.Security.Vuejs
?> <article v-for="post in posts" class="listing-post">
	<a v-bind:href="'/research'+post.permalink" class="column-4"><h4 v-html="post.title"></h4></a> <!-- Warning. -->
</article> <?php

// WordPressVIPMinimum.UserExperience.AdminBarRemoval
add_filter( 'show_admin_bar', '__return_false' ); // Error.
show_admin_bar( false ); // Error.
echo '<style type="text/css">
#wpadminbar {
	visibility: hidden; /* Error. */
	display: none; /* Error. */
	opacity: 0; /* Error. */
}
</style>';
echo '<style type="text/css">.show-admin-bar { visibility: hidden; }</style>'; // Error.
?> <style type="text/css">
.show-admin-bar {
	visibility: hidden; /* Error. */
	display: none; /* Error. */
	opacity: 0; /* Error. */
}
</style> <?php

// WordPressVIPMinimum.Variables.RestrictedVariables
$query = "SELECT * FROM $wpdb->users"; // Error.
$x = foo( sanitize_text_field( $_SERVER['HTTP_USER_AGENT'] ) ); // phpcs:ignore WordPress.Security.ValidatedSanitizedInput.InputNotValidated -- Warning.
foo( $_SESSION['bar'] ); // Error.

// WordPressVIPMinimum.Variables.ServerVariables
// phpcs:disable WordPress.Security.ValidatedSanitizedInput.InputNotValidated,WordPress.Security.ValidatedSanitizedInput.InputNotSanitized
$test = $_SERVER['PHP_AUTH_PW']; // Error.
bar( $_SERVER['HTTP_X_IP_TRAIL'] ); // Error.
$_SERVER['HTTP_X_FORWARDED_FOR']; // Error.
$_SERVER["REMOTE_ADDR"]; // Error.
// phpcs:enable WordPress.Security.ValidatedSanitizedInput.InputNotValidated,WordPress.Security.ValidatedSanitizedInput.InputNotSanitized


// WordPressVIPMinimum.Variables.VariableAnalysis
function foo() {
	$a = 'Hello';
	$c = compact( $a, $b ); // Warning x 2.
}

// WordPressVIPMinimum.VersionControl.MergeConflict
function is_prime( $n ) {
	if ( 2 === $n  ) {
	}
//phpcs:ignore Generic.PHP.Syntax.PHPSyntax
======= // Error.
	if ( $n % 2 === 0 ) {
	}
}

// Squiz.WhiteSpace.SuperfluousWhitespace
// Error. ?>




