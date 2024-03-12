<?php

	require_once('classes/user.php');

	session_start();

	if(isset($_SESSION['user'])){
		$user = unserialize($_SESSION['user']);
	}else{
		header('Location: index.php');
	}
	
	$user->logout();
	
	header('Location: index.php');
?>