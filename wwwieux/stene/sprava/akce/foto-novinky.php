<?php
include_once "../../vklad/dibi.php";  
include_once "../../vklad/spoj.php";  
include_once "../../vklad/fce.php";

$naber = dibi::query("SELECT cis, rubrika, nazev, datum
	FROM [novinky]
	WHERE [fotky] IS NOT NULL
	ORDER BY [datum] DESC");
while ($vypis =  $naber->fetch()){
	$cesta = cestaKfotkam("novinky",mile_url($vypis["nazev"]),NULL,$vypis["datum"],$vypis["rubrika"]);
	$adresar = "../../".$cesta; 
	if (!is_dir($adresar)) { 
	 if(mkdir($adresar, 0777)){
//	 	echo $adresar."<br>\n";
	 }
	 chmod($adresar, 0777);
	} 
	$adresar_obr = "../../".$cesta."/obr"; 
	if (!is_dir($adresar_obr)) { 
	 if(mkdir($adresar_obr, 0777)){
//	 	echo $adresar_obr."<br>\n";
	 }
	 chmod($adresar_obr, 0777); 
	} 
	$adresar_pidi="../../".$cesta."/pidi"; 
	if (!is_dir($adresar_pidi)) { 
	 if(mkdir($adresar_pidi, 0777)){
//		 echo $adresar_pidi."<br>\n";
	 }
	 chmod($adresar_pidi, 0777);
	}
}
header('Location: ' . $_SERVER['HTTP_REFERER']);
?>
