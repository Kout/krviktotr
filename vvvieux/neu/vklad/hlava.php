<?php
include 'fce.php';
include 'spoj.php';
?>
<!DOCTYPE HTML PUBLIC "-//W3C//DTD HTML 4.01 Strict//EN">
<html>
  <head>
  <meta http-equiv="content-type" content="text/html; charset=utf-8">
  <link rel="stylesheet" type="text/css" href="<?php
     uroven_vys();
  ?>vzhled/vzhled-KT.css" media="screen">
   <link rel="stylesheet" type="text/css" href="<?php
     uroven_vys();
  ?>vzhled/vzhled-KT-2blok.css" media="screen">
  <?php 
	if(!$_GET["odd"]){
		echo "<link rel=\"stylesheet\" type=\"text/css\" href=\"vzhled/vzhled-KT-2hlava.css\" media=\"screen\">\n";
		echo "<link rel=\"stylesheet\" type=\"text/css\" href=\"vzhled/vzhled-index.css\" media=\"screen\">\n";
	}else{
		if ($_GET["odd"] == "zive" || $_GET["odd"] == "tvorba" || $_GET["podstr"] == "lide"){
			echo "<link rel=\"stylesheet\" type=\"text/css\" href=\"";
			uroven_vys();
			echo "vzhled/vzhled-KT-1pocin.css\" media=\"screen\">\n";
		}
		if (pod_podhlava() > 0){
			echo "<link rel=\"stylesheet\" type=\"text/css\" href=\"";
			uroven_vys();
			echo "vzhled/vzhled-KT-3hlava.css\" media=\"screen\">\n";
			//echo "<style type=\"text/css\">#pod-podhlava{background-image: url(obr/pod-podhlavu.png);}</style>\n";
		}else {
			echo "<link rel=\"stylesheet\" type=\"text/css\" href=\"";
			uroven_vys();
			echo "vzhled/vzhled-KT-2hlava.css\" media=\"screen\">\n";
			// echo "<style type=\"text/css\"> #podhlava {background-image: url(obr/podhlavu.png);}</style>\n";
		}
		echo "<link rel=\"stylesheet\" type=\"text/css\" href=\"";
		uroven_vys(); 
		echo "str/".$_GET["odd"]."/vzhled-KT-".$_GET["odd"].".css\" media=\"screen\">\n";
	}
?><title>Krvik Totr</title>
  </head>
  <body>
<div id="telo">

  <div id="stat">
     