<?php
session_start();
$_SESSION[ 'LOG_INT_CODIGO' ] = '1';

header( 'Location: index.php' );
?>