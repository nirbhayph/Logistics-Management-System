<?php
	include "database_credentials.php";
	$deliveryInfo = array();
	$id = $_POST["id"];
	$deliveryInfo = array();
	$deliveryInfo["managerLat"] = $_POST["managerLat"];
	$deliveryInfo["managerLong"] = $_POST["managerLong"];
	$deliveryInfo["managerAdrs"] = $_POST["managerAdrs"];
	$gcm_id = "";
    $sql = "SELECT gcm_id FROM delivery_boy_info WHERE uid = '$id';";
    $result = $conn->query($sql);
    $row = $result->fetch_assoc();
    $gcm_id = array( $row["gcm_id"] );
	$conn->close();
	define( 'API_ACCESS_KEY', 'AIzaSyDu4yW_GuWfpdd_JDJiU4cfGUOXon7SinI' );
	$msg = array-
	(
		'message' 	=> json_encode($deliveryInfo)
	);
	$fields = array
	(
		'registration_ids' 	=> $gcm_id,
		'data'			=> $msg
	);	 
	$headers = array
	(
		'Authorization: key=' . API_ACCESS_KEY,
		'Content-Type: application/json'
	);	 
	$ch = curl_init();
	curl_setopt( $ch,CURLOPT_URL, 'https://android.googleapis.com/gcm/send' );
	curl_setopt( $ch,CURLOPT_POST, true );
	curl_setopt( $ch,CURLOPT_HTTPHEADER, $headers );
	curl_setopt( $ch,CURLOPT_RETURNTRANSFER, true );
	curl_setopt( $ch,CURLOPT_SSL_VERIFYPEER, false );
	curl_setopt( $ch,CURLOPT_POSTFIELDS, json_encode( $fields ) );
	$response = curl_exec($ch );
	curl_close( $ch );
	echo $response;
?>