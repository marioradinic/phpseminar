<?php
	session_start();

	unset($_POST);
	unset($_SESSION['user']);
	unset($_SESSION['kosarica']);

	$_SESSION['user']['is_logon'] = '0';
	
	header("Location: index.php?pg=1");
	exit;
?>