<?php

get_option( "taxonomy_rating_$obj->term_id" ); // NOK.
delete_option( "taxonomy_rating_{$obj->term_id}" ); // NOK.
get_option( "taxonomy_rating_$tag_id" ); // NOK.
update_option( 'taxonomy_rating_' . $category_id ); // NOK.
get_option( "taxonomy_rating_{$cat_id}" ); // NOK.
add_option( 'taxonomy_rating_' . $obj->term_id ); // NOK.