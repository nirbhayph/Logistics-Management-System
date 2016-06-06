
    <?php
include "config.php";
session_start();
    
    $place=$_GET['place'];
    
    $loca=str_replace(" ","+",$place);

    $api_key = "AIzaSyBexseIE4mvQE4SIBkIsl6QrJn26wU4V5g";
    $url = "https://maps.googleapis.com/maps/api/geocode/json?address=".$loca."&key=".$api_key;
    $ch = curl_init();

    curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
    curl_setopt($ch, CURLOPT_URL, $url);
    $result_insert = curl_exec($ch);
    curl_close($ch);
    $obj = json_decode($result_insert,true);
    $lat_insert=$obj['results'][0]['geometry']['location']['lat'];
    $lng_insert=$obj['results'][0]['geometry']['location']['lng'];

    $date1 = date_create();
    $date1 = date_format($date1,'U')."".rand(0,2000);
    $id_=$date1;
$k=$_SESSION['username'];
    
    
    $sql = "INSERT INTO client_location(lat,lng,address,id_,manager_assoc) VALUES('$lat_insert', '$lng_insert','$place','$id_','$k')";

    if(!mysql_query($sql,$sConn))
     {
         die('Error : ' . mysql_error());
     }
	else {
	 header('location: http://52.38.52.58/y.php');

}
 
    
    ?>

