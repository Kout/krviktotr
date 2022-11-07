<?php 
include '../../vklad/hlava.php';
if($_GET["podstr"]){
	switch ($_GET["podstr"]){
		case "lide":
			include 'vklad/'.$_GET["podstr"].'.php';
			echo "</div>\n";
			bok();
			echo "</div><div id=\"vycet\">\n<ul>\n";
			$dotaz = "SELECT url, jmeno,prijmi
			  FROM lidi
			  ORDER BY prijmi";	
			dotaz($dotaz);
			while($lidi = mysql_fetch_assoc($vysl)){
				echo "<li><a href=\"";
				uroven_vys();
				echo "o-krvik-totr/lide/";
				if(!isset($_GET["pod_podstr"])){
					$pod_podstr = "soubor";
				}else{
					$pod_podstr = $_GET["pod_podstr"];
				}
				echo $pod_podstr."/".$lidi["url"];
				echo ".kt\">";
				echo $lidi["jmeno"]." ".$lidi["prijmi"];
				echo "</a></li>\n";
			}
			echo "\n</ul>\n</div>";
			break;
		case "dejepis":
			include 'vklad/dejepis.php';
			break;
		case "slovo-o-tvorbe":
			include 'vklad/slovo-o-tvorbe.php';
			break;
		case "z-tisku":
			include 'vklad/z-tisku.php';
			break;
		default:
			include 'vklad/dejepis.php';
	}
}else{
	echo "není podstr";
}

include '../../vklad/pata.php';
?>