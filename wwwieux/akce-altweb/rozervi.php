<?php
if(empty($_POST) || !isset($_POST)){
	header("Location: ../");
}
include "../vklad/fce.php";
include_once "../vklad/dibi.php";
require '../vklad/spoj.php';

$hrajeme = dibi::fetch("SELECT st_vystup.nazev AS vystup, st_vystup.kdy, st_saly.nazev AS sal, st_inseminace.nazev
	FROM [st_vystup]
	LEFT JOIN [st_vystup_saly] ON st_vystup_saly.cis_vystup = st_vystup.cis
	LEFT JOIN [st_saly] ON st_vystup_saly.cis_salu = st_saly.cis
	LEFT JOIN [st_vystup_inseminace] ON st_vystup_inseminace.cis_vystup = st_vystup.cis
	LEFT JOIN [st_inseminace] ON st_vystup_inseminace.cis_inseminace = st_inseminace.cis
	WHERE st_vystup.cis = %i",$_POST["vystup"]);
//pre($_POST);
//pre($hrajeme);
if(is_null($hrajeme["vystup"])){
	$predstavko = $hrajeme["nazev"];
}else{
	$predstavko = $hrajeme["vystup"];
}
if(!is_null($_POST["kdo"])&&!is_null($_POST["meno"])){
	$reservace = array();
  	$reservace["meno"] = "'".mysql_real_escape_string($_POST["meno"])."'";
  	$reservace["emajl"] = "'".mysql_real_escape_string($_POST["kdo"])."'";
  	$reservace["kolik"] = "'".mysql_real_escape_string($_POST["kolik"])."'";
  	$reservace["vystup"] = "'".mysql_real_escape_string($_POST["vystup"])."'";
  	$reservace["vzkaz"] = "'".mysql_real_escape_string($_POST["vzkaz"])."'";
//Jakub Vrána php.vrana.cz/spolecny-formular-pro-editaci-a-vlozeni-zaznamu.php
  	$zapis = "INSERT INTO st_reservace (" . implode(", ", array_keys($reservace)) . ") VALUES (" . implode(", ", $reservace) . ")";
//pre($zapis);
	$location = "../";
  	if(!mysql_query($zapis)){
		$location .= "?zapis=0";
		header("Location: " . $location);
  	}else{
		$ted = datum(time());
//pre($ted);
		$hlavickaEmailu  = "MIME-Version: 1.0\n";
		$hlavickaEmailu .= "From: Krvik Totr<info@krviktotr.cz>\n";
		$hlavickaEmailu .= "Content-Type: text/html; charset=utf-8\n";
		$zpravaKrvikum = "<p>Přibyla nová reservace:<br>\n" . $_POST["kolik"] . "&times;<em>" . $predstavko . "</em> (" . datum(strtotime($hrajeme["kdy"]))  . ")<br>\nod: ".$_POST["kdo"]. "<br>\nreservováno: ". $ted."</p>\n<p>Vzkaz: " . $_POST["vzkaz"] . "<br>" . $_POST["meno"] . "</p>";
		$zpravaHostu = "<p>Vaše rezervace na jméno <em>" . $_POST["meno"] . "</em>,<br>\n" . $_POST["kolik"] . "&times; na představení <em>" . $predstavko . "</em>, které hrajeme " . datum(strtotime($hrajeme["kdy"]))  . " s místem konání <em>" . $hrajeme["sal"] . "</em>,<br>\nodeslaná ". $ted . ",<br>\nbyla v&nbsp;pořádku doručena Krvik Totr, kteří Vám z&nbsp;celého svého srdce děkují a těší se na Vás!</p>\n";
		$hlavickaEmailu .= "Reply-To: info@krviktotr.cz\n";
		if(mail('info@krviktotr.cz', 'Reservace na Krvik Totr', $zpravaKrvikum, $hlavickaEmailu)&&mail($_POST["kdo"], "Utvrzení rezervace", $zpravaHostu, $hlavickaEmailu)){
			$location .= "?zapis=1";
		}else{
			$location .= "?zapis=0";
		}
	header("Location: " .$location);
	}
}
//pre($zpravaHostu);
//pre($zpravaKrvikum);
//echo $location;
?>
