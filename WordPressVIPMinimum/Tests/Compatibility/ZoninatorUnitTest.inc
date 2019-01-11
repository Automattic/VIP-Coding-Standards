<?php

wpcom_vip_load_plugin( 'zoninator', 'plugins', '0.8' ); // NOK.
wpcom_vip_load_plugin( "zoninator", 'plugins', '0.8' ); // NOK.
wpcom_vip_load_plugin( 'zoninator', 'plugins', '0.9' ); // NOK.
wpcom_vip_load_plugin( 'zoninator', 'plugins', '1.9' ); // NOK.

wpcom_vip_load_plugin( 'zoninator', 'plugins', '0.7' ); // OK.
wpcom_vip_load_plugin( 'zoninator', 'plugins', '0.6' ); // OK.
wpcom_vip_load_plugin( 'zoninator', 'plugins', '0.6' ); // OK.

wpcom_vip_load_plugin( 'zoninator', 'plugins' ); // OK.
wpcom_vip_load_plugin( 'zoninator' ); // OK.