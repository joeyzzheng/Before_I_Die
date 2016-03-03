<?php
	/* File : Rest.bucketlist.php
	 * Author : ShengFu
	*/
	
	require_once("../../includes/Rest.inc.php");
	require_once("../../includes/psl-config.php");
	class BUCKLIST extends REST{
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
		    
		}
		
		/*
		* get all the bucketlist, no check privilege
		*/
		public function ALLCANGETALL($username){
		    if(strcmp($this->get_request_method(),"GET")==0){
		    	if (strlen($username) > 50) {
                    $temp["success"] = "false";
                    $temp["error_msg"] = "Username is too long. Must Less than 50 characters";
                    $this->response(json_encode($temp),200);
                }
		        $query = "call Before_I_Die.BucketListSelect (?)";
			    // Using prepared statements means that SQL injection is not possible.
			    if($stmt = $this->db->prepare($query)){
			        $stmt->bind_param('s', $username);  // Bind to parameter.
			        $stmt->execute();    // Execute the prepared query.
			        $stmt->store_result();
			        $stmt->num_rows();
			        // get variables from result.
			        if( $stmt->num_rows() >0 ){
			        	
			        	$stmt->bind_result($col1, $col2, $col3, $col4, $col5, $col6, $col7, $col8, $col9, $col10, $col11, $col12);
				        $total_retrieve_result = 0;
				        while($stmt->fetch()){
				        	
				        	$json_result[$total_retrieve_result]["ID"]           = $col1;
				        	$json_result[$total_retrieve_result]["title"]        = $col2;
				        	$json_result[$total_retrieve_result]["content"]      = $col3;
				        	$json_result[$total_retrieve_result]["location"]     = $col4;
				        	$json_result[$total_retrieve_result]["image"]        = $col5;
				        	$json_result[$total_retrieve_result]["private"]      = $col6;
				        	$json_result[$total_retrieve_result]["orderIndex"]   = $col7;
				        	$json_result[$total_retrieve_result]["createDate"]   = $col8;
				        	$json_result[$total_retrieve_result]["openToTorch"]  = $col9;
				        	$json_result[$total_retrieve_result]["completeTime"] = $col10;
				        	$json_result[$total_retrieve_result]["inheritFrom"]  = $col11;
				        	$json_result[$total_retrieve_result]["hashTag"]      = $col12;
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
			        	$temp["error_msg"] = "No bucketlist for username, ".$username ;
			        	$this->response(json_encode($temp),200);
			        }
			    }
			    else{
			    	$temp["success"] = "false";
		        	$temp["error_msg"] = "BUCKLIST ALLGET() prepare".$query." fail.";
		        	$this->response(json_encode($temp),200);
			    }
		    }
		    else{
		        $temp["success"] = "false";
		        $temp["error_msg"] = "BUCKLIST ALLGET() can not accept none GET method";
		        $this->response(json_encode($temp),200);
		    }
		}
}