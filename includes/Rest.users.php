<?php
	/* File : Rest.users.php
	 * Author : ShengFu
	*/
	
	require_once("../../includes/Rest.inc.php");
	
	class USERS extends REST{
		/**
		 * These are the database login details
		 */
		private $db;
		
		public function __construct(){
			$this->db = new mysqli(self::DB_SERVER, self::DB_USER, self::DB_PASSWORD, self::DB);

            if ($this->db->connect_error) {
                $temp["success"] = "false";
                $temp["error_msg"] = "USERS connect DB error";
                $this->response(json_encode($temp),500);
            }
		}
		
		
		
		public function PUT(){
		    $error_msg = "";
            //$this->response(json_encode($_POST),500);
            if (isset($_POST['username'], $_POST['email'], $_POST['p'], $_POST['firstname'], $_POST['lastname'])) {
                
                $username = filter_input(INPUT_POST, 'username', FILTER_SANITIZE_STRING);
                if (strlen($username) > 50) {
                    $error_msg .= "Invalid username, limits to 50 characters.";
                }
                
                $email = filter_input(INPUT_POST, 'email', FILTER_SANITIZE_EMAIL);
                $email = filter_var($email, FILTER_VALIDATE_EMAIL);
                if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
                    // Not a valid email
                    $error_msg .= "The email address you entered is not valid";
                }
                if (strlen($email) > 200) {
                    $error_msg .= "Invalid email, limits to 200 characters.";
                }
                
                $password = filter_input(INPUT_POST, 'p', FILTER_SANITIZE_STRING);
                if (strlen($password) != 128) {
                    // The hashed pwd should be 128 characters long.
                    // If it's not, something really odd has happened
                    $error_msg .= "Invalid password configuration";
                }
                
                $firstName = filter_input(INPUT_POST, 'firstname', FILTER_SANITIZE_STRING);
                if (strlen($firstName) > 50) {
                    $error_msg .= "Invalid firstname, limits to 50 characters.";
                }
                
                $lastName = filter_input(INPUT_POST, 'lastname', FILTER_SANITIZE_STRING);;
                if (strlen($lastName) > 50) {
                    $error_msg .= "Invalid lastname, limits to 50 characters.";
                }
                
                if(!empty($error_msg)){
                    $temp["success"] = "false";
                    $temp["error_msg"] = $error_msg;
                    $this->response(json_encode($temp),500);
                }   
                
            
                include 'processProfilePicUpload.php';
                
                if(!empty($error_msg)){
                    $temp["success"] = "false";
                    $temp["error_msg"] = $error_msg;
                    $this->response(json_encode($temp),500);
                }
                
                // Create a random salt
                $random_salt = hash('sha512', uniqid(openssl_random_pseudo_bytes(16), TRUE));
        
                // Create salted password 
                $password = hash('sha512', $password . $random_salt);
        
                // Insert the new user into the database 
                if ($insert_stmt = $this->db->prepare("call UsersInsert (?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?)")) {
                //if ($insert_stmt = $mysqli->prepare("INSERT INTO Users (Username, Email, FirstName, LastName, Title, Description, City, State, ProfilePic, Salt, Password) 
                //VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?)")) {
                    
                    $title = "";
                    $description = "";
                    $city = "";
                    $state = "";
                    if($uploadOk == 1){
                        $profilePic = $target_file;
                    }
                    else{
                        $profilePic ="";
                    }
                    $insert_stmt->bind_param('sssssssssss', $username, $email, $firstName, $lastName, $title, $description, $city, $state, $profilePic, $random_salt, $password);
                    // Execute the prepared query.
                    if (! $insert_stmt->execute()) {
                        $temp["success"] = "false";
                        $temp["error_msg"] = "Register insertion execute fail";
                        $this->response(json_encode($temp), 500);
                    }
                    else{
                        
                        $insert_stmt->store_result();
                        $insert_stmt->bind_result($col1, $col2);
                         /* fetch values */
                        while ($insert_stmt->fetch()) {
                            if(empty($col1)){
                                $error_msg .= "col1: ".$col1.",col2: ".$col2;
                            }
                        }
                        $insert_stmt->close();
                        if(!empty($error_msg)){
                            $temp["success"] = "false";
                            $temp["error_msg"] = "Register insertion fetch fail".$col1.$col2;
                            $this->response(json_encode($temp), 500);
                        }
                        $temp["success"] = "true";
                        $temp["error_msg"] = "null";
                        $this->response(json_encode(["success"]),200);
                    }
                }//prepare
                else{
                    $temp["success"] = "false";
                    $temp["error_msg"] = "Register insert prepare fail.";
                    $this->response(json_encode($temp), 500);
                }
                // if(empty($error_msg)){
                //     $this->response("success",200);
                // }
                // else{
                //     header("Location: ../error.php?err=".$error_msg."Register Insertion Fail");
                //     exit();
                // }
                
            }//Not enough POST
            else{
                //$this->response(json_encode(["Inside if"]),500);
                $temp["success"] = "false";
                $temp["error_msg"] = "Username, passward, first name or last name does not exist.";
                $this->response(json_encode($temp),501);
            }
		}//end PUT
	}//end CLASS	
?>