# Contributing to VIP Coding Standards

Hi, thank you for your interest in contributing to the VIP Coding Standards! We look forward to working with you.

## Reporting Bugs

Before reporting a bug, you should check what sniff an error is coming from.
Running `phpcs` with the `-s` flag will show the name of the sniff with each error.

Bug reports containing a minimal code sample which can be used to reproduce the issue are highly appreciated as those are most easily actionable.

### Upstream Issues

Since VIPCS employs many sniffs that are part of PHPCS, and makes use of WordPress Coding Standards sniffs, sometimes an issue will be caused by a bug upstream and not in VIPCS itself. If the error message in question doesn't come from a sniff whose name starts with `WordPressVIPMinimum`, the issue is probably a bug in PHPCS itself, and should be [reported there](https://github.com/squizlabs/PHP_CodeSniffer/issues).

----

## tl;dr Composer Scripts

This package contains Composer scripts to quickly run the developer checks which are described (with setups) further below.

After `composer install`, you can do:

 - `composer test`: **Run all checks and tests** - this should pass cleanly before you submit a pull request.
     - `composer lint`: Just run PHP and XML linters.
     - `composer phpcs`: Just run PHPCS against this package.
     - `composer phpunit`: Just run the unit tests.
     - `composer ruleset`: Just run the ruleset tests.

## Branches

Ongoing development will be done in features branches then pulled against the `master` branch, with work for VIP Go currently done in the `vip-go` branch.

To contribute an improvement to this project, fork the repo and open a pull request to the relevant branch. Alternatively, if you have push access to this repo, create a feature branch prefixed by `feature/` and then open an intra-repo PR from that branch to the right branch.

## Code Standards for this project

The sniffs and test files - not test _case_ files! - for VIPCS should be written such that they pass the `WordPress-Extra` and the `WordPress-Docs` code standards using the custom ruleset as found in `.phpcs.xml.dist`.

## Public properties

When writing sniffs, always remember that any `public` sniff property can be overruled via a custom ruleset by the end-user.
Only make a property `public` if that is the intended behaviour.

When you introduce new `public` sniff properties, or your sniff extends a class from which you inherit a `public` property, please don't forget to update the [public properties wiki page](https://github.com/WordPress-Coding-Standards/WordPress-Coding-Standards/wiki/Customizable-sniff-properties) with the relevant details once your PR has been merged into the `develop` branch.

## Unit Testing

### Pre-requisites
* VIP Coding Standards
* WordPress-Coding-Standards
* PHP_CodeSniffer 3.x
* PHPUnit 4.x, 5.x, 6.x or 7.x

The VIP Coding Standards use the PHP_CodeSniffer native unit test suite for unit testing the sniffs.

Presuming you have installed PHP_CodeSniffer, VIP Coding Standards, and the WordPress-Coding-Standards as [noted in the WPCS README](https://github.com/WordPress-Coding-Standards/WordPress-Coding-Standards#how-to-use-this), all you need now is `PHPUnit`.

N.B.: If you installed VIPCS using Composer, make sure you used `--prefer-source` or run `composer install --prefer-source` now to make sure the unit tests are available.

If you already have PHPUnit installed on your system: Congrats, you're all set.

If not, you can navigate to the directory where the `PHP_CodeSniffer` repo is checked out and do `composer install` to install the `dev` dependencies.
Alternatively, you can [install PHPUnit](https://phpunit.de/manual/5.7/en/installation.html) as a PHAR file.

### Before running the unit tests

N.B.: _If you used Composer to install the WordPress Coding Standards, you can skip this step._

For the unit tests to work, you need to make sure PHPUnit can find your `PHP_CodeSniffer` install.

The easiest way to do this is to add a `phpunit.xml` file to the root of your VIPCS installation and set a `PHPCS_DIR` environment variable from within this file. Make sure to adjust the path to reflect your local setup.
```xml
<phpunit xmlns:xsi="http://www.w3.org/2001/XMLSchema-instance"
	xsi:noNamespaceSchemaLocation="https://schema.phpunit.de/6.3/phpunit.xsd"
	beStrictAboutTestsThatDoNotTestAnything="false"
	bootstrap="./tests/bootstrap.php"
	backupGlobals="true"
	colors="true">
	<php>
		<env name="PHPCS_DIR" value="/path/to/PHP_CodeSniffer/"/>
	</php>
</phpunit>
```

### Running the unit tests

* Make sure you have registered the directory in which you installed VIPCS with PHPCS using;
    
    ```sh
    phpcs --config-set installed_paths path/to/VIPCS
    ```
* Navigate to the directory in which you installed VIPCS.
* To run the unit tests:
    
    ```sh
    phpunit --filter WordPressVIPMinimum $PHPCS_DIR/tests/AllTests.php
    ```

Expected output:
```
PHPUnit 7.2.6 by Sebastian Bergmann and contributors.

.................................                                 33 / 33 (100%)

Tests generated 29 unique error codes; 0 were fixable (0%)

Time: 268 ms, Memory: 30.00MB
```

### Unit Testing conventions

If you look inside the `WordPressVIPMinimum/Tests` subdirectory, you'll see the structure mimics the `WordPressVIPMinimum/Sniffs` subdirectory structure. For example, the `WordPressVIPMinimum/Sniffs/VIP/WPQueryParams.php` sniff has its unit test class defined in `WordPressVIPMinimum/Tests/VIP/WPQueryParamsUnitTest.php` which checks the `WordPressVIPMinimum/Tests/VIP/WPQueryParamsUnitTest.inc` test case file. See the file naming convention?

Lets take a look at what's inside `WPQueryParamsUnitTest.php`:

```php
...
namespace WordPressVIPMinimum\Tests\VIP;

use PHP_CodeSniffer\Tests\Standards\AbstractSniffUnitTest;

/**
 * Unit test class for the WP_Query params sniff.
 *
 * @package VIPCS\WordPressVIPMinimum
 */
class WPQueryParamsUnitTest extends AbstractSniffUnitTest {

	/**
	 * Returns the lines where errors should occur.
	 *
	 * @return array <int line number> => <int number of errors>
	 */
	public function getErrorList() {
		return array(
			5  => 1,
			17 => 1,
		);
	}
...
```

Also note the class name convention. The method `getErrorList()` MUST return an array of line numbers indicating errors (when running `phpcs`) found in `WordPressVIPMinimum/Tests/VIP/WPQueryParamsUnitTest.inc`.
If you run:

```sh
$ cd /path-to-cloned/phpcs
$ ./bin/phpcs --standard=WordPressVIPMinimum -s --sniffs=WordPressVIPMinimum.VIP.WPQueryParams /path/to/WordPressVIPMinimum/Tests/VIP/WPQueryParamsUnitTest.inc
...
E 1 / 1 (100%)



FILE: /path/to/vipcs/WordPressVIPMinimum/Tests/VIP/WPQueryParamsUnitTest.inc
--------------------------------------------------------------------------------------------------------------------------------
FOUND 2 ERRORS AND 2 WARNINGS AFFECTING 4 LINES
--------------------------------------------------------------------------------------------------------------------------------
  4 | WARNING | Using `post__not_in` should be done with caution. (WordPressVIPMinimum.VIP.WPQueryParams.post__not_in)
  5 | ERROR   | Setting `suppress_filters` to `true` is probihited.
    |         | (WordPressVIPMinimum.VIP.WPQueryParams.suppressFiltersTrue)
 11 | WARNING | Using `post__not_in` should be done with caution. (WordPressVIPMinimum.VIP.WPQueryParams.post__not_in)
 17 | ERROR   | Setting `suppress_filters` to `true` is probihited.
    |         | (WordPressVIPMinimum.VIP.WPQueryParams.suppressFiltersTrue)
--------------------------------------------------------------------------------------------------------------------------------
....
```
You'll see the line number and number of ERRORs we need to return in the `getErrorList()` method.

The `--sniffs=...` directive limits the output to the sniff you are testing.

## Ruleset Tests

The ruleset tests, previously named here as _integration tests_, are our way of ensuring that _rulesets_ do check for the violations we expect them to.

An example where it might not would be when a ruleset references a local sniff or a sniff from upstream (WPCS or PHPCS), but that the violation code, sniff name or category name has changed. Without a ruleset test, this would go unnoticed.

The `composer test` or `composer ruleset` commands run the `ruleset-test.php` files (one for each standard), which internally run `phpcs` against the "dirty" test files (`ruleset-test.inc`), and looks out for a known number of errors, warnings, and messages on each line. This is then compared against the expected errors, warnings and messages to see if there are any missing or unexpected violations or difference in messages.

When adding or changing a sniff, the ruleset test files should be updated to match.
