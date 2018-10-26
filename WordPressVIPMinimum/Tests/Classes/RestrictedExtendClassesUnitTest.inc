<?php

class OkTestClass extends WPCOM_VIP_CLI_Command { } // Ok - correct class.
class  AnotherOkTestClass  extends   WPC__CLI_Command { } // Ok - spacing + similar class.
class Ok_TestClass extends wpcom_vip_cli_command { } // Ok - lowercase correct class.
class Ok_Test_Class extends WpCoM_VIP_cli_command { } // Ok - mixed case correct class.
class WP_CLI_Command { } // Ok - not extending.

class BadTestClass extends WP_CLI_Command { } // Warning - wrong class.
class AnotherBadTestClass extends wp_CLI_comMand { } // Warning - mixed case wrong class.
class AnotherBad_TestClass   extends   WP_CLI_comMand { } // Warning - spacing + mixed case wrong class.
class Bad_Test_Class extends wp_cli_command { } // Warning - lowercase wrong class.
