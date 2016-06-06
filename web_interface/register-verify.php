<?php

$db = "(DESCRIPTION=(ADDRESS_LIST = (ADDRESS = (PROTOCOL = TCP)(HOST = nirbhaylms.c3x2dwubess3.us-west-2.rds.amazonaws.com)(PORT = 1521)))(CONNECT_DATA=(SID=orcl)))";

$conn = oci_connect('nirbhaylms', 'nirbhaylms', $db);
if (!$conn) {
    $e = oci_error();
    trigger_error(htmlentities($e['message'], ENT_QUOTES), E_USER_ERROR);
}
$timestamp = time();
$man_id=$timestamp + rand(0,2000);
$username=$_POST['username'];
$password=$_POST['passwd'];
$email = $_POST['email'];
// echo "<br>";
// echo $phone;
// echo "<br>";


if(preg_match('/@gmail/',$email)){
echo $query8="INSERT INTO manager_gmail (manager_id, username, password, recorded_at, email) VALUES ('".$man_id."', '".$username."', '".$password."', '".$timestamp."', '".$email."')";
}
else {
echo $query8="INSERT INTO manager_others (manager_id, username, password, recorded_at, email) VALUES ('".$man_id."', '".$username."', '".$password."', '".$timestamp."', '".$email."')";
}

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
echo $r;
if (!$r) {
    $e = oci_error($stid);
    trigger_error(htmlentities($e['message'], ENT_QUOTES), E_USER_ERROR);
}
else{
    //echo "HELLO2";
}
//Fetch the results of the query
// print "<table border='1'>\n";
// while ($row = oci_fetch_array($stid, OCI_ASSOC+OCI_RETURN_NULLS)) {
//     print "<tr>\n";
//     foreach ($row as $item) {
//         print "    <td>" . ($item !== null ? htmlentities($item, ENT_QUOTES) : "&nbsp;") . "</td>\n";
//     }
//     print "</tr>\n";
// }
// print "</table>\n";
oci_free_statement($stid);
header('location: http://52.38.52.58/ack.php');




?>



