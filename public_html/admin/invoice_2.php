<!-- Show these admin pages only when the admin is logged in -->
<?php require '../assets/partials/_admin-check.php';   ?>


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
    <script src="https://code.jquery.com/jquery-2.1.3.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/sweetalert/1.1.3/sweetalert-dev.js"></script>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/sweetalert/1.1.3/sweetalert.css">
    <?php
    require '../assets/styles/admin.php';
    require '../assets/styles/admin-options.php';
    $page = "booking";
    ?>
</head>

<body>
    <!-- Requiring the admin header files -->
    <?php require '../assets/partials/_admin-header.php'; ?>
    <!-- Add, Edit and Delete Bookings -->
    <?php
    /*
            1. Check if an admin is logged in
            2. Check if the request method is POST
        */
    if ($loggedIn && $_SERVER["REQUEST_METHOD"] == "POST") {
        if (isset($_POST["submit"])) {
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
            // $dep_timing = $_POST["dep_timing"];

            $booking_exists = exist_booking($conn, $customer_id, $route_id);
            $booking_added = false;

            if (!$booking_exists) {
                // Route is unique, proceed
                $sql = "INSERT INTO `bookings` (`customer_id`, `route_id`, `customer_route`, `booked_amount`, `booked_seat`, `booking_created`) VALUES ('$customer_id', '$route_id','$route', '$amount', '$booked_seat', current_timestamp());";

                $result = mysqli_query($conn, $sql);
                // Gives back the Auto Increment id
                $autoInc_id = mysqli_insert_id($conn);
                // If the id exists then, 
                if ($autoInc_id) {
                    $key = "1234567890ABCDEFGHIJKLMNOPQRSTUVWXYZ";
                    $code = "";
                    for ($i = 0; $i < 5; ++$i)
                        $code .= $key[rand(0, strlen($key) - 1)];

                    // Generates the unique bookingid
                    $booking_id = $code . $autoInc_id;

                    $query = "UPDATE `bookings` SET `booking_id` = '$booking_id' WHERE `bookings`.`id` = $autoInc_id;";
                    $queryResult = mysqli_query($conn, $query);

                    if (!$queryResult)
                        echo "Not Working";
                }

                if ($result)
                    $booking_added = true;
            }

            if ($booking_added) {
                // Show success alert
                echo '<div class="my-0 alert alert-success alert-dismissible fade show" role="alert">
                    <strong>ลบรายการแจ้งชำระเงิน</strong> สำเร็จ
                    <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                    </div>';

                // Update the Seats table
                $bus_no = get_from_table($conn, "routes", "route_id", $route_id, "bus_no");
                $seats = get_from_table($conn, "seats", "bus_no", $bus_no, "seat_booked");
                if ($seats) {
                    $seats .= "," . $booked_seat;
                } else
                    $seats = $booked_seat;

                $updateSeatSql = "UPDATE `seats` SET `seat_booked` = '$seats' WHERE `seats`.`bus_no` = '$bus_no';";
                mysqli_query($conn, $updateSeatSql);
            } else {
                // Show error alert
                echo '<div class="my-0 alert alert-danger alert-dismissible fade show" role="alert">
                    <strong>เกิดข้อผิดพลาด</strong> ลบรายการแจ้งชำระเงินไม่สำเร็จ
                    <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                    </div>';
            }
        }
        if (isset($_POST["verify"])) {
            // EDIT BOOKING
            // echo "<pre>";
            // var_export($_POST);
            // echo "</pre>";die;
            $idx = $_POST["idx"];


            if ($idx) {
                $updateSql = "UPDATE `payment` SET
                    `pay_status` = 'จ่ายแล้ว'
                    WHERE `id` = '$idx';";

                $updateResult = mysqli_query($conn, $updateSql);
                $rowsAffected = mysqli_affected_rows($conn);

                $messageStatus = "danger";
                $messageInfo = "";
                $messageHeading = "Error!";

                if (!$rowsAffected) {
                    $messageInfo = "No Edits Administered!";
                } elseif ($updateResult) {
                    // Show success alert
                    $messageStatus = "success";
                    $messageHeading = "Successfull!";
                    $messageInfo = "อนุมัติรายการโอนเงินแล้วค่ะ";
                } else {
                    // Show error alert
                    $messageInfo = "Your request could not be processed due to technical Issues from our part. We regret the inconvenience caused";
                }

                // MESSAGE
                echo '<div class="my-0 alert alert-' . $messageStatus . ' alert-dismissible fade show" role="alert">
                    <strong>' . $messageHeading . '</strong> ' . $messageInfo . '
                    <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                    </div>';
            } else {
                // If customer details already exists
                echo '<div class="my-0 alert alert-danger alert-dismissible fade show" role="alert">
                    <strong>Error!</strong> Customer already exists
                    <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                    </div>';
            }
        }

        if (isset($_POST["notverify"])) {
            // EDIT BOOKING
            // echo "<pre>";
            // var_export($_POST);
            // echo "</pre>";die;
            $idx = $_POST["idx"];


            if ($idx) {
                $updateSql = "UPDATE `payment` SET
                    `pay_status` = 'ไม่อนุมัติ'
                    WHERE `id` = '$idx';";

                $updateResult = mysqli_query($conn, $updateSql);
                $rowsAffected = mysqli_affected_rows($conn);

                $messageStatus = "danger";
                $messageInfo = "";
                $messageHeading = "Error!";

                if (!$rowsAffected) {
                    $messageInfo = "No Edits Administered!";
                } elseif ($updateResult) {
                    // Show success alert
                    $messageStatus = "success";
                    $messageHeading = "Successfull!";
                    $messageInfo = "ทำรายการสำเร็จ";
                } else {
                    // Show error alert
                    $messageInfo = "Your request could not be processed due to technical Issues from our part. We regret the inconvenience caused";
                }

                // MESSAGE
                echo '<div class="my-0 alert alert-' . $messageStatus . ' alert-dismissible fade show" role="alert">
                    <strong>' . $messageHeading . '</strong> ' . $messageInfo . '
                    <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                    </div>';
            } else {
                // If customer details already exists
                echo '<div class="my-0 alert alert-danger alert-dismissible fade show" role="alert">
                    <strong>Error!</strong> Customer already exists
                    <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                    </div>';
            }
        }

        if (isset($_POST["wait"])) {
            // EDIT BOOKING
            // echo "<pre>";
            // var_export($_POST);
            // echo "</pre>";die;
            $idx = $_POST["idx"];


            if ($idx) {
                $updateSql = "UPDATE `payment` SET
                    `pay_status` = 'รอตรวจสอบ'
                    WHERE `id` = '$idx';";

                $updateResult = mysqli_query($conn, $updateSql);
                $rowsAffected = mysqli_affected_rows($conn);

                $messageStatus = "danger";
                $messageInfo = "";
                $messageHeading = "Error!";

                if (!$rowsAffected) {
                    $messageInfo = "No Edits Administered!";
                } elseif ($updateResult) {
                    // Show success alert
                    $messageStatus = "success";
                    $messageHeading = "Successfull!";
                    $messageInfo = "ทำรายการสำเร็จ";
                } else {
                    // Show error alert
                    $messageInfo = "Your request could not be processed due to technical Issues from our part. We regret the inconvenience caused";
                }

                // MESSAGE
                echo '<div class="my-0 alert alert-' . $messageStatus . ' alert-dismissible fade show" role="alert">
                    <strong>' . $messageHeading . '</strong> ' . $messageInfo . '
                    <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                    </div>';
            } else {
                // If customer details already exists
                echo '<div class="my-0 alert alert-danger alert-dismissible fade show" role="alert">
                    <strong>Error!</strong> Customer already exists
                    <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                    </div>';
            }
        }


        if (isset($_POST["delete"])) {
            // DELETE BOOKING

            $id = $_POST["id"];

            // Delete the booking with id => id
            $deleteSql = "DELETE FROM `payment` WHERE `id` = '$id' ";

            $deleteResult = mysqli_query($conn, $deleteSql);
            $rowsAffected = mysqli_affected_rows($conn);
            $messageStatus = "danger";
            $messageInfo = "";
            $messageHeading = "Error!";

            if (!$rowsAffected) {

                $messageInfo = "Record Doesn't Exist" . $deleteSql;
            } elseif ($deleteResult) {
                $messageStatus = "success";
                $messageInfo = "Booking Details deleted";
                $messageHeading = "Successfull!";
            } else {

                $messageInfo = "Your request could not be processed due to technical Issues from our part. We regret the inconvenience caused ";
            }

            // Message
            echo '<div class="my-0 alert alert-' . $messageStatus . ' alert-dismissible fade show" role="alert">
                <strong>' . $messageHeading . '</strong> ' . $messageInfo . '
                <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                </div>';
        }
    }
    ?>
    <?php
    $resultSql = "SELECT * FROM `payment` WHERE pay_status = 'จ่ายแล้ว' ORDER BY uploaded_on ASC";

    $resultSqlResult = mysqli_query($conn, $resultSql);

    if (!mysqli_num_rows($resultSqlResult)) { ?>
        <!-- Bookings are not present -->
        <div class="container mt-4">
            <div id="noCustomers" class="alert alert-dark " role="alert">
                <h1 class="alert-heading">ไม่มีรายการแจ้งโอนเงิน!!</h1>
                <p class="fw-light">Be the first person to add one!</p>
                <hr>
                <div id="addCustomerAlert" class="alert alert-success" role="alert">
                    คลิกเพื่อ <button id="add-button" class="button btn-sm" type="button" data-bs-toggle="modal" data-bs-target="#addModal">แจ้งชำระเงิน <i class="fas fa-plus"></i></button> ตอนนี้!
                </div>
            </div>
        </div>
    <?php } else { ?>
        <section id="booking">
            <div id="head">
                <h4>รายการโอนเงิน</h4>
            </div>
            <div>
                        <button onclick='window.location="invoice.php"' class="button btn-sm btn-info" type="button">ทั้งหมด</button>
                        <button onclick='window.location="invoice_2.php"' class="button btn-sm btn-success" type="button">จ่ายแล้ว</button>
                        <button onclick='window.location="invoice_3.php"' class="button btn-sm btn-warning" type="button">รอตรวจสอบ</button>
                        <button onclick='window.location="invoice_4.php"' class="button btn-sm btn-danger" type="button">ไม่อนุมัติ</button>
                    </div> <br>
            <div id="booking-results">

                <table class="table table-hover table-bordered">
                    <thead>
                        <th>ลำดับที่</th>
                        <th>ภาพสลิป</th>
                        <th>รหัสลูกค้า</th>
                        <th>หมายเลข PNR</th>
                        <th>ชื่อลูกค้า</th>
                        <th>วันที่แจ้ง</th>
                        <th>สถานะ</th>

                    </thead>
                    <?php
                    while ($row = mysqli_fetch_assoc($resultSqlResult)) {
                        // echo "<pre>";
                        // var_export($row);
                        // echo "</pre>";
                        $id = $row["id"];
                        $customer_id = $row["customer_id"];
                        $pnr_id = $row["pnr_id"];
                        $image_payment = $row['file_name'];
                        $customer_name = $row['customername'];
                        $pay_status = $row['pay_status'];

                        $pay_date = $row["uploaded_on"];
                    ?>
                        <tr>
                            <td>
                                <?php
                                echo $id;
                                ?>
                            </td>
                            <td style='width:130px;'>
                                <?php
                                $path_slip = "../assets/partials/uploads/" . $image_payment;
                                echo "<a href='$path_slip' target='_blank'>
                                    <img src='$path_slip' width='100%' height='100%' style='object-fit:cover;display:block;' ></a> ";
                                ?>
                            </td>
                            <td>
                                <?php
                                echo $customer_id;
                                ?>
                            </td>
                            <td>
                                <?php
                                echo $pnr_id;
                                ?>
                            </td>
                            <td>
                                <?php
                                echo $customer_name;
                                ?>
                            </td>
                            <td>
                                <?php
                                echo $pay_date;
                                ?>
                            </td>

                            <td>
                                <?php if ($pay_status == "จ่ายแล้ว") {
                                    echo "<button class='button btn-sm-4 btn-success'>$pay_status</button>";
                                } elseif ($pay_status == "รอตรวจสอบ") {
                                    echo "<button class='button btn-sm-4 btn-warning text-white'>$pay_status</button>";
                                } elseif ($pay_status == "ไม่อนุมัติ") {
                                    echo "<button class='button btn-sm-4 btn-danger text-white'>$pay_status</button>";
                                }


                                ?>
                            </td>


                            <td>
                                <button class="button btn-sm-4 btn-success check_order" data-bs-toggle="modal" data-bs-target="#verifyModal" data-id="<?= $id; ?>">ตรวจสอบ</button>
                                <button class="button btn-sm-4  btn-danger" data-bs-toggle="modal" data-bs-target="#deleteModal" data-id="<?php
                                                                                                                                            echo $id; ?>">ลบรายการ</button>
                            </td>
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
    <script type="text/javascript">
        $(document).ready(function() {

            $('.check_order').click(function() {
                var bid = $(this).data("id")
                document.getElementById("verify-id").value = bid;
            });

        }); //END $(document).ready()
    </script>
    <!-- Requiring _getJSON.php-->
    <!-- Will have access to variables 
        1. routeJson
        2. customerJson
        3. seatJson
        4. busJson
        5. adminJson
        6. bookingJSON
    -->
    <?php require '../assets/partials/_getJSON.php'; ?>

    <!-- All Modals Here -->
    <!-- Add Booking Modal -->
    <div class="modal fade" id="addModal" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
        <div class="modal-dialog modal-lg modal-dialog-scrollable">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="exampleModalLabel">แจ้งชำระเงิน</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <form action="../assets/partials/_handlePaymentModal.php" method="POST" enctype="multipart/form-data">
                        <!-- Passing Route JSON -->
                        <input type="hidden" id="routeJson" name="routeJson" value='<?php echo $routeJson; ?>'>
                        <!-- Passing Customer JSON -->
                        <input type="hidden" id="customerJson" name="customerJson" value='<?php echo $customerJson; ?>'>
                        <!-- Passing Seat JSON -->
                        <input type="hidden" id="seatJson" name="seatJson" value='<?php echo $seatJson; ?>'>

                        <div class="mb-3">
                            <label for="cid" class="form-label">หมายเลข PNR</label>
                            <!-- Search Functionality -->
                            <div class="searchQuery">
                                <input type="text" class="form-control searchInput" id="pnr" name="pnr" required>
                                <div class="sugg">

                                </div>
                            </div>
                        </div>

                        <div class="mb-3">
                            <label for="cid" class="form-label">รหัสลูกค้า</label>
                            <!-- Search Functionality -->
                            <div class="searchQuery">
                                <input type="text" class="form-control searchInput" id="cid" name="cid" required>
                                <div class="sugg">

                                </div>
                            </div>
                        </div>



                        <div class="mb-3">
                            <label for="firstname" class="form-label">ชื่อ</label>
                            <input type="text" class="form-control" id="cname" name="firstname" readonly>
                        </div>

                        <div class="mb-3">

                            <label for="" class="form-label">แนบสลิป</label>
                            <input type="file" name="file" id="file">

                        </div>

                        <button type="submit" class="btn btn-success" name="submit">แจ้งชำระเงิน</button>
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
                <div class="modal-body text-center">
                    <h2 class="text-center pb-4">
                        คุณแน่ใจหรือไม่?
                    </h2>
                    <p>
                        กรุณากรอก <b>ลำดับที่</b> ของใบแจ้งชำระเงิน <strong>คลิกปุ่ม Delete เพื่อลบ</strong>
                    </p>
                    <!-- Needed to pass id -->
                    <form action="<?php echo $_SERVER['REQUEST_URI']; ?>" id="delete-form" method="POST" class="text-center">
                        <input id="delete-id" name="id" placeholder="ลำดับที่" width="100%">

                    </form>
                </div>
                <div class="modal-footer d-flex justify-content-center">
                    <button type="submit" form="delete-form" name="delete" class="btn btn-danger">Delete</button>

                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
                </div>
            </div>
        </div>
    </div>


    <!-- Verify Modal -->
    <div class="modal fade" id="verifyModal" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="exampleModalLabel"><i class="fas fa-exclamation-circle"></i></h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body text-center">
                    <h2 class="text-center pb-4">
                        คุณแน่ใจหรือไม่?
                    </h2>
                    <p>
                        ต้องการยืนยัน <b>รายการโอนเงิน</b> ลำดับที่ <strong>คลิกปุ่ม อนุมัติ เพื่อยืนยัน</strong>
                    </p>
                    <!-- Needed to pass id -->
                    <form action="<?php echo $_SERVER['REQUEST_URI']; ?>" id="verify-form" method="POST" class="text-center">



                        <input type="hidden" id="verify-id" name="idx" placeholder="ลำดับที่" width="100%">

                    </form>
                </div>
                <div class="modal-footer d-flex justify-content-center">
                    <button type="submit" form="verify-form" name="verify" class="btn btn-success">อนุมัติ</button>
                    <button type="submit" form="verify-form" name="notverify" class="btn btn-danger">ไม่อนุมัติ</button>
                    <button type="submit" form="verify-form" name="wait" class="btn btn-warning text-white">รอตรวจสอบ</button>

                    <!--<button type="button" class="btn btn-primary" data-bs-dismiss="modal">ยกเลิก</button>-->
                </div>
            </div>
        </div>
    </div>


    <script src="../assets/scripts/admin_booking.js"></script>
    <!-- Option 1: Bootstrap Bundle with Popper -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.0.1/dist/js/bootstrap.bundle.min.js" integrity="sha384-gtEjrD/SeCtmISkJkNUaaKMoLD0//ElJ19smozuHV6z3Iehds+3Ulb9Bn9Plx0x4" crossorigin="anonymous"></script>
</body>

</html>