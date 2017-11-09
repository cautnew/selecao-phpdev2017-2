<?php
$loc = "Location: login.php";

if( $_POST[ 'us' ] == 'admin' && $_POST[ 'pw' ] == '213243' )
{
	session_start();
	$_SESSION[ 'LOG_INT_CODIGO' ] = '1';
	$loc = "Location: index.php";
}

header( $loc );

?>