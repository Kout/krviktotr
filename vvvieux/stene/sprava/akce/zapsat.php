<?php
include "../../vklad/dibi.php";  
include "../../vklad/spoj.php";  
include "../../vklad/fce.php";

// pre($_POST);
//pre($_GET);

if(isset($_GET["upravit"])){
	$location = "Location: ../index.php";
	if(!isset($_GET["pryc"])){ //úprava stávajícího záznamu
			if($_GET["upravit"] == "st_str"){
				$zmeny["title%s"] = ocista($_POST["title"]);
				$zmeny["nadpis%s"] = ocista($_POST["nadpis"]);
				$zmeny["nazev%s"] = ocista($_POST["nazev"]);
				$zmeny["obsah%s"] = stripslashes(ocista($_POST["obsah"]));
				$zmeny["fotky%s"] = ocista($_POST["fotky"]);
				$zmeny["zobr%s"] = $_POST["zobr"];
			}elseif($_GET["upravit"] == "novinky"){
				$zmeny["nazev%s"] = $_POST["nazev"];
				$zmeny["rubrika%s"] = ocista($_POST["rubrika"]);
				$zmeny["obsah%s"] = stripslashes($_POST["obsah"]);
				$zmeny["datum%d"] = $_POST["datum"];
				$zmeny["kdy%s"] = ocista($_POST["kdy"]);
				$zmeny["fotky%s"] = ocista($_POST["fotky"]);
				$zmeny["struk%s"] = ocista($_POST["struk"]);
				$zmeny["zobr%s"] = $_POST["zobr"];		
			}elseif($_GET["upravit"] == "st_vystup"){
				if($_POST["novinka"] == "nix"){
					empty($_POST["novinka"]);
				}
				$zmeny["nazev%s"] = ocista($_POST["nazev"]);
				$zmeny["o%s"] = stripslashes(ocista($_POST["o"]));
				$zmeny["kronika%s"] = stripslashes(ocista($_POST["kronika"]));
				$zmeny["fotky%s"] = ocista($_POST["fotky"]);
				$zmeny["kdy%t"] = ocista($_POST["kdy"]);
				$zmeny["nekdy%s"] = ocista($_POST["nekdy"]);
				$zmeny["prilezitost%s"] = ocista($_POST["prilezitost"]);
				$zmeny["spoluucinkujici%s"] = ocista($_POST["spoluucinkujici"]);
				$zmeny["priznak%s"] = ocista($_POST["priznak"]);
				$zmeny["vstup%s"] = ocista($_POST["vstup"]);
				$zmeny["reservace%in"] = ocista($_POST["reserve"]);
				$zmeny["zobr%s"] = $_POST["zobr"];		
			}elseif($_GET["upravit"] == "st_inseminace"){
				$zmeny["nazev%s"] = ocista($_POST["nazev"]);
				$zmeny["podtitul%s"] = stripslashes(ocista($_POST["podtitul"]));
				$zmeny["premca%s"] = ocista($_POST["premca"]);
				$zmeny["naposledy%s"] = ocista($_POST["naposledy"]);
				$zmeny["derniera%s"] = ocista($_POST["derniera"]);
				$zmeny["prem%i"] = ocista($_POST["prem"]);
				$zmeny["napos%i"] = ocista($_POST["napos"]);
				$zmeny["dern%i"] = ocista($_POST["dern"]);
				$zmeny["rok%s"] = ocista($_POST["rok"]);
				$zmeny["co%s"] = ocista($_POST["co"]);
				$zmeny["autori%s"] = ocista($_POST["autori"]);
				$zmeny["anotace%s"] = ocista($_POST["anotace"]);
				$zmeny["text%s"] = ocista($_POST["text"]);
				$zmeny["popis%s"] = ocista($_POST["popis"]);
				//$zmeny["plakat%s"] = ocista($_POST["plakat"]);
				$zmeny["zobr%s"] = $_POST["zobr"];		
			}elseif($_GET["upravit"] == "lidi"){
				$zmeny["jmeno%s"] = ocista($_POST["jmeno"]);
				$zmeny["jmenavigace%sn"] = ocista($_POST["jmenavigace"]); 
				$zmeny["poradi%i"] = $_POST["poradi"]; 
				$zmeny["url%s"] = $_POST["url"]; 
				$zmeny["oddo%sn"] = ocista($_POST["oddo"]); 
				$zmeny["bio%sn"] = ocista($_POST["bio"]); 
				$zmeny["pozn%sn"] = ocista($_POST["pozn"]); 
				$zmeny["strejcek%sn"] = ocista($_POST["strejcek"]); 
				$zmeny["fotky%in"] = $_POST["fotky"];
				$zmeny["zobr%in"] = $_POST["zobr"]; 
			}elseif($_GET["upravit"] == "st_saly"){
				$zmeny["nazev%s"] = ocista($_POST["nazev"]);
				$zmeny["nadnazev%s"] = stripslashes(ocista($_POST["nadnazev"]));
				$zmeny["mesto%s"] = ocista($_POST["mesto"]);
				$zmeny["ctvrt%s"] = ocista($_POST["ctvrt"]);
				$zmeny["adresa%s"] = ocista($_POST["adresa"]);
				$zmeny["www%s"] = ocista($_POST["www"]);
				$zmeny["kudytam%s"] = ocista($_POST["kudytam"]);
				$zmeny["pozn%s"] = ocista($_POST["pozn"]);
				$zmeny["zobr%s"] = $_POST["zobr"];		
			}elseif($_GET["upravit"] == "st_fotky"){
				$zmeny["popisek%s"] = stripslashes(ocista($_POST["popisek"]));
				$zmeny["autor%s"] = stripslashes(ocista($_POST["autor"]));
				$zmeny["kdy%d"] = ocista($_POST["kdy"]);
				$zmeny["nekdy%s"] = ocista($_POST["nekdy"]);
				$zmeny["zobr%s"] = $_POST["zobr"];		
			}	
//pre($zmeny);
			if(!isset($_POST["nieuw"])){
				if($_GET["upravit"] != "st_fotky"){
					$location .= "?jak=upravit&upravit=" . $_GET["upravit"]."&kuprave=" . $_GET["kde"];
				}else{
					$location .= "?jak=upravit&upravit=fotky&kuprave=" . $_GET["jaka"] . "&sekce=".$_GET["odkud"]."&fotky=";
				}
				$dotaz[] = "UPDATE [".$_GET['upravit']."] SET ";
				array_push($dotaz, $zmeny," WHERE `cis` = %i",$_GET["kde"]);
				if($_GET["upravit"] == "st_vystup"){
					if(isset($_POST["sal"]) || isset($_POST["novinka"])){
						dibi::query($dotaz);
						if(empty($_POST["novinka"])){	
							dibi::query("UPDATE [st_vystup_saly] SET [cis_salu] = ".$_POST["sal"]." WHERE [cis_vystup] = %i",$_GET["kde"]);
						}elseif(!isset($_POST["sal"])) {
							dibi::query("DELETE FROM [st_kronika] WHERE [cis_vystup] %i", intval($_GET["kde"]));
							if($_POST["novinka"][0] <> "nix"){
								foreach ($_POST["novinka"] as $novinka) {
									$zapsat["cis_novinky"] = $novinka;
									$zapsat["cis_vystup"] = $_GET["kde"];
									dibi::query("INSERT INTO [st_kronika]",$zapsat, "ON DUPLICATE KEY UPDATE %a",$zapsat);	
								}							
							}
						}elseif(isset($_POST["sal"]) && !empty($_POST["novinka"])){
							dibi::query("DELETE FROM [st_kronika] WHERE [cis_vystup] = %i", intval($_GET["kde"]));
							if($_POST["novinka"][0] <> "nix"){
								foreach ($_POST["novinka"] as $novinka) {
									$zapsat["cis_novinky"] = $novinka;
									$zapsat["cis_vystup"] = $_GET["kde"];
									dibi::query("INSERT INTO [st_kronika]",$zapsat, "ON DUPLICATE KEY UPDATE %a",$zapsat);	
								}							
							}
							dibi::query("UPDATE [st_vystup_saly] SET [cis_salu] = ".$_POST["sal"]." WHERE [cis_vystup] = %i",$_GET["kde"]);

						}
						header($location."&upr=1");
						exit;
					}
				}
			}elseif(isset($_POST["nieuw"])){
				 //vložení nového záznamu  */
				$location .= "?jak=vypis&upravit=" . $_GET["upravit"];
				$dotaz[] = "INSERT INTO [" . $_GET["upravit"] . "]";
				array_push($dotaz,$zmeny, "ON DUPLICATE KEY UPDATE %a", $zmeny);
				if($_GET["upravit"] == "st_vystup"){
					if(isset($_POST["sal"])){
						dibi::query($dotaz);
						$noVystup = dibi::insertId();
						dibi::query("INSERT INTO [st_vystup_saly] (cis_salu, cis_vystup) VALUES (".$_POST["sal"].",".$noVystup.")");
						dibi::query("INSERT INTO [st_vystup_inseminace] (cis_inseminace, cis_vystup) VALUES (".$_POST["hra"].",".$noVystup.")");
						header($location."&upr=1");
						exit;
					}
				}
			}		
			//"smazání", ale ve skutečnosti jen skrytí daného záznamu
		}elseif(isset($_GET["pryc"])){
			if($_GET["upravit"] != "st_fotky"){
				$location .= "?jak=vypis&upravit=" . $_GET["upravit"];
			}else{
				$location .= "?jak=upravit&upravit=fotky&kuprave=" . $_GET["jaka"] . "&sekce=".$_GET["odkud"]."&fotky=";
			}
			$dotaz =  "UPDATE " . $_GET["upravit"] . " SET " . $_GET["jak"] . " = NULL WHERE cis = '".$_GET["kuprave"]."'";
		}
//dibi::dump();		
	if(!dibi::query($dotaz)){
		$location .= "&upr=0";
	}else{
		$location .= "&upr=1";
	}
	
// pre($dotaz);
// dibi::dump();	
// pre($location);
}
header($location);
?>
