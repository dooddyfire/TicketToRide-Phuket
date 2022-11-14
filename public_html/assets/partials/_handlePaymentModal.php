<?php
    require '_functions.php';

    $db = db_connect();


    


    if(!$db)
        die("Oh Shoot!! Connection Failed");

        if(isset($_POST["submit"]) && !empty($_FILES["file"]["name"])){
        // echo "<pre>";
        // var_export($_POST);
        // echo "</pre>";

        $fullName = $_POST["firstname"];
        $customer_id = $_POST["cid"];
        $pnr_id = $_POST["pnr"];

        $statusMsg = '';

        // File upload path
        $targetDir = "uploads/";
        $fileName = basename($_FILES["file"]["name"]);
        $targetFilePath = $targetDir . $fileName;
        $fileType = pathinfo($targetFilePath,PATHINFO_EXTENSION);
        
        
            // Allow certain file formats
            $allowTypes = array('jpg','png','jpeg','gif','pdf');
            if(in_array($fileType, $allowTypes)){
                // Upload file to server
                if(move_uploaded_file($_FILES["file"]["tmp_name"], $targetFilePath)){
                    // Insert image file name into database
                    $insert = $db->query("INSERT into payment (file_name, uploaded_on, customer_id, customername,pnr_id,pay_status) VALUES ('".$fileName."', NOW(), '$customer_id', '$fullName','$pnr_id','รอตรวจสอบ' )");
                    if($insert){
                        $statusMsg = "The file ".$fileName. " has been uploaded successfully.";
                    }else{
                        $statusMsg = "File upload failed, please try again.";
                    } 
                }else{
                    $statusMsg = "Sorry, there was an error uploading your file.";
                }
            }else{
                $statusMsg = 'Sorry, only JPG, JPEG, PNG, GIF, & PDF files are allowed to upload.';
            }
        }else{
            $statusMsg = 'Please select a file to upload.';
        }
        
        // Display status message
        echo $statusMsg;


        // Redirect Page
    

?>