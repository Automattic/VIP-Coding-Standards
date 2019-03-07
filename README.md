# VIP-Coding-Standards

This project maintains the minimum ruleset of [PHP_CodeSniffer rules](https://github.com/squizlabs/PHP_CodeSniffer) (sniffs) to validate code developed for [WordPress.com VIP](https://vip.wordpress.com/).

This project contains 2 PHP Codesniffer rulesets:

 - `WordPressVIPMinimum` - for use on WordPress.com projects
 - `WordPress-VIP-Go` - for use on VIP Go projects

These rulesets contain only the rules which are considered being "blockers" and "warnings" according to the [WordPress VIP Go documentation](https://vip.wordpress.com/documentation/vip-go/code-review-blockers-warnings-notices/)

The ruleset takes advantage of existing rules in the [WordPress-Coding-Standards](https://github.com/WordPress-Coding-Standards/WordPress-Coding-Standards) project.

Go to https://vip.wordpress.com/documentation/phpcs-review-feedback/ to learn about why various things are flagged as errors vs warnings and what the levels mean for us.

## Installation

### Note

Currently, the VIP Go Coding Standards are built on top of the WordPress Coding Standards 1.* release. If you are using `master` here, you will need to checkout [1.2.1 tag](https://github.com/WordPress-Coding-Standards/WordPress-Coding-Standards/releases/tag/1.2.1).

First, make sure you have WPCS 1.* and PHPCS v3+ installed. If you do not, please refer to the [installation instructions for installing PHP CodeSniffer for WordPress.com VIP](https://vip.wordpress.com/documentation/how-to-install-php-code-sniffer-for-wordpress-com-vip/). Note that VIPCS does not currently work with the `develop` or `master` branch of WPCS.

You will also find additional information at the [WordPress Coding Standards for PHP_CodeSniffer project](https://github.com/WordPress-Coding-Standards/WordPress-Coding-Standards#installation).

Then, clone this repo to your local machine, and add the standard to PHPCodeSniffer by appending the folder you cloned into to the end of the installed paths. e.g.

`phpcs --config-set installed_paths [/path/to/wpcsstandard],[path/to/vipcsstandard],etc`

Alternatively, we recommend the [PHP_CodeSniffer Standards Composer Installer Plugin](https://github.com/Dealerdirect/phpcodesniffer-composer-installer), which handles the registration of all of the installed standards, so there is no need to set the `installed_paths` config value manually, for single or multiple standards.

### Minimal requirements

* [PHPCS 3+](https://github.com/squizlabs/PHP_CodeSniffer/releases)
* [WPCS 1.*](https://github.com/WordPress-Coding-Standards/WordPress-Coding-Standards/releases)

### Setup note

Should you wish to run both standards (WordPress.com VIP minimum standard & WordPress.com VIP coding standard), you can add both to PHPCS by running the following configuration command:

`phpcs --config-set installed_paths [/path/to/standard],[path/to/standard]`

Note the comma separating each standard.
