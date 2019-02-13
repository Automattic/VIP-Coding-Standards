<?php

// WordPressVIPMinimum.Functions.RestrictedFunctions.file_ops_delete
delete( $file ); // Error.

// WordPressVIPMinimum.Functions.RestrictedFunctions.file_ops_file_put_contents
file_put_contents( 'file.txt', '', FILE_APPEND ); // Error.

// WordPressVIPMinimum.Functions.RestrictedFunctions.file_ops_flock
if ( flock( $fp, LOCK_EX ) ) { // Error.
}

// WordPressVIPMinimum.Functions.RestrictedFunctions.file_ops_fputcsv
fputcsv( $fp, $array ); // Error.

// WordPressVIPMinimum.Functions.RestrictedFunctions.file_ops_fputs
fputs( $fp, 'test' ); // Error.

// WordPressVIPMinimum.Functions.RestrictedFunctions.file_ops_fwrite
fwrite( $fp, 'test' ); // Error.

// WordPressVIPMinimum.Functions.RestrictedFunctions.file_ops_ftruncate
ftruncate( $fp, 1 ); // Error.

// WordPressVIPMinimum.Functions.RestrictedFunctions.file_ops_is_writable
is_writable( 'file.txt' ); // Error.

// WordPressVIPMinimum.Functions.RestrictedFunctions.file_ops_is_writeable
is_writeable( $file ); // Error.

// WordPressVIPMinimum.Functions.RestrictedFunctions.file_ops_link
link( 'file.txt', 'newfile.txt' ); // Error.

// WordPressVIPMinimum.Functions.RestrictedFunctions.file_ops_rename
rename( 'oldfile.txt', $file ); // Error.

// WordPressVIPMinimum.Functions.RestrictedFunctions.file_ops_symlink
symlink( $file, 'file.txt' ); // Error.

// WordPressVIPMinimum.Functions.RestrictedFunctions.file_ops_tempname
tempnam( $dir, 'pre' ); // Error.

// WordPressVIPMinimum.Functions.RestrictedFunctions.file_ops_touch
touch( $file ); // Error.

// WordPressVIPMinimum.Functions.RestrictedFunctions.file_ops_unlink
unlink( $file ); // Error.

// WordPressVIPMinimum.Functions.RestrictedFunctions.cookies_setcookie
setcookie( 'cookie[three]', 'cookiethree' ); // Error. 

// WordPressVIPMinimum.Variables.RestrictedVariables.cache_constraints___COOKIE
$x = isset( $_COOKIE['bar'] ) ? sanitize_key( $_COOKIE['bar'] ) : ''; // Error.

// WordPressVIPMinimum.Variables.RestrictedVariables.cache_constraints___SERVER__HTTP_USER_AGENT__
if ( ! isset( $_SERVER['HTTP_USER_AGENT'] ) ) { // Error.
}

// WordPress.Security.EscapeOutput.OutputNotEscaped
echo $foo; // Error.
echo 'Some Raw String';  // Ok.
echo esc_html( $good . $better ) . $foo; // Error.
echo esc_html( $food . 'include'  ); // Ok.
echo esc_html( strtoupper( $ok ) ) . ' ' . esc_html( strtolower( $ok ) ); // Ok.
echo filter_var( $bar, FILTER_VALIDATE_EMAIL ); // Ok.
echo filter_input( INPUT_GET, $bar, FILTER_SANITIZE_SPECIAL_CHARS ); // Ok.
echo ent2ncr( $text ); // Error.
echo empty( $foo ) ? 'no foo' : $foo; // Error.
echo $foo ? esc_html( $foo ) : 'no foo'; // Ok.

// WordPress.WP.AlternativeFunctions.file_system_read_fclose
fclose( $fp ); // Error.

// WordPress.WP.AlternativeFunctions.file_system_read_fopen
fopen( 'file.txt', 'r' ); // Error.

// WordPressVIPMinimum.Performance.FetchingRemoteData.FileGetContentsUnknown
$external_resource = file_get_contents( $test ); // Error.
$file_content = file_get_contents( 'my-file.svg' ); // Ok.
wpcom_vip_file_get_contents( $bar ); // Ok.

// WordPress.Security.NonceVerification.NoNonceVerification
function bar() {
	if ( ! isset( $_POST['test'] ) ) { // Warning.
		return;
	}
}
function foo_bar() {
	if ( ! isset( $_POST['test'] ) || ! wp_verify_nonce( 'some_action' ) ) { // Ok.
		exit;
	}
}

// WordPress.Security.ValidatedSanitizedInput.InputNotSanitized
do_something( $_POST ); // Ok.
if ( isset( $_POST['foo2'] ) ) {
	bar( wp_unslash( $_POST['foo2'] ) ); // Warning.
}

// WordPress.WP.PostsPerPage.posts_per_page_posts_per_page
$args = array(
	'posts_per_page' => 999, // Warning.
	'posts_per_page' => 1, // OK.
	'posts_per_page' => '1', // OK.
);
_query_posts( 'posts_per_page=999' ); // Warning.
$query_args['posts_per_page'] = 999; // Warning.
$query_args['posts_per_page'] = 1; // OK.

// WordPressVIPMinimum.Hooks.RestrictedHooks.UploadMimes
add_filter( 'upload_mimes', 'foo' ); // Warning.

// WordPressVIPMinimum.Security.PHPFilterFunctions
filter_var( $url ); // Warning.
filter_var_array( $array ); // Warning.
filter_input_array( $array ); // Warning.
filter_var( $url, FILTER_SANITIZE_URL ); // Ok.
filter_input( INPUT_GET, 'foo' ); // Warning.
filter_input( INPUT_GET, 'foo', FILTER_SANITIZE_STRING ); // Ok.
filter_var( $url, FILTER_DEFAULT ); // Warning.
filter_var_array( $array, FILTER_UNSAFE_RAW ); // Warning.
filter_var_array( $array, FILTER_SANITIZE_STRING ); // Ok.
filter_input_array( $array,FILTER_SANITIZE_STRING      ); // Ok.
filter_input( INPUT_GET, 'foo', FILTER_DEFAULT ); // Warning.

// WordPressVIPMinimum.Functions.RestrictedFunctions.dbDelta_dbdelta
dbDelta( $query, true ); // Warning.

// WordPressVIPMinimum.Functions.RestrictedFunctions.wp_mail_wp_mail
wp_mail(); // Warning.
mail(); // Warning.

// WordPressVIPMinimum.Functions.RestrictedFunctions.attachment_url_to_postid_attachment_url_to_postid
wpcom_vip_attachment_url_to_postid( $url ); // Ok.
attachment_url_to_postid( $url ); // Warning.

// WordPressVIPMinimum.Functions.RestrictedFunctions.get_adjacent_post_get_adjacent_post
wpcom_vip_get_adjacent_post(); // Ok.
get_adjacent_post(); // Warning.
get_previous_post(); // Warning.
get_next_post(); // Warning.
get_previous_post_link(); // Warning.
get_next_post_link(); // Warning.

// WordPressVIPMinimum.Functions.RestrictedFunctions.get_page_by_title_get_page_by_title
wpcom_vip_get_page_by_title(); // Ok.
get_page_by_title( $page_title ); // Warning.

// WordPressVIPMinimum.Functions.RestrictedFunctions.get_posts_get_children
get_child(); // Ok.
get_children(); // Warning.

// WordPressVIPMinimum.Functions.RestrictedFunctions.get_posts_get_posts
get_posts(); // Warning.
get_post( 123 ); // Ok.

// WordPressVIPMinimum.Functions.RestrictedFunctions.get_posts_wp_get_recent_posts
wp_get_recent_posts(); // Warning.

// WordPressVIPMinimum.Functions.RestrictedFunctions.url_to_postid_url_to_postid
wpcom_vip_url_to_postid( $url ); // Ok.
url_to_postid( $url ); // Warning.

// WordPressVIPMinimum.Functions.RestrictedFunctions.wp_old_slug_redirect_wp_old_slug_redirect
wpcom_vip_old_slug_redirect(); // Ok.
wp_old_slug_redirect(); // Warning.

// WordPress.CodeAnalysis.AssignmentInCondition.Found
if ($a = 123) { // Warning.
}

// WordPress.PHP.DiscouragedPHPFunctions.urlencode_urlencode
urlencode(); // Warning.
rawurlencode(); // Ok.

// WordPress.PHP.DontExtract
extract( array( 'a' => 1 ) ); // Error.
$this->extract(); // Ok.

// WordPress.PHP.StrictComparisons.LooseComparison
true == $true; // Warning.
false === $true; // Ok.

// WordPress.PHP.StrictInArray.MissingTrueStrict
in_array( 1, array( '1', 1, true ), true ); // Ok.
in_array( 1, array( '1', 1, true ) ); // Warning.
in_array( 1, array( '1', 1, true ), false ); // Warning.
array_search( 1, $array, false ); // Warning.
array_keys( array( '1', 1, true ), 'my_key' ); // Warning.

// WordPress.Security.EscapeOutput.UnsafePrintingFunction
_e( $some_nasty_var ); // Error.
_ex( $some_nasty_var, 'context' ); // Error.
echo esc_html_x( 'Something', 'context' ); // Ok.
echo esc_html_x( $some_nasty_var, 'context' ); // Ok.

// WordPress.WP.GlobalVariablesOverride.OverrideProhibited
global $wpdb;
$wpdb = 'test'; // Error.
$GLOBALS['domain']['subkey'] = 'something else'; // Error.

// WordPress.WP.EnqueuedResources.NonEnqueuedScript
echo wp_kses( '<script src="' . esc_url( $script ) . '">', [ 'script' => [ 'src' => [], ], ] ); // Warning.
?> <script src="http://someurl/somefile.js"></script> <!-- Warning. -->

<!-- WordPress.WP.EnqueuedResources.NonEnqueuedStylesheet -->
<link rel="stylesheet" href="http://someurl/somefile.css"> <!-- Warning. -->
<?php echo '<link rel="stylesheet" src="' . esc_url( SOMESCRIPT ) . '">'; // Warning.

// WordPressVIPMinimum.Performance.LowExpiryCacheTime.LowCacheTime
wp_cache_set( 'test', $data, $group, 100 ); // Warning.
wp_cache_add( 'test', $data, $group,  2*MINUTE_IN_SECONDS  ); // Warning.
wp_cache_replace( $testing, $data, '', 1.5 * MINUTE_IN_SECONDS ); // Warning.

// WordPressVIPMinimum.Files.IncludingFile
include_once ( MY_CONSTANT . "my_file.php" ); // Warning.
require_once( custom_function( 'test_file.php' ) ); // Warning.
require_once "my_file.php"; // Warning.
require '../../my_file.php'; // Warning.
include("http://www.google.com/bad_file.php"); // Warning.

// WordPressVIPMinimum.Variables.VariableAnalysis.UndefinedVariable
function foo_bar_bar() {
	$b . 'test'; // Warning.
}

// WordPressVIPMinimum.Variables.VariableAnalysis.UnusedVariable
function foo_bar_foo() {
	$a = 'Hello'; // Warning
}

// WordPressVIPMinimum.UserExperience.AdminBarRemoval
add_filter( 'show_admin_bar', '__return_false' ); // Error.
add_filter( 'show_admin_bar', '__return_true' ); // Ok.
show_admin_bar( false ); // Error.
show_admin_bar( true ); // Ok.
add_filter( 'show_admin_bar', 'my_own_return_false' ); // Error.
echo '<style type="text/css">
#wpadminbar {
	visibility: hidden; /* Error. */
	display: none; /* Error. */
	opacity: 0; /* Error. */
}
</style>';
?> <style type="text/css">
#wpadminbar {
	visibility: hidden; /* Error. */
	display: none; /* Error. */
	opacity: 0; /* Error. */
}
#not-wpadminbar {
	visibility: hidden; /* OK. */
	display: none; /* OK. */
	opacity: 0; /* OK. */
}
.show-admin-bar {
	visibility: hidden; /* Error. */
	display: none; /* Error. */
	opacity: 0; /* Error. */
}
</style> <?php

// Generic.PHP.NoSilencedErrors
$test = @in_array( $array, $needle, true ); // Error.

// WordPressVIPMinimum.Security.ProperEscapingFunction.htmlAttrNotByEscHTML
echo '<a href="' . esc_attr( $some_var ) . '"></a>'; // Error.
echo '<a title="' . esc_html( $some_var ) . '"></a>'; // Error.
echo '<a href="' . esc_url( $some_var ) . '"></a>'; // OK.
?><a href="<?php echo esc_attr( $some_var ); ?>">Hello</a> <!-- Error. -->
<a href="" class="<?php echo esc_html( $some_var); ?>">Hey</a> <!-- Error. -->
<a href="<?php esc_url( $url );?>"></a> <!-- Ok. -->
<a title="<?php esc_attr( $url );?>"></a> <?php // Ok.

// WordPressVIPMinimum.Functions.RestrictedFunctions.is_multi_author_is_multi_author
multi_author(); // Ok.
is_multi_author(); // Warning.

// WordPressVIPMinimum.Functions.RestrictedFunctions.switch_to_blog_switch_to_blog
switch_blog(); // Ok.
switch_to_blog( $blogid ); // Warning.

// WordPressVIPMinimum.Performance.WPQueryParams.PostNotIn
$args( [
	'post__not_in' => array( 1, 2, 3 ), // Warning.
	'posts_per_page' => 1,
] );

/* Rules that are being silenced and should NOT be flagged. */
// WordPress.DB.SlowDBQuery.slow_db_query_meta_key
$query = new WP_Query( [
	'meta_key' => 'foo', // Ok.
] );
$args = 'foo=bar&meta_key=foo'; // Ok.

// WordPressVIPMinimum.Performance.BatcacheWhitelistedParams
if ( isset( $_GET['migSource'] ) && wp_verify_nonce( sanitize_text_field( $_GET['migSource'] ) ) ) {
	$test = sanitize_text_field( $_GET['migSource'] ); // Ok.
}

// WordPressVIPMinimum.Functions.RestrictedFunctions.user_meta_add_user_meta
add_user_meta( 123, $foo, $bar ); // Ok.

// WordPressVIPMinimum.Functions.RestrictedFunctions.user_meta_delete_user_meta
delete_user_meta( $foo, $bar, '123' ); // Ok.

// WordPressVIPMinimum.Functions.RestrictedFunctions.user_meta_get_user_meta
get_user_meta();

// WordPressVIPMinimum.Functions.RestrictedFunctions.user_meta_update_user_meta
update_user_meta( $bar, '123', $foo );

// WordPressVIPMinimum.Variables.RestrictedVariables.user_meta__wpdb__usermeta
$query = "SELECT * FROM $wpdb->usermeta"; // Ok.

// WordPressVIPMinimum.Functions.RestrictedFunctions.site_option_delete_site_option
delete_site_option( $foo ); // Ok.

// WordPressVIPMinimum.Functions.RestrictedFunctions.site_option_update_site_option
update_site_option( $bar, $foo, true ); // Ok.

// WordPressVIPMinimum.Functions.RestrictedFunctions.site_option_add_site_option
add_site_option( 'foo', $bar ); // Ok.

?> <!-- closing PHP tag should be omitted -->
