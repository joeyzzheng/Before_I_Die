<?php
    $uploadOk = 0;
    
    if(!empty($_FILES["fileToUpload"]["tmp_name"])){
        $target_dir = "../profilePic/";
        $target_file = $target_dir . $_POST['username'] .'_'. basename($_FILES["fileToUpload"]["name"]) ;
        $uploadOk = 1;
        $imageFileType = pathinfo($target_file,PATHINFO_EXTENSION);
        
        //Check if image file is a actual image or fake image
        $check = getimagesize($_FILES["fileToUpload"]["tmp_name"]);
        if($check !== false) {
            $uploadOk = 1;
        } else {
            $uploadOk = 0;
        }
        
        // // Check if file already exists
        // if (file_exists($target_file)) {
        //     //rewrite anyway
        //     $uploadOk = 1;
        // }
        // Check file size
        if ($_FILES["fileToUpload"]["size"] > 500000) {
            $error_msg .= "Image file size is too large";
            $uploadOk = 0;
        }
        // Allow certain file formats
        if($imageFileType != "jpg" && $imageFileType != "png" && $imageFileType != "jpeg"
        && $imageFileType != "gif" ) {
            $error_msg .= "Sorry, only JPG, JPEG, PNG & GIF files are allowed.";
            $uploadOk = 0;
        }
        // Check if $uploadOk is set to 0 by an error
        if ($uploadOk == 0) {
            $error_msg .= "Sorry, your file was not uploaded.";
        // if everything is ok, try to upload file
        } else {
            if (move_uploaded_file($_FILES["fileToUpload"]["tmp_name"], $target_file)) {
                //delete old file
                //$error_msg .= "The file ". basename( $_FILES["fileToUpload"]["name"]). " has been uploaded.";
            } else {
                $error_msg .= "Sorry, there was an error uploading your file.";
            }
        }
    }