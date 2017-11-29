<?php

echo '<a href="' . esc_attr( $some_var ) . '"></a>'; // NOK.

echo "<a href='" . esc_attr( $some_var ) . "'></a>"; // NOK.

echo '<a href="' . esc_url( $some_var ) . '"></a>'; // OK.

echo "<a href='" . esc_url( $some_var ) . "'></a>"; // OK.

echo '<a title="' . esc_attr( $some_var ) . '"></a>'; // OK.

echo "<a title='" . esc_attr( $some_var ) . "'></a>"; // OK.

echo '<a title="' . esc_html( $some_var ) . '"></a>'; // NOK.

echo "<a title='" . esc_html( $some_var ) . "'></a>"; // NOK.

?>

<a href="<?php echo esc_attr( $some_var ); ?>">Hello</a> <!-- NOK. -->

<a href="" class="<?php echo esc_html( $some_var); ?>">Hey</a> <!-- NOK. -->

<a href="<?php esc_url( $url );?>"></a> <!-- OK. -->

<a title="<?php esc_attr( $url );?>"></a> <!-- OK. -->
