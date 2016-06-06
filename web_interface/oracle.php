 

<?php
session_start();
include "config.php";
$query = "select * from location_info,delivery_boy_info where current_status='A' ORDER BY record_id DESC;";



$result=mysql_query($query);
echo count($result)."HI";

                        while ($row = mysql_fetch_assoc($result)) {
                        echo '<br>';
                                    echo " | ";
                                    echo $row['latitude']." | ";
                                   echo $row['longitude']." | "; 
                                    $lat1=$row['latitude'];
                                    $lng1=$row['longitude'];
                                    echo $_SESSION['username'];
                                    echo '<br>';  
                                    $id=$row['delivery_boy_id'];
                                    break;                             
                            
                        } 
$query2="select * from manager_register where username='".$_SESSION['username']."'";
$result2=mysql_query($query2);
while ($row = mysql_fetch_assoc($result2)) {
                        echo ' Manager <br>';
                                    echo " | ";
                                    echo $row['lat']." | ";
                                   echo $row['lng']." | "; 
                                    $lat2=$row['lat'];
                                    $lng2=$row['lng'];
                                    echo '<br>';  
                                                                
                            
                        } 

include "calculation.php";
$km=distance($lat1,$lng1,$lat2,$lng2,"K");
$cost=12.50;

$total_eval_cost=$km*$cost;
echo $total_eval_cost;
$query3 = "select * from delivery_boy_info where uid='".$id."'";
$result3=mysql_query($query3);
while ($row = mysql_fetch_assoc($result3)) {
                    $uid=$row['uid'];   
                    $name=$row['name'];  
                    $email=$row['email'];  
                    $phone=$row['phone'];   

                                                                
                            
            } 

$db = "(DESCRIPTION=(ADDRESS_LIST = (ADDRESS = (PROTOCOL = TCP)(HOST = nirbhaylms.c3x2dwubess3.us-west-2.rds.amazonaws.com)(PORT = 1521)))(CONNECT_DATA=(SID=orcl)))";





$conn = oci_connect('nirbhaylms', 'nirbhaylms', $db);
if (!$conn) {
    $e = oci_error();
    trigger_error(htmlentities($e['message'], ENT_QUOTES), E_USER_ERROR);
}
echo $timestamp = rand(1,2000)*rand(1,20);
echo "<br>";
echo $phone;
echo "<br>";
echo $query4="INSERT INTO new_bill (rec_id, del_uid, del_name, del_phone, del_email, cost_eval, cost_rate_per_km) VALUES ('".$timestamp."', '".$uid."', '".$name."', '".$phone."','".$email."', '".$cost."','".$total_eval_cost."');";

// Prepare the statement
$stid = oci_parse($conn, $query4);
if (!$stid) {
    $e = oci_error($conn);
    trigger_error(htmlentities($e['message'], ENT_QUOTES), E_USER_ERROR);
}

// Perform the logic of the query
$r = oci_execute($stid);
echo "nir".$r."nir";
if (!$r) {
    $e = oci_error($stid);
    trigger_error(htmlentities($e['message'], ENT_QUOTES), E_USER_ERROR);
}

Fetch the results of the query
print "<table border='1'>\n";
while ($row = oci_fetch_array($stid, OCI_ASSOC+OCI_RETURN_NULLS)) {
    print "<tr>\n";
    foreach ($row as $item) {
        print "    <td>" . ($item !== null ? htmlentities($item, ENT_QUOTES) : "&nbsp;") . "</td>\n";
    }
    print "</tr>\n";
}
print "</table>\n";
oci_free_statement($stid);
oci_close($conn);

?>




