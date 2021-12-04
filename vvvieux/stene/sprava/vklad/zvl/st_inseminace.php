<?php	
if(isset($jak)){
	if($jak == "vypis"){
		$novinky = dibi::query("SELECT cis, nazev AS nazev
			FROM [st_inseminace]
			WHERE [zobr] IS NOT NULL");
		echo "<table class=\"table table-striped\">\n<caption>Úpravy inscenací</caption>\n<tr><th>název</th><th>odstranit</th></tr>\n";
		echo "<tr><td colspan=\"3\"><a href=\"?jak=upravit&upravit=" . $co . "&kuprave=&nova=\" title=\"přidat zprávu\" class=\"nova\">nová hra</a></td></tr>\n";	
		while($hra = $novinky->fetch()){
			echo "<tr>
			<td><a href=\"?jak=upravit&upravit=" . $co . "&kuprave=".$hra["cis"]."\" title=\"upravit údaje\">".$hra["nazev"]."</a></td>
			<td class=\"smazat\"><a href=\"akce/zapsat.php?upravit=" . $co . "&kuprave=" . $hra["cis"] . "&jak=zobr&pryc=\" title=\"odstranit\">smazat</a></td>
			</tr>\n";
		}
		echo "</table>";
	}elseif($jak == "upravit"){
		$popisky = array(
			"nazev" => "Název inscenace",
			"podtitul" => "Podtitul",
			"co" => "Typ",
			"autori" => "Autoři",
			"rok" => "Rok dopsání",
			"premca" => "Premiéra <small>(cokoli slovně, přebije případné propjení)</small>",
			"prem" => "Premiéra <small>(propojit s výstupem)</small>",
			"naposledy" => "Naposledy <small>(cokoli slovně, přebije případné propjení)</small>",
			"napos" => "Naposledy <small>(propojit s výstupem)</small>",
			"derniera" => "Derniéra <small>(cokoli slovně, přebije případné propjení)</small>",
			"dern" => "Derniéra (propojit s výstupem)",
			"anotace" => "Anotace",
			"popis" => "Cokoli o",
			"text" => "Text",
		);
		$hra = dibi::fetch("SELECT *
			FROM [st_inseminace]
			WHERE [cis] = %i",$_GET["kuprave"]);
		echo "<form action=\"akce/zapsat.php?upravit=" . $co . "&podle=cis&kde=".$hra["cis"]."\" method=\"post\"><fieldset><legend>" . $hra["nazev"] . "</legend>\n";
		if(isset($_GET["nova"])){
			echo "<input type=\"hidden\" name=\"nieuw\">\n";
		}
		echo "<br><input type=\"submit\" value=\"změnit\" class='horni'><br>\n";
		echo "<a href=\"akce/foto-tvorba.php\" title=\"Vytvořit adresář\">Vytvořit adresáře pro fotky</a><br>\n";
		echo "<a href=\"akce/nacist-fotky.php?jak=upravit&upravit=fotky&kuprave=".$hra["cis"]."&sekce=" . $_GET["upravit"] . "\" title=\"Načíst fotky\">Načíst fotky</a><br>\n";
		echo "<a href=\"?jak=upravit&upravit=fotky&kuprave=".$hra["cis"]."&sekce=" . $_GET["upravit"];
		if(!is_null($hra["plakat"])){
			echo "&fotky=";
		}
		echo "\" title=\"Upravit fotky\">Upravit fotky</a>";
//putInput($zDtB, $nazvy, $co, $klic, $size = 60, $typ = false, $cols=100, $rows=35)	
		echo putInput($hra, $popisky, "input", "nazev");
		echo putInput($hra, $popisky, "input", "podtitul");
		echo putInput($hra, $popisky, "input", "co");
		echo putInput($hra, $popisky, "input", "autori");
		echo putInput($hra, $popisky, "input", "rok");
		echo putInput($hra, $popisky, "input", "premca");
		vyznamne("prem");
		echo putInput($hra, $popisky, "input", "naposledy");
		vyznamne("napos");
		echo putInput($hra, $popisky, "input", "derniera");
		vyznamne("dern");
		echo putInput($hra, $popisky, "text", "anotace",NULL,NULL,100,10);
		echo putInput($hra, $popisky, "text", "popis");
		echo putInput($hra, $popisky, "text", "text");
/*echo "<label for=\"na-plakat\">Plakát<input type=\"checkbox\" id=\"na-plakat\" name=\"plakat\" value=\"1\"";
if(!is_null($hra["plakat"])){
	echo " checked";
}
echo ">";*/
		echo "<input type=\"hidden\" name=\"zobr\" value=\"1\">\n";
		echo "<br><input type=\"submit\" value=\"změnit\">\n</fieldset>\n</form>\n";
	}
}
?><script type="text/javascript">
$('.smazat a').click(function(){
	smazat = confirm('Opravdu smazat?');
	if(!smazat){
		return false;
	};
});
</script>