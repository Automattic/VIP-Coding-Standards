<?php

// Parse error/live coding (missing close parentheses).
// This should be the only test in this file.
wp_cache_set( $key, $data, '', (-(2 * 60) + 600 ); // OK.
