<?php
    require 'assets/partials/_functions.php';
    $conn = db_connect();    

    if(!$conn) 
        die("Connection Failed");
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Bus Ticket Bookings</title>
    <!-- google fonts -->
    <link rel="preconnect" href="https://fonts.gstatic.com">
    <link href="https://fonts.googleapis.com/css2?family=Montserrat:wght@100;200;300;400;500&display=swap" rel="stylesheet">
    <!-- Font-awesome -->
    <script src="https://kit.fontawesome.com/d8cfbe84b9.js" crossorigin="anonymous"></script>
    <!-- Bootstrap CSS -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.0.1/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-+0n0xVW2eSR5OomGNYDnhzAbDsOXxcvSN1TPprVMTNDbiYZCxYbOOl7+AMvyTG2x" crossorigin="anonymous">
    <!-- CSS -->
    <?php 
        require 'assets/styles/styles.php'
    ?>
</head>
<body>
<style>
    #myBtn {
  display: none; /* Hidden by default */
  position: fixed; /* Fixed/sticky position */
  bottom: 20px; /* Place the button at the bottom of the page */
  right: 30px; /* Place the button 30px from the right */
  z-index: 99; /* Make sure it does not overlap */
  border: none; /* Remove borders */
  outline: none; /* Remove outline */
  background-color: blue; /* Set a background color */
  color: white; /* Text color */
  cursor: pointer; /* Add a mouse pointer on hover */
  padding: 15px; /* Some padding */
  border-radius: 10px; /* Rounded corners */
  font-size: 18px; /* Increase font size */
}

#myBtn:hover {
  background-color: #555; /* Add a dark-grey background on hover */
}
</style>
    <button onclick="topFunction()" id="myBtn" title="Go to top">เลื่อนขึ้น</button>
    <script>
            // Get the button:
let mybutton = document.getElementById("myBtn");

// When the user scrolls down 20px from the top of the document, show the button
window.onscroll = function() {scrollFunction()};

function scrollFunction() {
  if (document.body.scrollTop > 20 || document.documentElement.scrollTop > 20) {
    mybutton.style.display = "block";
  } else {
    mybutton.style.display = "none";
  }
}

// When the user clicks on the button, scroll to the top of the document
function topFunction() {
  document.body.scrollTop = 0; // For Safari
  document.documentElement.scrollTop = 0; // For Chrome, Firefox, IE and Opera
}
            
            </script>
    <?php
    
    if(isset($_GET["booking_added"]) && !isset($_POST['pnr-search']))
    {
        if($_GET["booking_added"])
        {
            echo '<div class="my-0 alert alert-success alert-dismissible fade show" role="alert">
                <strong>Successful!</strong> Booking Added, your PNR is <span style="font-weight:bold; color: #272640;">'. $_GET["pnr"] .'</span>
                <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                </div>';
        }
        else{
            // Show error alert
            echo '<div class="my-0 alert alert-danger alert-dismissible fade show" role="alert">
            <strong>Error!</strong> Booking already exists
            <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
            </div>';
        }
    }

    if($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST["pnr-search"]))
    {
        $pnr = $_POST["pnr"];

        $sql = "SELECT * FROM bookings WHERE booking_id='$pnr'";
        $result = mysqli_query($conn, $sql);

        $num = mysqli_num_rows($result);

        if($num)
        {
            $row = mysqli_fetch_assoc($result);
            $route_id = $row["route_id"];
            $customer_id = $row["customer_id"];
            
            $customer_name = get_from_table($conn, "customers", "customer_id", $customer_id, "customer_name");

            $customer_phone = get_from_table($conn, "customers", "customer_id", $customer_id, "customer_phone");

            $customer_route = $row["customer_route"];
            $booked_amount = $row["booked_amount"];
            $booked_amphor = $row["amphor"];
            $booked_seat = $row["booked_seat"];
            $booked_timing = $row["booking_created"];
            $note = $row["note"];

            $dep_date = get_from_table($conn, "routes", "route_id", $route_id, "route_dep_date");

            $dep_time = get_from_table($conn, "routes", "route_id", $route_id, "route_dep_time");

            $bus_no = get_from_table($conn, "routes", "route_id", $route_id, "bus_no");
            ?>

            <div class="alert alert-dark alert-dismissible fade show" role="alert">
            
            <h4 class="alert-heading">Booking Information!</h4>
            <p>
                <button class="btn btn-sm btn-success"><a href="assets/partials/_download.php?pnr=<?php echo $pnr; ?>" class="link-light">Download</a></button>
                <!--<button class="btn btn-danger btn-sm" id="deleteBooking" data-bs-toggle="modal" data-bs-target="#deleteModal" data-pnr="<?php echo $pnr;?>" data-seat="<?php echo $booked_seat;?>" data-bus="<?php echo $bus_no; ?>">
                    Delete
                </button>-->
            </p>
            <hr>
                <p class="mb-0">
                    <ul class="pnr-details">
                        <li>
                            <strong>PNR : </strong>
                            <?php echo $pnr; ?>
                        </li>
                        <li>
                            <strong>ชื่อ : </strong>
                            <?php echo $customer_name; ?>
                        </li>
                        <li>
                            <strong>เบอร์โทรศัพท์ : </strong>
                            <?php echo $customer_phone; ?>
                        </li>
                        <li>
                            <strong>เส้นทางเดินรถ : </strong>
                            <?php echo $customer_route; ?>
                        </li>
                        <li>
                            <strong>หมายเลขรถตู้ : </strong>
                            <?php echo $bus_no; ?>
                        </li>
                        <li>
                            <strong>อำเภอปลายทาง : </strong>
                            <?php echo $booked_amphor; ?>
                        </li>
                        <li>
                            <strong>หมายเลขที่นั่ง : </strong>
                            <?php echo $booked_seat; ?>
                        </li>
                        <li>
                            <strong>วันเดินทาง : </strong>
                            <?php echo $dep_date; ?>
                        </li>
                        <li>
                            <strong>เวลาเดินทาง : </strong>
                            <?php echo $dep_time; ?>
                        </li>
                        <li>
                            <strong>เวลาที่จอง : </strong>
                            <?php echo $booked_timing; ?>
                        </li>

                        <li>
                            <strong>หมายเหตุ : </strong>
                            <?php echo $note; ?>
                        </li>

                        <li>


                            <strong>สถานะการโอนเงิน : </strong>

                            <?php
                            $resultSqlx = "SELECT * FROM `payment` ";
                            
                            $resultSqlResultx = mysqli_query($conn, $resultSqlx);
                            while($row_pay = mysqli_fetch_assoc($resultSqlResultx)){
                                if($pnr == $row_pay["pnr_id"]){
                                    $pay_status = $row_pay["pay_status"];
                                    if($row_pay["pay_status"] == "รอตรวจสอบ"){
                                        echo "<button class='btn-sm btn-warning'>$pay_status</button>";


                                    }elseif($row_pay["pay_status"] == "จ่ายแล้ว" ){
                                        
                                        echo "<button class='btn-sm btn-success'>$pay_status</button>";


                                    }elseif($row_pay["pay_status"] == "ไม่อนุมัติ"){
                                        echo "<button class='btn-sm btn-danger text-white'>$pay_status</button>";


                                    }
                                }

                            }                
                            
                            ?>

                        </li>


                </p>
            <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
            </div>
        <?php }
        else{
            echo '<div class="my-0 alert alert-danger alert-dismissible fade show" role="alert">
                <strong>Error!</strong> Record Doesnt Exist
                <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                </div>';
        }
        
    ?>
        
    <?php }


        // Delete Booking
        if($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST["deleteBtn"]))
        {
            $pnr = $_POST["id"];
            $bus_no = $_POST["bus"];
            $booked_seat = $_POST["booked_seat"];

            $deleteSql = "DELETE FROM `bookings` WHERE `bookings`.`booking_id` = '$pnr'";

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
    ?>

    <!-- Navbar -->
    <header>
        <nav>
            <div>
                    <!--<a href="#" class="nav-item nav-logo">SBTBS</a> -->
                    <!-- <a href="#" class="nav-item">Gallery</a> -->
            </div>
                
            <ul>
                <li><a href="#" class="nav-item">หน้าแรก</a></li>
                <li><a href="#about" class="nav-item">เกี่ยวกับเรา</a></li>
                <li><a href="#contact" class="nav-item">ติดต่อเรา</a></li>
            </ul>
            <div>
            <a href="#" class="login nav-item" data-bs-toggle="modal" data-bs-target="#registerModal"><i class="fas fa-sign-in-alt" style="margin-right: 0.4rem;"></i>สมัครสมาชิก</a>

                <a href="#" class="login nav-item" data-bs-toggle="modal" data-bs-target="#loginModal"><i class="fas fa-sign-in-alt" style="margin-right: 0.4rem;"></i>เข้าสู่ระบบ</a>
                <a href="#pnr-enquiry" class="pnr nav-item">ค้นหาตั๋ว</a>
            </div>


        </nav>
    </header>
    <!-- Login Modal -->
    <?php require 'assets/partials/_loginModal.php'; 
    require 'assets/partials/_registerModal.php'; 
        require 'assets/partials/_getJSON.php';

        $routeData = json_decode($routeJson);
        $busData = json_decode($busJson);
        $customerData = json_decode($customerJson);
    ?>
    

    <section id="home">
        <div id="route-search-form">
            <h1>TicketToRide</h1>

            <p class="text-center">ยินดีต้อนรับสู่เว็บไซต์ TicketToRide บริการจองตั๋วรถตู้ชุมพร ไป ประจวบ ออนไลน์ได้แล้ววันนี้</p>

            <center>
            <button class="btn btn-success " data-bs-toggle="modal" data-bs-target="#registerModal">สมัครสมาชิก</button>

            <button class="btn btn-warning " data-bs-toggle="modal" data-bs-target="#loginModal">เข้าสู่ระบบ</button>
                
            </center>

            <br>
            <center>
            <a href="#pnr-enquiry"><button class="btn btn-primary">เลื่อนลง <i class="fa fa-arrow-down"></i></button></a>
            </center>
            
        </div>
    </section>
    <div id="block">
        <section id="info-num">
            <figure>
                <img src="assets/img/route.svg" alt="Bus Route Icon" width="100px" height="100px">
                <figcaption>
                    <span class="num counter" data-target="<?php echo count($routeData); ?>">999</span>
                    <span class="icon-name">เส้นทาง</span>
                </figcaption>
            </figure>
            <figure>
                <img src="assets/img/bus.svg" alt="Bus Icon" width="100px" height="100px">
                <figcaption>
                    <span class="num counter" data-target="<?php echo count($busData); ?>">999</span>
                    <span class="icon-name">รถตู้</span>
                </figcaption>
            </figure>
            <figure>
                <img src="assets/img/customer.svg" alt="Happy Customer Icon" width="100px" height="100px">
                <figcaption>
                    <span class="num counter" data-target="<?php echo count($customerData); ?>">999</span>
                    <span class="icon-name">ความเห็นลูกค้า</span>
                </figcaption>
            </figure>
            <figure>
                <img src="assets/img/ticket.svg" alt="Instant Ticket Icon" width="100px" height="100px">
                <figcaption>
                    <span class="num"><span class="counter" data-target="20">999</span> วินาที</span> 
                    <span class="icon-name">จองตั๋วออนไลน์ทันใจ</span>
                </figcaption>
            </figure>
        </section>
        <section id="info-num">
        <?php
            $resultSql = "SELECT * FROM `routes` ORDER BY route_created DESC";
                            
            $resultSqlResult = mysqli_query($conn, $resultSql);
            if(!mysqli_num_rows($resultSqlResult)){ ?>
                <!-- Routes are not present -->
                <div class="container mt-4">
                    <div id="noRoutes" class="alert alert-dark " role="alert">
                        <h1 class="alert-heading">No Routes Found!!</h1>
                        <p class="fw-light">Be the first person to add one!</p>
                        <hr>
                        <div id="addRouteAlert" class="alert alert-success" role="alert">
                                Click on <button id="add-button" class="button btn-sm"type="button"data-bs-toggle="modal" data-bs-target="#addModal">ADD <i class="fas fa-plus"></i></button> to add a route!
                        </div>
                    </div>
                </div>
            <?php }
            else { ?>
                <!-- Routes Are present -->
                <section id="route">
                    <div id="head">
                        <h4>สถานะเส้นทางการเดินรถ</h4>
                    </div>
                    <div id="route-results">
                        <table class="table table-hover table-bordered">
                            <thead>
                                <th>เส้นทางเดินรถ</th>
                                <th>รถตู้</th>
                                <th>เวลาออกเดินทาง</th>
                            </thead>
                            <?php
                                while($row = mysqli_fetch_assoc($resultSqlResult))
                                {
                                        // echo "<pre>";
                                        // var_export($row);
                                        // echo "</pre>";
                                    $id = $row["id"];
                                    $route_id = $row["route_id"];
                                    $route_cities = $row["route_cities"];
                                    $route_dep_time = $row["route_dep_time"];
                                    $route_dep_date = $row["route_dep_date"];
                                    $route_step_cost = $row["route_step_cost"];
                                    $bus_no = $row["bus_no"];
                                        ?>
                                    <tr>
                                        <td>
                                            <?php 
                                                echo $route_cities;
                                            ?>
                                        </td>
                                        <td>
                                            <?php 
                                                echo $bus_no;
                                            ?>
                                        </td>
                                        <td>
                                            <?php 
                                                echo $route_dep_time;
                                            ?>
                                        </td>


                                    </tr>
                                <?php 
                                }
                            ?>
                        </table>
                    </div>
                    </section>
                <?php  } ?>
                </section>
        <section id="pnr-enquiry">
            <div id="pnr-form">
                <h2>ค้นหาตั๋ว</h2>
                <form action="<?php echo $_SERVER["REQUEST_URI"]; ?>" method="POST">
                    <div>
                        <input type="text" name="pnr" id="pnr" placeholder="กรุณากรอกหมายเลข PNR" required oninvalid="this.setCustomValidity('กรุณากรอกหมายเลข PNR ของคุณที่ได้จากการจอง')" >
                    </div>
                    <button type="submit" name="pnr-search">ค้นหา</button>
                </form>
            </div>
        </section>
        <section id="about">
            <div>
                <h1>เกี่ยวกับเรา</h1>
                <!--<h4>จำหน่ายตั๋วรวดเร็วทันใจ</h4>-->
                <p>
                    จุดจอดรถ : ท่ารถตู้ชุมพรไนท์พลาซ่า 208-10 ในกรมหลวงชุมพร ซอย กรมหลวงชุมพร 10 ตำบล นาทุ่ง อำเภอเมืองชุมพร ชุมพร 86000 โทรติดต่อ 087-9344231.
                </p>
            </div>
        </section>
        <section id="contact">
            <div id="contact-form">
                <h1>ติดต่อเรา</h1>
                <form action="">
                    <div>
                        <label for="name">ชื่อ</label>
                        <input type="text" name="name" id="name">
                    </div>
                    <div>
                        <label for="email">อีเมลล์</label>
                        <input type="email" name="email" id="email">
                    </div>
                    <div>
                        <label for="message">ข้อความ</label>
                        <textarea name="message" id="message" cols="30" rows="10"></textarea>
                    </div>
                    <div></div>
                </form>
            </div>
        </section>
        <footer>
        <p>
                        <i class="far fa-copyright"></i> <?php echo date('Y');?> - TicketToRide
                        </p>
        </footer>
    </div>
    
    <!-- Delete Booking Modal -->
    <div class="modal fade" id="deleteModal" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
  <div class="modal-dialog">
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
                Do you really want to delete your booking? <strong>This process cannot be undone.</strong>
            </p>
            <!-- Needed to pass pnr -->
            <form action="<?php echo $_SERVER['PHP_SELF']; ?>" id="delete-form"  method="POST">
                    <input id="delete-id" type="hidden" name="id">
                    <input id="delete-booked-seat" type="hidden" name="booked_seat">
                    <input id="delete-booked-bus" type="hidden" name="bus">
            </form>
      </div>
      <div class="modal-footer">
        <button type="submit" form="delete-form" class="btn btn-primary btn-danger" name="deleteBtn">Delete</button>
      </div>
    </div>
  </div>
</div>
     <!-- Option 1: Bootstrap Bundle with Popper -->
     <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.0.1/dist/js/bootstrap.bundle.min.js" integrity="sha384-gtEjrD/SeCtmISkJkNUaaKMoLD0//ElJ19smozuHV6z3Iehds+3Ulb9Bn9Plx0x4" crossorigin="anonymous"></script>
    <!-- External JS -->
    <script src="assets/scripts/main.js"></script>
</body>
</html>