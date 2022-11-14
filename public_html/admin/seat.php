<!-- Show these admin pages only when the admin is logged in -->
<?php  require '../assets/partials/_admin-check.php';   ?>


<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Seats</title>
        <!-- google fonts -->
    <link rel="preconnect" href="https://fonts.gstatic.com">
    <link href="https://fonts.googleapis.com/css2?family=Montserrat:wght@100;200;300;400;500&display=swap" rel="stylesheet">
    <!-- Bootstrap CSS -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.0.1/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-+0n0xVW2eSR5OomGNYDnhzAbDsOXxcvSN1TPprVMTNDbiYZCxYbOOl7+AMvyTG2x" crossorigin="anonymous">
    <!-- Font Awesome -->
    <script src="https://kit.fontawesome.com/d8cfbe84b9.js" crossorigin="anonymous"></script>
    <!-- External CSS -->
    <?php 
        require '../assets/styles/admin.php';
        require '../assets/styles/admin-options.php';
        $page="seat";
    ?>
</head>
<body>
    <!-- Requiring the admin header files -->
    <?php require '../assets/partials/_admin-header.php';?>
    <?php
                $busSql = "Select * from buses";
                $resultBusSql = mysqli_query($conn, $busSql);
                $arr = array();
                while($row = mysqli_fetch_assoc($resultBusSql))
                    $arr[] = $row;
                $busJson = json_encode($arr);
            ?>

            <section id="seat">
                <div id="head">
                    <h4>สถานะที่นั่ง</h4>
                </div>
                <div id="main">
                    <form action="<?php echo $_SERVER['PHP_SELF']; ?>" method="GET">
                        <div class="searchBus">
                            <input type="text" id="bus-no" name="bus-no" placeholder="กรุณากรอกรหัสรถตู้"
                            class="busnoInput">
                            <div class="sugg">
                            </div>
                        </div>

                        <!-- Sending busJson -->
                        <input type="hidden" id="busJson" name="busJson" value='<?php echo $busJson; ?>'>
                        <button type="submit" name="submit">ค้นหา</button>
                    </form>
                    <div id="seat-results">
                        <?php
                            if(isset($_GET["submit"]))
                            {
                                $busno = $_GET["bus-no"];
                                $sql = "SELECT * FROM seats WHERE bus_no='$busno'";
                                $result = mysqli_query($conn, $sql);

                                $booked_seats = false;
                                if(mysqli_num_rows($result))
                                {
                                    $row = mysqli_fetch_assoc($result);
                                    $booked_seats = $row["seat_booked"];
                                }
                                if($booked_seats)
                                { ?>


                            <table id="seatsDiagram" class='text-center'>
                                <tr>
                                    <td id="seat-1" data-name="1">1</td>
                                    <td class="space"></td>
                                    <td id="seat-99" data-name="99" class="notAvailable" style='font-size:10px;'>คนขับ</td>
                                    
                                </tr>

                                <tr>
                                    <td id="seat-3" data-name="3">3</td>
                                    <td id="seat-4" data-name="4">4</td>
                                    <td id="seat-5" data-name="5">5</td>
                     
                                </tr>
                                <tr>
                                    <td id="seat-11" data-name="11">11</td>
                                    <td id="seat-12" data-name="12">12</td>
                                    <td id="seat-131" data-name="13">13</td>
                                   
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

                            <div style="text-align: center; color: #9a031e; font-weight: bold;">
                                <?php 
                                    echo $busno;
                                ?>
                            </div>
                            <?php }
                            // If booked_seats is empty
                            else { ?>
                                <p>No seat Booked</p>
                            <?php }
                            }
                        ?>
                    </div>
                </div>
            </section>
        </div>
    </main>
    <script src="../assets/scripts/admin_seat.js"></script>
</body>
</html>