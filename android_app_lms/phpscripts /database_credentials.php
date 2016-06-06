<?php
	define("database", "database_here");
	define("server", "server_here");
	define("username", "username_here");
	define("password", "passwd_here");
	$conn = new mysqli(server, username, password, database);
	if ($conn->connect_error)
		echo "ERROR";
?>
