<?php	
if(isset($jak)){
	if($jak == "vypis"){
		$str = dibi::query("SELECT cis, nazev
				FROM [st_str]
				WHERE [zobr] = '1'
				AND [rodic] IS NULL
				ORDER BY poradi");
		echo "<table class=\"table table-striped\">\n<caption>Úpravy stránek</caption>\n<tr><th>název stránky</th></tr>\n";
		while($stranky = $str->fetch()){
			echo "<tr>
			<td><a href=\"?jak=upravit&upravit=" . $co . "&kuprave=".$stranky["cis"]."\" title=\"upravit údaje\">".$stranky["nazev"]."</a></td>
			</tr>\n";
			$podstr = dibi::query("SELECT cis, nazev
				FROM [" . $co . "]
				WHERE [zobr] = '1'
				AND [rodic] = %i",$stranky["cis"],
				"ORDER BY poradi");
			while($podstranky = $podstr->fetch()){
				echo "<tr class=\"dite\">
				<td><a href=\"?jak=upravit&upravit=" . $co . "&kuprave=".$podstranky["cis"]."\" title=\"upravit údaje\">".$podstranky["nazev"]."</a></td>
				</tr>\n";
				if($podstranky["cis"] == 10){ //bio, které jako jediné má podstránky, tj. 3. úroveň
					$podpodstr = dibi::query("SELECT cis, nazev
					FROM [" . $co . "]
					WHERE [zobr] = '1'
					AND [rodic] = %i",$podstranky["cis"],
					"ORDER BY poradi");
					while($podpodstranky = $podpodstr->fetch()){
						echo "<tr class=\"dite\">
						<td><a href=\"?jak=upravit&upravit=" . $co . "&kuprave=".$podpodstranky["cis"]."\" title=\"upravit údaje\">".$podpodstranky["nazev"]."</a></td>
						</tr>\n";
					}
				}
			}
		}
		echo "</table>";
	}elseif($jak == "upravit"){
		$popisky = array(
			"title" => "Titulek stránky <span class=\"pozn\">(titulek v záhlaví okna prohlížeče, důležité pro vyhledávače)</span>",
			"nadpis" => "Nadpis stránky <span class=\"pozn\">(hlavní nadpis stránky)</span>",
			"nazev" => "Název stránky<span class=\"pozn\">(odkaz v navigaci, může být shodný s nadpisem či titulkem, ale je to další prostor pro klíčová slova)</span>",
			"obsah" => "Obsah stránky",
		);
		$stranka = dibi::fetch("SELECT *
			FROM [st_str]
			WHERE [cis] = %i",$_GET["kuprave"]);
		echo "<form action=\"akce/zapsat.php?upravit=" . $co . "&podle=cis&kde=".$stranka["cis"]."\" method=\"post\"><fieldset><legend>Úpravy stránky <em>".$stranka["nazev"]."</em></legend>\n";
		if(isset($_GET["nova"])){
			echo "<input type=\"hidden\" name=\"nieuw\">\n";
		}
		echo "<br><input type=\"submit\" value=\"změnit\" class='horni'><br>\n";
//		echo "<a href=\"?jak=upravit&upravit=adresare&kuprave=".$stranka["cis"]."&sekce=st_str\" title=\"Vytvořit adresář\">Vytvořit adresáře pro fotky</a><br>\n";
		echo "<a href=\"akce/foto-str.php\" title=\"Vytvořit adresář\">Vytvořit adresáře pro fotky</a><br>\n";
		echo "<a href=\"akce/nacist-fotky.php?jak=upravit&upravit=fotky&kuprave=".$stranka["cis"]."&sekce=" . $_GET["upravit"]. "\" title=\"Načíst fotky\">Načíst fotky</a><br>\n";
		
		echo "<a href=\"?jak=upravit&upravit=fotky&kuprave=".$stranka["cis"]."&sekce=" . $_GET["upravit"];
		if(!is_null($stranka["fotky"])){
			echo "&fotky=";
		}
		echo "\" title=\"Upravit fotky\">Upravit fotky</a>";
		echo putInput($stranka, $popisky, "input", "title", 100);
		echo putInput($stranka, $popisky, "input", "nadpis");
		echo putInput($stranka, $popisky, "input", "nazev");
		echo putInput($stranka, $popisky, "text", "obsah");
		echo "<label for=\"na-fotky\">Fotky<input type=\"checkbox\" id=\"na-fotky\" name=\"fotky\" value=\"1\"";
		if(!is_null($stranka["fotky"])){
			echo " checked";
		}
		echo ">";
		echo "<input type=\"hidden\" name=\"zobr\" value=\"1\">\n";
		echo "<br><input type=\"submit\" value=\"změnit\">\n</fieldset>\n</form>\n";

	}
}
?>