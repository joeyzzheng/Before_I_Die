<?php
    
	/* 
		This is an example class script proceeding secured API
		To use this class you should keep same as query string and function name
		Ex: If the query string value rquest=delete_user Access modifiers doesn't matter but function should be
		     function delete_user(){
				 You code goes here
			 }
		Class will execute the function dynamically;
		
		usage :
		
		    $object->response(output_data, status_code);
			$object->_request	- to get santinized input 	
			
			output_data : JSON (I am using)
			status_code : Send status message for headers
			
		Add This extension for localhost checking :
			Chrome Extension : Advanced REST client Application
			URL : https://chrome.google.com/webstore/detail/hgmloofddffdnphfgcellkdfbfbjeloo
		
		I used the below table for demo purpose.
		
		CREATE TABLE IF NOT EXISTS `users` (
		  `user_id` int(11) NOT NULL AUTO_INCREMENT,
		  `user_fullname` varchar(25) NOT NULL,
		  `user_email` varchar(50) NOT NULL,
		  `user_password` varchar(50) NOT NULL,
		  `user_status` tinyint(1) NOT NULL DEFAULT '0',
		  PRIMARY KEY (`user_id`)
		) ENGINE=InnoDB DEFAULT CHARSET=latin1 AUTO_INCREMENT=1 ;
 	*/
	
	require_once("../../includes/Rest.inc.php");
	require_once("../../includes/Rest.users.php");
	require_once("../../includes/Rest.bucketlist.php");
	require_once("../../includes/Rest.bucket_item.php");
	
	class API extends REST {
	
		public $data = "";
		
		
		
		private $db = NULL;
		private $parseURL ;
		private $error_msg;
		
		private $myUsers = NULL;
		private $myBucketList = NULL;
		private $myBucketItem = NULL;
		
		public function setMyUsers($instance){
			$this->myUsers = $instance;
		}
		
		public function setMyBucketList($instance){
			$this->myBucketList = $instance;
		}
		
		public function setMyBucketItem($instance){
			$this->myBucketItem = $instance;
		}
		
		public function __construct(){
			parent::__construct();				// Init parent contructor
			$this->dbConnect();					// Initiate Database connection
		}
		
		/*
		 *  Database connection 
		*/
		private function dbConnect(){
			// $this->db = mysql_connect(self::DB_SERVER,self::DB_USER,self::DB_PASSWORD);
			// if($this->db)
			// 	mysql_select_db(self::DB,$this->db);
				
			$this->db = new mysqli(DB_SERVER, DB_USER, DB_PASSWORD, DB);
		    if ($this->db->connect_error) {
		    	$temp["success"] = "false";
		    	$temp["error_msg"] = "DB connect_error";
		        $this->response($this->json($temp),200);
		    }
		}
		
		/*
		 * Public method for access api.
		 * This method dynmically call the method based on the query string
		 *
		 */
		public function processApi(){
			//$this->response(DB_SERVER,200); //for debug
			
			//refresh session
			if(!$this->sec_session_start()){
				$temp["success"] = "false";
				$temp["error_msg"] = $this->error_msg;
				$this->response($this->json($temp),200);
			}
			
			if(empty($_REQUEST)){
				include("../home.html");
                exit();
			}

			$input = (explode('/',strtolower(str_replace("","",$_REQUEST['rquest']))));
			$this->parseURL = $input;
			
			//validate URL is {domain}/api/ or {domain}/personal/
			if(strcmp($input[0],"")==0){
				include("../home.html");
				exit(); 
			}
			if(strcmp($input[0],"api") != 0 && strcmp($input[0], "personal") != 0 && strcmp($input[0],"login") != 0 ){
				$temp["success"] = "false";
				$temp["error_msg"] = "API URL should begin with {domain}/api/method or {domain}/personal/username or {domain}/login or {domain}/logout";
				$this->response($this->json($temp),200);
			}
			if(sizeof($input) < 2 && strcmp($input[0],"api") == 0 && strcmp($input[0], "personal") == 0){
				$temp["success"] = "false";
				$temp["error_msg"] = "The 2nd portion of the URL is not given. {domain}/1/2/";
				$this->response($this->json($temp),200);
			}

			//register first
			if((sizeof($input) == 2) && (strcmp($input[1],"register") == 0)) {
				//$this->response($this->json($input),200);
				$this->myUsers->PUT();
			}
			//login first
			if((strcmp($input[0],"login") == 0)){
				include("../login.html");
				exit();
			}
			//popular_item first
			if(strcmp($input[0],"api") == 0 && strcmp($input[1],"popular_item") == 0){
				$this->myBucketItem->popular_item();
			}
			//recent_item first
			if(strcmp($input[0],"api") == 0 && strcmp($input[1],"recent_item") == 0){
				$this->myBucketItem->recent_item();
			}
			//torch_item first
			if(strcmp($input[0],"api") == 0 && strcmp($input[1],"torch_item") == 0){
				$this->myBucketItem->torch_item();
			}
			// parse personal page requests
			if(strcmp($input[0], "personal") == 0){
				if($this->login_check()){
            		include("../personal.html");
                	exit();
				}
				else{
					header("Location: ../login");
					exit();
				}
            }
            // log out
			if(strcmp($input[1],"logout")==0 && strcmp($input[0],"api")==0){
				// Unset all session values 
				$_SESSION = array();
				
				// get session parameters 
				$params = session_get_cookie_params();
				
				// Delete the actual cookie. 
				setcookie(session_name(),'', time() - 42000, $params["path"], $params["domain"], $params["secure"], $params["httponly"]);
				
				// Destroy session 
				session_destroy();
				
				// logout
				$temp["success"] = "true";
				$temp["error_msg"] = "null";
				$this->response($this->json($temp),200);
				
				// include("../home.html");
				// exit();
			}
			$this->error_msg = "";

			// //validate Login status
			if(strcmp($input[1],"login")!=0 && (!$this->login_check())){
				$temp["success"] = "false";
				$temp["error_msg"] = "Not Login, Error Message: ".$this->error_msg;
				$this->response($this->json($temp),200);
			}
			//recommendation 
			if(strcmp($input[0],"api") == 0 && strcmp($input[1],"recommendation") == 0){
				$this->myUsers->recommendation();
			}
            
			
			//$this->response($func,200);
			$func = $input[1];
			//$this->response($func,200); //for debug
			if((int)method_exists($this,$func) > 0)
				$this->$func();
			else{
				$temp["success"] = "false";
				$temp["error_msg"] = "API Path Not Found";
				$this->response($this->json($temp),200);				// If the method not exist with in this class, response would be "Page not found".
				
			}
		}
		
		/*
		*
		*refresh session
		*
		*/
		private function sec_session_start() {
		    $session_name = 'sec_session_id';   // Set a custom session name 
		    $secure = true;
		
		    // This stops JavaScript being able to access the session id.
		    $httponly = true;
		
		    // Forces sessions to only use cookies.
		    if (ini_set('session.use_only_cookies', 1) === FALSE) {
		        $this->error_msg .= "Could not initiate a safe session (ini_set)";
		        return false;
		    }
		
		    // Gets current cookies params.
		    $cookieParams = session_get_cookie_params();
		    session_set_cookie_params($cookieParams["lifetime"], $cookieParams["path"], $cookieParams["domain"], $secure, $httponly);
		
		    // Sets the session name to the one set above.
		    session_name($session_name);
		
		    session_start();            // Start the PHP session 
		    session_regenerate_id();    // regenerated the session, delete the old one. 
		    return true;
		}
		
		/*
		*
		*Login Check
		*
		*/
		private function login_check() {
			
		    // Check if all session variables are set 
		    if (isset($_SESSION['username'], $_SESSION['login_string'])) {
		        
		        $username = $_SESSION['username'];
		        $login_string = $_SESSION['login_string'];
		        
		        if (strlen($username) > 50) {
                    $this->error_msg .= "Invalid username, limits to 50 characters in check login.";
                }
		        
		        // Get the user-agent string of the user.
		        $user_browser = $_SERVER['HTTP_USER_AGENT'];
		        
		        //
		        if ($stmt = $this->db->prepare("SELECT Password 
				        FROM Users 
		                WHERE username = ? LIMIT 1")) {
		            $stmt->bind_param('s', $username);  // Bind "$username" to parameter.
		            $stmt->execute();    // Execute the prepared query.
		            $stmt->store_result();
		
		            // get variables from result.
		            $stmt->bind_result($password);
		            $stmt->fetch();
		            $stmt->close();
		            if(empty($password)){
		                //login fail, eamil is wrong
		                $this->error_msg .= "Chech Login Fail: username is wrong in check login";
		                return false;
		            }
		            
		            $login_check = hash('sha512', $password . $user_browser);
		            if ($login_check == $login_string) {
		                // Logged In!!!! 
		                return true;
		            } else {
		                // Not logged in
		                $this->error_msg .= "Session string error in check login";
		                return false;
		            }
		        }
		        else {
		            // Could not create a prepared statement
		            $this->error_msg .= "Database Error: Cannot prepare password statement in check login";
		            return false;
		        }
		    } else {
		        // Not logged in 
		        $this->error_msg .= "Should go api/login first";
		        return false;
		    }
		}
		
		/*
		*
		*
		*
		*
		*/
		private function profilePicture(){
			$func = strtolower(trim(str_replace("/","",$_REQUEST['rquest'])));
			$this->response($this->json($this->parseURL),200);
			//$this->response($func,200);
		}
		
		/* 
		 *	Simple login API
		 *  Login must be POST method
		 *  email : <USER EMAIL>
		 *  pwd : <USER PASSWORD>
		 */
		private function login(){
			//$this->response($this->json($_POST),200); //for debug
			$this->error_msg = "";
			if (isset($_POST['username'], $_POST['p'])) {
				
			    $username = filter_input(INPUT_POST, 'username', FILTER_SANITIZE_STRING);
			    if (strlen($username) > 50) {
			        $this->error_msg .= 'Invalid username, limits to 50 characters.';
			    }
			    
			    $password = filter_input(INPUT_POST, 'p', FILTER_SANITIZE_STRING);
			    if (strlen($password) != 128) {
			        // The hashed pwd should be 128 characters long.
			        // If it's not, something really odd has happened
			        $this->error_msg .= "Invalid password configuration. ";
			    }
			    
			    if(!empty($this->error_msg)){
			    	$temp["success"] = "false";
			    	$temp["error_msg"] = $this->error_msg;
			    	$this->response($this->json($temp),200);
			    }
			}
			else{
				$temp["success"] = "false";
			    $temp["error_msg"] = "No Username or Password";
				$this->response($this->json($temp),200);
			}
			
		    $query = "call Before_I_Die.SaltSelect (?)";
		    if($stmt = $this->db->prepare($query)){
		        
		        $stmt->bind_param('s', $username);  // Bind "$username" to parameter.
		        $stmt->execute();    // Execute the prepared query.
		        $stmt->store_result();
		    
		        // get variables from result.
		        $stmt->bind_result($salt);
		        $stmt->fetch();
		        $stmt->close();
		        if(empty($salt)){
		            //login fail, eamil is wrong
		            $this->error_msg .= "Username is not in DB.";
		            $temp["success"] = "false";
		            $temp["error_msg"] = $this->error_msg;
		            $this->response($this->json($temp),200);
		        }
		    }
		    else{
		    	$temp["success"] = "false";
	            $temp["error_msg"] = $this->error_msg;
	            $this->response($this->json($temp),200);
		    }
		    
		    
		        
		    //hash password with salt
		    $password = hash('sha512', $password . $salt); 
		    
		    $query = "call Before_I_Die.Login (?,?,@Result,@Msg)";
		    // Using prepared statements means that SQL injection is not possible.
		    if($stmt = $this->db->prepare($query)){
		        //$stmt = $mysqli->prepare("call Before_I_Die.Login (?,?)");
		        $stmt->bind_param('ss', $username, $password);  // Bind "$email"/$password to parameter.
		        $stmt->execute();    // Execute the prepared query.
		        $stmt->close();
		        
		        $query = "SELECT @Result, @Msg";
                if ($insert_stmt = $this->db->query($query)) {
                    $result = $insert_stmt->fetch_assoc();
                    $insert_stmt->close();
                    if($result["@Result"] == 0){
                        $temp["success"] = "false";
                        $temp["error_msg"] = $result["@Msg"];
                        $this->response(json_encode($temp), 200);
                    }

                    // Password is correct!
		        
			        // Get the user-agent string of the user.
			        $user_browser = $_SERVER['HTTP_USER_AGENT'];
			        
			        //hash the password and user browser as login proof
			        $_SESSION['login_string'] = hash('sha512', $password . $user_browser);
			        
			        // unique email as identity
			        $_SESSION['username'] = strtolower($username);
			        
			        //login success
			        $temp["success"] = "true";
			        $temp["error_msg"] = "null";
			        $this->response($this->json($temp),200);

                    $temp["success"] = "true";
                    $temp["error_msg"] = "null";
                    $this->response(json_encode([$temp]),200);
                }
                else{
                    $temp["success"] = "false";
                    $temp["error_msg"] = "Can not query UserInsert result msg";
                    $this->response(json_encode($temp), 200);
                }
		    }
		    else{
		    	$temp["success"] = "false";
		    	$temp["error_msg"] ="Prepare login fail.";
		        $this->response($this->json($temp),200);
		    }
			
		}
		/*
		*  BucketList
		*/
		private function bucketlist(){
			if(strcmp($this->get_request_method(),"GET") == 0){
				if(sizeof($this->parseURL) < 3){
					$temp["success"] = "false";
					$temp["error_msg"] = "No username assign";
					$this->response(json_encode($temp),200);
				}
				if(strcmp($this->parseURL[2],"") == 0){
					$temp["success"] = "false";
					$temp["error_msg"] = "No username assign";
					$this->response(json_encode($temp),200);
				}
				//validate Login status
				if(strcmp($_SESSION["username"],$this->parseURL[2]) != 0){
					$this->myBucketList->ALLCANGETPUBLIC($this->parseURL[2]);
				}
				else{
					$this->myBucketList->SELFCANGETALL();
				}
			}
			else{
				$temp["success"] = "false";
				$temp["error_msg"] = "HTTP method not found";
				$this->response(json_encode($temp),200);
			}
		}
		
		private function users(){
			if(strcmp($this->get_request_method(),"GET") == 0){
				if(sizeof($this->parseURL) < 3){
					$temp["success"] = "false";
					$temp["error_msg"] = "No username assign";
					$this->response(json_encode($temp),200);
				}
				if(strcmp($this->parseURL[2],"") == 0){
					$temp["success"] = "false";
					$temp["error_msg"] = "No username assign";
					$this->response(json_encode($temp),200);
				}
				
				$this->myUsers->ALLCANGETALL($this->parseURL[2]);
			}
			if(strcmp($this->get_request_method(),"POST") == 0){
				if(sizeof($this->parseURL) > 2 && strcmp($this->parseURL[2],"") != 0){
					$temp["success"] = "false";
					$temp["error_msg"] = "api/users/redundant URL assign";
					$this->response(json_encode($temp),200);
				}
				
				
				
				$this->myUsers->update();
				
			}
			else{
				$temp["success"] = "false";
				$temp["error_msg"] = "HTTP method not found";
				$this->response(json_encode($temp),200);
			}
		}
		/*
		* api/bucket_item
		*/
		private function bucket_item(){
			if(strcmp($this->get_request_method(),"POST") == 0){
				if(sizeof($this->parseURL) < 3){
					$temp["success"] = "false";
					$temp["error_msg"] = "api/bucket_item/method no method assign";
					$this->response(json_encode($temp),200);
				}
				if(strcmp($this->parseURL[2],"") == 0){
					$temp["success"] = "false";
					$temp["error_msg"] = "api/bucket_item/method no method assign";
					$this->response(json_encode($temp),200);
				}
				
				$func = $this->parseURL[2];
				if((int)method_exists($this->myBucketItem,$func) > 0)
					$this->myBucketItem->$func();
				else{
					$temp["success"] = "false";
					$temp["error_msg"] = "api/bucket_item/method no method match";
					$this->response($this->json($temp),200);				// If the method not exist with in this class, response would be "Page not found".
					
				}
			}
			else if(strcmp($this->get_request_method(),"GET") == 0){
				$func = $this->parseURL[2];
				if((int)method_exists($this->myBucketItem,$func) > 0)
					$this->myBucketItem->$func();
				else{
					$temp["success"] = "false";
					$temp["error_msg"] = "api/bucket_item/method no method match";
					$this->response($this->json($temp),200);				// If the method not exist with in this class, response would be "Page not found".
					
				}
			}
			else{
				$temp["success"] = "false";
				$temp["error_msg"] = "HTTP method not found";
				$this->response(json_encode($temp),200);
			}
		}
		/*
		* popular_item
		*/
		private function popular_item(){
			if(strcmp($this->get_request_method(),"GET") == 0){
				$this->myBucketItem->popular_item();
			}
			else{
				$temp["success"] = "false";
				$temp["error_msg"] = "HTTP method not found";
				$this->response(json_encode($temp),200);
			}
		}
		/*
		* recent_item
		*/
		private function recent_item(){
			if(strcmp($this->get_request_method(),"GET") == 0){
				$this->myBucketItem->recent_item();
			}
			else{
				$temp["success"] = "false";
				$temp["error_msg"] = "HTTP method not found";
				$this->response(json_encode($temp),200);
			}
		}
		/*
		* torch_item
		*/
		private function torch_item(){
			if(strcmp($this->get_request_method(),"GET") == 0){
				$this->myBucketItem->torch_item();
			}
			else{
				$temp["success"] = "false";
				$temp["error_msg"] = "HTTP method not found";
				$this->response(json_encode($temp),200);
			}
		}
		
		/*
		 *	Encode array into JSON
		*/
		private function json($data){
			if(is_array($data)){
				return json_encode($data);
			}
		}
	}
// include_once '../includes/db_connect.php';
// include_once '../includes/functions.php';
// 	// Initiiate Library
// sec_session_start();

// if (login_check($mysqli) == true	
	$api = new API;
	$users = new USERS;
	$bucketlist = new BUCKLIST;
	$bucketitem = new BUCKETITEM;
	$api->setMyUsers($users);
	$api->setMyBucketList($bucketlist);
	$api->setMyBucketItem($bucketitem);
	$api->processApi();
?>
