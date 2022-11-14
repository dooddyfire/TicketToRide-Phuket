<?php

    require '_functions.php';
    $conn = db_connect();

    // Getting user details
    $user_id = $_SESSION['user_id'];
    $sql = "SELECT * FROM users WHERE user_id = $user_id";
    $result = mysqli_query($conn, $sql);
    if($row = mysqli_fetch_assoc($result))
    {
        $user_fullname = $row["user_fullname"];
        $user_name = $row["user_name"];
        $role = $row["role"];
    }



    

    




?>

<!-- <header>
        <nav id="navbar">
            <ul>
                <li class="nav-item">
 
                </li>
                <li class="nav-item">
                    <img class="adminDp" src="../assets/img/admin_pic.jpg" alt="Admin Profile Pic" width="22px" height="22px">
                </li>
            </ul>
        </nav>
    </header> -->



    <main id="container">
        <div id="sidebar">
            <h4><i class="fas fa-bus"></i> TicketToRide</h4>
            <div>
                <img class="adminDp" src="../assets/img/userav-min.png" height="125px" alt="Admin Profile Pic">
                <p>
                    <?php  echo '@'.$user_name;  ?>
                </p>
                <?php if($role == "admin"){ 
                    echo "<p>System Administrator</p>";   
                }
                    else{
                        echo "<p>Member</p>";
                    }

                 ?>

                <p><?php 
                
                $sql = "SELECT * FROM customers";
                $result = mysqli_query($conn, $sql);
                $data = mysqli_fetch_all($result, MYSQLI_ASSOC);
                foreach($data AS $row) {
                    
                    
                   if($row['customer_name'] == $user_fullname){
                       
                       $customer_id = $row['customer_id'];
					   $customer_phone = $row['customer_phone'];
                       echo $customer_id; 
                   }
            
                }
                
                ?></p>
            </div>



            <ul id="options">
                <li class="option <?php if($page=='dashboard'){ echo 'active';}?>"> 
                    <a href="./dashboard.php">
                    <i class="fas fa-tachometer-alt"></i> แผงควบคุม
                    </a>
                </li>
                
               

    


               
                <li class="option <?php if($page=='bus'){ echo 'active';}?>">
                    <a href="./bus.php">
                    <i class="fas fa-bus"></i> รถตู้
                    </a>
                </li>
                

                 
                <li class="option <?php if($page=='route'){ echo 'active';}?>">
                    <a href="./route.php">
                    <i class="fas fa-road"></i> เส้นทางเดินรถ
                    </a>
                </li>
               
                

                <?php if($role == "admin"){ ?>   
                <li class="option <?php if($page=='customer'){ echo 'active';}?>">
                    <a href="./customer.php">
                    <i class="fas fa-users"></i> ลูกค้า
                    </a>
                </li>

                <?php  } ?>
                <li class="option <?php if ((basename($_SERVER['PHP_SELF']) == 'booking.php')) { echo 'active';}?>">
                    <a href="./booking.php">
                    <i class="fas fa-ticket-alt"></i> จองตั๋ว
                    </a>
                </li>
                <li class="option <?php if($page=='seat'){ echo 'active';}?>">
                    <a href="./seat.php">
                    <i class="fas fa-th"></i> ค้นหาที่นั่ง
                    </a>
                </li>

                <?php if($role == "admin"){ ?>   
                <li class="option <?php if($page=='signup'){ echo 'active';}?>">
                    <a href="./signup.php">
                    <i class="fas fa-user-lock"></i> เพิ่มแอดมินคนใหม่       
                    </a>
                </li>
                <?php  } ?>
				
				<?php if ($role != 'admin') { ?>
                <li class="option <?php if($page=='payment'){ echo 'active';}?>">
                    <a href="./payment.php">
                    <i class="fas fa-user-lock"></i> แจ้งชำระเงิน        
                    </a>
                </li>
				<?php  } ?>

                
                <?php if($role == "admin"){ ?>   
                <li class="option <?php if ((basename($_SERVER['PHP_SELF']) == 'invoice.php')) { echo 'active';}?>">
                    <a href="./invoice.php">
                    <i class="fas fa-user-lock"></i> รายการแจ้งชำระเงิน
                    </a>
                </li>
                <?php  } ?>
                

            </ul>
        </div>
        <div id="main-content">
            <section id="welcome">
                <ul>
                    <li class="welcome-item">ยินดีต้อนรับ, 
                        <span id="USER">
                            <?php 
                                echo $user_fullname;
                            ?>
                        </span>
                    </li>
                    <li class="welcome-item">
                        <button id="logout" class="btn-sm">
                            <a href="../assets/partials/_logout.php">LOGOUT</a>
                        </button>
                    </li>
                </ul>
            </section>