<?php
    include "database_credentials.php";
	$response = array();
	if (isset($_POST['email']) && isset($_POST['password']))
	{
		$email = $_POST['email'];
		$password = $_POST['password'];
		if ($conn->connect_error)
        {
        	$response["success"] = -2;
            $response["message"] = "Error : Could not connect to database";
            echo json_encode($response);
        } 
        else
        {
            $sql = "SELECT uid, (COALESCE(gcm_id, '') = '') as gcm_id_status FROM delivery_boy_info WHERE email = '$email' AND password = '$password';";
            $result = $conn->query($sql);
            if($result == false)
            {
                $response["success"] = -1;
                $response["message"] = "Error : Could not execute SQL statement : $sql";
                echo json_encode($response);
            }
            else if ($result->num_rows == 1)
            {
            	$row = $result->fetch_assoc();
            	$response["success"] = 1;
            	$response["message"] = "Login successful";
            	$response["id"] = $row["uid"];
                $response["gcm_id_status"] = $row["gcm_id_status"];
            	echo json_encode($response);
            }
            else
            {
            	$response["success"] = 0;
            	$response["message"] = "Invalid credentials";
            	echo json_encode($response);
            }
            $conn->close();
        }
	}
	else
	{
		$response["success"] = -1;
   	    $response["message"] = "Required field(s) is missing";
   	    echo json_encode($response);
	} 
?>