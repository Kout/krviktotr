<?php
include_once "../../vklad/dibi.php";  
include_once "../../vklad/spoj.php";  
include_once "../../vklad/fce.php";

$naber = dibi::query("SELECT st_vystup.nazev, st_vystup.kdy, st_vystup.priznak, st_inseminace.nazev AS kus
	FROM [st_vystup]
	LEFT JOIN [st_vystup_inseminace] ON st_vystup_inseminace.cis_vystup = st_vystup.cis
	LEFT JOIN [st_inseminace] ON st_vystup_inseminace.cis_inseminace = st_inseminace.cis
	WHERE [fotky] IS NOT NULL
	");
while ($vypis =  $naber->fetch()){
	$cesta = cestaKfotkam("kdy-hrajeme",$vypis["nazev"],$vypis["kus"],$vypis["kdy"],$vypis["priznak"]);
	$adresar ="../../".$cesta; 
	if (!is_dir($adresar)) { 
	 if(mkdir($adresar, 0777)){
	 	// echo $adresar."<br>\n";
	 }
	 chmod($adresar, 0777);
	} 
	$adresar_obr = "../../".$cesta."/obr"; 
	if (!is_dir($adresar_obr)) { 
	 if(mkdir($adresar_obr, 0777)){
	 	// echo $adresar_obr."<br>\n";
	 }
	 chmod($adresar_obr, 0777); 
	} 
	$adresar_pidi="../../".$cesta."/pidi"; 
	if (!is_dir($adresar_pidi)) { 
	 if(mkdir($adresar_pidi, 0777)){
		 // echo $adresar_pidi."<br>\n";
	 }
	 chmod($adresar_pidi, 0777);
	}
}
header('Location: ' . $_SERVER['HTTP_REFERER']);
?>
