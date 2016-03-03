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
		        if(isset($_POST[itemID],$_POST[complete])){
		            
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