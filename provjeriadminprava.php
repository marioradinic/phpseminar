<?php

include("provjerimain.php");

if (!isset($_SESSION['user']['is_logon']) 
	|| !$_SESSION['user']['is_logon'] == '1'
	|| !$_SESSION['user']['is_admin'] == '1')
{
	header("Location: index.php?pg=1");
}