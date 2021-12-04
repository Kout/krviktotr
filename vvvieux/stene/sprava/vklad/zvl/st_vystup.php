<?php	
if(isset($jak)){
	if($jak == "vypis"){
		$vystoupeni = dibi::query("SELECT st_vystup.cis AS cislo, st_vystup.nazev AS vystup, st_vystup.kdy, st_vystup.nekdy, st_vystup.prilezitost, st_vystup.spoluucinkujici, st_vystup.vstup, st_vystup.o, st_vystup.fotky, st_vystup.reservace, st_vystup.priznak, st_saly.mesto, st_saly.nazev AS sal, st_saly.nadnazev, st_saly.mesto, st_inseminace.cis, st_inseminace.nazev AS kus, st_inseminace.podtitul, st_inseminace.co
		FROM [st_vystup]
		LEFT JOIN [st_vystup_saly] ON st_vystup_saly.cis_vystup = st_vystup.cis
		LEFT JOIN [st_saly] ON st_vystup_saly.cis_salu = st_saly.cis
		LEFT JOIN [st_vystup_inseminace] ON st_vystup_inseminace.cis_vystup = st_vystup.cis
		LEFT JOIN [st_inseminace] ON st_vystup_inseminace.cis_inseminace = st_inseminace.cis
		WHERE st_vystup.zobr IS NOT NULL
		ORDER BY st_vystup.kdy DESC ");
		echo "<table class=\"table table-striped\">\n<caption>Úpravy vystoupení</caption>\n<tr><th>název</th><th>datum</th><th>Kde</th><th>odstranit</th></tr>\n";
		echo "<tr>
		<td colspan=\"4\"><a href=\"?jak=upravit&upravit=" . $co . "&kuprave=&nova=\" title=\"přidat vystoupení\" class=\"nova btn btn-success\"><i class=\"icon-pencil icon-white\"></i> nové vystoupení</a></td>
		</tr>\n";	
		while($vystup = $vystoupeni->fetch()){
			if(!isset($vystup["vystup"])){
				$nazev = $vystup["kus"];
			}else{
				$nazev = $vystup["vystup"];
			}
			echo "<tr>
			<td><a href=\"?jak=upravit&upravit=" . $co . "&kuprave=".$vystup["cislo"]."\" title=\"upravit údaje\">".$nazev."</a></td>
<td><a href=\"?jak=upravit&upravit=" . $co . "&kuprave=".$vystup["cislo"]."\" title=\"upravit údaje\">".datum(strtotime($vystup["kdy"]))."</a></td>
			<td>".$vystup["sal"].", ".$vystup["mesto"]."</td>
<td class=\"smazat\"><a href=\"akce/zapsat.php?upravit=" . $co . "&kuprave=" . $vystup["cislo"] . "&jak=zobr&pryc=\" title=\"odstranit\">smazat</a></td></tr>\n";
		}
		echo "</table>";
	}elseif($jak == "upravit"){
		$popisky = array(
			"nazev" => "Název vystoupení (je-li prázdno, bere se název inscenace)",
			"kdy" => "Datum a čas (rrrr-mm-dd hh:mm:ss)",
			"nekdy" => "Neurčité datum",
			"prilezitost" => "Příležitost",
			"spoluucinkujici" => "Spoluúčinkující",
			"vstup" => "Vstupné",
			"o" => "Cokoli o",
			"kronika" => "Kronika externích odkazů",
			"priznak" => "Příznak (interní pro orientaci v adresářích)",
		);
		$vystup = dibi::fetch("SELECT st_vystup.*, st_saly.cis AS sal, st_inseminace.cis AS kus, st_inseminace.nazev AS inseminace
			-- st_kronika.cis_novinky AS novinka
		FROM [st_vystup]
		LEFT JOIN [st_vystup_saly] ON st_vystup_saly.cis_vystup = st_vystup.cis
		LEFT JOIN [st_saly] ON st_vystup_saly.cis_salu = st_saly.cis
		LEFT JOIN [st_vystup_inseminace] ON st_vystup_inseminace.cis_vystup = st_vystup.cis
		LEFT JOIN [st_inseminace] ON st_vystup_inseminace.cis_inseminace = st_inseminace.cis
		-- LEFT JOIN [st_kronika] ON st_kronika.cis_vystup = st_vystup.cis
		WHERE st_vystup.cis = %i
		ORDER BY st_vystup.kdy DESC",intval($_GET["kuprave"]));
		if(!isset($vystup["nazev"])){
			$nazev = $vystup["inseminace"];
		}else{
			$nazev = $vystup["nazev"];
		}
		echo "<form action=\"akce/zapsat.php?upravit=" . $co . "&podle=cis&kde=".$vystup["cis"]."\" method=\"post\"><fieldset><legend>" . $nazev . " <em>" . datum(strtotime($vystup["kdy"])) . "</em></legend>\n";
		if(isset($_GET["nova"])){
			echo "<input type=\"hidden\" name=\"nieuw\">\n";
			$dilo = dibi::query("SELECT cis, nazev
				FROM st_inseminace");
			$hry = array();
			while($hra = $dilo->fetch()){
				$hry[$hra["cis"]] = $hra["nazev"];
			}
			echo "<label for=\"na-hra\">Vybrat hru</label>\n";
			echo "<select name=\"hra\">\n";
	//optionlist($options, $selected = array(), $not_values = false)		
			echo optionlist($hry,$hra);
			echo "</select><br>\n";	
		}
		echo "<br><input type=\"submit\" value=\"změnit\" class='horni btn btn-success'><ul class=\"nav navbar nav-tabs\">\n";
//		echo "<a href=\"?jak=upravit&upravit=adresare&kuprave=".$vystup["cis"]."&sekce=st_vystup\" title=\"Vytvořit adresář\">Vytvořit adresáře pro fotky</a><br>\n";
		echo "<li><a href=\"akce/foto-vystupy.php\" title=\"Vytvořit adresář\">Vytvořit adresáře pro fotky</a></li>\n";
		echo "<li><a href=\"akce/nacist-fotky.php?jak=upravit&upravit=fotky&kuprave=".$vystup["cis"]."&sekce=" . $_GET["upravit"]. "\" title=\"Načíst fotky\">Načíst fotky</a></li>\n";
		echo "<li><a href=\"?jak=upravit&upravit=fotky&kuprave=".$vystup["cis"]."&sekce=" . $_GET["upravit"];
		if(!is_null($vystup["fotky"])){
			echo "&fotky=";
		}
		echo "\" title=\"Upravit fotky\">Upravit fotky</a></li>\n</ul>\n";
//putInput($zDtB, $nazvy, $co, $klic, $size = 60, $typ = false, $cols=100, $rows=35)	
		echo putInput($vystup, $popisky, "input", "nazev");
		echo putInput($vystup, $popisky, "input", "kdy");
		echo putInput($vystup, $popisky, "input", "nekdy");
		echo putInput($vystup, $popisky, "input", "priznak");
		echo putInput($vystup, $popisky, "input", "prilezitost");
		echo putInput($vystup, $popisky, "input", "spoluucinkujici");
		echo putInput($vystup, $popisky, "input", "vstup");
		echo putInput($vystup, $popisky, "text", "o");
// $skupiny = mysql_get_vals("SELECT id, nazev FROM skupiny ORDER BY nazev");
		$kronikaNovinek = dibi::query("SELECT cis, nazev, datum
			FROM novinky
			ORDER BY datum DESC");
		$novinky = array();
		while($novinka = $kronikaNovinek->fetch()){
			$novinky[$novinka["cis"]] = $novinka["nazev"].", ".date("j. n. 'y", strtotime($novinka["datum"]));
		}
		$vybrano = dibi::query("SELECT cis_novinky AS cis
			FROM st_kronika 
			WHERE cis_vystup = %i", intval($_GET["kuprave"]));		
		while($nov = $vybrano->fetch()) {
			$novinka[] = $nov["cis"];
		}		
		echo "<label for=\"na-novinka\">Vybrat novinky (CTRL + klik!!!)</label>\n";
		echo "<select name=\"novinka[]\" multiple=\"multiple\" class=\"input-xxlarge\" size=\"15\">\n";
		echo "<option value=\"nix\">žádná novinka</option>";
		echo optionlist($novinky,$novinka);
		echo "</select><br>\n";	
		
		echo putInput($vystup, $popisky, "text", "kronika");
		
		$adresarSalu = dibi::query("SELECT cis, nazev, mesto
			FROM st_saly
			ORDER BY nazev");
		$saly = array();
		while($sal = $adresarSalu->fetch()){
			$saly[$sal["cis"]] = $sal["nazev"].", ".$sal["mesto"];
		}
		echo "<label for=\"na-sal\">Vybrat sál</label>\n";
		echo "<select name=\"sal\">\n";
//optionlist($options, $selected = array(), $not_values = false)		
		echo optionlist($saly,$vystup["sal"]);
		echo "</select><br>\n";	
			
		echo "<label for=\"na-reserve\" class=\"checkbox inline\">Reservace<input type=\"checkbox\" id=\"na-reserve\" name=\"reserve\" value=\"1\"";
		if(!is_null($vystup["reservace"])){
			echo " checked";
		}
		echo "></label>\n";
		echo "<label for=\"na-fotky\" class=\"checkbox inline\">Fotky<input type=\"checkbox\" id=\"na-fotky\" name=\"fotky\" value=\"1\"";
		if(!is_null($vystup["fotky"])){
			echo " checked";
		}
		echo "></label>\n<br>";
		echo "<input type=\"hidden\" name=\"zobr\" value=\"1\">\n";
		echo "<br><input type=\"submit\" value=\"změnit\" class=\"btn btn-success\">\n</fieldset>\n</form>\n";
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