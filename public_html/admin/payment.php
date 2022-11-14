<!-- Show these admin pages only when the admin is logged in -->
<?php  require '../assets/partials/_admin-check.php';   ?>

<!DOCTYPE html>
<html lang="en">
    <head>
        <meta charset="UTF-8">
        <meta http-equiv="X-UA-Compatible" content="IE=edge">
        <meta name="viewport" content="width=device-width, initial-scale=1.0">
        <title>Payment</title>
            <!-- google fonts -->
        <link rel="preconnect" href="https://fonts.gstatic.com">
        <link href="https://fonts.googleapis.com/css2?family=Montserrat:wght@100;200;300;400;500&display=swap" rel="stylesheet">
        <!-- Bootstrap CSS -->
        <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.0.1/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-+0n0xVW2eSR5OomGNYDnhzAbDsOXxcvSN1TPprVMTNDbiYZCxYbOOl7+AMvyTG2x" crossorigin="anonymous">
        <!-- Font Awesome -->
        <script src="https://kit.fontawesome.com/d8cfbe84b9.js" crossorigin="anonymous"></script>
        <!-- External CSS -->
        <?php 
        require '../assets/styles/admin.php'; /*ตรงนี้คือไฟล์ CSS*/
        require '../assets/styles/signup.php';
        $page="signup";
    ?>
    </head>
<body>
    <!-- Requiring the admin header files -->
    <?php require '../assets/partials/_admin-header.php';?>

        <!-- Signup Status -->
            <?php
                if(isset($_GET['signup']))
                {
                    if($_GET['signup'])
                    {
                        // Show success alert
                        echo '<div class="my-0 alert alert-success alert-dismissible fade show" role="alert">
                        <strong>Successful!</strong> Account created successfully
                        <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                        </div>';
                    }

                    elseif($_GET['user_exists'])
                        // Show error alert
                        echo '<div class="my-0 alert alert-danger alert-dismissible fade show" role="alert">
                        <strong>Error!</strong> Username already exists
                        <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                        </div>';
                }
            ?>
            <section id="add-admin">
                <div>
                    <div id="paymentForm">
                        <h2>แจ้งชำระเงิน</h2>
                        <p for=""><b>กรุณากรอกชื่อ-นามสกุล ให้ตรงกับชื่อ-นามสกุล ในระบบดูได้จากด้านบนตัวหนังสือสีฟ้า</b></p>
                        <br>
                        <h2>ช่องทางการชำระเงิน</h2>
                        <h4>ธนาคารกรุงไทย : 9681615334</h4>
                        <h4>นางสาวสุกฤตา อนุเผ่า</h4>
                        <form action="../assets/partials/_handlePayment.php" method="POST" enctype="multipart/form-data">
                       
                            <div>
                                
                                <input readonly type="text" name="firstName" placeholder="ชื่อ*" value="<?php echo $user_fullname; ?>">
                                <input type="hidden" name="lastName" placeholder="นามสกุล*" value="<?php echo $user_fullname; ?>" required>
                            </div>
                            <div>
                                <input readonly type="text" name="cid" placeholder="รหัสลูกค้า" value="<?php echo $customer_id; ?>" required>
                            </div>
                            <div>
                                <?php
                                $resultSql = "SELECT * FROM `bookings` WHERE customer_id = '$customer_id' ORDER BY booking_created DESC";
                                $resultSqlResult = mysqli_query($conn, $resultSql);
                                ?>
                                <!--
                                <input type="text" name="pnr" placeholder="หมายเลข PNR" required>
                                -->
                                <select name="pnr" id="pnr" class="form-control" style="border-color: black;">
                                <option selected="">เลือกหมายเลข PNR</option>
                                <?php while ($row = mysqli_fetch_assoc($resultSqlResult)) { ?>
                                <option value="<?= $row["booking_id"]; ?>"><?= $row["booking_id"]; ?></option>
                                <?php } ?>
                            </select>
                            </div>

                            <div>
                                <label for=""><b>หลักฐานการชำระเงิน</b></label>
                        <input type="file" name="file" id="file">
                            </div>

                            <button id="signup-btn" type="submit" name="submit" style='font-family:Kanit;'>แจ้งชำระเงิน</button>
                        </form>
                    </div>
                </div>
                <div>
                </div>
            </section>
        </div>
    <script src="../assets/scripts/admin_signup.js">
    </script>
    <!-- Option 1: Bootstrap Bundle with Popper -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.0.1/dist/js/bootstrap.bundle.min.js" integrity="sha384-gtEjrD/SeCtmISkJkNUaaKMoLD0//ElJ19smozuHV6z3Iehds+3Ulb9Bn9Plx0x4" crossorigin="anonymous"></script>  
</body>
</html>