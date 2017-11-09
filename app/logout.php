<?php
session_start();
unset( $_SESSION[ 'LOG_INT_CODIGO' ] );
session_destroy();

header( 'Location: index.php' );
?>