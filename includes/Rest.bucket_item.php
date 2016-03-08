<?php
	/* File : Rest.users.php
	 * Author : ShengFu
	*/
	
	require_once("../../includes/Rest.inc.php");
	require_once("../../includes/psl-config.php");
	class BUCKETITEM extends REST{
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
		
		public function update(){
		    $error_msg = "";
		    if(strcmp($this->get_request_method(),"POST") != 0){
		    	$temp["success"] = "false";
                $temp["error_msg"] = "bucket_item insert method must be POST.";
                $this->response(json_encode($temp),200);
		    }
            //$this->response(json_encode($_POST),200);
            if (isset($_POST['itemID'], $_POST['title'], $_POST['content'], $_POST["imag"])) {
                
                $itemID = filter_input(INPUT_POST, 'itemID', FILTER_SANITIZE_STRING);
                
                $title = filter_input(INPUT_POST, 'title', FILTER_SANITIZE_STRING);
                if (strlen($title) > 100) {
                    $error_msg .= "Invalid title, limits to 100 characters.";
                }
                
                $content = filter_input(INPUT_POST, 'content', FILTER_SANITIZE_STRING);
                if (strlen($content) > 2000) {
                    
                    $error_msg .= "Invalid content, limits to 2000 characters.";
                }
                $location       = isset($_POST["location"]) ? $_POST["locaiton"] : NULL;
                //$orderindex     = isset($_POST["orderindex"]) ? $_POST["orderindex"] : NULL;
                    
                if(strlen($location) > 200) $error_msg .= "Invalid location, limits to 200 characters.";
                //if(strlen($orderindex) > 100) $error_msg .= "Invalid orderindex, limits to 100 characters.";
                
                    
                if(!empty($error_msg)){
                    $temp["success"] = "false";
                    $temp["error_msg"] = $error_msg;
                    $this->response(json_encode($temp),200);
                }   
                
            
                include 'processBucketImagUpload.php';
                $imag = filter_input(INPUT_POST, 'imag', FILTER_SANITIZE_STRING);
                $imag = ($uploadOk == 1) ? $target_dir : $imag;
                if(strlen($imag) > 200) $error_msg .= "pfofilePic file length is too long, limits to 200 characters.";
                
                if(!empty($error_msg)){
                    $temp["success"] = "false";
                    $temp["error_msg"] = $error_msg;
                    $this->response(json_encode($temp),200);
                }
        
                // Insert the new bucket item into the database
                $query = "call Before_I_Die.BucketItemUpdate (?, ?, ?, ?, ?, @Result, @Msg)";
                if ($insert_stmt = $this->db->prepare($query)) {
                //if ($insert_stmt = $mysqli->prepare("INSERT INTO Users (Username, Email, FirstName, LastName, Title, Description, City, State, ProfilePic, Salt, Password) 
                //VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?)")) {
                    
                    $insert_stmt->bind_param('issss', $itemID, $title, $content, $location, $imag);
                    // Execute the prepared query.
                    if (! $insert_stmt->execute()) {
                        $temp["success"] = "false";
                        $temp["error_msg"] = "bucketitem update execute fail";
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
                    $temp["error_msg"] = "bucketitem update prepare fail.";
                    $this->response(json_encode($temp), 200);
                }
                
            }//Not enough POST
            else{
                //$this->response(json_encode(["Inside if"]),200);
                $temp["success"] = "false";
                $temp["error_msg"] = "itemID, title, content or imag does not exist.";
                $this->response(json_encode($temp),200);
            }
		}
	    
	    public function insert(){
		    $error_msg = "";
		    if(strcmp($this->get_request_method(),"POST") != 0){
		    	$temp["success"] = "false";
                $temp["error_msg"] = "bucket_item insert method must be POST.";
                $this->response(json_encode($temp),200);
		    }
            //$this->response(json_encode($_POST),200);
            if (isset($_POST['title'], $_POST['content'])) {
                
                // $username = filter_input(INPUT_POST, 'username', FILTER_SANITIZE_STRING);
                // if (strlen($username) > 50) {
                //     $error_msg .= "Invalid username, limits to 50 characters.";
                // }
                $username = $_SESSION["username"];
                
                $title = filter_input(INPUT_POST, 'title', FILTER_SANITIZE_STRING);
                if (strlen($title) > 100) {
                    $error_msg .= "Invalid title, limits to 100 characters.";
                }
                
                $content = filter_input(INPUT_POST, 'content', FILTER_SANITIZE_STRING);
                if (strlen($content) > 2000) {
                    // The hashed pwd should be 128 characters long.
                    // If it's not, something really odd has happened
                    $error_msg .= "Invalid content, limits to 2000 characters.";
                }
                $location       = isset($_POST["location"]) ? $_POST["locaiton"] : NULL;
                $orderindex     = isset($_POST["orderindex"]) ? $_POST["orderindex"] : NULL;
                    
                if(strlen($location) > 200) $error_msg .= "Invalid location, limits to 200 characters.";
                //if(strlen($orderindex) > 100) $error_msg .= "Invalid orderindex, limits to 100 characters.";
                
                    
                if(!empty($error_msg)){
                    $temp["success"] = "false";
                    $temp["error_msg"] = $error_msg;
                    $this->response(json_encode($temp),200);
                }   
                
            
                include 'processBucketImagUpload.php';
                
                $imag = ($uploadOk == 1) ? $target_dir : "/resource/pic/bucketPic/default_bucket_pic.jpg";
                if(strlen($imag) > 200) $error_msg .= "pfofilePic file length is too long, limits to 200 characters.";
                
                if(!empty($error_msg)){
                    $temp["success"] = "false";
                    $temp["error_msg"] = $error_msg;
                    $this->response(json_encode($temp),200);
                }
        
                // Insert the new bucket item into the database
                $query = "call Before_I_Die.BucketItemInsert (?, ?, ?, ?, ?, ?, @Result, @Msg)";
                if ($insert_stmt = $this->db->prepare($query)) {
                //if ($insert_stmt = $mysqli->prepare("INSERT INTO Users (Username, Email, FirstName, LastName, Title, Description, City, State, ProfilePic, Salt, Password) 
                //VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?)")) {
                    
                    $insert_stmt->bind_param('sssssi', $username, $title, $content, $location, $imag, $orderindex);
                    // Execute the prepared query.
                    if (! $insert_stmt->execute()) {
                        $temp["success"] = "false";
                        $temp["error_msg"] = "bucketitem insertion execute fail";
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
                    $temp["error_msg"] = "bucketitem insert prepare fail.";
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
                $temp["error_msg"] = "username, title or content does not exist.";
                $this->response(json_encode($temp),200);
            }
		}
		/*
		* bucket item delete
		*/
		public function delete(){
		    if(strcmp($this->get_request_method(),"POST") == 0){
		        if(isset($_POST["itemID"])){
		            $itemID = $_POST["itemID"];
		            
		            $query = "call Before_I_Die.BucketItemDelete( ?, @Result, @Msg)";
		            if($stmt = $this->db->prepare($query)){
		                $stmt->bind_param('i', $itemID);  // Bind to parameter.
			            $stmt->execute();    // Execute the prepared query.
			            $stmt->close();
			            $query = "SELECT @Result, @Msg";
			            if ($stmt = $this->db->query($query)) {
                            $result = $stmt->fetch_assoc();
                            $stmt->close();
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
                            $temp["error_msg"] = "Can not query Bucketitem Delete result msg";
                            $this->response(json_encode($temp), 200);
                        }
		            }
		            else{
		                $temp["success"] = "false";
                        $temp["error_msg"] = "Prepare BucketItemDelete fail.";
                        $this->response(json_encode($temp),200);  
		            }
		        }
		        else{
                    $temp["success"] = "false";
                    $temp["error_msg"] = "ItemID does not set.";
                    $this->response(json_encode($temp),200);
                }
		    }
		    else{
                $temp["success"] = "false";
                $temp["error_msg"] = "bucket_item/delete method must be POST";
                $this->response(json_encode($temp),200);
            }
		}
		
		public function complete(){
		    if(strcmp($this->get_request_method(),"POST") == 0){
		        if(isset($_POST["itemID"],$_POST["complete"])){
		            $itemID = $_POST["itemID"];
		            $complete = $_POST["complete"];
		      //      if(strcmp($complete,"0") != 0 || strcmp($complete,"1") != 0){
		    		// 	$temp["success"] = "false";
        //         		$temp["error_msg"] = "complete is not 0 or 1";
        //         		$this->response(json_encode($temp),200);
		    		// }
		            $query = "call Before_I_Die.BucketItemCompleteUpdate( ?, ?, @Result, @Msg)";
		            if($stmt = $this->db->prepare($query)){
		                $stmt->bind_param('ii', $itemID, $complete);  // Bind to parameter.
			            $stmt->execute();    // Execute the prepared query.
			            $stmt->close();
			            $query = "SELECT @Result, @Msg";
			            if ($stmt = $this->db->query($query)) {
                            $result = $stmt->fetch_assoc();
                            $stmt->close();
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
                            $temp["error_msg"] = "Can not query Bucketitem Complete result msg";
                            $this->response(json_encode($temp), 200);
                        }
		            }
		            else{
		                $temp["success"] = "false";
                        $temp["error_msg"] = "Prepare BucketItemCompleteUpdate fail.";
                        $this->response(json_encode($temp),200);  
		            }
		        }
		        else{
                    $temp["success"] = "false";
                    $temp["error_msg"] = "ItemID or complete does not set.";
                    $this->response(json_encode($temp),200);
                }
		    }
		    else{
                $temp["success"] = "false";
                $temp["error_msg"] = "bucket_item/complete method must be POST";
                $this->response(json_encode($temp),200);
            }
		}
		
		public function request_relay(){
		    if(strcmp($this->get_request_method(),"POST") == 0){
		    	if(isset($_POST["itemID"], $_POST["openToTorch"])){
		    		$itemID = $_POST["itemID"];
		    		$openToTorch = $_POST["openToTorch"];
		    		// if(strcmp($openToTorch,"0") != 0 || strcmp($openToTorch,"1") != 0){
		    		// 	$temp["success"] = "false";
        //         		$temp["error_msg"] = "openToTorch is not 0 or 1";
        //         		$this->response(json_encode($temp),200);
		    		// }
		    		$query = "call Before_I_Die.BucketItemTorchUpdate( ?, ?, @Result, @Msg)";
		    		if($stmt = $this->db->prepare($query)){
		                $stmt->bind_param('ii', $itemID, $openToTorch);  // Bind to parameter.
			            $stmt->execute();    // Execute the prepared query.
			            $stmt->close();
			            $query = "SELECT @Result, @Msg";
			            if ($stmt = $this->db->query($query)) {
                            $result = $stmt->fetch_assoc();
                            $stmt->close();
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
                            $temp["error_msg"] = "Can not query BucketItemTorchUpdate result msg";
                            $this->response(json_encode($temp), 200);
                        }
		            }
		            else{
		                $temp["success"] = "false";
                        $temp["error_msg"] = "Prepare BucketItemTorchUpdate fail.";
                        $this->response(json_encode($temp),200);  
		            }
		    	}
		    	else{
		    		$temp["success"] = "false";
                    $temp["error_msg"] = "ItemID or openToTorch does not set.";
                    $this->response(json_encode($temp),200);
		    	}
		    }
		    else{
		    	$temp["success"] = "false";
                $temp["error_msg"] = "bucket_item/request_relay method must be POST";
                $this->response(json_encode($temp),200);
		    }
		}
		
		public function privacy(){
		    if(strcmp($this->get_request_method(),"POST") == 0){
		    	if(isset($_POST["itemID"], $_POST["private"])){
		    		$itemID = $_POST["itemID"];
		    		$private = $_POST["private"];
		    		// if(strcmp($private,"0") != 0 || strcmp($private,"1") != 0){
		    		// 	$temp["success"] = "false";
        //         		$temp["error_msg"] = "private is not 0 or 1";
        //         		$this->response(json_encode($temp),200);
		    		// }
		    		$query = "call Before_I_Die.BucketItemPrivacyUpdate( ?, ?, @Result, @Msg)";
		    		if($stmt = $this->db->prepare($query)){
		                $stmt->bind_param('ii', $itemID, $private);  // Bind to parameter.
			            $stmt->execute();    // Execute the prepared query.
			            $stmt->close();
			            $query = "SELECT @Result, @Msg";
			            if ($stmt = $this->db->query($query)) {
                            $result = $stmt->fetch_assoc();
                            $stmt->close();
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
                            $temp["error_msg"] = "Can not query Bucketitem Privacy result msg";
                            $this->response(json_encode($temp), 200);
                        }
		            }
		            else{
		                $temp["success"] = "false";
                        $temp["error_msg"] = "Prepare BucketItemPrivacyUpdate fail.";
                        $this->response(json_encode($temp),200);  
		            }
		    	}
		    	else{
		    		$temp["success"] = "false";
                    $temp["error_msg"] = "ItemID or privacy does not set.";
                    $this->response(json_encode($temp),200);
		    	}
		    }
		    else{
		    	$temp["success"] = "false";
                $temp["error_msg"] = "bucket_item/privacy method must be POST";
                $this->response(json_encode($temp),200);
		    }
		}
		
		public function like(){
		    if(strcmp($this->get_request_method(),"POST") == 0){
		    	if(isset($_POST["itemID"],$_POST["liked"])){
		    		$itemID = $_POST["itemID"];
		    		$likeusername = $_SESSION["username"];
		    		$liked = filter_input(INPUT_POST, 'liked', FILTER_SANITIZE_STRING);
		    		// if(strcmp($liked,"0") != 0 || strcmp($liked,"1") != 0){
		    		// 	$temp["success"] = "false";
        //         		$temp["error_msg"] = "liked is not 0 or 1";
        //         		$this->response(json_encode($temp),200);
		    		// }
		    		$query = "call Before_I_Die.BucketItemLikeUpdate( ?, ?, ?, @Result, @Msg)";
		    		if($stmt = $this->db->prepare($query)){
		                $stmt->bind_param('isi', $itemID, $likeusername, $liked);  // Bind to parameter.
			            $stmt->execute();    // Execute the prepared query.
			            $stmt->close();
			            $query = "SELECT @Result, @Msg";
			            if ($stmt = $this->db->query($query)) {
                            $result = $stmt->fetch_assoc();
                            $stmt->close();
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
                            $temp["error_msg"] = "Can not query Bucketitem Like result msg";
                            $this->response(json_encode($temp), 200);
                        }
		            }
		            else{
		                $temp["success"] = "false";
                        $temp["error_msg"] = "Prepare BucketItemLikeUpdate fail.";
                        $this->response(json_encode($temp),200);  
		            }
		    	}
		    	else{
		    		$temp["success"] = "false";
                    $temp["error_msg"] = "ItemID or liked does not set.";
                    $this->response(json_encode($temp),200);
		    	}
		    }
		    else if(strcmp($this->get_request_method(),"GET") == 0){
		    	if(isset($_GET["itemID"])){
		    		$itemID = $_GET["itemID"];
		    		$query = "call Before_I_Die.BucketItemLikeSelect (?)";
		        	$json_like_result = NULL;
		        	if($stmt_like = $this->db->prepare($query)){
		        		$stmt_like->bind_param('i', $itemID);  // Bind to parameter.
				        $stmt_like->execute();    // Execute the prepared query.
				        $stmt_like->store_result();
				        
				        if($stmt_like->num_rows() > 0){
				        	$stmt_like->bind_result($col1_like);
				        	$total_retrieve_like_result = 0;
				        	while($stmt_like->fetch()){
				        		
				        		$json_like_result[$total_retrieve_like_result] = $col1_like;
	
				        		$total_retrieve_like_result++;
				        	}
				        }
				        $stmt_like->close();
				        $temp["success"] = "true";
	    				$temp["error_msg"] = "null";
	    				$temp["responseJSON"] = $json_like_result;
	    				$this->response(json_encode($temp),200);
				        
		        	}
		        	else{
		        		$temp["success"] = "false";
	    				$temp["error_msg"] = "BUCKLIST BucketItemLikeSelect prepare".$query." fail.";
	    				$this->response(json_encode($temp),200);
		        	}
		    	}
		    	else{
		    		$temp["success"] = "false";
	    			$temp["error_msg"] = "itemID does not exist";
	    			$this->response(json_encode($temp),200);
		    	}
		    	
		    }
		    else{
		    	$temp["success"] = "false";
                $temp["error_msg"] = "bucket_item/like method must be POST or GET";
                $this->response(json_encode($temp),200);
		    }
		}
		
		public function torch(){
		    if(strcmp($this->get_request_method(),"POST") == 0){
		    	if(isset($_POST["itemID"])){
		    		$itemID = $_POST["itemID"];
		    		$childUsername = $_SESSION["username"];
		    		$query = "call Before_I_Die.BucketItemInheritInsert( ?, ?, @Result, @Msg)";
		    		if($stmt = $this->db->prepare($query)){
		                $stmt->bind_param('is', $itemID, $childUsername);  // Bind to parameter.
			            $stmt->execute();    // Execute the prepared query.
			            $stmt->close();
			            $query = "SELECT @Result, @Msg";
			            if ($stmt = $this->db->query($query)) {
                            $result = $stmt->fetch_assoc();
                            $stmt->close();
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
                            $temp["error_msg"] = "Can not query Bucketitem torch result msg";
                            $this->response(json_encode($temp), 200);
                        }
		            }
		            else{
		                $temp["success"] = "false";
                        $temp["error_msg"] = "Prepare BucketItemInheritInsert fail.";
                        $this->response(json_encode($temp),200);  
		            }
		    	}
		    	else{
		    		$temp["success"] = "false";
                    $temp["error_msg"] = "ItemID does not set.";
                    $this->response(json_encode($temp),200);
		    	}
		    }
		    else{
		    	$temp["success"] = "false";
                $temp["error_msg"] = "bucket_item/torch method must be POST";
                $this->response(json_encode($temp),200);
		    }
		}
		
		public function comment(){
		    if(strcmp($this->get_request_method(),"POST") == 0){
		    	if(isset($_POST["itemID"], $_POST["comment"])){
		    		$itemID = $_POST["itemID"];
		    		$commentusername = $_SESSION["username"];
		    		$comment = $_POST["comment"];
		    		$query = "call Before_I_Die.BucketItemCommentInsert( ?, ?, ?, @Result, @Msg)";
		    		if($stmt = $this->db->prepare($query)){
		                $stmt->bind_param('iss', $itemID, $commentusername, $comment);  // Bind to parameter.
			            $stmt->execute();    // Execute the prepared query.
			            $stmt->close();
			            $query = "SELECT @Result, @Msg";
			            if ($stmt = $this->db->query($query)) {
                            $result = $stmt->fetch_assoc();
                            $stmt->close();
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
                            $temp["error_msg"] = "Can not query Bucketitem comment result msg";
                            $this->response(json_encode($temp), 200);
                        }
		            }
		            else{
		                $temp["success"] = "false";
                        $temp["error_msg"] = "Prepare BucketItemCommentInsert fail.";
                        $this->response(json_encode($temp),200);  
		            }
		    	}
		    	else{
		    		$temp["success"] = "false";
                    $temp["error_msg"] = "ItemID or comment does not set.";
                    $this->response(json_encode($temp),200);
		    	}
		    }//POST
		    else if(strcmp($this->get_request_method(),"GET") == 0){
		    	if(isset($_GET["itemID"])){
		    		$itemID = $_GET["itemID"];
		    		$query = "call Before_I_Die.BucketItemCommentSelect (?)";
		        	$json_like_result = NULL;
		        	if($stmt_like = $this->db->prepare($query)){
		        		$stmt_like->bind_param('i', $itemID);  // Bind to parameter.
				        $stmt_like->execute();    // Execute the prepared query.
				        $stmt_like->store_result();
				        
				        if($stmt_like->num_rows() > 0){
				        	$stmt_like->bind_result($col1_like,$col2_like,$col3_like,$col4_like, $col5_like);
				        	$total_retrieve_like_result = 0;
				        	while($stmt_like->fetch()){
				        		
				        		$json_like_result[$total_retrieve_like_result]["username"]    = $col2_like;
                                $json_like_result[$total_retrieve_like_result]["profilePic"] = $col3_like;
				        		$json_like_result[$total_retrieve_like_result]["comment"]     = $col4_like;
				        		$json_like_result[$total_retrieve_like_result]["createdDate"] = $col5_like;
	
				        		$total_retrieve_like_result++;
				        	}
				        }
				        $stmt_like->close();
				        $temp["success"] = "true";
	    				$temp["error_msg"] = "null";
	    				$temp["responseJSON"] = $json_like_result;
	    				$this->response(json_encode($temp),200);
				        
		        	}
		        	else{
		        		$temp["success"] = "false";
	    				$temp["error_msg"] = "BUCKLIST BucketItemCommentSelect prepare".$query." fail.";
	    				$this->response(json_encode($temp),200);
		        	}
		    	}
		    	else{
		    		$temp["success"] = "false";
	    			$temp["error_msg"] = "itemID does not exist";
	    			$this->response(json_encode($temp),200);
		    	}
		    	
		    }	
		    else{
		    	$temp["success"] = "false";
                $temp["error_msg"] = "bucket_item/comment method must be POST or GET";
                $this->response(json_encode($temp),200);
		    }
		}
		
		/*
		* get popular_item
		*/
		public function popular_item(){
		    if(strcmp($this->get_request_method(),"GET")==0){
		    	
		        $query = "call Before_I_Die.PopularUserSelect ()";
			    // Using prepared statements means that SQL injection is not possible.
			    if($stmt = $this->db->prepare($query)){
			        //$stmt->bind_param('s', $username);  // Bind to parameter.
			        $stmt->execute();    // Execute the prepared query.
			        $stmt->store_result();
			        $stmt->num_rows();
			        // get variables from result.
			        if( $stmt->num_rows() > -1 ){
			        	
			        	$stmt->bind_result($col1, $col2, $col3, $col4, $col5, $col6, $col7, $col8, $col9, $col10);
				        $total_retrieve_result = 0;
				        while($stmt->fetch()){
				        	
				        	
				        	//$json_result[$total_retrieve_result]["userID"]           = $col1;
				        	$json_result[$total_retrieve_result]["username"]        = $col2;
				        	$json_result[$total_retrieve_result]["email"]           = $col3;
				        	$json_result[$total_retrieve_result]["firstName"]       = $col4;
				        	$json_result[$total_retrieve_result]["lastName"]        = $col5;
				        	$json_result[$total_retrieve_result]["title"]           = $col6;
				        	$json_result[$total_retrieve_result]["description"]     = $col7;
				        	$json_result[$total_retrieve_result]["city"]            = $col8;
				        	$json_result[$total_retrieve_result]["state"]           = $col9;
				        	$json_result[$total_retrieve_result]["profilePicture"]  = $col10;
				        	
				        	
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
			        	$temp["error_msg"] = "No bucketlist at all ";
			        	$this->response(json_encode($temp),200);
			        }
			    }
			    else{
			    	$temp["success"] = "false";
		        	$temp["error_msg"] = "BUCKLIST popular_item prepare".$query." fail.";
		        	$this->response(json_encode($temp),200);
			    }
		    }
		    else{
		        $temp["success"] = "false";
		        $temp["error_msg"] = "BUCKLIST popular_item can not accept none GET method";
		        $this->response(json_encode($temp),200);
		    }
		}
		
		/*
		* get recent_item
		*/
		public function recent_item(){
		    if(strcmp($this->get_request_method(),"GET")==0){
		    	
		        $query = "call Before_I_Die.RecentUserSelect ()";
			    // Using prepared statements means that SQL injection is not possible.
			    if($stmt = $this->db->prepare($query)){
			        //$stmt->bind_param('s', $username);  // Bind to parameter.
			        $stmt->execute();    // Execute the prepared query.
			        $stmt->store_result();
			        $stmt->num_rows();
			        // get variables from result.
			        if( $stmt->num_rows() > -1 ){
			        	
			        	$stmt->bind_result($col1, $col2, $col3, $col4, $col5, $col6, $col7, $col8, $col9, $col10);
				        $total_retrieve_result = 0;
				        while($stmt->fetch()){
				        	
				        	
				        	//$json_result[$total_retrieve_result]["userID"]           = $col1;
				        	$json_result[$total_retrieve_result]["username"]        = $col2;
				        	$json_result[$total_retrieve_result]["email"]           = $col3;
				        	$json_result[$total_retrieve_result]["firstName"]       = $col4;
				        	$json_result[$total_retrieve_result]["lastName"]        = $col5;
				        	$json_result[$total_retrieve_result]["title"]           = $col6;
				        	$json_result[$total_retrieve_result]["description"]     = $col7;
				        	$json_result[$total_retrieve_result]["city"]            = $col8;
				        	$json_result[$total_retrieve_result]["state"]           = $col9;
				        	$json_result[$total_retrieve_result]["profilePicture"]  = $col10;
				        	
				        	
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
			        	$temp["error_msg"] = "No bucketlist at all ";
			        	$this->response(json_encode($temp),200);
			        }
			    }
			    else{
			    	$temp["success"] = "false";
		        	$temp["error_msg"] = "BUCKLIST recent_item prepare".$query." fail.";
		        	$this->response(json_encode($temp),200);
			    }
		    }
		    else{
		        $temp["success"] = "false";
		        $temp["error_msg"] = "BUCKLIST recent_item can not accept none GET method";
		        $this->response(json_encode($temp),200);
		    }
		}
		/*
		* get torch_item
		*/
		public function torch_item(){
		    if(strcmp($this->get_request_method(),"GET")==0){
		    	
		        $query = "call Before_I_Die.RecentTorchSelect ()";
			    // Using prepared statements means that SQL injection is not possible.
			    if($stmt = $this->db->prepare($query)){
			        //$stmt->bind_param('s', $username);  // Bind to parameter.
			        $stmt->execute();    // Execute the prepared query.
			        $stmt->store_result();
			        $stmt->num_rows();
			        // get variables from result.
			        if( $stmt->num_rows() > -1 ){
			        	
			        	$stmt->bind_result($col1, $col2, $col3, $col4, $col5);
				        $total_retrieve_result = 0;
				        while($stmt->fetch()){
				        	
				        	
				        	$json_result[$total_retrieve_result]["username"]            = $col1;
				        	$json_result[$total_retrieve_result]["bucketItemID"]        = $col2;
				        	$json_result[$total_retrieve_result]["bucketItemTitle"]     = $col3;
				        	$json_result[$total_retrieve_result]["bucketItemContent"]   = $col4;
				        	$json_result[$total_retrieve_result]["image"]               = $col5;
				        	
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
			        	$temp["error_msg"] = "No bucketlist at all ";
			        	$this->response(json_encode($temp),200);
			        }
			    }
			    else{
			    	$temp["success"] = "false";
		        	$temp["error_msg"] = "BUCKLIST torch_item prepare".$query." fail.";
		        	$this->response(json_encode($temp),200);
			    }
		    }
		    else{
		        $temp["success"] = "false";
		        $temp["error_msg"] = "BUCKLIST torch_item can not accept none GET method";
		        $this->response(json_encode($temp),200);
		    }
		}
		
	}