
   <script src="https://code.jquery.com/jquery-2.1.3.min.js"></script>
  <script src="https://cdnjs.cloudflare.com/ajax/libs/sweetalert/1.1.3/sweetalert-dev.js"></script>
  <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/sweetalert/1.1.3/sweetalert.css">

<?php
    require '_functions.php';
    $conn = db_connect();

    if(!$conn)
        die("Oh Shoot!! Connection Failed");

    if($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST["submit"]))
    {
  

              // Should be validated client-side
              $cname = $_POST["cfirstname"] . " " . $_POST["clastname"];
              $cphone = $_POST["cphone"];
      
              $customer_exists = exist_customers($conn,$cname,$cphone);
              $customer_added = false;
      
              if(!$customer_exists)
              {
                  // Route is unique, proceed
                  
                  $username = $_POST["username"];
                  $password = $_POST["password"]; 
                 
                  // Check if the username already exists
                  $user_exists = exist_user($conn, $username);
                  $signup_sucess = false;
                
                  if(!$user_exists)
                  {
                      $hash = password_hash($password, PASSWORD_DEFAULT);
                      $sql = "INSERT INTO `users` (`user_name`, `user_fullname`, `user_password`, `user_created`) VALUES ('$username', '$cname', '$hash', current_timestamp());";
          
                      $result = mysqli_query($conn, $sql);
                      
                      if($result)
                          $signup_sucess = true;
                  }


                  $sql = "INSERT INTO `customers` (`customer_name`, `customer_phone`, `customer_created`) VALUES ('$cname', '$cphone', current_timestamp());";
                  $result = mysqli_query($conn, $sql);
                  // Gives back the Auto Increment id
                  $autoInc_id = mysqli_insert_id($conn);
                  // If the id exists then, 
                  if($autoInc_id)
                  {
                      $code = rand(1,99999);
                      // Generates the unique userid
                      $customer_id = "CUST-".$code.$autoInc_id;
                      
                      $query = "UPDATE `customers` SET `customer_id` = '$customer_id' WHERE `customers`.`id` = $autoInc_id;";
                      //echo $username;
                      $query2 = "UPDATE `users` SET `customer_id` = '$customer_id' WHERE `user_name` = '$username;'";
                      //echo $query;
                      $queryResult = mysqli_query($conn, $query);

                      $queryResult2 = mysqli_query($conn,$query2); 
                      //echo $queryResult2;
                      if(!$queryResult)
                          echo "Not Working";
                  }

                  if($result)
                      $customer_added = true;


                  
                
              }
  
              if($customer_added)
              {
                  // Show success alert

                  echo ' <script>
                  setTimeout(function() {
                   swal({
                       title: "สมัครสมาชิกสำเร็จ",
                       text: "",
                       type: "success"
                   }, function() {
                       window.location = "../../index.php"; //หน้าที่ต้องการให้กระโดดไป
                   });
               }, 1000);
           </script>';
                //   echo '<div class="my-0 alert alert-success alert-dismissible fade show" role="alert">
                //   <strong>Successful!</strong> Customer Added
                //   <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                //   </div>';
              }
              else{
                  // Show error alert
                //   echo '<div class="my-0 alert alert-danger alert-dismissible fade show" role="alert">
                //   <strong>Error!</strong> Customer already exists
                //   <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                //   </div>'.$sql ;
                echo ' <script>
                setTimeout(function() {
                 swal({
                     title: "สมัครสมาชิกไม่สำเร็จ",
                     text: "",
                     type: "error"
                 }, function() {
                     window.location = "../../index.php"; //หน้าที่ต้องการให้กระโดดไป
                 });
             }, 1000);
         </script>';
              }
    }
?>