# Change Log for VIP Coding Standards

All notable changes to this project will be documented in this file.

The format is based on [Keep a Changelog](https://keepachangelog.com/en/1.0.0/), and this project adheres to [Semantic Versioning](https://semver.org/spec/v2.0.0.html).

## [2.0.0] - 2019-07-12

This release switches from having WPCS `1.*` as a dependency, to WPCS `2.*`. It is not compatible with WPCS `1.*`.

The sniffs in WPCS `2.*` are more accurate, so you may see new violations there weren't being reported before, and a reduction in violations for false positives.  

### Added

- Switch to using WPCS `2.*`.
	- Remove reference to WPCS's `PHPAliases.php`.
	- Remove WPCS `1.*`'s `WordPress.VIP` references from rulesets.
	- Bump PHPCS minimum required version to 3.3.1.
	- Update the WPCS namespace.
	- Update ruleset and ruleset test to account for WPCS 2's switch to `WordPress.PHP.IniSet` sniff.
	- Update ruleset test for WPCS security sniffs.
	- Update `DiscouragedPHPFunctions` group exclusion in `WordPressVIPMinimum` ruleset.
	
### Changed 

- Downgrade use of file operation functions from Error to Warning:
	- `delete`
	- `file_put_contents`
	- `flock`
	- `fputcsv`
	- `fputs`
	- `fwrite`
	- `ftruncate`
	- `is_writable`
	- `is_writeable`
	- `link`
	- `rename`
	- `symlink`
	- `tempnam`
	- `touch`
	- `unlink`
	- `fclose`
	- `fopen`
	- `file_get_contents`
- Simplify Travis config.
- Switch references from `vip.wordpress.com` to `wpvip.com`.
- Documentation updates.
- Switch development to a `git-flow` workflow.

## Fixed

- Fixed CS violations in VIPCS code.

## [1.0.0] - 2019-04-24

This release contains many breaking changes.

It requires PHP `>= 5.6`, PHPCS `3.2.3+`, and WPCS `1.*`. It does not work with WPCS `2.*`.

### Reorganisation and Renaming

The sniffs in VIPCS have been reorganised into different categories, with new sniff names and new violation codes. The changes are detailed in the table below. If you reference any of the old violations in your custom ruleset (to change severity, type, or message), or with `// phpcs:ignore` or `// phpcs:disable`, you will need to updates these references to the new violation codes.

| Original Violation | New Violation |
|--------------------|---------------|
| `WordPressVIPMinimum.Actions.PreGetPostSniff.PreGetPosts` | `WordPressVIPMinimum.Hooks.PreGetPosts.PreGetPosts` |
| `WordPressVIPMinimum.Cache.BatcacheWhitelistedParams.strippedGetParam` | `WordPressVIPMinimum.Performance.BatcacheWhitelistedParams.StrippedGetParam` |
| `WordPressVIPMinimum.Cache.CacheValueOverride.CacheValueOverride` | `WordPressVIPMinimum.Performance.CacheValueOverride.CacheValueOverride` |
| `WordPressVIPMinimum.Cache.LowExpiryCacheTime.LowCacheTime` | `WordPressVIPMinimum.Performance.LowExpiryCacheTime.LowCacheTime` |
| `WordPressVIPMinimum.Classes.DeclarationCompatibility.DeclarationCompatibility` | No change  |
| `WordPressVIPMinimum.Classes.RestrictedExtendClasses.wp_cli_wp_cli_command` | `WordPressVIPMinimum.Classes.RestrictedExtendClasses.wp_cli` |
| `WordPressVIPMinimum.Constants.ConstantsRestrictions.ConstantRestrictions` | `WordPressVIPMinimum.Constants.RestrictedConstants.DefiningRestrictedConstant`<br>`WordPressVIPMinimum.Constants.RestrictedConstants.UsingRestrictedConstant` |
| `WordPressVIPMinimum.Constants.ConstantString.NotCheckingConstantName` | No change |
| `WordPressVIPMinimum.Files.IncludingFile.IncludingFile` | `WordPressVIPMinimum.Files.IncludingFile.UsingVariable`<br>`WordPressVIPMinimum.Files.IncludingFile.UsingCustomConstant`<br>`WordPressVIPMinimum.Files.IncludingFile.UsingCustomFunction`<br>`WordPressVIPMinimum.Files.IncludingFile.NotAbsolutePath`<br>`WordPressVIPMinimum.Files.IncludingFile.ExternalURL`<br>`WordPressVIPMinimum.Files.IncludingFile.RestrictedConstant` |
| `WordPressVIPMinimum.Files.IncludingNonPHPFile.IncludingSVGCSSFile` | `WordPressVIPMinimum.Files.IncludingNonPHPFile.IncludingSVGCSSFile` |
| `WordPressVIPMinimum.Files.IncludingNonPHPFile.IncludingNonPHPFile` | `WordPressVIPMinimum.Files.IncludingNonPHPFile.IncludingNonPHPFile` |
| `WordPressVIPMinimum.Filters.AlwaysReturn.voidReturn` | `WordPressVIPMinimum.Hooks.AlwaysReturnInFilter.VoidReturn` |
| `WordPressVIPMinimum.Filters.AlwaysReturn.missingReturnStatement` | `WordPressVIPMinimum.Hooks.AlwaysReturnInFilter.MissingReturnStatement` |
| `WordPressVIPMinimum.Filters.RestrictedHook.UploadMimes` | `WordPressVIPMinimum.Hooks.RestrictedHooks.upload_mimes_upload_mimes` |
| `WordPressVIPMinimum.Filters.RestrictedHook.HighTimeout` | `WordPressVIPMinimum.Hooks.RestrictedHooks.http_request_http_request_args`<br>`WordPressVIPMinimum.Hooks.RestrictedHooks.http_request_http_request_timeout` |
| `WordPressVIPMinimum.Functions.CheckReturnValue.CheckReturnValue` | `WordPressVIPMinimum.Functions.CheckReturnValue.DirectFunctionCall`<br>`WordPressVIPMinimum.Functions.CheckReturnValue.NonCheckedVariable` |
| `WordPressVIPMinimum.Functions.CreateFunction.CreateFunction` | `WordPressVIPMinimum.Functions.RestrictedFunctions.create_function_create_function` |
| `WordPressVIPMinimum.Functions.DynamicCalls.DynamicCalls` | No change |
| `WordPressVIPMinimum.Functions.StripTags.StripTagsOneParameter` | No change |
| `WordPressVIPMinimum.Functions.StripTags.StripTagsTwoParameters` | No change |
| `WordPressVIPMinimum.JS.DangerouslySetInnerHTML.dangerouslySetInnerHTML` | `WordPressVIPMinimum.JS.DangerouslySetInnerHTML.Found` |
| `WordPressVIPMinimum.JS.HTMLExecutingFunctions.html` | No change |
| `WordPressVIPMinimum.JS.HTMLExecutingFunctions.append` | No change |
| `WordPressVIPMinimum.JS.HTMLExecutingFunctions.write` | No change |
| `WordPressVIPMinimum.JS.HTMLExecutingFunctions.writeln` | No change |
| `WordPressVIPMinimum.JS.InnerHTML.innerHTML` | `WordPressVIPMinimum.JS.InnerHTML.Found` |
| `WordPressVIPMinimum.JS.StringConcat.StringConcatNext` | `WordPressVIPMinimum.JS.StringConcat.Found` |
| `WordPressVIPMinimum.JS.StrippingTags.VulnerableTagStripping` | No change |
| `WordPressVIPMinimum.JS.Window.VarAssignment` | No change |
| `WordPressVIPMinimum.JS.Window.location` | No change |
| `WordPressVIPMinimum.JS.Window.name` | No change |
| `WordPressVIPMinimum.JS.Window.status` | No change |
| `WordPressVIPMinimum.Plugins.Zoninator.Zoninator` | `WordPressVIPMinimum.Compatibility.Zoninator.RequiresRESTAPI` |
| `WordPressVIPMinimum.TemplatingEngines.UnescapedOutputMustache.{{{` | `WordPressVIPMinimum.Security.Mustache.OutputNotation` |
| `WordPressVIPMinimum.TemplatingEngines.UnescapedOutputMustache.{{&` | `WordPressVIPMinimum.Security.Mustache.VariableNotation` |
| `WordPressVIPMinimum.TemplatingEngines.UnescapedOutputMustache.delimeterChange` | `WordPressVIPMinimum.Security.Mustache.DelimiterChange` |
| `WordPressVIPMinimum.TemplatingEngines.UnescapedOutputMustache.SafeString` | `WordPressVIPMinimum.Security.Mustache.SafeString` |
| `WordPressVIPMinimum.TemplatingEngines.UnescapedOutputTwig.autoescape false` | `WordPressVIPMinimum.Security.Twig.AutoescapeFalse` |
| `WordPressVIPMinimum.TemplatingEngines.UnescapedOutputTwig.raw` | `WordPressVIPMinimum.Security.Twig.RawFound` |
| `WordPressVIPMinimum.TemplatingEngines.UnescapedOutputUnderscorejs.<%=` | `WordPressVIPMinimum.Security.Underscorejs.OutputNotation` |
| `WordPressVIPMinimum.TemplatingEngines.UnescapedOutputUnderscorejs.interpolate` | `WordPressVIPMinimum.Security.Underscorejs.InterpolateFound` |
| `WordPressVIPMinimum.TemplatingEngines.UnescapedOutputVuejs.v-html` | `WordPressVIPMinimum.Security.Vuejs.Found` |
| `WordPressVIPMinimum.Variables.ServerVariables.BasicAuthentication` | No change |
| `WordPressVIPMinimum.Variables.ServerVariables.UserControlledHeaders` | No change |
| `WordPressVIPMinimum.Variables.VariableAnalysis.VariableRedeclaration` | No change |
| `WordPressVIPMinimum.Variables.VariableAnalysis.UndefinedVariables` | `WordPressVIPMinimum.Variables.VariableAnalysis.UndefinedVariable` |
| `WordPressVIPMinimum.Variables.VariableAnalysis.$...` | `WordPressVIPMinimum.Variables.VariableAnalysis.SelfInsideClosure`<br>`WordPressVIPMinimum.Variables.VariableAnalysis.SelfOutsideClass`<br>`WordPressVIPMinimum.Variables.VariableAnalysis.StaticInsideClosure`<br>`WordPressVIPMinimum.Variables.VariableAnalysis.StaticOutsideClass` |
| `WordPressVIPMinimum.Variables.VariableAnalysis.UnusedVariable` | No change |
| `WordPressVIPMinimum.VIP.ErrorControl.ErrorControl` | Replaced with `Generic.PHP.NoSilencedErrors` |
| `WordPressVIPMinimum.VIP.EscapingVoidReturnFunctions.escapingVoidReturningFunction` | `WordPressVIPMinimum.Security.EscapingVoidReturnFunctions.Found` |
| `WordPressVIPMinimum.VIP.ExitAfterRedirect.NoExitInConditional` | `WordPressVIPMinimum.Security.ExitAfterRedirect.NoExitInConditional` |
| `WordPressVIPMinimum.VIP.ExitAfterRedirect.NoExit` | `WordPressVIPMinimum.Security.ExitAfterRedirect.NoExit` |
| `WordPressVIPMinimum.VIP.FetchingRemoteData.fileGetContentsUknown` | `WordPressVIPMinimum.Performance.FetchingRemoteData.FileGetContentsUnknown` |
| `WordPressVIPMinimum.VIP.FetchingRemoteData.fileGetContentsRemoteFile` | `WordPressVIPMinimum.Performance.FetchingRemoteData.FileGetContentsRemoteFile` |
| `WordPressVIPMinimum.VIP.FlushRewriteRules.FlushRewriteRules` | Replaced with `WordPressVIPMinimum.Functions.RestrictedFunctions.flush_rewrite_rules_flush_rewrite_rules` and `WordPressVIPMinimum.Functions.RestrictedFunctions.flush_rewrite_rules_flush_rewrite_rules` |
| `WordPressVIPMinimum.VIP.MergeConflict.HEAD` | `WordPressVIPMinimum.MergeConflict.MergeConflict.Start` |
| `WordPressVIPMinimum.VIP.MergeConflict.DELIMITER` | `WordPressVIPMinimum.MergeConflict.MergeConflict.End`<br>`WordPressVIPMinimum.MergeConflict.MergeConflict.Separator` |
| `WordPressVIPMinimum.VIP.PHPFilterFunctions.MissingThirdParameter` | `WordPressVIPMinimum.Security.PHPFilterFunctions.MissingThirdParameter` |
| `WordPressVIPMinimum.VIP.PHPFilterFunctions.RestrictedFilter` | `WordPressVIPMinimum.Security.PHPFilterFunctions.RestrictedFilter` |
| `WordPressVIPMinimum.VIP.PHPFilterFunctions.MissingSecondParameter` | `WordPressVIPMinimum.Security.PHPFilterFunctions.MissingSecondParameter` |
| `WordPressVIPMinimum.VIP.ProperEscapingFunction.hrefSrcEscUrl` | `WordPressVIPMinimum.Security.ProperEscapingFunction.hrefSrcEscUrl` |
| `WordPressVIPMinimum.VIP.ProperEscapingFunction.htmlAttrNotByEscHTML` | `WordPressVIPMinimum.Security.ProperEscapingFunction.htmlAttrNotByEscHTML` |
| `WordPressVIPMinimum.VIP.RegexpCompare.compare_compare` | `WordPressVIPMinimum.Performance.RegexCompare.compare_compare` |
| `WordPressVIPMinimum.VIP.RegexpCompare.compare_meta_compare` | `WordPressVIPMinimum.Performance.RegexCompare.compare_meta_compare` |
| `WordPressVIPMinimum.VIP.RemoteRequestTimeout.timeout_timeout` | `WordPressVIPMinimum.Performance.RemoteRequestTimeout.timeout_timeout` |
| `WordPressVIPMinimum.VIP.RestrictedFunctions.wp_cache_get_multi.wp_cache_get_multi` | `WordPressVIPMinimum.Functions.RestrictedFunctions.wp_cache_get_multi_wp_cache_get_multi` |
| `WordPressVIPMinimum.VIP.RestrictedFunctions.opcache_opcache_reset` | `WordPressVIPMinimum.Functions.RestrictedFunctions.opcache_opcache_reset` |
| `WordPressVIPMinimum.VIP.RestrictedFunctions.opcache_opcache_invalidate` | `WordPressVIPMinimum.Functions.RestrictedFunctions.opcache_opcache_invalidate` |
| `WordPressVIPMinimum.VIP.RestrictedFunctions.opcache_opcache_compile_file` | `WordPressVIPMinimum.Functions.RestrictedFunctions.opcache_opcache_compile_file` |
| `WordPressVIPMinimum.VIP.RestrictedFunctions.config_settings_opcache_is_script_cached` | `WordPressVIPMinimum.Functions.RestrictedFunctions.config_settings_opcache_is_script_cached` |
| `WordPressVIPMinimum.VIP.RestrictedFunctions.config_settings_opcache_get_status` | `WordPressVIPMinimum.Functions.RestrictedFunctions.config_settings_opcache_get_status` |
| `WordPressVIPMinimum.VIP.RestrictedFunctions.config_settings_opcache_get_configuration` | `WordPressVIPMinimum.Functions.RestrictedFunctions.config_settings_opcache_get_configuration` |
| `WordPressVIPMinimum.VIP.RestrictedFunctions.get_super_admins_get_super_admins` | `WordPressVIPMinimum.Functions.RestrictedFunctions.get_super_admins_get_super_admins` |
| `WordPressVIPMinimum.VIP.RestrictedFunctions.internal_wpcom_vip_irc` | `WordPressVIPMinimum.Functions.RestrictedFunctions.internal_wpcom_vip_irc` |
| `WordPressVIPMinimum.VIP.RestrictedFunctions.rewrite_rules_flush_rewrite_rules` | `WordPressVIPMinimum.Functions.RestrictedFunctions.flush_rewrite_rules_flush_rewrite_rules` |
| `WordPressVIPMinimum.VIP.RestrictedFunctions.attachment_url_to_postid_attachment_url_to_postid` | `WordPressVIPMinimum.Functions.RestrictedFunctions.attachment_url_to_postid_attachment_url_to_postid` |
| `WordPressVIPMinimum.VIP.RestrictedFunctions.dbDelta_dbDelta` | `WordPressVIPMinimum.Functions.RestrictedFunctions.dbDelta_dbDelta` |
| `WordPressVIPMinimum.VIP.RestrictedFunctions.switch_to_blog_switch_to_blog` | `WordPressVIPMinimum.Functions.RestrictedFunctions.switch_to_blog_switch_to_blog` |
| `WordPressVIPMinimum.VIP.RestrictedFunctions.get_page_by_title_get_page_by_title` | `WordPressVIPMinimum.Functions.RestrictedFunctions.get_page_by_title_get_page_by_title` |
| `WordPressVIPMinimum.VIP.RestrictedFunctions.url_to_postid_url_to_postid` | `WordPressVIPMinimum.Functions.RestrictedFunctions.url_to_postid_url_to_postid` |
| `WordPressVIPMinimum.VIP.RestrictedFunctions.url_to_postid_url_to_post_id` | Removed |
| `WordPressVIPMinimum.VIP.RestrictedFunctions.custom_role_add_role` | `WordPressVIPMinimum.Functions.RestrictedFunctions.custom_role_add_role` |
| `WordPressVIPMinimum.VIP.RestrictedFunctions.user_meta_get_user_meta` | `WordPressVIPMinimum.Functions.RestrictedFunctions.user_meta_get_user_meta` |
| `WordPressVIPMinimum.VIP.RestrictedFunctions.user_meta_update_user_meta` | `WordPressVIPMinimum.Functions.RestrictedFunctions.user_meta_update_user_meta` |
| `WordPressVIPMinimum.VIP.RestrictedFunctions.user_meta_delete_user_meta` | `WordPressVIPMinimum.Functions.RestrictedFunctions.user_meta_delete_user_meta` |
| `WordPressVIPMinimum.VIP.RestrictedFunctions.user_meta_add_user_meta` | `WordPressVIPMinimum.Functions.RestrictedFunctions.user_meta_add_user_meta` |
| `WordPressVIPMinimum.VIP.RestrictedFunctions.term_exists_term_exists` | `WordPressVIPMinimum.Functions.RestrictedFunctions.term_exists_term_exists` |
| `WordPressVIPMinimum.VIP.RestrictedFunctions.count_user_posts_count_user_posts` | `WordPressVIPMinimum.Functions.RestrictedFunctions.count_user_posts_count_user_posts` |
| `WordPressVIPMinimum.VIP.RestrictedFunctions.wp_old_slug_redirect_wp_old_slug_redirect` | `WordPressVIPMinimum.Functions.RestrictedFunctions.wp_old_slug_redirect_wp_old_slug_redirect` |
| `WordPressVIPMinimum.VIP.RestrictedFunctions.get_adjacent_post_get_adjacent_post` | `WordPressVIPMinimum.Functions.RestrictedFunctions.get_adjacent_post_get_adjacent_post` |
| `WordPressVIPMinimum.VIP.RestrictedFunctions.get_adjacent_post_get_previous_post` | `WordPressVIPMinimum.Functions.RestrictedFunctions.get_adjacent_post_get_previous_post` |
| `WordPressVIPMinimum.VIP.RestrictedFunctions.get_adjacent_post_get_previous_post_link` | `WordPressVIPMinimum.Functions.RestrictedFunctions.get_adjacent_post_get_previous_post_link` |
| `WordPressVIPMinimum.VIP.RestrictedFunctions.get_adjacent_post_get_next_post` | `WordPressVIPMinimum.Functions.RestrictedFunctions.get_adjacent_post_get_next_post` |
| `WordPressVIPMinimum.VIP.RestrictedFunctions.get_adjacent_post_get_next_post_link` | `WordPressVIPMinimum.Functions.RestrictedFunctions.get_adjacent_post_get_next_post_link` |
| `WordPressVIPMinimum.VIP.RestrictedFunctions.get_intermediate_image_sizes_get_intermediate_image_sizes` | `WordPressVIPMinimum.Functions.RestrictedFunctions.get_intermediate_image_sizes_get_intermediate_image_sizes` |
| `WordPressVIPMinimum.VIP.RestrictedFunctions.wp_is_mobile_wp_is_mobile` | `WordPressVIPMinimum.Functions.RestrictedFunctions.wp_is_mobile_wp_is_mobile` |
| `WordPressVIPMinimum.VIP.RestrictedFunctions.wp_mail_wp_mail` | `WordPressVIPMinimum.Functions.RestrictedFunctions.wp_mail_wp_mail` |
| `WordPressVIPMinimum.VIP.RestrictedFunctions.wp_mail_mail` | `WordPressVIPMinimum.Functions.RestrictedFunctions.wp_mail_mail` |
| `WordPressVIPMinimum.VIP.RestrictedFunctions.is_multi_author_is_multi_author` | `WordPressVIPMinimum.Functions.RestrictedFunctions.is_multi_author_is_multi_author` |
| `WordPressVIPMinimum.VIP.RestrictedFunctions.advanced_custom_fields_the_sub_field` | `WordPressVIPMinimum.Functions.RestrictedFunctions.advanced_custom_fields_the_sub_field` |
| `WordPressVIPMinimum.VIP.RestrictedFunctions.advanced_custom_fields_the_field` | `WordPressVIPMinimum.Functions.RestrictedFunctions.advanced_custom_fields_the_field` |
| `WordPressVIPMinimum.VIP.RestrictedFunctions.wp_remote_get_wp_remote_get` | `WordPressVIPMinimum.Functions.RestrictedFunctions.wp_remote_get_wp_remote_get` |
| `WordPressVIPMinimum.VIP.RestrictedFunctions.cookies_setcookie` | `WordPressVIPMinimum.Functions.RestrictedFunctions.cookies_setcookie` |
| `WordPressVIPMinimum.VIP.RestrictedFunctions.get_posts_get_posts` | `WordPressVIPMinimum.Functions.RestrictedFunctions.get_posts_get_posts` |
| `WordPressVIPMinimum.VIP.RestrictedFunctions.get_posts_wp_get_recent_posts` | `WordPressVIPMinimum.Functions.RestrictedFunctions.get_posts_wp_get_recent_posts` |
| `WordPressVIPMinimum.VIP.RestrictedFunctions.get_posts_get_children` | `WordPressVIPMinimum.Functions.RestrictedFunctions.get_posts_get_children` |
| `WordPressVIPMinimum.VIP.RestrictedFunctions.wpcom_vip_get_term_link_wpcom_vip_get_term_link` | `WordPressVIPMinimum.Functions.RestrictedFunctions.wpcom_vip_get_term_link_wpcom_vip_get_term_link` |
| `WordPressVIPMinimum.VIP.RestrictedFunctions.wpcom_vip_get_term_by_wpcom_vip_get_term_by` | `WordPressVIPMinimum.Functions.RestrictedFunctions.wpcom_vip_get_term_by_wpcom_vip_get_term_by` |
| `WordPressVIPMinimum.VIP.RestrictedFunctions.wpcom_vip_get_category_by_slug_wpcom_vip_get_category_by_slug` | `WordPressVIPMinimum.Functions.RestrictedFunctions.wpcom_vip_get_category_by_slug_wpcom_vip_get_category_by_slug` |
| `WordPressVIPMinimum.VIP.Robotstxt.RobotstxtSniff` | `WordPressVIPMinimum.Hooks.RestrictedHooks.robotstxt_do_robotstxt`<br>`WordPressVIPMinimum.Hooks.RestrictedHooks.robotstxt_robots_txt` |
| `WordPressVIPMinimum.VIP.StaticStrreplace.StaticStrreplace` | `WordPressVIPMinimum.Security.StaticStrreplace.StaticStrreplace` |
| `WordPressVIPMinimum.VIP.TaxonomyMetaInOptions.PossibleTermMetaInOptions` | `WordPressVIPMinimum.Performance.TaxonomyMetaInOptions.PossibleTermMetaInOptions` |
| `WordPressVIPMinimum.VIP.WPQueryParams.suppressFiltersTrue` | `WordPressVIPMinimum.Performance.WPQueryParams.SuppressFiltersTrue` |
| `WordPressVIPMinimum.VIP.WPQueryParams.post__not_in` | `WordPressVIPMinimum.Performance.WPQueryParams.PostNotIn` |

### Added

- New violations:
	- `WordPressVIPMinimum.Functions.RestrictedFunctions.chmod_chgrp`
	- `WordPressVIPMinimum.Functions.RestrictedFunctions.chmod_chown`
	- `WordPressVIPMinimum.Functions.RestrictedFunctions.chmod_chmod`
	- `WordPressVIPMinimum.Functions.RestrictedFunctions.chmod_lchgrp`
	- `WordPressVIPMinimum.Functions.RestrictedFunctions.chmod_lchown`
	- `WordPressVIPMinimum.Functions.RestrictedFunctions.directory_mkdir`
	- `WordPressVIPMinimum.Functions.RestrictedFunctions.directory_rmdir`
	- `WordPressVIPMinimum.Functions.RestrictedFunctions.file_ops_delete`
	- `WordPressVIPMinimum.Functions.RestrictedFunctions.file_ops_file_put_contents`
	- `WordPressVIPMinimum.Functions.RestrictedFunctions.file_ops_flock`
	- `WordPressVIPMinimum.Functions.RestrictedFunctions.file_ops_fputcsv`
	- `WordPressVIPMinimum.Functions.RestrictedFunctions.file_ops_fputs`
	- `WordPressVIPMinimum.Functions.RestrictedFunctions.file_ops_ftruncate`
	- `WordPressVIPMinimum.Functions.RestrictedFunctions.file_ops_fwrite`
	- `WordPressVIPMinimum.Functions.RestrictedFunctions.file_ops_is_writable`
	- `WordPressVIPMinimum.Functions.RestrictedFunctions.file_ops_is_writeable`
	- `WordPressVIPMinimum.Functions.RestrictedFunctions.file_ops_link`
	- `WordPressVIPMinimum.Functions.RestrictedFunctions.file_ops_rename`
	- `WordPressVIPMinimum.Functions.RestrictedFunctions.file_ops_symlink`
	- `WordPressVIPMinimum.Functions.RestrictedFunctions.file_ops_tempnam`
	- `WordPressVIPMinimum.Functions.RestrictedFunctions.file_ops_touch`
	- `WordPressVIPMinimum.Functions.RestrictedFunctions.file_ops_unlink`
	- `WordPressVIPMinimum.Functions.RestrictedFunctions.session_session_abort`
	- `WordPressVIPMinimum.Functions.RestrictedFunctions.session_session_cache_expire`
	- `WordPressVIPMinimum.Functions.RestrictedFunctions.session_session_cache_limiter`
	- `WordPressVIPMinimum.Functions.RestrictedFunctions.session_session_commit`
	- `WordPressVIPMinimum.Functions.RestrictedFunctions.session_session_create_id`
	- `WordPressVIPMinimum.Functions.RestrictedFunctions.session_session_decode`
	- `WordPressVIPMinimum.Functions.RestrictedFunctions.session_session_destroy`
	- `WordPressVIPMinimum.Functions.RestrictedFunctions.session_session_encode`
	- `WordPressVIPMinimum.Functions.RestrictedFunctions.session_session_gc`
	- `WordPressVIPMinimum.Functions.RestrictedFunctions.session_session_get_cookie_params`
	- `WordPressVIPMinimum.Functions.RestrictedFunctions.session_session_id`
	- `WordPressVIPMinimum.Functions.RestrictedFunctions.session_session_is_registered`
	- `WordPressVIPMinimum.Functions.RestrictedFunctions.session_session_module_name`
	- `WordPressVIPMinimum.Functions.RestrictedFunctions.session_session_name`
	- `WordPressVIPMinimum.Functions.RestrictedFunctions.session_session_regenerate_id`
	- `WordPressVIPMinimum.Functions.RestrictedFunctions.session_session_register_shutdown`
	- `WordPressVIPMinimum.Functions.RestrictedFunctions.session_session_register`
	- `WordPressVIPMinimum.Functions.RestrictedFunctions.session_session_reset`
	- `WordPressVIPMinimum.Functions.RestrictedFunctions.session_session_save_path`
	- `WordPressVIPMinimum.Functions.RestrictedFunctions.session_session_set_cookie_params`
	- `WordPressVIPMinimum.Functions.RestrictedFunctions.session_session_set_save_handler`
	- `WordPressVIPMinimum.Functions.RestrictedFunctions.session_session_start`
	- `WordPressVIPMinimum.Functions.RestrictedFunctions.session_session_status`
	- `WordPressVIPMinimum.Functions.RestrictedFunctions.session_session_unregister`
	- `WordPressVIPMinimum.Functions.RestrictedFunctions.session_session_unset`
	- `WordPressVIPMinimum.Functions.RestrictedFunctions.session_session_write_close`
	- `WordPressVIPMinimum.Functions.RestrictedFunctions.site_option_add_site_option`
	- `WordPressVIPMinimum.Functions.RestrictedFunctions.site_option_delete_site_option`
	- `WordPressVIPMinimum.Functions.RestrictedFunctions.site_option_update_site_option`
	- `WordPressVIPMinimum.Performance.NoPaging.nopaging_nopaging`
	- `WordPressVIPMinimum.Performance.OrderByRand.orderby_orderby`
	- `WordPressVIPMinimum.UserExperience.AdminBarRemoval.HidingDetected`
	- `WordPressVIPMinimum.UserExperience.AdminBarRemoval.RemovalDetected`
	- `WordPressVIPMinimum.Variables.RestrictedVariables.user_meta__wpdb__users`
	- `WordPressVIPMinimum.Variables.RestrictedVariables.user_meta__wpdb__usermeta`
	- `WordPressVIPMinimum.Variables.RestrictedVariables.cache_constraints___COOKIE`
	- `WordPressVIPMinimum.Variables.RestrictedVariables.cache_constraints___SERVER__HTTP_USER_AGENT__`
	- `WordPressVIPMinimum.Variables.RestrictedVariables.cache_constraints___SERVER__REMOTE_ADDR__`
	- `WordPressVIPMinimum.Variables.RestrictedVariables.session___session`
- `WordPress-VIP-Go` ruleset test.
- docs about ruleset tests.
- XSD reference and ruleset validation.
- `phpcodesniffer-composer-installer` plugin.
- Copy `has_html_open_tag()` from WPCS.
- Copy `AbstractVariableRestrictionsSniff` from WPCS.

### Changed
- Switch from PHPCS 2.* class names to PHPCS 3.* namespaced classes.
- Refactor all sniffs to extend `VIPCS\Sniff`, which is an extension of `WordPress\Sniff`.
- Tidied up:
	- unused imports
	- unused local variables
	- unused parameters
	- unused private field
	- duplicate array keys
	- redundant self-assignment
	- assignment in conditions
	- not returning void function calls
	- undefined class fields
	- strict comparisons
	- missing scope keywords
	- parentheses to clarify one specific conditional
	- consolidate multiple `isset()` calls
	- consolidate positive nested `if()`’s
	- difference in case for function calls
	- simplified return statements
	- switched to `__DIR__`
	- switched FQCN to import statements
	- use static property
	- use more performant `strpos()` instead of `substr()`
	- split or remove `else` / `elseif` workflows for lower complexity and more comprehension
	- misuse of `array_push()`
	- misuse of `array_values()`
	- misuse of `in_array()`
	- useless `return`
	- redundant `continue`
	- comments that were naming parameters
	- default assignments of `null` to class properties
	- function parameters that match default arg values
	- redundant parentheses
- Ruleset Test improvements:
	- Move mostly duplicate `PHPCS_Ruleset_Test` classes into new `RulesetTest` class.
	- Refactor new class:
		- Accept a ruleset name in the constructor
		- Change public method from `run()` to `passes()`.
		- Break out logic into smaller private methods to make the logic more self-documenting.
		- Refactor variable names in some methods.
		- Decode JSON into objects, not arrays
		- Fix incorrect reference to local `$expected` to `$this->expected`. Somehow, this was still working regardless.
		- Fix bug where it doesn't catch proper number of errors/warnings on a line basis due to order of operations of incrementating after assignment.
		- Add further documentation.
	- Change ruleset test class usage, including adding the name to the "tests passed!" message.
	- Replaced WPCS whitelisting `// XSS OK` comments in this files with `// phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped` comments.
	- Change naming of tests from "integration test" to "ruleset tests", to make it more intuitive exactly what these are (composer script, Travis, bin filename).
- Improve `addError()` and `addWarning()` calls
- Remove `Generic.NamingConventions.ConstructorName.OldStyle` from `WordPress-VIP-Go` ruleset
- Travis: Restrict PHPUnit versions to match PHPCS
- Travis: Use `7.4snapshot` instead of nightly, switch from Trusty to Xenial, remove `sudo: false`.
- `EscapingVoidReturnFunctions`: Fix docs and improve logic
- `AlwaysReturnSniff`: trigger errors instead of warnings, don't give violation for when callback args is passed by reference
- Change `exec()` and `shell_exec()` to be flagged as Error.
- Disallow long array syntax in VIPCS sniff code.
- Remove a `WordPress.CodeAnalysis.AssignmentInCondition.FoundInWhileCondition` exclusion in the PHPCS config for VIPCS itself.
- Update docs.

### Fixed
- Bumped PHPCompatibility `testVersion` to match PHP requirement.
- Silence `Generic.PHP.DisallowShortOpenTag.EchoFound` for `WordPress-VIP-Go` ruleset: `<?=` is no longer reported.
- Silence `WordPress.WP.AlternativeFunctions.file_system_read_fwrite` and `WordPress.WP.AlternativeFunctions.file_system_read_file_put_contents` since we have `WordPressVIPMinimum.Functions.RestrictedFunctions.file_ops_*`.
- Silence Short Echo tags on `WordPress-VIP-Go`.

## 0.4.0 - 2018-12-19

This release contains breaking changes.

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
 - ~~Integration~~ Ruleset tests not running in Travis.
 
### Removed
 - BREAKING: `WordPressVIPMinimum.SVG.HTMLCodeSniff` (SVG support), since it was not working well. You should remove any reference to this in your custom ruleset.
 - `var_dump` from `WordPressVIPMinimum` ruleset since it should be the same type as `var_export`
 - `wpcom_vip_get_page_by_path` from `WordPressVIPMinimum.VIP.RestrictedFunctions`
 - Version check for PHP 7 or less in `WordPressVIPMinimum.Variables.VariableAnalysis` unit test since tests are not failing anymore.

[2.0.0]: https://github.com/Automattic/VIP-Coding-Standards/compare/1.0.0...2.0.0
[1.0.0]: https://github.com/Automattic/VIP-Coding-Standards/compare/0.4.0...1.0.0
