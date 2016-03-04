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
		    
		}
	    
	    public function insert(){
		    
		}
		
		public function delete(){
		    
		}
		
		public function complete(){
		    if(strcmp($this->get_request_method(),"POST") == 0){
		        if(isset($_POST["itemID"],$_POST["complete"])){
		            $itemID = $_POST["itemID"];
		            $complete = $_POST["complete"];
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
		    	if(isset($_POST["itemID"],$_POST["likeusername"],$_POST["liked"])){
		    		$itemID = $_POST["itemID"];
		    		$likeusername = $_POST["likeusername"];
		    		$liked = $_POST["liked"];
		    		
		    		
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
                    $temp["error_msg"] = "ItemID likeusername or liked does not set.";
                    $this->response(json_encode($temp),200);
		    	}
		    }
		    else{
		    	$temp["success"] = "false";
                $temp["error_msg"] = "bucket_item/like method must be POST";
                $this->response(json_encode($temp),200);
		    }
		}
		
		public function torch(){
		    if(strcmp($this->get_request_method(),"POST") == 0){
		    	if(isset($_POST["itemID"], $_POST["childUsername"])){
		    		$itemID = $_POST["itemID"];
		    		$childUsername = $_POST["childUsername"];
		    		$query = "call Before_I_Die.BucketItemInheritInsert( ?, ?, @Result, @Msg)";
		    		if($stmt = $this->db->prepare($query)){
		                $stmt->bind_param('ii', $itemID, $childUsername);  // Bind to parameter.
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
                    $temp["error_msg"] = "ItemID or childUsername does not set.";
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
		    
		}
	}