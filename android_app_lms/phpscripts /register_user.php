<?php
    include "database_credentials.php";
	$response = array();
    $name = $_POST['name'];
    $email = $_POST['email'];
    $password = $_POST['password'];
    $phone = $_POST['phone'];
    $id = $_POST['id'];
    if ($conn->connect_error)
    {
        $response["success"] = 0;
        $response["message"] = "Error : Could not connect to database";
        echo json_encode($response);
    } 
    else
    {
        $sql = "INSERT INTO `delivery_boy_info` (`uid`, `name`, `email`, `password`, `phone`,
         `current_status`, `gcm_id`) VALUES ('$id', '$name', '$email', '$password', '$phone', 'O', NULL);";
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