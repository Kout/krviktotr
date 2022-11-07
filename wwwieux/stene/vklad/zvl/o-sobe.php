<?php
echo "prk";
global $ferman;
$clenstvi = array(
	"soubor" => "Soubor",
	"divadilna" => "Hráli s námi",
	"hoste" => "Jako host",
	"diky" => "Děkujeme za pomoc",
	);
foreach($clenstvi as $jak => $clen){
	$dotaz = "SELECT cis,jmeno,jmenavigace,url,bio,oddo,fotky FROM [lidi] WHERE [clen] = '".$jak."' AND [zobr] IS NOT NULL ORDER BY [poradi]";
	$vypis = dibi::query($dotaz);

	echo "<a name=\"" . $jak . "\"></a>";
	echo esli_vypis($clenstvi[$jak],"h1");
	while ($vypsat =  $vypis->fetch()){
		if(is_null($vypsat["jmenavigace"])){
			$nazev = $vypsat["jmeno"];
		}else{
			$nazev = $vypsat["jmenavigace"];						
		}
		echo "\n<div class=\"clanek\">\n<div class=\"nadClankem\"><a name=\"" . mile_url($nazev) ."\"></a>\n";
		echo esli_vypis($vypsat["jmeno"],"h2");
		echo "</div>";
		echo "<div class=\"text\">\n";
		echo esli_vypis($vypsat["oddo"],"p");
		echo esli_vypis($vypsat["bio"],"");
		echo "</div>";
		if(!is_null($vypsat["fotky"])){
		$fotky = dibi::query("SELECT st_fotky.cis, st_fotky.soubor, st_fotky.popisek, st_fotky.autor, st_fotky.kdy, st_fotky.nekdy, st_fotky.zobr
				FROM [st_foto_lidi]
				LEFT JOIN [st_fotky] ON st_fotky.cis = st_foto_lidi.cis_fotky
				WHERE st_foto_lidi.cis_lidi= %i", $vypsat["cislo"],
					" ORDER BY st_fotky.soubor");
			echo "<div class=\"pfota\">\n";
			// cestaKfotkam($sekce,$nazev,$nahradni= NULL,$kdy,$priznak)
			$cestaKobr = cestaKfotkam("o-sobe",$nazevFotky,NULL,$vypsat["url"],$vypsat["priznak"])."/pidi/";
	 // echo $cestaKobr;			
			while($fotka = $fotky->fetch()){
				$foto = getimagesize($cestaKobr.$fotka["soubor"].".jpg");
				echo "<a href=\"";
				echo CESTA."/".str_replace("/pidi/", "/obr/", $cestaKobr) . $fotka["soubor"]. ".jpg";
				echo "\" rel=\"nov-" . $vypsat["cislo"] . "\" title=\"";
				echo $fotka["popisek"] ?  " | " . $fotka["popisek"]:"";
				echo $fotka["autor"] ?  " | " . $fotka["autor"]:"";
				if(is_null($fotka["nekdy"])){
					echo $fotka["kdy"] ? datum(strtotime($fotka["kdy"])):"";
				}else{
					echo $fotka["nekdy"];
				}
				echo "\"";
				echo (is_null($fotka["zobr"]) ? " class=\"skryt\"" : "");
				echo "><img src=\"".CESTA."/".$cestaKobr.$fotka["soubor"].".jpg\" ".$foto[3]." alt=\"" . $fotka["popisek"]  . "\"></a>\n";
			}
			echo "</div>\n";
		}
		echo "<hr>\n</div>\n";
	}
}
echo "<script type=\"text/javascript\">
$('.pfota a').colorbox({current: '{current}. obrázek z {total}', top: '5%'});\n
</script>";
?>
