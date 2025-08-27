<?
error_reporting(0);
include "include/connect.php";
?>
<!DOCTYPE html>
<html lang="en">
<head>
	<title>PTSP Kementrian Agama Lampung Selatan</title>
	<meta charset="UTF-8">
	<meta name="viewport" content="width=device-width, initial-scale=1">
<!--===============================================================================================-->	
	    <link rel="shortcut icon" href="img/favicon.png">
<!--===============================================================================================-->
	<link rel="stylesheet" type="text/css" href="fonts/font-awesome-4.7.0/css/font-awesome.min.css">
<!--===============================================================================================-->
	<link rel="stylesheet" type="text/css" href="fonts/iconic/css/material-design-iconic-font.min.css">
	<link rel="stylesheet" type="text/css" href="css/util.css">
	<link rel="stylesheet" type="text/css" href="css/main2.css">
<!--===============================================================================================-->
</head>
<body>
	<?
	$d1 = mysql_fetch_array(mysql_query("SELECT * FROM _instansi"));
	?>
		<div class="limiter">
			<div class="container-login100" style="background-image: url('images/bg-01.jpg');">
				<div class="wrap-login100">
					<form class="login100-form validate-form" method="post" action="module/login.php" enctype="multipart/form-data">
						<span class="login100-form-title p-b-34 p-t-27" style="margin-top:-70px;line-height: 80%;">
							<span style="font-size:0.6em;line-height: 50%;">PELAYANAN TERPADU SATU PINTU</span>
						</span>
						<div style="text-align:center;margin:-20px auto"><img src="img/kemenag.png" width="55%"></div>

						<span class="login100-form-title p-b-34 p-t-27" style="line-height: 80%;">
							<span style="font-size:0.6em;line-height: 50%;"><?echo $d1[nama]?></br><?echo $d1[kota]?></span>
							<p style="font-size:10px;color:#fff;margin-top:10px"><?echo $d1[alamat]?></p>
						</span>

						<div class="wrap-input100 validate-input" data-validate = "Enter username">
							<input class="input100" type="text" name="user" placeholder="Username">
							<span class="focus-input100" data-placeholder="&#xf207;"></span>
						</div>

						<div class="wrap-input100 validate-input" data-validate="Enter password">
							<input class="input100" type="password" name="pass" placeholder="Password">
							<span class="focus-input100" data-placeholder="&#xf191;"></span>
						</div>

						<div class="container-login100-form-btn">
							<input type="hidden" name="masuk" value="1">
							<button class="login100-form-btn">
								Login
							</button>
						</div>
						
					</form>
					</br></br>
					<center style="font-size:12px;color:#fff">Copyright@2020</center>
				</div>
			</div>
		</div>
</body>
</html>
