<?php

wp_remote_post( $this->endpoint, array(
        'method'      => 'POST',
        'timeout'     => 45, // Bad.
        'httpversion' => '1.1',
        'blocking'    => false,
        'body'        => wp_json_encode( $this->logs, JSON_UNESCAPED_SLASHES ),
    )
);
