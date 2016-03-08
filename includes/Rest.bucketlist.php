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
				        	// get comment
				        	$query = "call Before_I_Die.BucketItemCommentSelect (?)";
				        	$commentDB = new mysqli(DB_SERVER, DB_USER, DB_PASSWORD, DB);
				        	$json_comment_result = NULL;
				        	if($stmt_comment = $commentDB->prepare($query)){
				        		$stmt_comment->bind_param('s', $col1);  // Bind to parameter.
						        $stmt_comment->execute();    // Execute the prepared query.
						        $stmt_comment->store_result();
						        if($stmt_comment->num_rows() > 0){
						        	$stmt_comment->bind_result($col1_comment, $col2_comment, $col3_comment, $col4_comment);
						        	$total_retrieve_comment_result = 0;
						        	while($stmt_comment->fetch()){
						        		
						        		//$json_comment_result[$total_retrieve_comment_result]["bucketItemID"] = $col1_comment;
						        		$json_comment_result[$total_retrieve_comment_result]["username"]     = $col2_comment;
						        		$json_comment_result[$total_retrieve_comment_result]["comment"]      = $col3_comment;
						        		$json_comment_result[$total_retrieve_comment_result]["createdDate"]  = $col4_comment;
			
						        		$total_retrieve_comment_result++;
						        	}
						        }
						        
				        	}
				        	else{
				        		$temp["success"] = "false";
		        				$temp["error_msg"] = "BUCKLIST BucketItemCommentSelect prepare".$query." fail.";
		        				$this->response(json_encode($temp),200);
				        	}
				        	$commentDB->close();
				        	// get like
				        	$query = "call Before_I_Die.BucketItemLikeSelect (?)";
				        	$likeDB = new mysqli(DB_SERVER, DB_USER, DB_PASSWORD, DB);
				        	$json_like_result = NULL;
				        	if($stmt_like = $likeDB->prepare($query)){
				        		$stmt_like->bind_param('s', $col1);  // Bind to parameter.
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
						        
				        	}
				        	else{
				        		$temp["success"] = "false";
		        				$temp["error_msg"] = "BUCKLIST BucketItemCommentSelect prepare".$query." fail.";
		        				$this->response(json_encode($temp),200);
				        	}
				        	$likeDB->close();
				        	
				        	
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
				        	$json_result[$total_retrieve_result]["comment"]      = $json_comment_result;
				        	$json_result[$total_retrieve_result]["like"]      = $json_like_result;
				        	
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
		
		/*
		* get all the "Public" bucketlist
		*/
		public function ALLCANGETPUBLIC(){
			if(strcmp($this->get_request_method(),"GET")==0){
				$username = $_SESSION["username"];
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
				        	if($col6 == 1){continue;}
				        	// get comment
				        	$query = "call Before_I_Die.BucketItemCommentSelect (?)";
				        	$commentDB = new mysqli(DB_SERVER, DB_USER, DB_PASSWORD, DB);
				        	$json_comment_result = NULL;
				        	if($stmt_comment = $commentDB->prepare($query)){
				        		$stmt_comment->bind_param('s', $col1);  // Bind to parameter.
						        $stmt_comment->execute();    // Execute the prepared query.
						        $stmt_comment->store_result();
						        if($stmt_comment->num_rows() > 0){
						        	$stmt_comment->bind_result($col1_comment, $col2_comment, $col3_comment, $col4_comment);
						        	$total_retrieve_comment_result = 0;
						        	while($stmt_comment->fetch()){
						        		
						        		//$json_comment_result[$total_retrieve_comment_result]["bucketItemID"] = $col1_comment;
						        		$json_comment_result[$total_retrieve_comment_result]["username"]     = $col2_comment;
						        		$json_comment_result[$total_retrieve_comment_result]["comment"]      = $col3_comment;
						        		$json_comment_result[$total_retrieve_comment_result]["createdDate"]  = $col4_comment;
			
						        		$total_retrieve_comment_result++;
						        	}
						        }
						        
				        	}
				        	else{
				        		$temp["success"] = "false";
		        				$temp["error_msg"] = "BUCKLIST BucketItemCommentSelect prepare".$query." fail.";
		        				$this->response(json_encode($temp),200);
				        	}
				        	$commentDB->close();
				        	// get like
				        	$query = "call Before_I_Die.BucketItemLikeSelect (?)";
				        	$likeDB = new mysqli(DB_SERVER, DB_USER, DB_PASSWORD, DB);
				        	$json_like_result = NULL;
				        	if($stmt_like = $likeDB->prepare($query)){
				        		$stmt_like->bind_param('s', $col1);  // Bind to parameter.
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
						        
				        	}
				        	else{
				        		$temp["success"] = "false";
		        				$temp["error_msg"] = "BUCKLIST BucketItemCommentSelect prepare".$query." fail.";
		        				$this->response(json_encode($temp),200);
				        	}
				        	$likeDB->close();
				        	
				        	
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
				        	$json_result[$total_retrieve_result]["comment"]      = $json_comment_result;
				        	$json_result[$total_retrieve_result]["like"]      = $json_like_result;
				        	
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
		/*
		* get all the self bucketlist
		*/
		public function SELFCANGETALL(){
		    if(strcmp($this->get_request_method(),"GET")==0){
		    	$username = $_SESSION["username"];
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
				        	// get comment
				        	$query = "call Before_I_Die.BucketItemCommentSelect (?)";
				        	$commentDB = new mysqli(DB_SERVER, DB_USER, DB_PASSWORD, DB);
				        	$json_comment_result = NULL;
				        	if($stmt_comment = $commentDB->prepare($query)){
				        		$stmt_comment->bind_param('s', $col1);  // Bind to parameter.
						        $stmt_comment->execute();    // Execute the prepared query.
						        $stmt_comment->store_result();
						        if($stmt_comment->num_rows() > 0){
						        	$stmt_comment->bind_result($col1_comment, $col2_comment, $col3_comment, $col4_comment);
						        	$total_retrieve_comment_result = 0;
						        	while($stmt_comment->fetch()){
						        		
						        		//$json_comment_result[$total_retrieve_comment_result]["bucketItemID"] = $col1_comment;
						        		$json_comment_result[$total_retrieve_comment_result]["username"]     = $col2_comment;
						        		$json_comment_result[$total_retrieve_comment_result]["comment"]      = $col3_comment;
						        		$json_comment_result[$total_retrieve_comment_result]["createdDate"]  = $col4_comment;
			
						        		$total_retrieve_comment_result++;
						        	}
						        }
						        
				        	}
				        	else{
				        		$temp["success"] = "false";
		        				$temp["error_msg"] = "BUCKLIST BucketItemCommentSelect prepare".$query." fail.";
		        				$this->response(json_encode($temp),200);
				        	}
				        	$commentDB->close();
				        	// get like
				        	$query = "call Before_I_Die.BucketItemLikeSelect (?)";
				        	$likeDB = new mysqli(DB_SERVER, DB_USER, DB_PASSWORD, DB);
				        	$json_like_result = NULL;
				        	if($stmt_like = $likeDB->prepare($query)){
				        		$stmt_like->bind_param('s', $col1);  // Bind to parameter.
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
						        
				        	}
				        	else{
				        		$temp["success"] = "false";
		        				$temp["error_msg"] = "BUCKLIST BucketItemCommentSelect prepare".$query." fail.";
		        				$this->response(json_encode($temp),200);
				        	}
				        	$likeDB->close();
				        	
				        	
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
				        	$json_result[$total_retrieve_result]["comment"]      = $json_comment_result;
				        	$json_result[$total_retrieve_result]["like"]      = $json_like_result;
				        	
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