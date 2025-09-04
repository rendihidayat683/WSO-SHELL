<?php  @session_start(); error_reporting(0);
// -------------- Koneksi Database ------------
$DB_HOST 	= 'localhost';
$DB_NAME 	= 'fasttrav_lisensi'; // Nama database
$DB_USER 	= 'fasttrav_swidodocom'; // User Database
$DB_PASSWD  = 'II^vDBwHRmZ&'; // Password Database
// -------------- Koneksi Database ------------
@define("DB_HOST", $DB_HOST);
@define("DB_NAME", $DB_NAME);
@define("DB_USER", $DB_USER);
@define("DB_PASSWD" , $DB_PASSWD);

$connection = NEW mysqli( $DB_HOST, $DB_USER, $DB_PASSWD, $DB_NAME );
if ($connection->connect_error){
	echo"<style>
			body{
				background:#000000;
				color:#ffffff;
			}
		</style>
			<h3 style='text-align:center;font-size:25px;line-height:30px;'>
			Koneksi database gagal<br>Silahkan cek kembali konfigurasi Database Anda<br>".mysqli_connect_error();
	exit();
} else {
	$query_site  = "SELECT * FROM setting LIMIT 1";
	$result_site = $connection->query($query_site);
	$row_site    = $result_site->fetch_assoc();
	extract($row_site);
}

if (empty($_SESSION['csrf_token'])) {
	$_SESSION['csrf_token'] = bin2hex(random_bytes(32));
}

if (!function_exists('base_url')) {
	function base_url($atRoot=FALSE, $atCore=FALSE, $parse=FALSE){
	if (isset($_SERVER['HTTP_HOST'])) {
		$http = isset($_SERVER['HTTPS']) && strtolower($_SERVER['HTTPS']) !== 'off' ? 'https' : 'http';
		$hostname = $_SERVER['HTTP_HOST'];
		$dir = str_replace(basename($_SERVER['SCRIPT_NAME']), '', $_SERVER['SCRIPT_NAME']);
		$core = preg_split('@/@', str_replace($_SERVER['DOCUMENT_ROOT'], '', realpath(dirname(__FILE__))), NULL, PREG_SPLIT_NO_EMPTY);
		$core = $core[0];
		$tmplt = $atRoot ? ($atCore ? "%s://%s/%s/" : "%s://%s/") : ($atCore ? "%s://%s/%s/" : "%s://%s%s");
		$end = $atRoot ? ($atCore ? $core : $hostname) : ($atCore ? $core : $dir);
		$base_url = sprintf( $tmplt, $http, $hostname, $end );
	}
	else $base_url = 'http://localhost/';
		if ($parse) {
			$base_url = parse_url($base_url);
			if (isset($base_url['path'])) if ($base_url['path'] == '/') $base_url['path'] = '';
		}
			return $base_url;
		}
}
$base_url = base_url();?>
