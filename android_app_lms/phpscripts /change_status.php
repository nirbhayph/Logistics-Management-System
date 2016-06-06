<?php
    include "database_credentials.php";
	$response = array();
    $id = $_POST['id'];
    $status = $_POST['status'];
    if ($conn->connect_error)
    {
        $response["success"] = 0;
        $response["message"] = "Error : Could not connect to database";
        echo json_encode($response);
    } 
    else
    {
        $sql = "UPDATE delivery_boy_info SET current_status = '$status' WHERE uid = '$id';";
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
        mysqli_close($conn);
    }
?>