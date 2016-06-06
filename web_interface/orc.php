<!DOCTYPE html>
 

<?php
session_start();
include "config.php";
$id_d=$_GET['did'];
$query = "select * from location_info,delivery_boy_info where delivery_boy_info.uid='".$id_d."' AND current_status='A' ORDER BY record_id DESC;";



$result=mysql_query($query);
// echo count($result)."HI";

                        while ($row = mysql_fetch_assoc($result)) {
                        // echo '<br>';
                        //             echo " | ";
                        //             echo $row['latitude']." | ";
                        //            echo $row['longitude']." | "; 
                                    $lat1=$row['latitude'];
                                    $lng1=$row['longitude'];
                                    // echo $_SESSION['username'];
                                    // echo '<br>';  
                                    $id=$id_d;
                                    break;                             
                            
                        } 
$query2="select * from update_lat_lng";
$result2=mysql_query($query2);
while ($row = mysql_fetch_assoc($result2)) {
                        // echo ' Manager <br>';
                        //             echo " | ";
                        //             echo $row['lat']." | ";
                        //            echo $row['lng']." | "; 
                                    $lat2=$row['lat'];
                                    $lng2=$row['lng'];
                                    $address_client = $row['address'];
                                    $id_client = $row['id_client'];
                                    // echo '<br>';  
                                                                
                            
                        } 

include "calculation.php";
$km=distance($lat1,$lng1,$lat2,$lng2,"K");
$cost=12.50;

$total_eval_cost=$km*$cost;
//echo $total_eval_cost;
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
$timestamp = rand(1,2000)*rand(1,20);
// echo "<br>";
// echo $phone;
// echo "<br>";
if(is_null($uid)){

$error="UNAUTHENTICATED ACCESS / DELIVERY MAN NOT IN APPROPRIATE MODE OF ACCESS";
//echo $query4="INSERT INTO new_bill (rec_id, del_uid, del_name, del_phone, del_email, cost_eval, cost_rate_per_km) VALUES ('".$timestamp."', '".$uid."', '".$name."', '".$phone."','".$email."', '".$cost."','".$total_eval_cost."')";

}

else
{
 $query4="INSERT INTO new_bill (rec_id, del_uid, del_name, del_phone, del_email, cost_eval, cost_rate_per_km) VALUES ('".$timestamp."', '".$uid."', '".$name."', '".$phone."','".$email."', '".$cost."','".$total_eval_cost."')";
$query8="INSERT INTO new_bill_client_details (rec_id, client_id, client_lat, client_lng, client_address) VALUES ('".$timestamp."', '".$id_client."', '".$lat2."', '".$lng2."', '".$address_client."')";
// Prepare the statement
$stid = oci_parse($conn, $query4);
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
header('location: http://52.38.52.58/receipt.php?inv_id='.$timestamp);
}



?>





<html>
    

<head>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1.0">
        <meta name="description" content="LMS">
        <meta name="author" content="Nirbhay P and R. Hari">

        <!-- App Favicon -->
        <link rel="shortcut icon" href="assets/images/favicon.ico">

        <!-- App title -->
        <title></title>

        <!-- App CSS -->
        <link href="assets/css/bootstrap.min.css" rel="stylesheet" type="text/css" />
        <link href="assets/css/core.css" rel="stylesheet" type="text/css" />
        <link href="assets/css/components.css" rel="stylesheet" type="text/css" />
        <link href="assets/css/icons.css" rel="stylesheet" type="text/css" />
        <link href="assets/css/pages.css" rel="stylesheet" type="text/css" />
        <link href="assets/css/menu.css" rel="stylesheet" type="text/css" />
        <link href="assets/css/responsive.css" rel="stylesheet" type="text/css" />

        

        <script src="assets/js/modernizr.min.js"></script>
<script>
    (function (i, s, o, g, r, a, m) {
        i['GoogleAnalyticsObject'] = r;
        i[r] = i[r] || function () {
                    (i[r].q = i[r].q || []).push(arguments)
                }, i[r].l = 1 * new Date();
        a = s.createElement(o),
                m = s.getElementsByTagName(o)[0];
        a.async = 1;
        a.src = g;
        m.parentNode.insertBefore(a, m)
    })(window, document, 'script', 'analytics.js', 'ga');
    ga('create', 'UA-74137680-1', 'auto');
    ga('send', 'pageview');
</script>

    </head>
    <body>

        <div class="account-pages"></div>
        <div class="clearfix"></div>
        <div class="wrapper-page">
            <div class="ex-page-content text-center">
                <div class="text-error">404</div>
                <h3 class="text-uppercase font-600">ERROR</h3>
                <p class="text-muted">
                  <b>  <?php echo $error; ?> </b>
                </p>
                <br>
                <a class="btn btn-success waves-effect waves-light" href="y.php"> Return Home</a>

            </div>
        </div>
        <!-- end wrapper page -->


    	<script>
            var resizefunc = [];
        </script>

        <!-- jQuery  -->
        <script src="assets/js/jquery.min.js"></script>
        <script src="assets/js/bootstrap.min.js"></script>
        <script src="assets/js/detect.js"></script>
        <script src="assets/js/fastclick.js"></script>
        <script src="assets/js/jquery.slimscroll.js"></script>
        <script src="assets/js/jquery.blockUI.js"></script>
        <script src="assets/js/waves.js"></script>
        <script src="assets/js/wow.min.js"></script>
        <script src="assets/js/jquery.nicescroll.js"></script>
        <script src="assets/js/jquery.scrollTo.min.js"></script>

        <!-- App js -->
        <script src="assets/js/jquery.core.js"></script>
        <script src="assets/js/jquery.app.js"></script>
	
	</body>

</html>

