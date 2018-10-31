<?php

$string = '<script>haxx0red</script>';
$html = '<br><a><b><i>';

strip_tag( 'Test', $html ); // Ok - similarly-named function.
wp_strip_all_tags( $string ); // Ok.

strip_tags( 'Testing' ); // Warning.
strip_tags( 'Test', $html ); // Warning.
strip_tags( 'Test' . ', ' . 'HTML' ); // Warning - concatenation on first parameter.
strip_tags( 'Test, String', $html ); // Warning - comma in first parameter.
strip_tags( $string ); // Warning.
