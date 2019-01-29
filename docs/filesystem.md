# FileSystem Usage

> VIP Go stores uploaded media in a globally distributed object store. We have seamlessly integrated this service with VIP Go WordPress installations using WordPress’ Filesystem API. For security reasons, the local container filesystem is mostly read-only: it is not possible for PHP to write files to any part of the system except the system temporary directory.

For more information on writing temporary files on the VIP platform, [visit the official VIP docs here](https://vip.wordpress.com/documentation/vip-go/writing-files-on-vip-go/)

## The Uploads Folder

Changes can be made to files in the uploads folder, but these must be done via the [`WP_Filesystem` API](https://codex.wordpress.org/Filesystem_API).

For example, to create/modify `example.com/wp-content/uploads/test.txt`:

```php
global $wp_filesystem;
 
if ( ! is_a( $wp_filesystem, 'WP_Filesystem_Base') ){
    $creds = request_filesystem_credentials( site_url() );
    wp_filesystem($creds);
}
$wp_filesystem->put_contents( wp_get_upload_dir()['basedir'] . '/test.txt', 'this is a test file');
```

For more information on using `WP_Filesystem` with VIP, [click here](https://vip.wordpress.com/documentation/using-wp_filesystem-instead-of-direct-file-access-functions/)