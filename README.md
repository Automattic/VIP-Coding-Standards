# VIP-Coding-Standards
This project maintains the minimum ruleset of [PHP_CodeSniffer rules](https://github.com/squizlabs/PHP_CodeSniffer) (sniffs) to validate code developed for [WordPress.com VIP](https://vip.wordpress.com/).

The ruleset contains only the rules which are considered being "blockers" according to the [WordPress.com VIP documentation](https://vip.wordpress.com/documentation/vip/code-review-what-we-look-for/)

The ruleset takes advantage of existing rules in the [WordPress-Coding-Standards](https://github.com/WordPress-Coding-Standards/WordPress-Coding-Standards) project.

# Installation

Please refer to the [installation instructions of WordPress Coding Standards for PHP_CodeSniffer project](https://github.com/WordPress-Coding-Standards/WordPress-Coding-Standards#installation).

## Minimal requirements

* [PHPCS v2.9.1](https://github.com/squizlabs/PHP_CodeSniffer/releases/tag/2.9.1)
* [WPCS v0.13.1](https://github.com/WordPress-Coding-Standards/WordPress-Coding-Standards/releases/tag/0.13.1)

# Setup note

Should you wish to run both standards (WordPress.com VIP minimum standard & WordPress.com VIP coding standard), you can add both to PHPCS by running the following configuration command:

`phpcs --config-set installed_paths [/path/to/standard],[path/to/standard]`

(note the comma separating each standard)
