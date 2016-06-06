<?php
	include "database_credentials.php";
	$response = array();
	$uid = $_POST['uid'];
	$gcm_id = $_POST['gcm_id'];
    if ($conn->connect_error)
    {
        $response["success"] = 0;
        $response["message"] = "Error : Could not connect to database";
        echo json_encode($response);
    } 
    else
    {
    	$sql = "UPDATE delivery_boy_info SET gcm_id = '$gcm_id' WHERE uid = '$uid';";
        if($conn->query($sql))
		{
			$response["success"]=1;
			echo json_encode($response);	
		}
		else
		{
			$response["success"]=0;
			echo json_encode($response);	
		}
		$conn->close();
    }
?>