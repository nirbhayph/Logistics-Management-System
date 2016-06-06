<?php 
$dbDatabase = "enter_db";
$dbServer = "enter_server";
$dbUser = "your_username_here";
$dbPass = "your_password_here";
$sConn = @mysql_connect($dbServer, $dbUser, $dbPass) or die("Couldn't connect to database server");
$dConn = @mysql_select_db($dbDatabase, $sConn) or die("Couldn't connect to database $dbDatabase");
?>
