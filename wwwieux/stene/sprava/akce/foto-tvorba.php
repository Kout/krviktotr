<?php
include_once "../../vklad/dibi.php";  
include_once "../../vklad/spoj.php";  
include_once "../../vklad/fce.php";

$naber = dibi::query("SELECT st_inseminace.nazev
	FROM [st_inseminace]
	WHERE [plakat] IS NOT NULL
	");
while ($vypis =  $naber->fetch()){
	$cesta = mile_url($vypis["nazev"]);
	$adresar ="../../obr/tvorba/".$cesta; 
	if (!is_dir($adresar)) { 
	 if(mkdir($adresar, 0777)){
//	 	echo $adresar."<br>\n";
	 }
	 chmod($adresar, 0777);
	} 
	$adresar_obr = "../../obr/tvorba/".$cesta."/obr"; 
	if (!is_dir($adresar_obr)) { 
	 if(mkdir($adresar_obr, 0777)){
//	 	echo $adresar_obr."<br>\n";
	 }
	 chmod($adresar_obr, 0777); 
	} 
	$adresar_pidi="../../obr/tvorba/".$cesta."/pidi"; 
	if (!is_dir($adresar_pidi)) { 
	 if(mkdir($adresar_pidi, 0777)){
//		 echo $adresar_pidi."<br>\n";
	 }
	 chmod($adresar_pidi, 0777);
	}
}
header('Location: ' . $_SERVER['HTTP_REFERER']);
?>
