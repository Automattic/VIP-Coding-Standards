<?php
$color = 'blue';
$url = 'https://test.com';
$themeURI = get_template_directory_uri();
$ga_ua = 'foo';
$page_title = '<h1>Test</h1>';
?>
<script type="text/javascript">
    var name  = decodeURIComponent( '<?php echo esc_js( $name ); ?>' ); // Error.
    var html  = document.createElement('h1');
    html.innerText = name;
</script>
<script>
    window.location.replace('<?php echo esc_js(site_url());?>/foo/#bar'); // Error.
    ga('create', <?php echo (esc_js($ga_ua)); ?>, 'auto'); // Error.
</script>
<script type="text/javascript" src="<?php echo esc_js($themeURI.'/js/lib.min.js' ); // OK - Not in script scope.
?>"></script> 
<script>
var url   = <?php echo wp_json_encode( esc_url( $url ) ) ?>; // OK.
var title = decodeURIComponent( '<?php echo rawurlencode( (string) $page_title ); ?>' ); // OK.
</script>

<style>
h1 {
  color: <?php echo esc_js( $color ) ?>; /* OK - Not in script scope. */
  font-family: verdana;
  font-size: 300%;
}
</style>
<script src="http://someurl/somefile.js"></script> <!-- Random comment here -->
<?php esc_js( _deprecated_argument() ); // OK.
