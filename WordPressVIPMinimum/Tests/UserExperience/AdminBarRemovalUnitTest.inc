<?php

add_filter( 'show_admin_bar', '__return_false' ); // Bad.
add_filter( 'show_admin_bar', '__return_true' ); // Ok.

show_admin_bar( false ); // Bad.
show_admin_bar( true ); // Ok.

add_filter( 'show_admin_bar', 'my_own_return_false' ); // Bad.

// phpcs:set WordPressVIPMinimum.UserExperience.AdminBarRemoval remove_only false
add_filter( 'show_admin_bar', '__return_true' ); // Bad.
show_admin_bar( true ); // Bad.
// phpcs:set WordPressVIPMinimum.UserExperience.AdminBarRemoval remove_only true

// Testing T_CONSTANT_ENCAPSED_STRING.
echo '<style type="text/css">
#wpadminbar {
	visibility: hidden; /* Bad. */
	display: none; /* Bad. */
	opacity: 0; /* Bad. */
}
</style>';

// Various text before/after combinations.
echo '<style type="text/css">.show-admin-bar { visibility: hidden; }</style>'; // Bad.

echo 'Some text about .show-admin-bar before a style tag <style type="text/css">.nothing-here { display: ' . 'none' . '; }</style>'; // Ok.
echo '<style type="text/css">.nothing-here { display: ' . 'none' . '; }</style>Some text about .show-admin-bar after a style tag'; // Ok.

echo '<style type="text/css">';
echo '.show-admin-bar { visibility: hidden; }'; // Bad.
echo '</style>';

// Testing T_DOUBLE_QUOTED_STRING.
echo "
<style type=\"text/css\">
	.show-admin-bar {
		visibility: $hidden; /* Ok, value not 'hidden'. */
	}
</style>";

// Testing T_HEREDOC.
$style = <<<EOT
<style type="text/css">
	.show-admin-bar {
		visibility: $hidden; /* Ok, value not 'hidden'. */
	}
</style>
EOT;

// Testing T_NOWDOC.
$style = <<<'EOT'
<style type="text/css">
	.show-admin-bar {
		visibility: hidden; /* Bad. */
		display: none; /* Bad. */
		opacity: 0; /* Bad. */
	}
</style>
EOT;

// Testing T_INLINE_HTML
?>

<style type="text/css">
#wpadminbar {
	visibility: hidden; /* Bad. */
	display: none; /* Bad. */
	opacity: 0; /* Bad. */
}
#not-wpadminbar {
	visibility: hidden; /* OK. */
	display: none; /* OK. */
	opacity: 0; /* OK. */
}
</style>

<style type="text/css">
	.show-admin-bar {
		visibility: hidden; /* Bad. */
		display: none; /* Bad. */
		opacity: 0; /* Bad. */
	}
</style>

<?php
// phpcs:set WordPressVIPMinimum.UserExperience.AdminBarRemoval remove_only false
$style = <<<EOT
<style type="text/css">
	.show-admin-bar {
		visibility: $hidden; /* Bad. */
	}
	.my-admin-bar {
		visibility: $hidden; /* OK. */
	}
</style>
EOT;
?>

<style type="text/css">
	.show-admin-bar {
		visibility: visible; /* Bad. */
		display: block; /* Bad. */
		opacity: 1; /* Bad. */
	}
</style>
<?php
/* phpcs:set WordPressVIPMinimum.UserExperience.AdminBarRemoval remove_only true */

/*
 * Safeguard against false positives for method calls and namespaced function calls
 * and various other syntaxes.
 */
my\ns\show_admin_bar( false ); // OK.
$this->show_admin_bar( false ); // OK.
$this?->show_admin_bar( false ); // OK.
MyClass::add_filter( 'show_admin_bar', '__return_false' ); // OK.
echo ADD_FILTER; // OK.
namespace\add_filter( 'show_admin_bar', '__return_false' ); // OK.

#[Show_Admin_Bar(false)] // OK. PHP 8.0+ class instantiation via an attribute. Can't contain a nested function call anyway.
function foo() {}

array_walk($filters, \add_filter(...),); // OK. PHP 8.1 first class callable.

// Incomplete function calls, should be ignored by the sniff.
$incorrect_but_ok = show_admin_bar(); // OK.
$incorrect_but_ok = add_filter(); // OK.

// Safeguard that the sniff only flags the "show_admin_bar" filter.
add_filter( 'not_show_admin_bar', '__return_false', ); // OK.

// Document that dynamic values will be flagged.
show_admin_bar( $unknown ); // Bad.
add_filter( 'show_admin_bar', $callable, ); // Bad.
add_filter('show_admin_bar', ...$params); // Bad. PHP 5.6 argument unpacking.

// Document that fully qualified function calls and functions in unconventional case will correctly be recognized.
\add_filter( "show_admin_bar", '__return_true' ); // OK.
\Add_Filter( 'show_admin_bar', "__return_false", ); // Bad.

\show_Admin_bar( true, ); // OK.
\show_admin_bar( false ); // Bad.
\SHOW_ADMIN_BAR( false, ); // Bad.

// Comments in parameters should be ignored.
show_admin_bar( true /* turn it on */ ); // OK.
add_filter(
	// Admin bar gives access to admin for users with the right permissions.
	'show_admin_bar',
	'__return_false'
); // Bad.
add_filter(
	'show_admin_bar',
	// Turn it on.
	'__return_true'
); // OK.

// Safeguard handling of function calls using PHP 8.0+ named parameters.
show_admin_bar( shown: false ); // OK, well not really, typo in param name, but that's not the concern of the sniff.
\show_admin_bar( show: true ); // OK.
show_admin_bar( show: $toggle ); // Bad.

add_filter(callback: '__return_false', priority: 10); // OK, well, not really, missing required $hook_name param, but that's not the concern of this sniff.
\add_filter(callback: '__return_false', hook_name: 'not_our_target'); // OK.
add_filter(hookName: 'show_admin_bar', callback: '__return_false',); // OK, well, not really, typo in param name, but that's not the concern of the sniff.
add_filter( callback: '__return_true', hook_name: 'show_admin_bar', ); // Ok.
\add_filter( callback: '__return_false', hook_name: 'show_admin_bar', ); // Bad.
