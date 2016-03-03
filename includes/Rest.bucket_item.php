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
		    if(strcmp(get_request_method(),"POST") == 0){
		        if(isset($_POST["itemID"],$_POST["complete"])){
		            $itemID = $_POST["itemID"];
		            $complete = $_POST["complete"];
		            $query = "call Before_I_Die.BucketItemCompleteUpdate( ?, ?, @Result, @Msg)";
		            if($stmt = $this->db->prepare($query)){
		                $stmt->bind_param('ib', $itemID, $complete);  // Bind to parameter.
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
		    
		}
		
		public function privacy(){
		    
		}
		
		public function like(){
		    
		}
		
		public function torch(){
		    
		}
		
		public function comment(){
		    
		}
	}