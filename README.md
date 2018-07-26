# VIP-Coding-Standards

This project maintains the minimum ruleset of [PHP_CodeSniffer rules](https://github.com/squizlabs/PHP_CodeSniffer) (sniffs) to validate code developed for [WordPress.com VIP](https://vip.wordpress.com/).

This project contains 2 PHP Codesniffer rulesets:

 - `WordPressVIPMinimum` - for use on WordPress.com projects
 - `WordPress-VIP-Go` - for use on VIP Go projects

These ruleset contains only the rules which are considered being "blockers" according to the [WordPress.com VIP documentation](https://vip.wordpress.com/documentation/vip-go/code-review-blockers-warnings-notices/)

The ruleset takes advantage of existing rules in the [WordPress-Coding-Standards](https://github.com/WordPress-Coding-Standards/WordPress-Coding-Standards) project.

# Installation


First, make sure you have WPCS v1+ and PHPCS v3+ installed. If you do not, please refer to the [installation instructions of the WordPress Coding Standards for PHP_CodeSniffer project](https://github.com/WordPress-Coding-Standards/WordPress-Coding-Standards#installation).

Then, clone this repo to your local machine, and add the standard to PHPCodeSniffer by appending the folder you cloned into to the end of the installed paths. e.g.

`phpcs --config-set installed_paths [/path/to/wpcsstandard],[path/to/vipcsstandard],etc`

## Minimal requirements

* [PHPCS v3](https://github.com/squizlabs/PHP_CodeSniffer/releases/tag/3.3.0)
* [WPCS v1.0.0](https://github.com/WordPress-Coding-Standards/WordPress-Coding-Standards/releases/tag/1.0.0)

# Setup note

Should you wish to run both standards (WordPress.com VIP minimum standard & WordPress.com VIP coding standard), you can add both to PHPCS by running the following configuration command:

`phpcs --config-set installed_paths [/path/to/standard],[path/to/standard]`

(note the comma separating each standard)
