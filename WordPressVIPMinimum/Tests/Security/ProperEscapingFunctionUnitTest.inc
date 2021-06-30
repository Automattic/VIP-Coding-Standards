<?php

echo '<a href="' . esc_attr( $some_var ) . '"></a>'; // Error.

echo "<a href='" . \esc_attr( $some_var ) . "'></a>"; // Error.

echo '<a href="' . \esc_url( $some_var ) . '"></a>'; // OK.

echo "<a href='" . esc_url( $some_var ) . "'></a>"; // OK.

echo '<a title="' . esc_attr( $some_var ) . '"></a>'; // OK.

echo "<a title='" . \esc_attr( $some_var ) . "'></a>"; // OK.

echo '<a title="' . esc_html_x( $some_var ) . '"></a>'; // Warning.

echo "<a title='" . \esc_html( $some_var ) . "'></a>"; // Warning.

?>

<a href="<?php echo esc_attr( $some_var ); ?>">Hello</a> <!-- Error. -->

<a href="" class="<?php esc_html_e( $some_var); ?>">Hey</a> <!-- Warning. -->

<a href="<?php esc_url( $url );?>"></a> <!-- OK. -->

<a title="<?php esc_attr( $url );?>"></a> <!-- OK. -->

<?php

echo '<media:content url="' . esc_url( $post_image ) . '" medium="image">'; // OK.

echo '<media:content url="' . esc_attr( $post_image ) . '" medium="image">'; // Error.

echo 'data-param-url="' . esc_url( $share_url ) . '"'; // OK.

echo 'data-param-url="' . Esc_HTML( $share_url ) . '"'; // Error.

?>

<form method="post" action="<?php echo esc_html(admin_url('admin.php?page='.$base_name.'&amp;mode=logs&amp;id='.$poll_id)); ?>"><!-- Error. -->
<input data-action="<?php echo esc_attr( $my_var ); ?>"><!-- OK. -->
<a href='https://demo.com?foo=bar&my-action=<?php echo esc_attr( $var ); ?>'>link</a><!-- OK. -->

<a href="#link"><?php echo esc_attr( 'testing' ); // Error.
?> </a>

<?php echo ' <h' . "2>    " ,        esc_attr( $heading ) . "</h2>"; // Error.
?> <style>
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

<h1><?php echo esc_attr__( $title, 'domain' ); ?></h1> <!-- Error --> ?>
<?php echo '<h1>' . esc_attr__( $some_var, 'domain' ) . '</h1>'; // Error.
echo '<h1>',      \esc_attr_x( $title, 'domain' ), '</h1>'; // Error.
echo "<$tag>  " , esc_attr( $test ) , "</$tag>"; // Error.
?>
<h1> <?php echo esc_attr( $title ) . '</h1>'; ?> // Error.
<div><?= esc_attr($attr) ?></div><!-- Error -->
<div><?php esc_attr_e($attr) ?></div><!-- Error -->
<div data-tag="<?= esc_attr( $attr ); ?>"> <!-- OK -->
<?php echo "<div>" . $test . "</div>"; // OK.
echo "<{$tag}>" . esc_attr( $tag_content ) . "</{$tag}>"; // Error.
echo "<$tag" . '   >' . esc_attr( $tag_content ) . "</$tag>"; // Error.
echo '<div class=\'' . esc_html($class) . '\'>'; // Warning.
echo "<div class=\"" . \esc_html__($class) . '">'; // Warning.
echo "<div $someAttribute class=\"" . esc_html($class) . '">'; // Warning.
echo '<a href=\'' . esc_html($url) . '\'>'; // Error.
echo "<img src=\"" . esc_html($src) . '"/>'; // Error.
echo "<div $someAttributeName-url=\"" . esc_html($url) . '">'; // Error.
echo '<a href="',  esc_html($url), '">'; // Error.

echo '<a href=', esc_html($url), '>'; // Error.

echo 'data-param-url="' . Esc_HTML::static_method( $share_url ) . '"'; // OK.

// Not a target for this sniff (yet).
printf( '<meta name="generator" content="%s">', esc_attr( $content ) ); // OK.
?>

// Making sure tabs and new lines before "action" are handled correctly.
<input class="something something-else something-more"
	action="<?php echo esc_attr( $my_var ); ?>"><!-- Error. -->
<?php
echo '<input class="something something-else something-more"
	action="', esc_url( $my_var ), '">'; // OK.
echo '<input class="something something-else something-more"
action="', esc_attr( $my_var ), '">'; // Error.

// Verify correct handling of comma's in short open echo tags, without affecting subsequent statements.
?>
<div>html</div>
<?= '<h1>' , esc_attr( $test ) , '</h1>'; // Error.
printf( '<meta name="generator" content="%s">', esc_attr( $content ) ); // OK.
echo '<a href="',  esc_html($url), '">'; // Error.
?>
<div>html</div>
<?= '<h1 class="', esc_attr( $test ), '">'; ?><!-- OK -->
<div>html</div>

// Issue #680 - only match = when preceeded by something which could be an HTML attribute.
<option value="<?php echo esc_attr( $i ); ?>" <?php echo ( $filter_importance === $i ) ? 'selected' : ''; ?> >
	&gt;=<?php echo esc_html( $i ); ?>
</option>

<A HREF="<?= esc_url($url) ?>" data-num2=<?= esc_attr( $num2 )><?php echo esc_html( $link ); ?><a/>
