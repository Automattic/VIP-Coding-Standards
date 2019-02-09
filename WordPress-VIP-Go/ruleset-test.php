<?php
/**
 * Ruleset test for the WordPress-VIP-Go ruleset
 *
 * The expected errors, warnings, and messages listed here, should match what is expected to be reported
 * when ruleset-test.inc is run against PHP_CodeSniffer and the WordPress-VIP-Go standard.
 *
 * To run the test, see ../bin/ruleset-tests.
 *
 * @package VIPCS\WordPressVIPMinimum
 */

namespace WordPressVIPMinimum;

// Expected values.
$expected = [
	'errors'   => [
		4   => 1,
		7   => 1,
		10  => 1,
		14  => 1,
		17  => 1,
		20  => 1,
		23  => 1,
		26  => 1,
		29  => 1,
		32  => 1,
		35  => 1,
		38  => 1,
		41  => 1,
		44  => 1,
		47  => 1,
		50  => 1,
		53  => 1,
		56  => 1,
		62  => 1,
		68  => 1,
		70  => 1,
		75  => 1,
		76  => 1,
		80  => 1,
		83  => 1,
		86  => 1,
		185 => 1,
		200 => 1,
		201 => 1,
		207 => 1,
		208 => 1,
		241 => 1,
		243 => 1,
		245 => 1,
		248 => 1,
		249 => 1,
		250 => 1,
		255 => 1,
		256 => 1,
		257 => 1,
		265 => 1,
		266 => 1,
		267 => 1,
		272 => 1,
		275 => 1,
		276 => 1,
		278 => 1,
		279 => 1,
	],
	'warnings' => [
		92  => 1,
		105 => 1,
		110 => 1,
		114 => 1,
		115 => 1,
		119 => 1,
		122 => 1,
		123 => 1,
		124 => 1,
		126 => 1,
		128 => 1,
		129 => 1,
		132 => 1,
		135 => 1,
		138 => 1,
		139 => 1,
		143 => 1,
		147 => 1,
		148 => 1,
		149 => 1,
		150 => 1,
		151 => 1,
		155 => 1,
		159 => 1,
		162 => 1,
		166 => 1,
		170 => 1,
		174 => 1,
		177 => 1,
		181 => 1,
		189 => 1,
		194 => 1,
		195 => 1,
		196 => 1,
		197 => 1,
		211 => 1,
		212 => 1,
		215 => 1,
		216 => 1,
		219 => 1,
		220 => 1,
		221 => 1,
		224 => 1,
		225 => 1,
		226 => 1,
		227 => 1,
		228 => 1,
		232 => 1,
		237 => 1,
		285 => 1,
		289 => 1,
		293 => 1,
	],
	'messages' => [
		4   => [
			'File system writes only work in /tmp/ and inside the /uploads/ folder on VIP Go. To do filesystem writes you must use the WP_Filesystem class, using functions such as delete() won\'t work or will return unexpected results. Read more here: https://vip.wordpress.com/documentation/using-wp_filesystem-instead-of-direct-file-access-functions/',
		],
		7   => [
			'File system writes only work in /tmp/ and inside the /uploads/ folder on VIP Go. To do filesystem writes you must use the WP_Filesystem class, using functions such as file_put_contents() won\'t work or will return unexpected results. Read more here: https://vip.wordpress.com/documentation/using-wp_filesystem-instead-of-direct-file-access-functions/',
		],
		10  => [
			'File system writes only work in /tmp/ and inside the /uploads/ folder on VIP Go. To do filesystem writes you must use the WP_Filesystem class, using functions such as flock() won\'t work or will return unexpected results. Read more here: https://vip.wordpress.com/documentation/using-wp_filesystem-instead-of-direct-file-access-functions/',
		],
		14  => [
			'File system writes only work in /tmp/ and inside the /uploads/ folder on VIP Go. To do filesystem writes you must use the WP_Filesystem class, using functions such as fputcsv() won\'t work or will return unexpected results. Read more here: https://vip.wordpress.com/documentation/using-wp_filesystem-instead-of-direct-file-access-functions/',
		],
		17  => [
			'File system writes only work in /tmp/ and inside the /uploads/ folder on VIP Go. To do filesystem writes you must use the WP_Filesystem class, using functions such as fputs() won\'t work or will return unexpected results. Read more here: https://vip.wordpress.com/documentation/using-wp_filesystem-instead-of-direct-file-access-functions/',
		],
		20  => [
			'File system writes only work in /tmp/ and inside the /uploads/ folder on VIP Go. To do filesystem writes you must use the WP_Filesystem class, using functions such as fwrite() won\'t work or will return unexpected results. Read more here: https://vip.wordpress.com/documentation/using-wp_filesystem-instead-of-direct-file-access-functions/',
		],
		23  => [
			'File system writes only work in /tmp/ and inside the /uploads/ folder on VIP Go. To do filesystem writes you must use the WP_Filesystem class, using functions such as ftruncate() won\'t work or will return unexpected results. Read more here: https://vip.wordpress.com/documentation/using-wp_filesystem-instead-of-direct-file-access-functions/',
		],
		26  => [
			'File system writes only work in /tmp/ and inside the /uploads/ folder on VIP Go. To do filesystem writes you must use the WP_Filesystem class, using functions such as is_writable() won\'t work or will return unexpected results. Read more here: https://vip.wordpress.com/documentation/using-wp_filesystem-instead-of-direct-file-access-functions/',
		],
		29  => [
			'File system writes only work in /tmp/ and inside the /uploads/ folder on VIP Go. To do filesystem writes you must use the WP_Filesystem class, using functions such as is_writeable() won\'t work or will return unexpected results. Read more here: https://vip.wordpress.com/documentation/using-wp_filesystem-instead-of-direct-file-access-functions/',
		],
		32  => [
			'File system writes only work in /tmp/ and inside the /uploads/ folder on VIP Go. To do filesystem writes you must use the WP_Filesystem class, using functions such as link() won\'t work or will return unexpected results. Read more here: https://vip.wordpress.com/documentation/using-wp_filesystem-instead-of-direct-file-access-functions/',
		],
		35  => [
			'File system writes only work in /tmp/ and inside the /uploads/ folder on VIP Go. To do filesystem writes you must use the WP_Filesystem class, using functions such as rename() won\'t work or will return unexpected results. Read more here: https://vip.wordpress.com/documentation/using-wp_filesystem-instead-of-direct-file-access-functions/',
		],
		38  => [
			'File system writes only work in /tmp/ and inside the /uploads/ folder on VIP Go. To do filesystem writes you must use the WP_Filesystem class, using functions such as symlink() won\'t work or will return unexpected results. Read more here: https://vip.wordpress.com/documentation/using-wp_filesystem-instead-of-direct-file-access-functions/',
		],
		41  => [
			'File system writes only work in /tmp/ and inside the /uploads/ folder on VIP Go. To do filesystem writes you must use the WP_Filesystem class, using functions such as tempnam() won\'t work or will return unexpected results. Read more here: https://vip.wordpress.com/documentation/using-wp_filesystem-instead-of-direct-file-access-functions/',
		],
		44  => [
			'File system writes only work in /tmp/ and inside the /uploads/ folder on VIP Go. To do filesystem writes you must use the WP_Filesystem class, using functions such as touch() won\'t work or will return unexpected results. Read more here: https://vip.wordpress.com/documentation/using-wp_filesystem-instead-of-direct-file-access-functions/',
		],
		47  => [
			'File system writes only work in /tmp/ and inside the /uploads/ folder on VIP Go. To do filesystem writes you must use the WP_Filesystem class, using functions such as unlink() won\'t work or will return unexpected results. Read more here: https://vip.wordpress.com/documentation/using-wp_filesystem-instead-of-direct-file-access-functions/',
		],
		50  => [
			'Due to server-side caching, server-side based client related logic might not work. We recommend implementing client side logic in JavaScript instead.',
		],
		53  => [
			'Due to server-side caching, server-side based client related logic might not work. We recommend implementing client side logic in JavaScript instead.',
		],
		56  => [
			'Due to server-side caching, server-side based client related logic might not work. We recommend implementing client side logic in JavaScript instead.',
		],
		62  => [
			'This is currently deprecated in PHP 7.0 and will be removed in the future. This will cause a fatal error on newer versions of PHP and should be fixed.',
		],
		80  => [
			'File operations should use WP_Filesystem methods instead of direct PHP filesystem calls. Found: fclose(). Read more here: https://vip.wordpress.com/documentation/using-wp_filesystem-instead-of-direct-file-access-functions/',
		],
		83  => [
			'File operations should use WP_Filesystem methods instead of direct PHP filesystem calls. Found: fopen(). Read more here: https://vip.wordpress.com/documentation/using-wp_filesystem-instead-of-direct-file-access-functions/',
		],
		86  => [
			'file_get_contents() is uncached. If this is being used to query a remote file please use wpcom_vip_file_get_contents() instead. If it\'s used for a local file please use WP_Filesystem instead. Read more here: https://vip.wordpress.com/documentation/using-wp_filesystem-instead-of-direct-file-access-functions/',
		],
		110 => [
			'Having more than 100 posts returned per page may lead to severe performance problems.',
		],
		114 => [
			'Having more than 100 posts returned per page may lead to severe performance problems.',
		],
		115 => [
			'Having more than 100 posts returned per page may lead to severe performance problems.',
		],
		143 => [
			'attachment_url_to_postid() is uncached, please use wpcom_vip_attachment_url_to_postid() instead.',
		],
		155 => [
			'get_page_by_title() is uncached, please use wpcom_vip_get_page_by_title() instead.',
		],
		159 => [
			'get_children() is uncached and performs a no limit query. Please use get_posts or WP_Query instead. More Info: https://vip.wordpress.com/documentation/vip-go/uncached-functions/',
		],
		170 => [
			'url_to_postid() is uncached, please use wpcom_vip_url_to_postid() instead.',
		],
		211 => [
			'Scripts should be registered/enqueued via `wp_enqueue_script`. This can improve the site\'s performance due to script concatenation.',
		],
		212 => [
			'Scripts should be registered/enqueued via `wp_enqueue_script`. This can improve the site\'s performance due to script concatenation.',
		],
		215 => [
			'Stylesheets should be registered/enqueued via `wp_enqueue_style`. This can improve the site\'s performance due to styles concatenation.',
		],
		216 => [
			'Stylesheets should be registered/enqueued via `wp_enqueue_style`. This can improve the site\'s performance due to styles concatenation.',
		],
		289 => [
			'Switch to blog may not work as expected since it only changes the database context for the blog and does not load the plugins or theme of that site. This means that filters or hooks that the blog you are switching to uses will not run.',
		],
	],
];

require __DIR__ . '/../tests/RulesetTest.php';

// Run the tests!
$test = new RulesetTest( 'WordPress-VIP-Go', $expected );
if ( $test->passes() ) {
	// phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped
	printf( 'All WordPress-VIP-Go tests passed!' . PHP_EOL );
	exit( 0 );
}

exit( 1 );
