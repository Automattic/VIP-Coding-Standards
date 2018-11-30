# Change Log for VIP Coding Standards

## 0.4.0 - 2018-12-19

### Added
 - `WordPressVIPMinimum.Cache.LowExpiryCacheTime` sniff.
 - `WordPressVIPMinimum.Classes.RestrictedExtendedClasses` sniff, for `WP_CLI_Command`.
 - `WordPressVIPMinimum.Filters.RestrictedHooks` sniff, for `upload_mimes`, as well as `http_request_timeout` and `http_request_args` filters which change timeouts, as we typically don't recommend anything above 3s.
 - `WordPressVIPMinimum.Functions.StripTags` sniff.
 - `WordPressVIPMinimum.JS.DangerouslySetInnerHTML` sniff.
 - `WordPressVIPMinimum.JS.Window` sniff.
 - `WordPressVIPMinimum.VIP.PHPFilterFunctions` sniff.
 - GitHub issue templates.
 - `opcache_*()` functions to list of restricted functions.
 - ACF templating function to list of restricted functions.
 - `.editorconfig` to repo.
 - `Generic.PHP.Syntax` to `WordPressVIPMinimum` ruleset.

### Changed
 - Allow unused `$e` when catching exceptions.
 - Improved accuracy of `WordPressVIPMinimum.Files.IncludingFile`
 - Refactor `WordPressVIPMinimum.VIP.RestrictedFunctions` sniff.
 - Include documentation links directly in error message for `WordPressVIPMinimum.VIP.WPQueryParams.post__not_in`.
 - Composer: Normalized `composer.json`.
 - Composer: Bump to PHPCompatibility ^9.
 - Change severity of `WordPress.CodeAnalysis.AssignmentInCondition.Found` to 1 instead of removing it.
 - Increases the PHPCS (3.2.3) and PHP (5.6+) minimum versions to supported and known good values.
 - Travis: Remove PHPUnit 6 workaround.
 - Travis: updates the PHPCS referenced in the Travis file, and remove the PHP 5.5 and 5.4 checks.
 - Travis: Switch to using build stages.
 - Travis: Extract shell scripts out of Travis config file.
 - Silence `WordPressVIPMinimum.Cache.BatcacheWhitelistedParams` for VIP Go ruleset.
 - Silence variable assignment condition rule.
 - Docs: Updated Readme for more accuracy.
 - Docs: Updated VIP link references.
 - Removed string concatenation for messages for better readability.

### Fixed
 - Unreplaced placeholders for violation messages in `WordPressVIPMinimum.VIP.FetchingRemoteDataSniff`.
 - `WordPressVIPMinimum.Filters.AlwaysReturnSniff` not reporting filter callbacks that don't `return` _anywhere_ inside the function body.
 - Incorrect severity level parameters in `WordPressVIPMinimum.Variables.VariableAnalysis` sniff since they are passed in as a string.
 - Detection of double quotes in `WordPressVIPMinimum.Variables.ServerVariables`, add additional server variables and update unit tests.
 - Typo: `WordPressVIPMinimum.Files.IncludingNonPHPFile` messages, switching `get_file_contents` to `file_get_contents`.
 - Typo: "returning" in `WordPressVIPMinimum.Filters.AlwaysReturn.voidReturn` message.
 - Typo: `WordPressVIPMinimum.VIP.WPQueryParameters.suppressFiltersTrue`, switching `probihted` to `prohibited`.
 - Integration tests not running in Travis.
 
### Removed
 - SVG support, since it was not working well.
 - `var_dump` from `WordPressVIPMinimum` ruleset since it should be the same type as `var_export`
 - `wpcom_vip_get_page_by_path` from `WordPressVIPMinimum.VIP.RestrictedFunctions`
 - Version check for PHP 7 or less in `WordPressVIPMinimum.Variables.VariableAnalysis` unit test since tests are not failing anymore.
