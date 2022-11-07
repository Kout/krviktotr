<?php	
if(isset($jak)){
	if($jak == "vypis"){
		$novinky = dibi::query("SELECT cis, nazev AS nazev, datum AS kdy
			FROM [novinky]
			ORDER BY [kdy] DESC");
		echo "<table>\n<caption>Úpravy zpráv</caption>\n<tr><th>název</th><th>datum</th><th>odstranit</th></tr>\n";
		echo "<tr><td colspan=\"3\"><a href=\"?jak=upravit&upravit=" . $co . "&kuprave=&nova=\" title=\"přidat zprávu\" class=\"nova\">nová zpráva</a></td></tr>\n";	
		while($novina = $novinky->fetch()){
			echo "<tr><td><a href=\"?jak=upravit&upravit=" . $co . "&kuprave=".$novina["cis"]."\" title=\"upravit údaje\">".$novina["nazev"]."</a></td><td><a href=\"?jak=upravit&upravit=" . $co . "&kuprave=".$novina["cis"]."\" title=\"upravit údaje\">".datum(strtotime($novina["kdy"]))."</a></td><td class=\"smazat\"><a href=\"akce/zapsat.php?upravit=" . $co . "&kuprave=" . $novina["cis"] . "&jak=zobr&pryc=\" title=\"odstranit\">smazat</a></td></tr>\n";
		}
		echo "</table>";
	}elseif($jak == "upravit"){
		$popisky = array(
			"nazev" => "Název novinky",
			"obsah" => "Obsah novinky",
			"datum" => "Datum",
			"kdy" => "Neurčité datum",
			"rubrika" => "Rubrika",
		);
		$novina = dibi::fetch("SELECT *
			FROM [novinky]
			WHERE [cis] = %i",$_GET["kuprave"]);
		echo "<form action=\"akce/zapsat.php?upravit=" . $co . "&podle=cis&kde=".$novina["cis"]."\" method=\"post\"><fieldset><legend>Zpráva";
		if(!empty($novina)){
			echo " <em>".datum(strtotime($novina["datum"]));
		}
		echo "</em></legend>\n";
		if(isset($_GET["nova"])){
			echo "<input type=\"hidden\" name=\"nieuw\">\n";
		}
		echo "<br><input type=\"submit\" value=\"změnit\" class='horni'><br>\n";
		echo "<a href=\"?jak=upravit&upravit=fotky&kuprave=".$novina["cis"]."&sekce=" . $_GET["upravit"];
		if(!is_null($novina["fotky"])){
			echo "&fotky=";
		}
		echo "\" title=\"Upravit fotky\">Upravit fotky</a>";
//putInput($zDtB, $nazvy, $co, $klic, $size = 60, $typ = false, $cols=100, $rows=35)	
		echo putInput($novina, $popisky, "input", "datum", 16, "date");
		echo putInput($novina, $popisky, "input", "kdy");
		echo putInput($novina, $popisky, "input", "nazev");
		echo putInput($novina, $popisky, "input", "rubrika");
		echo putInput($novina, $popisky, "text", "obsah");
		echo "<label for=\"na-fotky\">Fotky<input type=\"checkbox\" id=\"na-fotky\" name=\"fotky\" value=\"1\"";
		if(!is_null($novina["fotky"])){
			echo " checked";
		}
		echo ">";
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