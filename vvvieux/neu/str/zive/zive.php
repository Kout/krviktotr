<?php 
include '../../vklad/hlava.php';
if(isset($_GET["podstr"]) && ($_GET["podstr"] == "odehrano" || $_GET["podstr"] == "kdy-hrajeme")){
	$bylo_bude = $_GET["podstr"];
	include 'vklad/vklad.php';
}elseif(isset($_GET["podstr"])){
	include 'vklad/'.$_GET["podstr"].'.php';
}else{
	$bylo_bude = "kdy-hrajeme";
	include 'vklad/vklad.php';
}
include '../../vklad/pata.php';
?>