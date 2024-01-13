<?php
include("provjerimain.php");

$serverName = ".";
$connectionInfo = array("Database"=>"zavrsniprojekt", "UID"=>"hpdev", "PWD"=>"hpdev", "CharacterSet" => "UTF-8");
$MsSQL = sqlsrv_connect($serverName, $connectionInfo);