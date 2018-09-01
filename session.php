<?php
session_start();

if(!$_SESSION['c_authenticated']){

	header('Location: ./ec/index.php');
	exit();

}


?>
