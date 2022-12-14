<!-- Show these admin pages only when the admin is logged in -->
<?php  require '../assets/partials/_admin-check.php';   ?>


<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Bookings</title>
        <!-- google fonts -->
    <link rel="preconnect" href="https://fonts.gstatic.com">
    <link href="https://fonts.googleapis.com/css2?family=Montserrat:wght@100;200;300;400;500&display=swap" rel="stylesheet">
    <!-- Font Awesome -->
    <script src="https://kit.fontawesome.com/d8cfbe84b9.js" crossorigin="anonymous"></script>
    <!-- Bootstrap CSS -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.0.1/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-+0n0xVW2eSR5OomGNYDnhzAbDsOXxcvSN1TPprVMTNDbiYZCxYbOOl7+AMvyTG2x" crossorigin="anonymous">
    <!-- External CSS -->
    <?php
        require '../assets/styles/admin.php';
        require '../assets/styles/admin-options.php';
        $page="booking";
    ?>
</head>
<body>
    <!-- Requiring the admin header files -->
    <?php require '../assets/partials/_admin-header.php';?>







    
<!-- Add, Edit and Delete Bookings -->
<?php
        /*
            1. Check if an admin is logged in
            2. Check if the request method is POST
        */
        if($loggedIn && $_SERVER["REQUEST_METHOD"] == "POST")
        {
            if(isset($_POST["submit"]))
            {
                /*
                    ADDING Bookings
                 Check if the $_POST key 'submit' exists
                */
                // Should be validated client-side
                // echo "<pre>";
                // var_export($_POST);
                // echo "</pre>";
                // die;
                $customer_id = $_POST["cid"];
                $customer_name = $_POST["cname"];
                $customer_phone = $_POST["cphone"];
                $route_id = $_POST["route_id"];
                $route_source = $_POST["sourceSearch"];
                $route_destination = $_POST["destinationSearch"];
                $route = $route_source . " &rarr; " . $route_destination;
                $booked_seat = $_POST["seatInput"];
                $amount = $_POST["bookAmount"];
                $amphor = $_POST["amphor"];
                $note = $_POST['note'];
                // $dep_timing = $_POST["dep_timing"];

                //$booking_exists = exist_booking($conn,$customer_id,$route_id);
                $booking_exists = false; // ????????????????????? route ????????? ???????????????????????????????????????
                $booking_added = false;
        
                if(!$booking_exists)
                {
                    // Route is unique, proceed
                    $sql = "INSERT INTO `bookings` (`customer_id`, `route_id`, `customer_route`, `booked_amount`, `booked_seat`, `booking_created`,`amphor`,`note`) VALUES ('$customer_id', '$route_id','$route', '$amount', '$booked_seat', current_timestamp(),'$amphor','$note');";
                    $test = $sql;

                    $result = mysqli_query($conn, $sql);
                    // Gives back the Auto Increment id
                    $autoInc_id = mysqli_insert_id($conn);
                    // If the id exists then, 
                    if($autoInc_id)
                    {
                        $key = "1234567890ABCDEFGHIJKLMNOPQRSTUVWXYZ";
                        $code = "";
                        for($i = 0; $i < 5; ++$i)
                            $code .= $key[rand(0,strlen($key) - 1)];
                        
                        // Generates the unique bookingid
                        $booking_id = $code.$autoInc_id;
                        
                        $query = "UPDATE `bookings` SET `booking_id` = '$booking_id' WHERE `bookings`.`id` = $autoInc_id;";
                        $queryResult = mysqli_query($conn, $query);

                        if(!$queryResult)
                            echo "Not Working";
                    }

                    if($result)
                        $booking_added = true;
                }
    
                if($booking_added)
                {
                    // Show success alert
                    echo '<div class="my-0 alert alert-success alert-dismissible fade show" role="alert">
                    <strong>?????????????????????!</strong> ??????????????????
                    <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                    </div>';

                    // Update the Seats table
                    $bus_no = get_from_table($conn, "routes", "route_id", $route_id, "bus_no");
                    $seats = get_from_table($conn, "seats", "bus_no", $bus_no, "seat_booked");
                    if($seats)
                    {
                        $seats .= "," . $booked_seat;
                    }
                    else 
                        $seats = $booked_seat;

                    $updateSeatSql = "UPDATE `seats` SET `seat_booked` = '$seats' WHERE `seats`.`bus_no` = '$bus_no';";
                    mysqli_query($conn, $updateSeatSql);
                }
                else{
                    // Show error alert
                    echo $route_id." ".$customer_id." ".$customer_name." ".$booking_exists;
                    echo '<div class="my-0 alert alert-danger alert-dismissible fade show" role="alert">
                    <strong>??????????????????????????????????????????!</strong> ??????????????????????????????????????????????????????????????????????????? 
                    <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                    </div>'.$test;
                }
            }
            if(isset($_POST["edit"]))
            {
                // EDIT BOOKING
                // echo "<pre>";
                // var_export($_POST);
                // echo "</pre>";die;
                $cname = $_POST["cname"];
                $cphone = $_POST["cphone"];
                $id = $_POST["id"];
                $customer_id = $_POST["customer_id"];
                $id_if_customer_exists = exist_customers($conn,$cname,$cphone);

                if(!$id_if_customer_exists || $customer_id == $id_if_customer_exists)
                {
                    $updateSql = "UPDATE `customers` SET
                    `customer_name` = '$cname',
                    `customer_phone` = '$cphone' WHERE `customers`.`customer_id` = '$customer_id';";

                    $updateResult = mysqli_query($conn, $updateSql);
                    $rowsAffected = mysqli_affected_rows($conn);
    
                    $messageStatus = "danger";
                    $messageInfo = "";
                    $messageHeading = "Error!";
    
                    if(!$rowsAffected)
                    {
                        $messageInfo = "No Edits Administered!";
                    }
    
                    elseif($updateResult)
                    {
                        // Show success alert
                        $messageStatus = "success";
                        $messageHeading = "Successfull!";
                        $messageInfo = "Customer details Edited";
                    }
                    else{
                        // Show error alert
                        $messageInfo = "Your request could not be processed due to technical Issues from our part. We regret the inconvenience caused";
                    }
    
                    // MESSAGE
                    echo '<div class="my-0 alert alert-'.$messageStatus.' alert-dismissible fade show" role="alert">
                    <strong>'.$messageHeading.'</strong> '.$messageInfo.'
                    <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                    </div>';
                }
                else{
                    // If customer details already exists
                    echo '<div class="my-0 alert alert-danger alert-dismissible fade show" role="alert">
                    <strong>Error!</strong> Customer already exists
                    <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                    </div>';
                }

            }
            if(isset($_POST["delete"]))
            {
                // DELETE BOOKING
                $id = $_POST["id"];
                $route_id = $_POST["route_id"];
                // Delete the booking with id => id
                $deleteSql = "DELETE FROM `bookings` WHERE `bookings`.`id` = $id";

                $deleteResult = mysqli_query($conn, $deleteSql);
                $rowsAffected = mysqli_affected_rows($conn);
                $messageStatus = "danger";
                $messageInfo = "";
                $messageHeading = "Error!";

                if(!$rowsAffected)
                {
                    $messageInfo = "Record Doesn't Exist";
                }

                elseif($deleteResult)
                {   
                    $messageStatus = "success";
                    $messageInfo = "Booking Details deleted";
                    $messageHeading = "Successfull!";

                    // Update the Seats table
                    $bus_no = get_from_table($conn, "routes", "route_id", $route_id, "bus_no");
                    $seats = get_from_table($conn, "seats", "bus_no", $bus_no, "seat_booked");

                    // Extract the seat no. that needs to be deleted
                    $booked_seat = $_POST["booked_seat"];

                    $seats = explode(",", $seats);
                    $idx = array_search($booked_seat, $seats);
                    array_splice($seats,$idx,1);
                    $seats = implode(",", $seats);

                    $updateSeatSql = "UPDATE `seats` SET `seat_booked` = '$seats' WHERE `seats`.`bus_no` = '$bus_no';";
                    mysqli_query($conn, $updateSeatSql);
                }
                else{

                    $messageInfo = "Your request could not be processed due to technical Issues from our part. We regret the inconvenience caused";
                }

                // Message
                echo '<div class="my-0 alert alert-'.$messageStatus.' alert-dismissible fade show" role="alert">
                <strong>'.$messageHeading.'</strong> '.$messageInfo.'
                <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                </div>';
            }
        }
        ?>
        <?php
            $resultSql = "SELECT * FROM `bookings` ORDER BY booking_created DESC";
                            
            $resultSqlResult = mysqli_query($conn, $resultSql);

            if(!mysqli_num_rows($resultSqlResult)){ ?>
                <!-- Bookings are not present -->
                <div class="container mt-4">
                    <div id="noCustomers" class="alert alert-dark " role="alert">
                        <h1 class="alert-heading">?????????????????????????????????!!</h1>
                        <p class="fw-light">???????????????????????????????????????????????????????????????!</p>
                        <hr>
                        <div id="addCustomerAlert" class="alert alert-success" role="alert">
                                Click on <button id="add-button" class="button btn-sm"type="button"data-bs-toggle="modal" data-bs-target="#addModal">ADD <i class="fas fa-plus"></i></button> to add a booking!
                        </div>
                    </div>
                </div>
            <?php }
            else { ?>   
            <section id="booking">
                <div id="head">
                    <h4>?????????????????????????????????</h4>
                </div>
                <div id="booking-results">
                    <div>

                        <button id="add-button" class="button btn-sm"type="button"data-bs-toggle="modal" data-bs-target="#addModal">?????????????????????<i class="fas fa-plus"></i></button>
                        

                    </div>
                    <table class="table table-hover table-bordered">
                        <thead>
                            <th>????????????????????? PNR</th>
                            <th>??????????????????????????????</th>
                            <th>?????????????????????????????????</th>
                            <th>???????????????????????????</th>
                            <th>???????????????????????????????????????</th>
                            <th>??????????????????????????????</th>
                            <th>????????????</th>
                            <th>???????????????????????????????????????????????????</th>
                            <th>??????????????????????????????</th>
                            <th>????????????????????????????????????</th>
                            <th>?????????????????????????????????</th>
                            <th>??????????????????</th>
                        </thead>
                        <?php
                            while($row = mysqli_fetch_assoc($resultSqlResult))
                            {
                                    // echo "<pre>";
                                    // var_export($row);
                                    // echo "</pre>";
                                $id = $row["id"];
                                $customer_idx = $row["customer_id"];
                                $route_id = $row["route_id"];

                                $pnr = $row["booking_id"];

                                $customer_name = get_from_table($conn, "customers","customer_id", $customer_idx, "customer_name");
                                
                                $customer_phone = get_from_table($conn,"customers","customer_id", $customer_idx, "customer_phone");

                                $bus_no = get_from_table($conn, "routes", "route_id", $route_id, "bus_no");

                                $route = $row["customer_route"];
                                $amphor = $row["amphor"];

                                $booked_seat = $row["booked_seat"];
                                
                                $booked_amount = $row["booked_amount"];

                                $dep_date = get_from_table($conn, "routes", "route_id", $route_id, "route_dep_date");

                                $dep_time = get_from_table($conn, "routes", "route_id", $route_id, "route_dep_time");

                                $booked_timing = $row["booking_created"];



                                


                                
                        ?>

                        <tr>
                            <?php if($role == "admin"){ ?>
                            <td>
                                <?php 
                                    echo $pnr;
                                ?>
                            </td>
                            <td>
                                <?php 
                                    echo $customer_idx; // customer_idx ????????? id ???????????????????????? database ????????????????????????????????????????????????????????????
                                ?>
                            </td>
                            <td>
                                <?php 
                                    echo $customer_phone;
                                ?>
                            </td>
                            <td>
                                <?php 
                                    echo $bus_no;
                                ?>
                            </td>
                            <td>
                                <?php 
                                    echo $route;
                                ?>
                            </td>
                            <td>
                                <?php 
                                    echo $booked_seat;
                                ?>
                            </td>
                            <td>
                                <?php 
                                    echo $booked_amount." "."?????????" ;
                                ?>
                            </td>
                            <td>
                                <?php 
                                    echo $dep_date . " , ". $dep_time;
                                ?>
                            </td>
                            <td>
                                <?php 
                                    echo $booked_timing;
                                ?>
                            </td>

                            <td>
                                <?php 
                                    echo $amphor;
                                ?>
                            </td>

                            <?php }else{  if($customer_id == $customer_idx){   ?>


                                <td>
                                <?php 
                                    echo $pnr;
                                ?>
                            </td>
                            <td>
                                <?php 
                                    echo $customer_idx;
                                ?>
                            </td>
                            <td>
                                <?php 
                                    echo $customer_phone;
                                ?>
                            </td>
                            <td>
                                <?php 
                                    echo $bus_no;
                                ?>
                            </td>
                            <td>
                                <?php 
                                    echo $route;
                                ?>
                            </td>
                            <td>
                                <?php 
                                    echo $booked_seat;
                                ?>
                            </td>
                            <td>
                                <?php 
                                    echo $booked_amount." "."?????????" ;
                                ?>
                            </td>

       
                            <td>
                                <?php 
                                    echo $dep_date . " , ". $dep_time;
                                ?>
                            </td>
                            <td>
                                <?php 
                                    echo $booked_timing;
                                ?>
                            </td>
                            
                      
                            <td>
                                <?php 
                                    echo $amphor;
                                ?>
                            </td>




                 




                            <?php } }?>
                            <?php if($customer_id == $customer_idx){ ?>
                            <td>
                     

                            <?php
                            $resultSqlx = "SELECT * FROM `payment` ";
                            
                            $resultSqlResultx = mysqli_query($conn, $resultSqlx);
                            while($row_pay = mysqli_fetch_assoc($resultSqlResultx)){
                                if($pnr == $row_pay["pnr_id"]){
                                    $pay_status = $row_pay["pay_status"];
                                    if($row_pay["pay_status"] == "???????????????????????????"){
                                        echo "<button class='button btn-sm-4 btn-warning text-white'>$pay_status</button>";


                                    }elseif($row_pay["pay_status"] == "????????????????????????" ){
                                        
                                        echo "<button class='button btn-sm-4 btn-success'>$pay_status</button>";


                                    }elseif($row_pay["pay_status"] == "??????????????????????????????"){
                                        echo "<button class='button btn-sm-4 btn-danger text-white'>$pay_status</button>";


                                    }
                                }

                            }                
                            
                            ?>

                            </td>

                            <?php }elseif($role == 'admin'){ ?>


                    

                                <td>
                                <?php 
                                    
                                    $resultSqlx = "SELECT * FROM `payment` ";
                            
                                    $resultSqlResultx = mysqli_query($conn, $resultSqlx);
                                    while($row_pay = mysqli_fetch_assoc($resultSqlResultx)){
                                        if($pnr == $row_pay["pnr_id"]){
                                            $pay_status = $row_pay["pay_status"];
                                            if($row_pay["pay_status"] == "???????????????????????????"){
                                                echo "<button class='button btn-sm-4 btn-warning text-white'>$pay_status</button>";
        
        
                                            }elseif($row_pay["pay_status"] == "????????????????????????" ){
                                                
                                                echo "<button class='button btn-sm-4 btn-success'>$pay_status</button>";
        
        
                                            }elseif($row_pay["pay_status"] == "??????????????????????????????"){
                                                echo "<button class='button btn-sm-4 btn-danger text-white'>$pay_status</button>";
        
        
                                            }
                                        }
        
                                    }  
                                    
                                    ?>
                                    </td>

                                <?php } ?>
                            <?php if($role == 'admin'){ ?>
                            <td>

                            

                                <button class="button delete-button btn-sm" data-bs-toggle="modal" data-bs-target="#deleteModal" 
                                data-id="<?php 
                                                
                                                echo $id;?>" data-bookedseat="<?php 
                                                echo $booked_seat;
                                            ?>" data-routeid="<?php 
                                            echo $route_id;
                                        ?>"> Delete</button>


                            </td>
                            <?php } ?>
                        </tr>
                        <?php 
                        }
                    ?>
                    </table>
                </div>
            </section>
            <?php } ?> 



            
        </div>
    </main>
    <!-- Requiring _getJSON.php-->
    <!-- Will have access to variables 
        1. routeJson
        2. customerJson
        3. seatJson
        4. busJson
        5. adminJson
        6. bookingJSON
    -->
    <?php require '../assets/partials/_getJSON.php';?>
    


    <div class="modal fade" id="addPNR" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
                <div class="modal-dialog modal-lg modal-dialog-scrollable">
                    <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title" id="exampleModalLabel">???????????????????????????</h5>
                        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                    </div>
                    <div class="modal-body">
                        <form id="addBookingForm" action="<?php echo $_SERVER['REQUEST_URI']; ?>" method="POST">
                            <!-- Passing Route JSON -->
                            <input type="hidden" id="routeJson" name="routeJson" value='<?php echo $routeJson; ?>'>
                            <!-- Passing Customer JSON -->
                            <input type="hidden" id="customerJson" name="customerJson" value='<?php echo $customerJson; ?>'>
                            <!-- Passing Seat JSON -->
                            <input type="hidden" id="seatJson" name="seatJson" value='<?php echo $seatJson; ?>'>

                            <div class="mb-3">
                                <label for="cid" class="form-label">????????????????????? PNR ??????????????????</label>
                                <!-- Search Functionality -->
                                <div class="searchQuery">
                                    <input type="text" class="form-control searchInput" id="cid" name="cid" required>
                                    <div class="sugg">
                                        
                                    </div>
                                </div>
                            </div>
                            
          
                            <button type="submit" class="btn btn-success" name="submit">???????????????</button>
                        </form>
                    </div>
                    <div class="modal-footer">
                        <!-- Add Anything -->
                    </div>
                    </div>
                </div>
        </div>


    <!-- All Modals Here -->
    <!-- Add Booking Modal -->
    <div class="modal fade" id="addModal" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
                <div class="modal-dialog modal-lg modal-dialog-scrollable">
                    <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title" id="exampleModalLabel">????????????????????????</h5>
                        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                    </div>
                    <div class="modal-body">
                        <form id="addBookingForm" action="<?php echo $_SERVER['REQUEST_URI']; ?>" method="POST">
                            <!-- Passing Route JSON -->
                            <input type="hidden" id="routeJson" name="routeJson" value='<?php echo $routeJson; ?>'>
                            <!-- Passing Customer JSON -->
                            <input type="hidden" id="customerJson" name="customerJson" value='<?php echo $customerJson; ?>'>
                            <!-- Passing Seat JSON -->
                            <input type="hidden" id="seatJson" name="seatJson" value='<?php echo $seatJson; ?>'>

                            <div class="mb-3">
                                <label for="cid" class="form-label">??????????????????????????????</label>
                                <!-- Search Functionality -->
                                <div class="searchQuery">
                                    <input type="text" class="form-control searchInput" value="CUST" placeholder='CUST-XXXXXXX' id="cid" name="cid" oninvalid="this.setCustomValidity('??????????????????????????? ?????????????????????????????? ??????????????????')" required>
                                    <div class="sugg">
                                        
                                    </div>
                                </div>
                            </div>
                            <div class="mb-3">
                                <label for="cname" class="form-label">????????????</label>
                                <input type="text" class="form-control" id="cname" name="cname" readonly>
                            </div>
                            <div class="mb-3">
                                <label for="cphone" class="form-label">?????????????????????????????????</label>
                                <input type="tel" class="form-control" id="cphone" name="cphone" readonly>
                            </div>

                            <div class="mb-3">
                            <label for="birthdaytime">???????????????????????????????????????</label>
                            <input type="date" id="godate" name="godate">

                            </div>

                            <div class="mb-3">
                                <label for="routeSearch" class="form-label">???????????????????????????????????????</label>
                                <!-- Search Functionality -->
                                <div class="searchQuery">
                                    <input type="text" class="form-control searchInput" id="routeSearch" name="routeSearch" placeholder="???????????????,??????????????????" value="???????????????" required>
                                    <div class="sugg">
                                    </div>
                                </div>
                            </div>
                            <!-- Send the route_id -->
                            <input type="hidden" name="route_id" id="route_id">
                            <!-- Send the departure timing too -->
                            <input type="hidden" name="dep_timing" id="dep_timing">

                            <div class="mb-3">
                                <label for="sourceSearch" class="form-label">?????????????????????????????????</label>
                                <!-- Search Functionality -->
                                <div class="searchQuery">
                                    <input type="text" class="form-control searchInput" id="sourceSearch" name="sourceSearch">
                                    <div class="sugg">
                                    </div>
                                </div>
                            </div>




                            <div class="mb-3">
                                <label for="destinationSearch" class="form-label">????????????????????????????????????</label>
                                <!-- Search Functionality -->
                                <div class="searchQuery">
                                    <input type="text" class="form-control searchInput" id="destinationSearch" name="destinationSearch">
                                    <div class="sugg">
                                    </div>
                                </div>
                            </div>

                            <div class="mb-3">
                                <label for="destinationSearch" class="form-label">????????????????????????????????????</label>
                                <!-- Search Functionality -->
                                <select name="amphor" id="amphor" class="form-control">
                                    <option value="??????????????????">??????????????????</option>
                                    <option value="???????????????">???????????????</option>
                                    <option value="????????????????????????">????????????????????????</option>
                                    <option value="????????????????????????????????????">????????????????????????????????????</option>
                                    <option value="?????????????????????">?????????????????????</option>
                                    <option value="???????????????????????????????????????">???????????????????????????????????????</option>
                                </select>
                               
                               
                            </div>


                            <div class="mb-3">
                                <label for="destinationSearch" class="form-label">???????????????????????????????????????????????????????????????????????????????????????</label>
                                <!-- Search Functionality -->
                                    
                                    <input type="text" class="form-control searchInput" name="note" >
                               
                               
                            </div>



                            <!-- Seats Diagram -->
                            <div class="mb-3 text-center">
                                <table id="seatsDiagram">
                                <tr>
                                    <td id="seat-1" data-name="1">1</td>
                                    <td class="space"></td>
                                    <td id="seat-99" data-name="99" class="notAvailable" style='font-size:10px;'>???????????????</td>
                                    
                                </tr>

                                <tr>
                                    <td id="seat-3" data-name="3">3</td>
                                    <td id="seat-4" data-name="4">4</td>
                                    <td id="seat-5" data-name="5">5</td>
                     
                                </tr>
                                <tr>
                                    <td id="seat-11" data-name="11">11</td>
                                    <td id="seat-12" data-name="12">12</td>
                                    <td id="seat-13" data-name="13">13</td>
                                   
                                </tr>

                                <tr>
                                    <td id="seat-21" data-name="21">21</td>
                                    <td id="seat-22" data-name="22">22</td>
                                    <td id="seat-23" data-name="23">23</td>
                                </tr>
                                <tr>
                                    <td id="seat-30" data-name="30">30</td>
                                    <td id="seat-31" data-name="31">31</td>
                                    <td id="seat-32" data-name="32">32</td>
                                    <td id="seat-33" data-name="33">33</td>
 
                                    </tr>
                                </table>
                            </div>
                            <div class="row g-3 align-items-center mb-3">
                                <div class="col-auto">
                                    <label for="seatInput" class="col-form-label">??????????????????????????????????????????</label>
                                </div>
                                <div class="col-auto">
                                    <input type="text" id="seatInput" class="form-control" name="seatInput" readonly>
                                </div>
                                <div class="col-auto">
                                    <span id="seatInfo" class="form-text">
                                    ?????????????????????????????????????????????????????????????????????????????????
                                    </span>
                                </div>
                            </div>
                            <div class="mb-3">
                                <label for="bookAmount" class="form-label">????????????</label>
                                <input type="text" class="form-control" id="bookAmount" name="bookAmount" readonly>
                            </div>
                            <button type="submit" class="btn btn-success" name="submit">?????????????????????</button>
                        </form>
                    </div>
                    <div class="modal-footer">
                        <!-- Add Anything -->
                    </div>
                    </div>
                </div>
        </div>
        <!-- Delete Modal -->
        <div class="modal fade" id="deleteModal" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered">
            <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="exampleModalLabel"><i class="fas fa-exclamation-circle"></i></h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <h2 class="text-center pb-4">
                    Are you sure?
                </h2>
                <p>
                    Do you really want to delete this booking? <strong>This process cannot be undone.</strong>
                </p>
                <!-- Needed to pass id -->
                <form action="<?php echo $_SERVER['REQUEST_URI']; ?>" id="delete-form"  method="POST">
                    <input id="delete-id" type="hidden" name="id">
                    <input id="delete-booked-seat" type="hidden" name="booked_seat">
                    <input id="delete-route-id" type="hidden" name="route_id">
                </form>
            </div>
            <div class="modal-footer d-flex justify-content-center">
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
                <button type="submit" form="delete-form" name="delete" class="btn btn-danger">Delete</button>
            </div>
            </div>
        </div>
    </div>
    <script src="../assets/scripts/admin_booking.js"></script>
    <!-- Option 1: Bootstrap Bundle with Popper -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.0.1/dist/js/bootstrap.bundle.min.js" integrity="sha384-gtEjrD/SeCtmISkJkNUaaKMoLD0//ElJ19smozuHV6z3Iehds+3Ulb9Bn9Plx0x4" crossorigin="anonymous"></script>
</body>
</html>