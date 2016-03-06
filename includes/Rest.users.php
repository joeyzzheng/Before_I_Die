<?php
	/* File : Rest.users.php
	 * Author : ShengFu
	*/
	
	require_once("../../includes/Rest.inc.php");
	require_once("../../includes/psl-config.php");
	class USERS extends REST{
		/**
		 * These are the database login details
		 */
		private $db;
		
		public function __construct(){
			$this->db = new mysqli(DB_SERVER, DB_USER, DB_PASSWORD, DB);

            if ($this->db->connect_error) {
                $temp["success"] = "false";
                $temp["error_msg"] = "USERS connect DB error";
                $this->response(json_encode($temp),200);
            }
		}
		
		
		
		public function PUT(){
		    $error_msg = "";
            //$this->response(json_encode($_POST),200);
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
                
                $title       = isset($_POST["title"]) ? $_POST["title"] : NULL;
                $description = isset($_POST["description"]) ? $_POST["description"] : NULL;
                $city        = isset($_POST["city"]) ? $_POST["city"] : NULL;
                $state       = isset($_POST["state"]) ? $_POST["state"] : NULL;
                
                if(strlen($title) > 100) $error_msg .= "Invalid title, limits to 100 characters.";
                if(strlen($description) > 100) $error_msg .= "Invalid description, limits to 500 characters.";
                if(strlen($city) > 100) $error_msg .= "Invalid city, limits to 100 characters.";
                if(strlen($state) > 100) $error_msg .= "Invalid state, limits to 100 characters.";
                
                if(!empty($error_msg)){
                    $temp["success"] = "false";
                    $temp["error_msg"] = $error_msg;
                    $this->response(json_encode($temp),200);
                }   
                
                include 'processProfilePicUpload.php';
                
                $profilePic = ($uploadOk == 1) ? $target_dir : "/resource/pic/profilePic/default_profile_pic.png";
                if(strlen($profilePic) > 200) $error_msg .= "pfofilePic file length is too long, limits to 200 characters.";
                    
                if(!empty($error_msg)){
                    $temp["success"] = "false";
                    $temp["error_msg"] = $error_msg;
                    $this->response(json_encode($temp),200);
                }
                
                // Create a random salt
                $random_salt = hash('sha512', uniqid(openssl_random_pseudo_bytes(16), TRUE));
        
                // Create salted password 
                $password = hash('sha512', $password . $random_salt);
        
                // Insert the new user into the database
                $query = "call UsersInsert (?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, @Result, @Msg)";
                if ($insert_stmt = $this->db->prepare($query)) {
                //if ($insert_stmt = $mysqli->prepare("INSERT INTO Users (Username, Email, FirstName, LastName, Title, Description, City, State, ProfilePic, Salt, Password) 
                //VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?)")) {
                    
                    
                    $insert_stmt->bind_param('sssssssssss', $username, $email, $firstName, $lastName, $title, $description, $city, $state, $profilePic, $random_salt, $password);
                    // Execute the prepared query.
                    if (! $insert_stmt->execute()) {
                        $temp["success"] = "false";
                        $temp["error_msg"] = "Register insertion execute fail";
                        $this->response(json_encode($temp), 200);
                    }
                    else{
                        
                        //$insert_stmt->store_result();
                        // $insert_stmt->bind_result($col1, $col2);
                        //  /* fetch values */
                        // while ($insert_stmt->fetch()) {
                        //     if(empty($col1)){
                        //         $error_msg .= "col1: ".$col1.",col2: ".$col2;
                        //     }
                        // }
                        
                        $insert_stmt->close();
                        
                        $query = "SELECT @Result, @Msg";
                        if ($insert_stmt = $this->db->query($query)) {
                            $result = $insert_stmt->fetch_assoc();
                            $insert_stmt->close();
                            if($result["@Result"] == 0){
                                $temp["success"] = "false";
                                $temp["error_msg"] = $result["@Msg"];
                                $this->response(json_encode($temp), 200);
                            }
                            $temp["success"] = "true";
                            $temp["error_msg"] = "null";
                            $this->response(json_encode([$temp]),200);
                        }
                        else{
                            $temp["success"] = "false";
                            $temp["error_msg"] = "Can not query UserInsert result msg";
                            $this->response(json_encode($temp), 200);
                        }
                    }//else execute fail
                }//prepare
                else{
                    $temp["success"] = "false";
                    $temp["error_msg"] = "Register insert prepare fail.";
                    $this->response(json_encode($temp), 200);
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
                //$this->response(json_encode(["Inside if"]),200);
                $temp["success"] = "false";
                $temp["error_msg"] = "Username, password, firstname or lastname does not exist.";
                $this->response(json_encode($temp),200);
            }
		}//end PUT
		
		/*
		* update users
		*/
		public function update(){
		    $error_msg = "";
            //$this->response(json_encode($_POST),200);
            if (isset($_POST['username'], $_POST['email'], $_POST['firstname'], $_POST['lastname'], $_POST['profilePic'])) {
                
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
                
                $firstName = filter_input(INPUT_POST, 'firstname', FILTER_SANITIZE_STRING);
                if (strlen($firstName) > 50) {
                    $error_msg .= "Invalid firstname, limits to 50 characters.";
                }
                
                $lastName = filter_input(INPUT_POST, 'lastname', FILTER_SANITIZE_STRING);;
                if (strlen($lastName) > 50) {
                    $error_msg .= "Invalid lastname, limits to 50 characters.";
                }
                
                $title       = isset($_POST["title"]) ? $_POST["title"] : NULL;
                $description = isset($_POST["description"]) ? $_POST["description"] : NULL;
                $city        = isset($_POST["city"]) ? $_POST["city"] : NULL;
                $state       = isset($_POST["state"]) ? $_POST["state"] : NULL;
                
                if(strlen($title) > 100) $error_msg .= "Invalid title, limits to 100 characters.";
                if(strlen($description) > 100) $error_msg .= "Invalid description, limits to 500 characters.";
                if(strlen($city) > 100) $error_msg .= "Invalid city, limits to 100 characters.";
                if(strlen($state) > 100) $error_msg .= "Invalid state, limits to 100 characters.";
                
                if(!empty($error_msg)){
                    $temp["success"] = "false";
                    $temp["error_msg"] = $error_msg;
                    $this->response(json_encode($temp),200);
                }   
                
                include 'processProfilePicUpload.php';
                $profilePic = filter_input(INPUT_POST, 'profilePic', FILTER_SANITIZE_STRING);
                $profilePic = ($uploadOk == 1) ? $target_dir : $profilePic;
                if(strlen($profilePic) > 200) $error_msg .= "pfofilePic file length is too long, limits to 200 characters.";
                    
                if(!empty($error_msg)){
                    $temp["success"] = "false";
                    $temp["error_msg"] = $error_msg;
                    $this->response(json_encode($temp),200);
                }
                
                
        
                // Insert the new user into the database
                $query = "call UsersInsert (?, ?, ?, ?, ?, ?, ?, ?, ?, @Result, @Msg)";
                if ($insert_stmt = $this->db->prepare($query)) {
                //if ($insert_stmt = $mysqli->prepare("INSERT INTO Users (Username, Email, FirstName, LastName, Title, Description, City, State, ProfilePic, Salt, Password) 
                //VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?)")) {
                    
                    
                    $insert_stmt->bind_param('sssssssss', $username, $email, $firstName, $lastName, $title, $description, $city, $state, $profilePic);
                    // Execute the prepared query.
                    if (! $insert_stmt->execute()) {
                        $temp["success"] = "false";
                        $temp["error_msg"] = "user update execute fail";
                        $this->response(json_encode($temp), 200);
                    }
                    else{
                        
                        $insert_stmt->close();
                        
                        $query = "SELECT @Result, @Msg";
                        if ($insert_stmt = $this->db->query($query)) {
                            $result = $insert_stmt->fetch_assoc();
                            $insert_stmt->close();
                            if($result["@Result"] == 0){
                                $temp["success"] = "false";
                                $temp["error_msg"] = $result["@Msg"];
                                $this->response(json_encode($temp), 200);
                            }
                            $temp["success"] = "true";
                            $temp["error_msg"] = "null";
                            $this->response(json_encode([$temp]),200);
                        }
                        else{
                            $temp["success"] = "false";
                            $temp["error_msg"] = "Can not query UserUpdate result msg";
                            $this->response(json_encode($temp), 200);
                        }
                    }//else execute fail
                }//prepare
                else{
                    $temp["success"] = "false";
                    $temp["error_msg"] = "User update prepare fail.";
                    $this->response(json_encode($temp), 200);
                }
                
                
            }//Not enough POST
            else{
                //$this->response(json_encode(["Inside if"]),200);
                $temp["success"] = "false";
                $temp["error_msg"] = "username, email, firstname , lastname or profilePic does not exist.";
                $this->response(json_encode($temp),200);
            }
		}//end update
		
		/*
		* no check privilege, anyone can get the other's user information
		*/
		public function ALLCANGETALL($username){
		    if(strcmp($this->get_request_method(),"GET")==0){
		        if (strlen($username) > 50) {
                    $temp["success"] = "false";
                    $temp["error_msg"] = "Username is too long. Must Less than 50 characters";
                    $this->response(json_encode($temp),200);
                }
		        $query = "call Before_I_Die.UsersSelect (?)";
			    // Using prepared statements means that SQL injection is not possible.
			    if($stmt = $this->db->prepare($query)){
			        $stmt->bind_param('s', $username);  // Bind to parameter.
			        $stmt->execute();    // Execute the prepared query.
			        $stmt->store_result();
			        $stmt->num_rows();
			        // get variables from result.
			        if( $stmt->num_rows() >0 ){
			        	
			        	$stmt->bind_result($col1, $col2, $col3, $col4, $col5, $col6, $col7, $col8, $col9, $col10);
				        $total_retrieve_result = 0;
				        while($stmt->fetch()){
				        	
				        	$json_result[$total_retrieve_result]["userID"]           = $col1;
				        	$json_result[$total_retrieve_result]["userName"]         = $col2;
				        	$json_result[$total_retrieve_result]["email"]            = $col3;
				        	$json_result[$total_retrieve_result]["firstName"]        = $col4;
				        	$json_result[$total_retrieve_result]["lastName"]         = $col5;
				        	$json_result[$total_retrieve_result]["title"]            = $col6;
				        	$json_result[$total_retrieve_result]["description"]      = $col7;
				        	$json_result[$total_retrieve_result]["city"]             = $col8;
				        	$json_result[$total_retrieve_result]["state"]            = $col9;
				        	$json_result[$total_retrieve_result]["profilePicture"]   = $col10;
				        	$total_retrieve_result++;
				        }
				        $stmt->close();
				        
				        $temp["success"] = "true";
				        $temp["error_msg"] = "null";
				        $temp["responseJSON"] = $json_result;
				        $this->response(json_encode($temp),200);
			        }
			        else{
			        	$temp["success"] = "true";
			        	$temp["error_msg"] = "No Users for username, ".$username ;
			        	$this->response(json_encode($temp),200);
			        }
			    }
			    else{
			    	$temp["success"] = "false";
		        	$temp["error_msg"] = "USERS ALLGETALL() prepare".$query." fail.";
		        	$this->response(json_encode($temp),200);
			    }
		    }
		    else{
		        $temp["success"] = "false";
		        $temp["error_msg"] = "USERS ALLGETALL() can not accept none GET method";
		        $this->response(json_encode($temp),200);
		    }
		}
	}//end CLASS	
?>