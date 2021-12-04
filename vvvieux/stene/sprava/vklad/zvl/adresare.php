<?php
include_once "../vklad/fce.php";
include_once "../vklad/dibi.php";
include_once "../vklad/spoj.php";
//pre($_GET);

if(isset($_GET["sekce"])){
	switch($_GET["sekce"]){
		case "novinky":
			$naber = dibi::query("SELECT cis, rubrika, nazev, datum
				FROM [novinky]
				WHERE [cis] = %i
				ORDER BY [datum] DESC",$_GET["kuprave"]);
			while ($vypis =  $naber->fetch()){
				$cesta = cestaKfotkam($vypis["nazev"],NULL,$vypis["datum"],$vypis["rubrika"]);
				$adresar ="../obr/novinky/".$cesta; 
				if (!is_dir($adresar)) { 
				 if(mkdir($adresar, 0777)){
						echo $adresar."<br>\n";
				 }
				 chmod($adresar, 0777);
				} 
				$adresar_obr = "../obr/novinky/".$cesta."/obr"; 
				if (!is_dir($adresar_obr)) { 
				 if(mkdir($adresar_obr, 0777)){
						echo $adresar_obr."<br>\n";
				 }
				 chmod($adresar_obr, 0777); 
				} 
				$adresar_pidi="../obr/novinky/".$cesta."/pidi"; 
				if (!is_dir($adresar_pidi)) { 
				 if(mkdir($adresar_pidi, 0777)){
					 echo $adresar_pidi."<br>\n";
				 }
				 chmod($adresar_pidi, 0777);
				}
			}
			break;
		case "st_vystup":
			$naber = dibi::query("SELECT st_vystup.nazev, st_vystup.kdy, st_vystup.priznak, st_inseminace.nazev AS kus
				FROM [st_vystup]
				LEFT JOIN [st_vystup_inseminace] ON st_vystup_inseminace.cis_vystup = st_vystup.cis
				LEFT JOIN [st_inseminace] ON st_vystup_inseminace.cis_inseminace = st_inseminace.cis
				WHERE [st_vystup.cis] = %i", $_GET["kuprave"]);
			while ($vypis =  $naber->fetch()){
				$cesta = cestaKfotkam($vypis["nazev"],$vypis["kus"],$vypis["kdy"],$vypis["priznak"]);
				$adresar ="../obr/kdy-hrajeme/".$cesta; 
				if (!is_dir($adresar)) { 
				 if(mkdir($adresar, 0777)){
						echo $adresar."<br>\n";
				 }
				 chmod($adresar, 0777);
				} 
				$adresar_obr = "../obr/kdy-hrajeme/".$cesta."/obr"; 
				if (!is_dir($adresar_obr)) { 
				 if(mkdir($adresar_obr, 0777)){
						echo $adresar_obr."<br>\n";
				 }
				 chmod($adresar_obr, 0777); 
				} 
				$adresar_pidi="../obr/kdy-hrajeme/".$cesta."/pidi"; 
				if (!is_dir($adresar_pidi)) { 
				 if(mkdir($adresar_pidi, 0777)){
					 echo $adresar_pidi."<br>\n";
				 }
				 chmod($adresar_pidi, 0777);
				}
			}
			break;
		case "tvorba":
			$naber = dibi::query("SELECT st_inseminace.nazev
			FROM [st_inseminace]
			WHERE [plakat] IS NOT NULL
			");
			while ($vypis =  $naber->fetch()){
				$cesta = mile_url($vypis["nazev"]);
				$adresar ="../obr/tvorba/".$cesta; 
				if (!is_dir($adresar)) { 
				 if(mkdir($adresar, 0777)){
						echo $adresar."<br>\n";
				 }
				 chmod($adresar, 0777);
				} 
				$adresar_obr = "../obr/tvorba/".$cesta."/obr"; 
				if (!is_dir($adresar_obr)) { 
				 if(mkdir($adresar_obr, 0777)){
						echo $adresar_obr."<br>\n";
				 }
				 chmod($adresar_obr, 0777); 
				} 
				$adresar_pidi="../obr/tvorba/".$cesta."/pidi"; 
				if (!is_dir($adresar_pidi)) { 
				 if(mkdir($adresar_pidi, 0777)){
					 echo $adresar_pidi."<br>\n";
				 }
				 chmod($adresar_pidi, 0777);
				}
			}
			break;
		case "st_str":
			$naber = dibi::query("SELECT nazev
				FROM [st_str]
				WHERE [cis] = %i", $_GET["kuprave"]);
			while ($vypis =  $naber->fetch()){
				$cesta = mile_url($vypis["nazev"]);
				$adresar ="../obr/str/".$cesta; 
				if (!is_dir($adresar)) { 
				 if(mkdir($adresar, 0777)){
						echo $adresar."<br>\n";
				 }
				 chmod($adresar, 0777);
				} 
				$adresar_obr = "../obr/str/".$cesta."/obr"; 
				if (!is_dir($adresar_obr)) { 
				 if(mkdir($adresar_obr, 0777)){
						echo $adresar_obr."<br>\n";
				 }
				 chmod($adresar_obr, 0777); 
				} 
				$adresar_pidi="../obr/str/".$cesta."/pidi"; 
				if (!is_dir($adresar_pidi)) { 
				 if(mkdir($adresar_pidi, 0777)){
					 echo $adresar_pidi."<br>\n";
				 }
				 chmod($adresar_pidi, 0777);
				}
			}
			break;
	}
}

$location = "?jak=upravit&upravit=".$_GET["sekce"]."&kuprave=".$_GET["kuprave"];
echo $location;
//header("Location: ".$location);
?>
