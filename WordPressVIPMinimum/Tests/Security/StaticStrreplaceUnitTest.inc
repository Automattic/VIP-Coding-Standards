<?php

str_replace( 'foo', 'bar', 'foobar' ); // Bad.

str_replace( 'foo', 'bar', $foobar ); // Ok.

str_replace( array( 'foo', 'bar' ), array( 'bar', 'foo' ), 'foobar' ); // Bad.

str_replace( array( 'foo', 'bar' ), array( 'bar', 'foo' ), $foobar ); // Ok.

str_replace( array( 'foo', 'bar' ), array( $foo, $bar ), $foobar ); // Ok.

str_replace( array( $foo, $bar ), array( 'bar', 'foo' ), $foobar ); // Ok.