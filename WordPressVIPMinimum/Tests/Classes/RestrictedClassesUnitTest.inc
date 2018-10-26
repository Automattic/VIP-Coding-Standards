<?php

class OkTestClass extends WPCOM_VIP_CLI_Command { } // Ok.
class  AnotherOkTestClass  extends   WPC__CLI_Command { } // Ok.
class Ok_TestClass extends wpcom_vip_cli_command { } // Ok.
class Ok_Test_Class extends WpCoM_VIP_cli_command { } // Ok.

class BadTestClass extends WP_CLI_Command { } // Warning.
class AnotherBadTestClass extends wp_CLI_comMand { } // Warning.
class AnotherBad_TestClass   extends   WP_CLI_comMand { } // Warning.
class Bad_Test_Class extends wp_cli_command { } // Warning.
