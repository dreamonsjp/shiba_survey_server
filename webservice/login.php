<?php
echo "[";
//load and connect to MySQL database stuff
require("config.inc.php");
error_reporting(0);
$expired = date('Y-m-d H:i:s');
$GetUser = $_GET['user'];
$GetPassword = $_GET['password'];
if (!empty($GetUser)) {
    //gets user's info based off of a username.
    $query = " 
            SELECT 
                uid, 
                users_name, 
                password
            FROM e_users 
            WHERE 
                users_name = :username 
        ";
    
    $query_params = array(
        ':username' => $GetUser
    );
    
    try {
        $stmt   = $db->prepare($query);
        $result = $stmt->execute($query_params);
    }
    catch (PDOException $ex) {
        // For testing, you could use a echo and message. 
        //echo("Failed to run query: " . $ex->getMessage());
        
        //or just use this use this one to product JSON data:
        $response["success"] = 0;
        $response["message"] = "Database Error1. Please Try Again!";
        $response["code"] = 110;
        echo(json_encode($response));
        
    }
    
    //This will be the variable to determine whether or not the user's information is correct.
    //we initialize it as false.
    $validated_info = false;
    
    //fetching all the rows from the query
    $row = $stmt->fetch();
    if ($row) {
        //if we encrypted the password, we would unencrypt it here, but in our case we just
        //compare the two passwords
       // $$Password = $_POST['password'];
       // $sPasswordHash=hash('sha256', $Password);
        $uid = $row['uid'];
        if (hash('sha256',$GetPassword) === $row['password']) {
            $login_ok = true;
			//setcookie("uid",$uid,time() + 3600);
        }
    }
    
    // If the user logged in successfully, then we send them to the private members-only page 
    // Otherwise, we display a login failed message and show the login form again 
    if ($login_ok) {
		$hashkey = md5(time());
		$query = "Select * FROM e_survey_user_online WHERE uid = '$uid'";
    
		$query_params = array(
			':' => ""
		);
		
		try {
			$stmt   = $db->prepare($query);
			$result = $stmt->execute($query_params);
		}
		catch (PDOException $ex) {
			// For testing, you could use a echo and message. 
			//echo("Failed to run query: " . $ex->getMessage());
			
			//or just use this use this one to product JSON data:
			$response["success"] = 0;
			$response["message"] = "Database Error1. Please Try Again!";
			echo(json_encode($response));
			
		}
		
		//This will be the variable to determine whether or not the user's information is correct.
		//we initialize it as false.
		$validated_info = false;
		
		//fetching all the rows from the query
		$row = $stmt->fetch();
		if ($row) {
			//if we encrypted the password, we would unencrypt it here, but in our case we just
			//compare the two passwords
			//print_r($row);
			 $Idlog = $row['id'];
			 //print_r($row['listpublic']);
			   //echo $Idlog;
				//echo 1;
			
		}
		if(!empty($Idlog)){
			$query = "UPDATE e_survey_user_online SET hashkey = :hashkey, expired = :expired WHERE uid = '$uid'";
    
			$query_params = array(
				':hashkey' => $hashkey,
				':expired' => $expired
			);
			
			try {
				$stmt   = $db->prepare($query);
				$result = $stmt->execute($query_params);
			}
			catch (PDOException $ex) {
				// For testing, you could use a echo and message. 
				//echo("Failed to run query: " . $ex->getMessage());
				
				//or just use this use this one to product JSON data:
				$response["success"] = 0;
				$response["message"] = "Database Error1. Please Try Again!";
				echo(json_encode($response));
				
			}
			
		}else{
        
        
		//initial query
        $query = "INSERT INTO e_survey_user_online ( 
            uid, 
            hashkey, 
            expired ) 
            VALUES ( 
            :user,
            :hashkey, 
            :expired ) 
            ";

        //Update query
        $query_params = array(
            ':user' => $uid,
            ':hashkey' => $hashkey,
            ':expired' => $expired
        );
      
        //execute query
        try {
            $stmt   = $db->prepare($query);
            $result = $stmt->execute($query_params);
        }
        catch (PDOException $ex) {
            // For testing, you could use a echo and message. 
            //echo("Failed to run query: " . $ex->getMessage());
            
            //or just use this use this one:
            $response["success"] = 0;
            $response["message"] = "Database Error. Couldn't add post!";
            echo(json_encode($response));
        }
	}
       // show json index
        $response["result"] = "SUCCESS";
        $response["hashkey"] = $hashkey;
        $response["message"] = "Login Ok";
        $response["code"] = 113;
         //echo $query;
        //now lets update what :user should be
        
        echo(json_encode($response));
    } else {
        $response["result"] = "False";
        $response["message"] = "Wrong password";
		$response["code"] = 111;
        echo(json_encode($response));
    }
} else {
}
echo "]";
?> 
