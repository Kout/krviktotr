<?php 
$Vystoupeni = dibi::query("SELECT st_vystup.cis AS cislo, st_vystup.nazev AS vystup, st_vystup.priznak, st_vystup.kdy, st_vystup.nekdy, st_vystup.reservace, st_saly.nazev AS sal, st_saly.nadnazev, st_saly.mesto, st_saly.ctvrt, st_saly.adresa, st_saly.kudytam, st_saly.pozn,st_saly.www, st_inseminace.cis, st_inseminace.nazev AS kus, st_inseminace.podtitul, st_inseminace.co
		FROM [st_vystup]
		LEFT JOIN [st_vystup_saly] ON st_vystup_saly.cis_vystup = st_vystup.cis
		LEFT JOIN [st_saly] ON st_vystup_saly.cis_salu = st_saly.cis
		LEFT JOIN [st_vystup_inseminace] ON st_vystup_inseminace.cis_vystup = st_vystup.cis
		LEFT JOIN [st_inseminace] ON st_vystup_inseminace.cis_inseminace = st_inseminace.cis
		WHERE st_vystup.kdy >= NOW()
		AND [st_vystup.zobr] IS NOT NULL
		AND [st_vystup.reservace] IS NOT NULL
		ORDER BY st_vystup.kdy
		LIMIT 3"); //počet vypisovaných vystoupení
	if(count($Vystoupeni) > 0){
		echo "<ul>\n";
		while ($vystoupeni =  $Vystoupeni->fetch()){
			if(!isset($vystoupeni["vystup"])){
				$nazev = $vystoupeni["kus"];
			}else{
				$nazev = $vystoupeni["vystup"];
			}
			$kdy = substr($vystoupeni["kdy"], 0,10);
			if(!is_null($vystoupeni["priznak"])){
				$priznak = "-".$vystoupeni["priznak"];
			}else{
				$priznak = "";
			}		
			echo "<li>";
			if(is_null($vystoupeni["nekdy"])){
				echo esli_vypis(date("j. n. 'y", (strtotime(substr($vystoupeni["kdy"], 0, 10)))),"","","");
			}else{
				echo esli_vypis($vystoupeni["nekdy"],"","","");
			}
			echo " | <a href=\"kdy-hrajeme#".mile_url($nazev)."-" . $kdy . $priznak . "\">".$nazev. "</a> | ".$vystoupeni["mesto"]." (".$vystoupeni["sal"].")";
			// echo "<a href=\"\" class=\"reserve\"></a>";
			echo " | <a href=\"\" class=\"reserve\">rezervace</a>";
			echo "<div class=\"reserve\">\n<form action=\"\" method=\"post\"><br>\n<fieldset><legend>Rezervace na " . $nazev . " " . date("j. n. 'y", (strtotime(substr($vystoupeni["kdy"], 0, 10)))). "<br><span class=\"pozn\">(všechny položky jsou povinné)</span></legend>\n</fieldset>\n
			<input name=\"web\" id=\"web\">
			<label for=\"meno\">Na jméno</label><br>\n<input name=\"meno\" id=\"meno\" placeholder=\"ctěné jméno\"><br>\n
			<label for=\"kdo\">Váš e-mail <span class=\"pozn\">(na který přijde potvrzení)</span></label><br>\n<input name=\"kdo\" id=\"kdo\" size=\"25\" maxlength=\"64\" placeholder=\"ctěná e-mailová adresa\"><br>\n
			<label for=\"kolik\">Počet lístků</label><br>\n<input type=\"number\" min=\"1\" max=\"999\" name=\"kolik\" id=\"kolik\" value=\"1\"><br>\n
			<input type=\"hidden\" name=\"vystup\" value=\"" . $vystoupeni["cislo"] . "\">
			<label for=\"vzkaz\">Vzkaz pro Krvik Totr <span class=\"pozn\">(nepovinné)</span></label><br>\n<textarea name=\"vzkaz\" id=\"vzkaz\" rows=\"10\" cols=\"50\" placeholder=\"Máte-li cos na srdci či na jazyku, sem s tím!\"></textarea>\n
			<input type=\"submit\" value=\"Rezervovat\">\n
			<button>Anebo pryč</button>
			</form>\n</div>\n";
			echo "\n</li>\n";
		}
		echo "</ul>\n";
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