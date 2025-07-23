<?php

class OkTestClass extends WPCOM_VIP_CLI_Command { } // Ok - correct class.
class  AnotherOkTestClass  extends   WPC__CLI_Command { } // Ok - spacing + similar class.
class Ok_TestClass extends \wpcom_vip_cli_command { } // Ok - lowercase correct class.
$ok = new class extends WpCoM_VIP_cli_command { }; // Ok - mixed case correct class.
class WP_CLI_Command { } // Ok - not extending.

class BadTestClass extends WP_CLI_Command { } // Warning - wrong class.
class AnotherBadTestClass extends \wp_CLI_comMand { } // Warning - mixed case wrong class.
$bad = new class()   extends   WP_CLI_comMand { }; // Warning - spacing + mixed case wrong class.
class Bad_Test_Class extends wp_cli_command { } // Warning - lowercase wrong class.

// phpcs:set WordPressVIPMinimum.Classes.RestrictedExtendClasses exclude[] wp_cli
class IgnoredClass extends WP_CLI_Command { } // OK, group is excluded.

// Reset to the default value.
// phpcs:set WordPressVIPMinimum.Classes.RestrictedExtendClasses exclude[]

class Ok_NotTheTargetFQN extends \Fully\Qualified\WP_CLI_Command { } // Ok - not the right namespace.
class Ok_NotTheTargetPQN extends Partially\Qualified\WP_CLI_Command { } // Ok - not the right namespace.
class Bad_NamespaceRelative extends namespace\WP_CLI_Command { } // Warning - this is not a namespaced file, so "namespace" resolves to the global namespace.
