<?php
session_start();

if(isset($_POST['submit'])){
	$user=$_POST['uname'];
$pass=$_POST['password'];
	$db = "(DESCRIPTION=(ADDRESS_LIST = (ADDRESS = (PROTOCOL = TCP)(HOST = nirbhaylms.c3x2dwubess3.us-west-2.rds.amazonaws.com)(PORT = 1521)))(CONNECT_DATA=(SID=orcl)))";

	$conn = oci_connect('nirbhaylms', 'nirbhaylms', $db);
	if (!$conn) {
    $e = oci_error();
    trigger_error(htmlentities($e['message'], ENT_QUOTES), E_USER_ERROR);
	}
	$query8="SELECT * FROM (SELECT * FROM MANAGER_OTHERS UNION SELECT * FROM MANAGER_GMAIL) where USERNAME = '$user' AND PASSWORD = '$pass'";
// Prepare the statement
$stid = oci_parse($conn, $query8);
if (!$stid) {
    $e = oci_error($conn);
    trigger_error(htmlentities($e['message'], ENT_QUOTES), E_USER_ERROR);
}
else{
    //echo "HELLO";
}

// Perform the logic of the query
$r = oci_execute($stid);
// echo $r;
if (!$r) {
    $e = oci_error($stid);
    trigger_error(htmlentities($e['message'], ENT_QUOTES), E_USER_ERROR);
}
else{
    //echo "HELLO2";
}
	$abc = oci_fetch_assoc($stid);
    $match  = oci_num_rows($stid);
		if($match > 0){

		$_SESSION['username']=$abc['USERNAME'];
		echo $cde=$abc['USERNAME'];
		header('location: http://52.38.52.58/reminder.php');
		}
	else
		header('location: http://52.38.52.58/login-error.html');



                                            

oci_free_statement($stid);
}
	
?>






