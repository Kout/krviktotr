<?php	
$GLOBALS["clenstvi"] = array(
    "soubor" => "Soubor",
    "divadilna" => "Hráli s námi",
    "hoste" => "Jako host",
    "diky" => "Děkujeme za pomoc",
    );
if(isset($jak)){
	if($jak == "vypis"){
		$lidi = dibi::query("SELECT cis, jmeno AS nazev, clen
			FROM [lidi]
			WHERE [zobr] IS NOT NULL
			ORDER BY [poradi]");
		echo "<table class=\"table table-striped\">\n<caption>Úpravy lidí</caption>\n<tr><th>jméno</th><th>soubor</th><th>odstranit</th></tr>\n";
		echo "<tr><td colspan=\"3\"><a href=\"?jak=upravit&upravit=" . $co . "&kuprave=&nova=\" title=\"přidat lida\" class=\"nova btn btn-success\"><i class=\"icon-pencil icon-white\"></i> nový člověk</a></td></tr>\n";	
		while($clovek = $lidi->fetch()){
			echo "<tr>
			<td><a href=\"?jak=upravit&upravit=" . $co . "&kuprave=".$clovek["cis"]."\" title=\"upravit údaje\">".$clovek["nazev"]."</a></td>
			<td><a href=\"?jak=upravit&upravit=" . $co . "&kuprave=".$clovek["cis"]."\" title=\"upravit údaje\">".$GLOBALS["clenstvi"][$clovek["clen"]]."</a></td>
			<td class=\"smazat\"><a href=\"akce/zapsat.php?upravit=" . $co . "&kuprave=" . $clovek["cis"] . "&jak=zobr&pryc=\" title=\"odstranit\">smazat</a></td>
			</tr>\n";
		}
		echo "</table>";
	}elseif($jak == "upravit"){
		$popisky = array(
			"jmeno" => "Jméno",
			"jmenavigace" => "Jméno do boční navigace (prázdné = použije se \"jméno\")",
			"poradi" => "Pořadí (RRRRMMDD...)",
			"url" => "URL",
			"oddo" => "Od&ndash;do (zřejmě zrušíme)",
			"strejcek" => "Kolonka pro strejčka příhodu (?)",
			"bio" => "Bio (veřejné)",
			"pozn" => "Poznámky (neveřejné)",			
		);
		$clovek = dibi::fetch("SELECT *
			FROM [lidi]
			WHERE [cis] = %i",$_GET["kuprave"]);
		echo "<form action=\"akce/zapsat.php?upravit=" . $co . "&podle=cis&kde=".$clovek["cis"]."\" method=\"post\"><fieldset><legend>";
		if(!empty($clovek)){
			echo " <em>".$clovek["jmeno"]." (".$GLOBALS["clenstvi"][$clovek["clen"]];
		}
		echo ")</em></legend>\n";
		if(isset($_GET["nova"])){
			echo "<input type=\"hidden\" name=\"nieuw\">\n";
		}
		echo "<br><input type=\"submit\" value=\"změnit\" class='horni btn btn-success'><ul class=\"nav navbar nav-tabs\">\n";
//		echo "<a href=\"?jak=upravit&upravit=adresare&kuprave=".$clovek["cis"]."&sekce=lidi\" title=\"Vytvořit adresář\">Vytvořit adresáře pro fotky</a><br>\n";
		echo "<li><a href=\"akce/foto-lidi.php\" title=\"Vytvořit adresář\">Vytvořit adresáře pro fotky</a></li>\n";
		echo "<li><a href=\"akce/nacist-fotky.php?jak=upravit&upravit=fotky&kuprave=".$clovek["cis"]."&sekce=" . $_GET["upravit"] . "\" title=\"Načíst fotky\">Načíst fotky</a></li>\n";
		echo "<li><a href=\"?jak=upravit&upravit=fotky&kuprave=".$clovek["cis"]."&sekce=" . $_GET["upravit"];
		if(!is_null($clovek["fotky"])){
			echo "&fotky=";
		}
		echo "\" title=\"Upravit fotky\">Upravit fotky</a></li>\n</ul>\n";
//putInput($zDtB, $nazvy, $co, $klic, $size = 60, $typ = false, $cols=100, $rows=35)	
		echo putInput($clovek, $popisky, "input", "jmeno");
		echo putInput($clovek, $popisky, "input", "jmenavigace");
		echo putInput($clovek, $popisky, "input", "poradi");
		echo putInput($clovek, $popisky, "input", "url");
		echo putInput($clovek, $popisky, "input", "oddo");
		echo putInput($clovek, $popisky, "text", "bio");
		echo putInput($clovek, $popisky, "text", "pozn");
		echo putInput($clovek, $popisky, "text", "strejcek");
		echo "<label for=\"na-fotky\">Fotky<input type=\"checkbox\" id=\"na-fotky\" name=\"fotky\" value=\"1\"";
		if(!is_null($clovek["fotky"])){
			echo " checked";
		}
		echo "></label>\n";		
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