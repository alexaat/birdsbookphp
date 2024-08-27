<?php
    //images.php
    
    function uploadImages($files,  $target_dir = "../images/"){

 print_r($files);

        //$target_dir = "../images/";
        $target_file = $target_dir . basename($files["image"]["name"]);
        $uploadOk = 1;
        $imageFileType = strtolower(pathinfo($target_file,PATHINFO_EXTENSION));
        
       
       
        // Check if image file is a actual image or fake image
        $check = getimagesize($files["image"]["tmp_name"]);
        if($check !== false) {
            echo "File is an image - " . $check["mime"] . ".";
            $uploadOk = 1;
        } else {
            echo "File is not an image.";
            $uploadOk = 0;
        }
        
        // Check file size
        if ($files["image"]["size"] > 5000000) {
            echo "Sorry, your file is too large.";
            $uploadOk = 0;
        }
        
        if ($uploadOk == 0) {
            echo "Sorry, your file was not uploaded.";
        // if everything is ok, try to upload file
        } else {
            $target_file = $target_dir . $files["image"]["name"];
          if (move_uploaded_file($files["image"]["tmp_name"], $target_file)) {
            echo "The file ". htmlspecialchars( basename( $_FILES["image"]["name"])). " has been uploaded.";
          } else {
            echo "Sorry, there was an error uploading your file.";
          }
        }
        
        return $uploadOk;
    }
?>