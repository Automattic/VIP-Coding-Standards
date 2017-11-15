<?php

wpcom_vip_irc(); // Bad.

get_children(); // Bad. Warning.

wp_cache_get_multi(); // Bad.

get_super_admins(); // Bad.

flush_rewrite_rules(); // Bad.

attachment_url_to_postid(); // Bad.

wpcom_vip_attachment_url_to_postid(); // OK.

wp_mail(); // Bad. Warning.

mail(); // Bad. Warning.