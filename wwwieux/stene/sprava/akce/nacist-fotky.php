<?php
include "../../vklad/dibi.php";  
include "../../vklad/spoj.php";  
include "../../vklad/fce.php";
//pre($_GET);
if($_GET["sekce"] == "novinky"){
	$novinka = dibi::fetch("SELECT novinky.cis, novinky.rubrika, novinky.nazev, novinky.datum, novinky.kdy, novinky.fotky
		FROM [novinky]
		WHERE [cis] = %i",$_GET["kuprave"]);
		// cestaKfotkam($nazev,$nahradni= NULL,$kdy,$priznak)
	$cestaKobr = "../../".cestaKfotkam("novinky",$novinka["nazev"],NULL,$novinka["datum"],$novinka["rubrika"])."/pidi/";
//echo $cestaKobr;
	$pfotky = glob($cestaKobr."*.jpg"); /*náběr názvů souborů včetně fotek, aby se getimgsize mělo na co ptát*/
//pre($pfotky);
	foreach($pfotky as $pfotka){
		$nazev = str_replace(".jpg", "", str_replace("/", "", strrchr($pfotka, "/"))); 
		$zapsat = array(
			"soubor" => $nazev,
			"cesta" => $cestaKobr,
		);	
		if(dibi::query("INSERT INTO [st_fotky] %v",$zapsat," ON DUPLICATE KEY UPDATE [zobr] = 1")){
			$zapis2 = "INSERT INTO st_foto_novinky (cis_fotky, cis_novinky) VALUES ('" .dibi::insertId(). "','" . $novinka["cis"] . "')";	
	//echo $zapis2 . "<br>\n";
			dibi::query($zapis2);
		}
		$zapis3 = "UPDATE novinky SET [fotky] = '1' WHERE [cis] = ".$novinka["cis"];	
//echo $zapis3 . "<br>\n";
		dibi::query($zapis3);
	}
	header('Location: ' . $_SERVER['HTTP_REFERER']);
	exit;	
}elseif($_GET["sekce"] == "st_vystup"){
	$vystoup = dibi::fetch("SELECT st_vystup.cis AS cislo, st_vystup.nazev, st_vystup.kdy AS datum, st_vystup.priznak, st_inseminace.nazev AS inseminace
		FROM [st_vystup]
		LEFT JOIN [st_vystup_inseminace] ON st_vystup_inseminace.cis_vystup = st_vystup.cis
		LEFT JOIN [st_inseminace] ON st_vystup_inseminace.cis_inseminace = st_inseminace.cis
		WHERE st_vystup.cis= %i", $_GET["kuprave"]);
	//-- LEFT JOIN [st_foto_vystup] ON st_vystup.cis = st_foto_vystup.cis_vystup
		// -- LEFT JOIN [st_fotky] ON st_fotky.cis = st_foto_vystup.cis_fotky
 // dibi::dump();				
	if(is_null($vystoup["nazev"])){
		$nazev = $vystoup["inseminace"];
	}else{
		$nazev = $vystoup["nazev"];
	}

	// cestaKfotkam($nazev,$nahradni= NULL,$kdy,$priznak)
	$cestaKobr = "../../".cestaKfotkam("kdy-hrajeme",$nazev,NULL,$vystoup["datum"],$vystoup["priznak"])."/pidi/";
// echo $cestaKobr;
	$pfotky = glob($cestaKobr."*.jpg"); /*náběr názvů souborů včetně fotek, aby se getimgsize mělo na co ptát*/
// pre($pfotky);
	foreach($pfotky as $pfotka){
// pre($pfotka);
		$nazev = str_replace(".jpg", "", str_replace("/", "", strrchr($pfotka, "/"))); 
		$zapsat = array(
			"soubor" => $nazev,
			"cesta" => $cestaKobr,
			);
		$zapis[] = "INSERT INTO st_fotky %v";
		array_push($zapis,$zapsat, " ON DUPLICATE KEY UPDATE %a", $zapsat);
		// dibi::test($zapis);
		if(dibi::query($zapis)){
			$zapis2 = "INSERT INTO st_foto_vystup (cis_fotky, cis_vystup) VALUES ('" .dibi::insertId(). "','" . $vystoup["cislo"] . "')";	
			// dibi::test($zapis2);
			dibi::query($zapis2);
		}
		unset($zapis);
		
		$zapis3 = "UPDATE st_vystup SET [fotky] = '1' WHERE [cis] = ". $vystoup["cislo"];	
//echo $zapis3 . "<br>\n";
		dibi::query($zapis3);
	}
	header('Location: ' . $_SERVER['HTTP_REFERER']);
	exit;
}elseif($_GET["sekce"] == "st_inseminace"){
	$insem = dibi::fetch("SELECT st_inseminace.cis, st_inseminace.nazev
		FROM [st_inseminace]
		WHERE st_inseminace.cis = %i", $_GET["kuprave"]);
	$nazev = $insem["nazev"];
	$cestaKobr = "../../".cestaKfotkam("tvorba",$nazev,NULL,NULL,NULL)."/pidi/";
	$pfotky = glob($cestaKobr."*.jpg"); /*náběr názvů souborů včetně fotek, aby se getimgsize mělo na co ptát*/
	foreach($pfotky as $pfotka){
		$nazev = str_replace(".jpg", "", str_replace("/", "", strrchr($pfotka, "/"))); 
		$zapsat = array(
			"soubor" => $nazev,
			"cesta" => $cestaKobr,
		);
		if(dibi::query("INSERT INTO [st_fotky] %v",$zapsat," ON DUPLICATE KEY UPDATE [zobr] = 1")){
			$zapis2 = "INSERT INTO st_foto_inseminace (cis_fotky, cis_inseminace) VALUES ('" .dibi::insertId(). "','" . $insem["cis"] . "')";	
			dibi::query($zapis2);
		}
		$zapis3 = "UPDATE st_inseminace SET [plakat] = '1' WHERE [cis] = ". $insem["cis"];	
		dibi::query($zapis3);
	}
	header('Location: ' . $_SERVER['HTTP_REFERER']);
	exit;
}elseif($_GET["sekce"] == "st_str"){
	$obsah= dibi::fetch("SELECT cis, title, nadpis, nazev, url, fotky
		FROM [st_str]
		WHERE [cis] = %i",$_GET["kuprave"]);
	$cestaKobr = "../../obr/str/".$obsah["url"]."/pidi/";
	$pfotky = glob($cestaKobr."*.jpg"); /*náběr názvů souborů včetně fotek, aby se getimgsize mělo na co ptát*/
	foreach($pfotky as $pfotka){
		$nazev = str_replace(".jpg", "", str_replace("/", "", strrchr($pfotka, "/"))); 
		$zapsat = array(
			"soubor" => $nazev,
			"cesta" => $cestaKobr,
		);
		if(dibi::query("INSERT INTO [st_fotky] %v",$zapsat," ON DUPLICATE KEY UPDATE [zobr] = 1")){
			$zapis2 = "INSERT INTO st_foto_str (cis_fotky, cis_str) VALUES ('" .dibi::insertId(). "','" . $obsah["cis"] . "')";	
			dibi::query($zapis2);
		}
		$zapis3 = "UPDATE st_str SET [fotky] = '1' WHERE [cis] = ".$obsah["cis"];	
		dibi::query($zapis3);
	}
	header('Location: ' . $_SERVER['HTTP_REFERER']);
	exit;
}elseif($_GET["sekce"] == "lidi"){
	$obsah= dibi::fetch("SELECT cis, url, fotky
		FROM [lidi]
		WHERE [cis] = %i",$_GET["kuprave"]);
	$cestaKobr = "../../obr/o-sobe/".$obsah["url"]."/pidi/";
	$pfotky = glob($cestaKobr."*.jpg"); /*náběr názvů souborů včetně fotek, aby se getimgsize mělo na co ptát*/
	foreach($pfotky as $pfotka){
		$nazev = str_replace(".jpg", "", str_replace("/", "", strrchr($pfotka, "/"))); 
		$zapsat = array(
			"soubor" => $nazev,
			"cesta" => $cestaKobr,
		);
		if(dibi::query("INSERT INTO [st_fotky] %v",$zapsat," ON DUPLICATE KEY UPDATE [zobr] = 1")){
			$zapis2 = "INSERT INTO st_foto_lidi (cis_fotky, cis_lidi) VALUES ('" .dibi::insertId(). "','" . $obsah["cis"] . "')";	
			dibi::query($zapis2);
		}
		$zapis3 = "UPDATE lidi SET [fotky] = '1' WHERE [cis] = ".$obsah["cis"];	
		dibi::query($zapis3);
	}
	header('Location: ' . $_SERVER['HTTP_REFERER']);
	exit;
}
?>
