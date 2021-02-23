<?php

echo '<a href="' . esc_attr( $some_var ) . '"></a>'; // Error.

echo "<a href='" . esc_attr( $some_var ) . "'></a>"; // Error.

echo '<a href="' . esc_url( $some_var ) . '"></a>'; // OK.

echo "<a href='" . esc_url( $some_var ) . "'></a>"; // OK.

echo '<a title="' . esc_attr( $some_var ) . '"></a>'; // OK.

echo "<a title='" . esc_attr( $some_var ) . "'></a>"; // OK.

echo '<a title="' . esc_html( $some_var ) . '"></a>'; // Error.

echo "<a title='" . esc_html( $some_var ) . "'></a>"; // Error.

?>

<a href="<?php echo esc_attr( $some_var ); ?>">Hello</a> <!-- Error. -->

<a href="" class="<?php echo esc_html( $some_var); ?>">Hey</a> <!-- Error. -->

<a href="<?php esc_url( $url );?>"></a> <!-- OK. -->

<a title="<?php esc_attr( $url );?>"></a> <!-- OK. -->

<?php

echo '<media:content url="' . esc_url( $post_image ) . '" medium="image">'; // OK.

echo '<media:content url="' . esc_attr( $post_image ) . '" medium="image">'; // Error.

echo 'data-param-url="' . esc_url( $share_url ) . '"'; // OK.

echo 'data-param-url="' . esc_html( $share_url ) . '"'; // Error.

?>

<form method="post" action="<?php echo esc_html(admin_url('admin.php?page='.$base_name.'&amp;mode=logs&amp;id='.$poll_id)); ?>">

?>

<a href="#link"><?php echo esc_attr( 'testing' ); // Error.
?> </a>

<style>
<style>
h1 {
  color: <?php echo esc_attr( $color ) ?>; /* OK */
  font-family: Arial;
  font-size: 10px;
}
</style>

<article id="foo-bar" data-property="<?php echo esc_attr( $year ); // OK.
?>">
Test
</article>

<h1><?php echo esc_attr__( $title, 'domain' ); ?></h1> <!-- Error -->