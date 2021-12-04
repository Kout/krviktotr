<?php
include_once "fce.php";
include_once "dibi.php";
include_once "spoj.php";

//function cestaKfotkam($nazev,$nahradni= NULL,$kdy,$priznak){
	$cesta = cestaKfotkam($vypis["nazev"],$vypis["kus"],$vypis["kdy"],$vypis["priznak"]);

$cestaKobr = "obr/".$_GET["str"]."/".$napln["url_CS"]."/pidi/";
$fotky = glob($cestaKobr."*.jpg"); /*náběr názvů souborů včetně fotek, aby se getimgsize mělo na co ptát*/
//pre($fotky);
foreach($fotky as $fotka){
	$nazev = str_replace(".jpg", "", str_replace("/", "", strrchr($fotka, "/"))); 
	$zapis = "INSERT INTO fotky (soubor) VALUES ('" .$nazev . "')";	
	echo $zapis . "<br>\n";
//	mysql_query($zapis);
	$zapis2 = "INSERT INTO fotopas (cis_str, cis_fotky) VALUES ('" . $napln["cis"] . "','" .mysql_insert_id($spoj). "')";	
	echo $zapis2 . "<br>\n";
//	mysql_query($zapis2);
}
?>
