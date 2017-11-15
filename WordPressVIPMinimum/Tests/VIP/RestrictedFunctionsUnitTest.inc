<?php

wpcom_vip_irc(); // Bad.

get_children(); // Bad. Warning.

wp_cache_get_multi(); // Bad.

get_super_admins(); // Bad.

flush_rewrite_rules(); // Bad.

attachment_url_to_postid(); // Bad.

wpcom_vip_attachment_url_to_postid(); // OK.

get_term_link(); // Ok.

get_term_by(); // Ok.

get_category_by_slug(); // Ok.

get_tag_link(); // Ok.

get_category_link(); // Ok.

get_cat_ID(); // Ok.

wpcom_vip_get_category_by_slug(); // Bad. Deprecated.

wpcom_vip_get_term_by(); // Bad. Deprecated.

wpcom_vip_get_term_link(); // Bad. Deprecated.

wp_mail(); // Bad. Warning.

mail(); // Bad. Warning.

dbDelta();