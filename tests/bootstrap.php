<?php
/**
 * Bootstrap file for running the tests on PHPCS 3.x.
 *
 * Load the PHPCS autoloader and the PHPCS cross-version helper.
 *
 * {@internal We need to load the PHPCS autoloader first, so as to allow their
 * auto-loader to find the classes we want to alias for PHPCS 3.x.
 * This aliasing has to be done before any of the test classes are loaded by the
 * PHPCS native unit test suite to prevent fatal errors.
 *
 * @package VIPCS\WordPressVIPMinimum
 * @license gpl-2.0-or-later
 */

if ( ! defined( 'PHP_CODESNIFFER_IN_TESTS' ) ) {
	define( 'PHP_CODESNIFFER_IN_TESTS', true );
}

$ds = DIRECTORY_SEPARATOR;

// Get the PHPCS dir from an environment variable.
$phpcsDir          = getenv( 'PHPCS_DIR' );
$composerPHPCSPath = dirname( __DIR__ ) . $ds . 'vendor' . $ds . 'squizlabs' . $ds . 'php_codesniffer';

// This may be a Composer install.
if ( $phpcsDir === false && is_dir( $composerPHPCSPath ) ) {
	$phpcsDir = $composerPHPCSPath;
} elseif ( $phpcsDir !== false ) {
	// PHPCS in a custom directory.
	$phpcsDir = realpath( $phpcsDir );
}

// Try and load the PHPCS autoloader.
if ( $phpcsDir !== false
	&& file_exists( $phpcsDir . $ds . 'autoload.php' )
	&& file_exists( $phpcsDir . $ds . 'tests' . $ds . 'bootstrap.php' )
) {
	require_once $phpcsDir . $ds . 'autoload.php';

	/*
	 * As of PHPCS 3.1, PHPCS supports PHPUnit 6.x and above, but needs a bootstrap,
	 * so load it if it's available.
	 */
	require_once $phpcsDir . $ds . 'tests' . $ds . 'bootstrap.php';
} else {
	echo 'Uh oh... can\'t find PHPCS.

If you use Composer, please run `composer install`.
Otherwise, make sure you set a `PHPCS_DIR` environment variable in your phpunit.xml file
pointing to the PHPCS directory.

Please read the contributors guidelines for more information:
https://github.com/Automattic/VIP-Coding-Standards/blob/develop/.github/CONTRIBUTING.md
';

	die( 1 );
}

/*
 * Set the PHPCS_IGNORE_TEST environment variable to ignore tests from other standards.
 */
$vipcsStandards = [
	'WordPressVIPMinimum' => true,
];

$allStandards   = PHP_CodeSniffer\Util\Standards::getInstalledStandards();
$allStandards[] = 'Generic';

$standardsToIgnore = [];
foreach ( $allStandards as $standard ) {
	if ( isset( $vipcsStandards[ $standard ] ) === true ) {
		continue;
	}

	$standardsToIgnore[] = $standard;
}

$standardsToIgnoreString = implode( ',', $standardsToIgnore );

// phpcs:ignore WordPress.PHP.DiscouragedPHPFunctions.runtime_configuration_putenv -- This is test code, not production.
putenv( "PHPCS_IGNORE_TESTS={$standardsToIgnoreString}" );

// Clean up.
unset( $ds, $phpcsDir, $composerPHPCSPath, $allStandards, $standardsToIgnore, $standard, $standardsToIgnoreString );
