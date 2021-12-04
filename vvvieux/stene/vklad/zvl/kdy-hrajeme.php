<?php
global $ferman;
$ferman = array(
	"pred" => array(
		"dotaz" => ">= NOW()",
		"nadpis" => "Přijďte",
		"rovnat" => "ASC",		
	),
	"za" => array(
		"dotaz" => "<= NOW()",
		"nadpis" => "Odehráno",
		"rovnat" => "DESC",
	),
);
foreach($ferman as $klic => $predstaveni){
	$Vystoupeni = dibi::query("SELECT st_vystup.cis AS cislo, st_vystup.nazev AS vystup, st_vystup.kdy, st_vystup.nekdy, st_vystup.prilezitost, st_vystup.spoluucinkujici, st_vystup.vstup, st_vystup.o, st_vystup.kronika, st_vystup.fotky, st_vystup.reservace, st_vystup.priznak, st_saly.nazev AS sal, st_saly.nadnazev, st_saly.mesto, st_saly.ctvrt, st_saly.adresa, st_saly.kudytam, st_saly.pozn,st_saly.www, st_inseminace.cis, st_inseminace.nazev AS kus, st_inseminace.podtitul, st_inseminace.co, st_inseminace.anotace
		FROM [st_vystup]
		LEFT JOIN [st_vystup_saly] ON st_vystup_saly.cis_vystup = st_vystup.cis
		LEFT JOIN [st_saly] ON st_vystup_saly.cis_salu = st_saly.cis
		LEFT JOIN [st_vystup_inseminace] ON st_vystup_inseminace.cis_vystup = st_vystup.cis
		LEFT JOIN [st_inseminace] ON st_vystup_inseminace.cis_inseminace = st_inseminace.cis
		WHERE st_vystup.kdy " . $predstaveni["dotaz"] . "
		AND [st_vystup.zobr] IS NOT NULL
		ORDER BY st_vystup.kdy " . $predstaveni["rovnat"]);

	// echo "<a name=\"" . $klic . "\"></a>";
	echo "<section id=\"".$klic."\" class=\"kotva\">\n";
	echo esli_vypis($predstaveni["nadpis"],"h1");
	while ($vystoupeni =  $Vystoupeni->fetch()){
		if(!isset($vystoupeni["vystup"])){
			$nazev = $vystoupeni["kus"];
		}else{
			$nazev = $vystoupeni["vystup"];
		}
		$nazevFotky = $nazev; //pro oučely fotek a kotvy potřeba název bez níže doplněného podtitulu

		if($klic == "pred"){
			$nazev .= " ".$vystoupeni["podtitul"];
		}
		$kdy = substr($vystoupeni["kdy"], 0,10);
		if(!is_null($vystoupeni["priznak"])){
			$priznak = "-".$vystoupeni["priznak"];
		}else{
			$priznak = "";
		}
		// echo "\n<div class=\"clanek\">\n<div class=\"nadClankem\"><a name=\"" . mile_url($nazevFotky) . "-" . $kdy.$priznak . "\"></a>\n";
		echo "\n<div class=\"clanek kotva\" id=\"" . mile_url($nazevFotky) . "-" . $kdy.$priznak . "\">\n<div class=\"nadClankem\">\n";
		if(is_null($vystoupeni["nekdy"])){
			echo esli_vypis(date("j. n. 'y", (strtotime(substr($vystoupeni["kdy"], 0, 10)))),"","<p class=\"datum\">","</p>\n");
		}else{
			echo esli_vypis($vystoupeni["nekdy"],"","<p class=\"datum\">","</p>\n");
		}
		echo esli_vypis($nazev,"h2");
		echo "</div>";
		echo "<div class=\"text\">\n<ul>\n";
		echo esli_vypis($vystoupeni["spoluucinkujici"],"","<li> + ","</li>");
		echo esli_vypis($vystoupeni["prilezitost"],"li");
		if($klic == "pred"){
			echo esli_vypis($vystoupeni["anotace"],"li");
		}
		// echo esli_vypis($vystoupeni["o"],"li");
		echo "</ul>\n";
		echo "<ul>\n<li>";
		$ww = $www = "";
		if($klic == "pred"){
			echo "<h4>Kde hrajeme:</h4>\n<address>\n";
			if(!is_null($vystoupeni["www"])){
				$ww .= "<a href=\"" . $vystoupeni["www"] . "\" title=\"přejít na stránky divadla\" target=\"_blank\">";
				$www .= "</a>";
			}
		}
		if($klic == "za"){
			echo "<address>\n<strong>";
		}
		echo $vystoupeni["mesto"];
		if($klic == "za"){
			echo "</strong>\n";
		}
		if($klic == "pred"){
			if(isset($vystoupeni["ctvrt"])){
				echo $vystoupeni["ctvrt"];
			}			
		}
		echo esli_vypis($ww.$vystoupeni["sal"].$www,"","<br>","<br>\n");
		if($klic == "pred" && !is_null($vystoupeni["reservace"])){
			echo esli_vypis($vystoupeni["adresa"],"","","<br>\n");
		}
		echo "</address></li>\n";
		if($klic == "pred"){
/* 			if(!is_null($vystoupeni["kudytam"])){
 * 				echo "<li><strong>Kudytam: </strong>".$vystoupeni["kudytam"]."</li>\n";
 * 			} */
			echo esli_vypis(date("H,i", strtotime(substr($vystoupeni["kdy"], 11, 8))),"","<li><strong>Začátek: </strong>","</li>");
			echo esli_vypis($vystoupeni["vstup"],"","<li><strong>Vstupné: </strong>","</li>");
			if(!is_null($vystoupeni["reservace"])){
				echo "<li><a href=\"#\" style=\"font-weight:bold;\" class=\"reserve\">Rezervujte si vstupenky</a>";
				echo "<div class=\"reserve\">\n<form action=\"\" method=\"post\">
				<fieldset>
				<legend>Rezervace na " . $nazev . " " . date("j. n. 'y", (strtotime(substr($vystoupeni["kdy"], 0, 10)))). "<br><span class=\"pozn\">(všechny položky jsou povinné)</span></legend>\n\n
				<input name=\"web\" class=\"web\">
				<label for=\"na-meno".$vystoupeni["cislo"]."\">Na jméno</label><br>\n<input id=\"na-meno".$vystoupeni["cislo"]."\" name=\"meno\" class=\"meno\" placeholder=\"ctěné jméno\"><br>\n
				<label for=\"na-kdo".$vystoupeni["cislo"]."\">Váš e-mail <span class=\"pozn\">(na který přijde potvrzení)</span></label><br>\n<input id=\"na-kdo".$vystoupeni["cislo"]."\" name=\"kdo\" class=\"kdo\" size=\"25\" maxlength=\"64\" placeholder=\"ctěná e-mailová adresa\"><br>\n
				<label for=\"na-kolik".$vystoupeni["cislo"]."\">Počet lístků</label><br>\n<input type=\"number\" min=\"1\" max=\"999\" id=\"na-kolik".$vystoupeni["cislo"]."\" name=\"kolik\" class=\"kolik\" value=\"1\"><br>\n
				<input type=\"hidden\" name=\"vystup\" value=\"" . $vystoupeni["cislo"] . "\">
				<label for=\"na-vzkaz".$vystoupeni["cislo"]."\">Vzkaz pro Krvik Totr <span class=\"pozn\">(nepovinné)</span></label><br>\n<textarea id=\"na-vzkaz".$vystoupeni["cislo"]."\" name=\"vzkaz\" class=\"vzkaz\" rows=\"10\" cols=\"50\" placeholder=\"Máte-li cos na srdci či na jazyku, sem s tím!\"></textarea>\n
				<input type=\"submit\" value=\"Rezervovat\">\n
				<button>Anebo pryč</button>
				</fieldset>
				</form>\n</div>\n</li>\n";
			}
		}
		if($klic == "za"){
			// echo esli_vypis($vystoupeni["o"],"li","<h4>O představení</h4>\n","");

			$kronika = dibi::query("SELECT novinky.rubrika, novinky.nazev, novinky.datum, novinky.kdy
				FROM st_kronika
				LEFT JOIN [novinky] ON [novinky.cis] = [st_kronika.cis_novinky]
				WHERE [st_kronika.cis_vystup] = %i", $vystoupeni["cislo"], 
				" ORDER BY [novinky.datum] DESC");
			if(count($kronika) > 0){
				echo "<li><h4>Kronika:</h4>\n<ul>\n";
				while ($souvisejici = $kronika->fetch()){
					echo "<li><span class=\"datum\">";
					if(is_null($souvisejici["kdy"])){
						echo date("j. n. 'y", strtotime($souvisejici["datum"]));
					}else{
						echo $souvisejici["kdy"];
					}
					// echo "<span class=\"rubrika\">".."</span>\n";				
					echo "</span> <a href=\"novinky#".mile_url($souvisejici['nazev'])."\">".$souvisejici['nazev']."</a> (".$souvisejici['rubrika'].")\n</li>\n";
				}
				echo "</ul>\n";
				echo esli_vypis($vystoupeni["kronika"],"");
				echo "</li>\n";
			}else{
				echo esli_vypis($vystoupeni["kronika"],"","<li><h4>Kronika:</h4>\n", "</li>\n");				
			}
			// dibi::dump();

		}
		if(!is_null($vystoupeni["kus"])){
			echo "<li><a href=\"tvorba#" . mile_url($vystoupeni["kus"]). "\" title=\"o hře\">Více o hře</a></li>\n";
		}
		
		echo "</ul>\n</div>\n";
		if(!is_null($vystoupeni["fotky"])){
		$fotky = dibi::query("SELECT st_fotky.cis, st_fotky.soubor, st_fotky.popisek, st_fotky.autor, st_fotky.kdy, st_fotky.nekdy, st_fotky.zobr
				FROM [st_foto_vystup]
				LEFT JOIN [st_fotky] ON st_fotky.cis = st_foto_vystup.cis_fotky
				WHERE st_foto_vystup.cis_vystup= %i", $vystoupeni["cislo"],
					" ORDER BY st_fotky.soubor");
			echo "<div class=\"pfota\">\n";
			// cestaKfotkam($sekce,$nazev,$nahradni= NULL,$kdy,$priznak)
			$cestaKobr = cestaKfotkam("kdy-hrajeme",$nazevFotky,NULL,$vystoupeni["kdy"],$vystoupeni["priznak"])."/pidi/";
	 // echo $cestaKobr;			
			while($fotka = $fotky->fetch()){
				$foto = getimagesize($cestaKobr.$fotka["soubor"].".jpg");
				$cesta = CESTA."/".str_replace("/pidi/", "/obr/", $cestaKobr) . $fotka["soubor"]. ".jpg";
				$popisek = $fotka["popisek"] ?  " | " . $fotka["popisek"]:"";
				$popisek .= $fotka["autor"] ?  " | " . $fotka["autor"]:"";
				if(is_null($fotka["nekdy"])){
					$popisek .= $fotka["kdy"] ? datum(strtotime($fotka["kdy"])):"";
				}else{
					$popisek .= $fotka["nekdy"];
				}
				echo "<a href=\"".$cesta."\" title=\"".$popisek."\" class=\"sada-" . $vystoupeni["cislo"] . "";
				echo (is_null($fotka["zobr"])) ? " skryt" : "";
				echo "\"><img src=\"".CESTA."/".$cestaKobr.$fotka["soubor"].".jpg\" ".$foto[3]." alt=\"" . strip_tags($fotka["popisek"])  . "\"></a>\n";			
			}
			echo "</div>\n";
			echo "<script type=\"text/javascript\">
			$('a.sada-".$vystoupeni["cislo"]."').colorbox({current: '{current}. obrázek z {total}', top: '5%', rel: '".$vystoupeni["cislo"]."'});\n
			</script>";
		}
		echo "<hr>\n</div>\n";
	}
	echo "</section>\n";
}
echo "<script type=\"text/javascript\" src=\"./skrypti/RESquery.js\"></script>\n";
if(isset($_GET["zapis"])){
	echo "<script type=\"text/javascript\">";
	if($_GET["zapis"] == 1){
		echo "alert('Rezervace odeslána. Pokud vám nepřijde potvrzení, napište prosím znovu na info@krviktotr.cz');";
	}elseif($_GET["zapis"] == 0){
		echo "alert('Rezervace se nepovedla. Zkuste to prosím znovu, nebo si o lístky napište na info@krviktotr.cz');";
	}
	echo "</script>";
}
?>
