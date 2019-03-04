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
		60  => 1,
		62  => 1,
		67  => 1,
		68  => 1,
		72  => 1,
		75  => 1,
		78  => 1,
		177 => 1,
		192 => 1,
		193 => 1,
		199 => 1,
		200 => 1,
		233 => 1,
		235 => 1,
		237 => 1,
		240 => 1,
		241 => 1,
		242 => 1,
		247 => 1,
		248 => 1,
		249 => 1,
		257 => 1,
		258 => 1,
		259 => 1,
		264 => 1,
		267 => 1,
		268 => 1,
		270 => 1,
		271 => 1,
		326 => 1,
		327 => 1,
		328 => 1,
		329 => 1,
		330 => 1,
		331 => 1,
		332 => 1,
		333 => 1,
		334 => 1,
		335 => 1,
		336 => 1,
		337 => 1,
		338 => 1,
		339 => 1,
	],
	'warnings' => [
		84  => 1,
		97  => 1,
		102 => 1,
		106 => 1,
		107 => 1,
		111 => 1,
		114 => 1,
		115 => 1,
		116 => 1,
		118 => 1,
		120 => 1,
		121 => 1,
		124 => 1,
		127 => 1,
		130 => 1,
		131 => 1,
		135 => 1,
		139 => 1,
		140 => 1,
		141 => 1,
		142 => 1,
		143 => 1,
		147 => 1,
		151 => 1,
		154 => 1,
		158 => 1,
		162 => 1,
		166 => 1,
		169 => 1,
		173 => 1,
		181 => 1,
		186 => 1,
		187 => 1,
		188 => 1,
		189 => 1,
		203 => 1,
		204 => 1,
		207 => 1,
		208 => 1,
		211 => 1,
		212 => 1,
		213 => 1,
		216 => 1,
		217 => 1,
		218 => 1,
		219 => 1,
		220 => 1,
		224 => 1,
		229 => 1,
		277 => 1,
		281 => 1,
		285 => 1,
		335 => 1,
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
		72  => [
			'File operations should use WP_Filesystem methods instead of direct PHP filesystem calls. Found: fclose(). Read more here: https://vip.wordpress.com/documentation/using-wp_filesystem-instead-of-direct-file-access-functions/',
		],
		75  => [
			'File operations should use WP_Filesystem methods instead of direct PHP filesystem calls. Found: fopen(). Read more here: https://vip.wordpress.com/documentation/using-wp_filesystem-instead-of-direct-file-access-functions/',
		],
		78  => [
			'file_get_contents() is uncached. If this is being used to query a remote file please use wpcom_vip_file_get_contents() instead. If it\'s used for a local file please use WP_Filesystem instead. Read more here: https://vip.wordpress.com/documentation/using-wp_filesystem-instead-of-direct-file-access-functions/',
		],
		102 => [
			'Having more than 100 posts returned per page may lead to severe performance problems.',
		],
		106 => [
			'Having more than 100 posts returned per page may lead to severe performance problems.',
		],
		107 => [
			'Having more than 100 posts returned per page may lead to severe performance problems.',
		],
		135 => [
			'attachment_url_to_postid() is uncached, please use wpcom_vip_attachment_url_to_postid() instead.',
		],
		147 => [
			'get_page_by_title() is uncached, please use wpcom_vip_get_page_by_title() instead.',
		],
		151 => [
			'get_children() is uncached and performs a no limit query. Please use get_posts or WP_Query instead. More Info: https://vip.wordpress.com/documentation/vip-go/uncached-functions/',
		],
		162 => [
			'url_to_postid() is uncached, please use wpcom_vip_url_to_postid() instead.',
		],
		203 => [
			'Scripts should be registered/enqueued via `wp_enqueue_script`. This can improve the site\'s performance due to script concatenation.',
		],
		204 => [
			'Scripts should be registered/enqueued via `wp_enqueue_script`. This can improve the site\'s performance due to script concatenation.',
		],
		207 => [
			'Stylesheets should be registered/enqueued via `wp_enqueue_style`. This can improve the site\'s performance due to styles concatenation.',
		],
		208 => [
			'Stylesheets should be registered/enqueued via `wp_enqueue_style`. This can improve the site\'s performance due to styles concatenation.',
		],
		281 => [
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
