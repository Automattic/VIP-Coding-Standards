# VIP Coding Standards

This project contains [PHP_CodeSniffer (PHPCS) sniffs and rulesets](https://github.com/squizlabs/PHP_CodeSniffer) to validate code developed for [WordPress.com VIP](https://wpvip.com/).

This project contains two rulesets:

 - `WordPressVIPMinimum` - for use with projects on the (older) WordPress.com VIP platform.
 - `WordPress-VIP-Go` - for use with projects on the (newer) VIP Go platform.

These rulesets contain only the rules which are considered to be "errors" and "warnings" according to the [WordPress VIP Go documentation](https://wpvip.com/documentation/vip-go/code-review-blockers-warnings-notices/)

The rulesets use rules from the [WordPress Coding Standards](https://github.com/WordPress/WordPress-Coding-Standards) (WPCS) project.

Go to https://wpvip.com/documentation/phpcs-review-feedback/ to learn about why violations are flagged as errors vs warnings and what the levels mean.

## Minimal requirements

* [PHPCS 3.3.1+](https://github.com/squizlabs/PHP_CodeSniffer/releases)
* [WPCS 2.*](https://github.com/WordPress-Coding-Standards/WordPress-Coding-Standards/releases)

## Installation

`composer require automattic/vipwpcs`, or `composer g require automattic/vipwpcs` if installing globally. 

This will install the latest compatible versions of PHPCS and WPCS. 


Please refer to the [installation instructions for installing PHP_CodeSniffer for WordPress.com VIP](https://wpvip.com/documentation/how-to-install-php-code-sniffer-for-wordpress-com-vip/) for more details.

We recommend the [PHP_CodeSniffer Standards Composer Installer Plugin](https://github.com/Dealerdirect/phpcodesniffer-composer-installer), which handles the registration of all of the installed standards, so there is no need to set the `installed_paths` config value manually, for single or multiple standards.

Alternatively, you should register the standard to PHPCS by appending the VIPCS directory to the end of the installed paths. e.g.

`phpcs --config-set installed_paths [/path/to/wpcsstandard],[path/to/vipcsstandard],etc`

## Contribution

Please see [CONTRIBUTION.md](CONTRIBUTING.md).

## License

Licensed under [GPL-2.0-or-later](LICENSE.md).
