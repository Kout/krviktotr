<?php
//pre($_GET);
echo "<div class=\"tabulkyFotek\">\n";
if($_GET["sekce"] == "novinky"){
	$novinka = dibi::fetch("SELECT novinky.cis, novinky.rubrika, novinky.nazev, novinky.datum, novinky.kdy, novinky.obsah, novinky.fotky
		FROM [novinky]
		WHERE [cis] = %i",$_GET["kuprave"]);
	if(isset($_GET["fotky"])){
		echo "<h1>Úpravy fotek k novince: " . $novinka["nazev"] . "</h1>";
		$fotky = dibi::query("SELECT st_fotky.cis, st_fotky.soubor, st_fotky.popisek, st_fotky.autor, st_fotky.kdy, st_fotky.nekdy, st_fotky.zobr
			FROM [st_foto_novinky]
			LEFT JOIN [st_fotky] ON st_fotky.cis = st_foto_novinky.cis_fotky
			WHERE st_foto_novinky.cis_novinky = %i", $novinka["cis"],
			"AND [st_fotky.zobr] IS NOT NULL");
		echo "<table>\n<tr><th>foto</th><th>popisek</th><th>datum</th><th>kdy slovně</th><th>autor</th><th>smazat</th><th>změnit</th></tr>";
		//cestaKfotkam($sekce,$nazev,$nahradni= NULL,$kdy,$priznak)
		$cestaKobr = cestaKfotkam("novinky",$novinka["nazev"],NULL,$novinka["datum"],$novinka["rubrika"])."/pidi/";
//echo $cestaKobr;
		while($fotka = $fotky->fetch()){
			$foto = getimagesize("../".$cestaKobr.$fotka["soubor"].".jpg");
			echo "<form action=\"akce/zapsat.php?upravit=st_fotky&kde=" . $fotka["cis"]. "&odkud=" . $_GET["sekce"] . "&jaka=" . $novinka["cis"] . "\" method=\"post\">\n<tr><td>";
	/*				echo  ?  " | " . $fotka["popisek"]:"";
				echo $fotka["autor"] ?  " | " . $fotka["autor"]:"";
				if(is_null($fotka["nekdy"])){
					echo $fotka["kdy"] ? datum(strtotime($fotka["kdy"])):"";
				}else{
					echo $fotka["nekdy"];
				}
				echo "\"";
			echo (is_null($fotka["zobr"]) ? " class=\"skryt\"" : "");
			*/
			echo "<img src=\"".CESTA."/".$cestaKobr.$fotka["soubor"].".jpg\" ".$foto[3]." title=\"" . $fotka["popisek"]  . "\" alt=\"" . strip_tags($fotka["popisek"])  . "\"><br><br>".$fotka["soubor"]."</td>";
			echo "<td><label>Popisek</label><textarea name=\"popisek\">" . $fotka["popisek"] . "</textarea></td>";
			echo "<td><label>Datum</label><input type=\"date\" name=\"kdy\" value=\"" . substr($fotka["kdy"],0,10). "\"</td>";
			echo "<td><label>Kdy (slovně)</label><input type=\"text\" name=\"nekdy\" value=\"" . $fotka["nekdy"] . "\"</td>";
			echo "<td><label>Autor</label><input type=\"text\" name=\"autor\" value=\"" . $fotka["autor"] . "\"</td>";
			echo "<td class=\"smazat\"><a href=\"akce/zapsat.php?upravit=st_fotky&kuprave=" . $fotka["cis"] . "&jak=zobr&odkud=" . $_GET["sekce"] . "&jaka=" . $novinka["cis"] . "&pryc=\" title=\"odstranit\">smazat</a></td>";
			echo "<td><input type=\"hidden\" name=\"zobr\" value=\"1\">\n<input type=\"submit\" value=\"upravit\" /></td></tr>\n</form>\n";
		}
		echo "</table>\n<hr>\n";
	}else{
		// cestaKfotkam($nazev,$nahradni= NULL,$kdy,$priznak)
		$cestaKobr = "../".cestaKfotkam("novinky",$novinka["nazev"],NULL,$novinka["datum"],$novinka["rubrika"])."/pidi/";
//echo $cestaKobr;
		$pfotky = glob($cestaKobr."*.jpg"); /*náběr názvů souborů včetně fotek, aby se getimgsize mělo na co ptát*/
	//pre($pfotky);
		foreach($pfotky as $pfotka){
			$nazev = str_replace(".jpg", "", str_replace("/", "", strrchr($pfotka, "/"))); 
			$zapsat = array(
				"soubor" => $nazev,
			);
			if(dibi::query("INSERT INTO [st_fotky] %v",$zapsat," ON DUPLICATE KEY UPDATE [zobr] = 1")){
				$zapis2 = "INSERT INTO st_foto_novinky (cis_fotky, cis_novinky) VALUES ('" .dibi::insertId(). "','" . $novinka["cis"] . "')";	
				dibi::query($zapis2);
			}
			$zapis3 = "UPDATE novinky SET [fotky] = '1' WHERE [cis] = ".$novinka["cis"];	
			dibi::query($zapis3);
		}
		header('Location: ' . $_SERVER['HTTP_REFERER']);
		exit;
	}
}elseif($_GET["sekce"] == "st_vystup"){ //st_fotky.cis, st_fotky.soubor, st_fotky.popisek, st_fotky.autor, st_fotky.kdy, st_fotky.nekdy, st_fotky.zobr, 
		$vystoup = dibi::fetch("SELECT st_vystup.cis, st_vystup.nazev, st_vystup.kdy AS datum, st_vystup.priznak, st_inseminace.nazev AS inseminace
				FROM [st_vystup] 
				LEFT JOIN [st_vystup_inseminace] ON st_vystup_inseminace.cis_vystup = st_vystup.cis
				LEFT JOIN [st_inseminace] ON st_vystup_inseminace.cis_inseminace = st_inseminace.cis
				WHERE st_vystup.cis= %i", $_GET["kuprave"]);
		if(!isset($vystoup["nazev"])){
			$nazev = $vystoup["inseminace"];
		}else{
			$nazev = $vystoup["nazev"];
		}
		if(isset($_GET["fotky"])){
			$fotky = dibi::query("SELECT st_fotky.cis, st_fotky.soubor, st_fotky.popisek, st_fotky.autor, st_fotky.kdy, st_fotky.nekdy, st_fotky.zobr
				FROM [st_foto_vystup]
				LEFT JOIN [st_fotky] ON st_fotky.cis = st_foto_vystup.cis_fotky
				WHERE st_foto_vystup.cis_vystup= %i", $_GET["kuprave"]);
// dibi::dump();					
			echo "<table>\n";
			//cestaKfotkam($sekce,$nazev,$nahradni= NULL,$kdy,$priznak)
			$cestaKobr = cestaKfotkam("kdy-hrajeme",$nazev,NULL,$vystoup["datum"],$vystoup["priznak"])."/pidi/";
			while($fotka = $fotky->fetch()){
				$foto = getimagesize("../".$cestaKobr.$fotka["soubor"].".jpg");
				echo "<form action=\"akce/zapsat.php?upravit=st_fotky&kde=" . $fotka["cis"]. "&odkud=" . $_GET["sekce"] . "&jaka=" . $vystoup["cis"] . "\" method=\"post\">\n<tr><td>";
				echo "<img src=\"".CESTA."/".$cestaKobr.$fotka["soubor"].".jpg\" ".$foto[3]." alt=\"" . $fotka["popisek"]  . "\"></td>";
				echo "<td><label>Popisek</label><textarea name=\"popisek\">" . $fotka["popisek"] . "</textarea><td>";
				echo "<td><label>Datum</label><input type=\"date\" name=\"kdy\" value=\"" . substr($fotka["kdy"],0,10). "\"<td>";
				echo "<td><label>Kdy (slovně)</label><input type=\"text\" name=\"nekdy\" value=\"" . $fotka["nekdy"] . "\"<td>";
				echo "<td><label>Autor</label><input type=\"text\" name=\"autor\" value=\"" . $fotka["autor"] . "\"<td>";
				echo "<td class=\"smazat\"><a href=\"akce/zapsat.php?upravit=st_fotky&kuprave=" . $fotka["cis"] . "&jak=zobr&odkud=" . $_GET["sekce"] . "&jaka=" . $vystoup["cis"] . "&pryc=\" title=\"odstranit\">smazat</a></td>";
				echo "<td><input type=\"hidden\" name=\"zobr\" value=\"1\">\n<input type=\"submit\" value=\"upravit\" /></td></tr>\n</form>\n";
			}
			echo "</table>\n<hr>\n";
		}else{
			// cestaKfotkam($nazev,$nahradni= NULL,$kdy,$priznak)
			$cestaKobr = "../".cestaKfotkam("kdy-hrajeme",$nazev,NULL,$vystoup["datum"],$vystoup["priznak"])."/pidi/";
			$pfotky = glob($cestaKobr."*.jpg"); /*náběr názvů souborů včetně fotek, aby se getimgsize mělo na co ptát*/
			foreach($pfotky as $pfotka){
				$nazev = str_replace(".jpg", "", str_replace("/", "", strrchr($pfotka, "/"))); 
				$zapis = "INSERT INTO st_fotky (soubor) VALUES ('" .$nazev . "')";	
				dibi::query($zapis);
				$zapis2 = "INSERT INTO st_foto_vystup (cis_fotky, cis_vystup) VALUES ('" .dibi::insertId(). "','" . $vystoupeni["cislo"] . "')";	
				dibi::query($zapis2);
				$zapis3 = "UPDATE st_vystup SET [fotky] = '1' WHERE [cis] = ". $vystoupeni["cislo"];	
				dibi::query($zapis3);
			}
		}
}elseif($_GET["sekce"] == "st_inseminace"){
		$insem = dibi::fetch("SELECT st_fotky.cis, st_fotky.soubor, st_fotky.popisek, st_fotky.autor, st_fotky.kdy, st_fotky.nekdy, st_fotky.zobr, st_inseminace.cis AS cislo, st_inseminace.nazev 
			FROM [st_foto_inseminace]
			LEFT JOIN [st_fotky] ON st_fotky.cis = st_foto_inseminace.cis_fotky
			LEFT JOIN [st_inseminace] ON st_foto_inseminace.cis_inseminace = st_inseminace.cis
			WHERE st_foto_inseminace.cis_inseminace= %i", $_GET["kuprave"]);
//dibi::dump();				
		if(isset($_GET["fotky"])){
			$fotky = dibi::query("SELECT st_fotky.cis, st_fotky.soubor, st_fotky.popisek, st_fotky.autor, st_fotky.kdy, st_fotky.nekdy, st_fotky.zobr
				FROM [st_foto_inseminace]
				LEFT JOIN [st_fotky] ON st_fotky.cis = st_foto_inseminace.cis_fotky
				WHERE st_foto_inseminace.cis_inseminace= %i", $insem["cislo"]);
//dibi::dump();						
			echo "<table>\n";
			//cestaKfotkam($sekce,$nazev,$nahradni= NULL,$kdy,$priznak)
			$cestaKobr = cestaKfotkam("tvorba",$insem["nazev"],NULL,NULL,NULL)."/pidi/";
//echo $cestaKobr;
			while($fotka = $fotky->fetch()){
				$foto = getimagesize("../".$cestaKobr.$fotka["soubor"].".jpg");
				echo "<form action=\"akce/zapsat.php?upravit=st_fotky&kde=" . $fotka["cis"]. "&odkud=" . $_GET["sekce"] . "&jaka=" . $insem["cislo"] . "\" method=\"post\">\n<tr><td>";
				echo "<img src=\"".CESTA."/".$cestaKobr.$fotka["soubor"].".jpg\" ".$foto[3]." alt=\"" . $fotka["popisek"]  . "\"></td>";
				echo "<td><label>Popisek</label><textarea name=\"popisek\">" . $fotka["popisek"] . "</textarea><td>";
				echo "<td><label>Datum</label><input type=\"date\" name=\"kdy\" value=\"" . substr($fotka["kdy"],0,10). "\"<td>";
				echo "<td><label>Kdy (slovně)</label><input type=\"text\" name=\"nekdy\" value=\"" . $fotka["nekdy"] . "\"<td>";
				echo "<td><label>Autor</label><input type=\"text\" name=\"autor\" value=\"" . $fotka["autor"] . "\"<td>";
				echo "<td class=\"smazat\"><a href=\"akce/zapsat.php?upravit=st_fotky&kuprave=" . $fotka["cis"] . "&jak=zobr&odkud=" . $_GET["sekce"] . "&jaka=" . $insem["cislo"] . "&pryc=\" title=\"odstranit\">smazat</a></td>";
				echo "<td><input type=\"hidden\" name=\"zobr\" value=\"1\">\n<input type=\"submit\" value=\"upravit\" /></td></tr>\n</form>\n";
			}
			echo "</table>\n<hr>\n";
		}else{
			// cestaKfotkam($nazev,$nahradni= NULL,$kdy,$priznak)
			$cestaKobr = "../".cestaKfotkam("kdy-hrajeme",$nazev,NULL,$insem["datum"],$insem["priznak"])."/pidi/";
			$pfotky = glob($cestaKobr."*.jpg"); /*náběr názvů souborů včetně fotek, aby se getimgsize mělo na co ptát*/
			foreach($pfotky as $pfotka){
				$nazev = str_replace(".jpg", "", str_replace("/", "", strrchr($pfotka, "/"))); 
				$zapis = "INSERT INTO st_fotky (soubor) VALUES ('" .$nazev . "')";	
				dibi::query($zapis);
				$zapis2 = "INSERT INTO st_foto_vystup (cis_fotky, cis_vystup) VALUES ('" .dibi::insertId(). "','" . $vystoupeni["cislo"] . "')";	
				dibi::query($zapis2);
				$zapis3 = "UPDATE st_vystup SET [fotky] = '1' WHERE [cis] = ". $vystoupeni["cislo"];	
				dibi::query($zapis3);
			}
		}
}elseif($_GET["sekce"] == "st_str"){
	$obsah= dibi::fetch("SELECT cis, title, nadpis, nazev, url, fotky
		FROM [st_str]
		WHERE [cis] = %i",$_GET["kuprave"]);
	if(!is_null($obsah["fotky"])){
		$fotky = dibi::query("SELECT st_fotky.cis, st_fotky.soubor, st_fotky.popisek, st_fotky.autor, st_fotky.kdy, st_fotky.nekdy, st_fotky.zobr
			FROM [st_foto_str]
			LEFT JOIN [st_fotky] ON st_fotky.cis = st_foto_str.cis_fotky
			WHERE st_foto_str.cis_str= %i", $obsah["cis"]);
		echo "<div class=\"pfota\">\n";
		//cestaKfotkam($sekce,$nazev,$nahradni= NULL,$kdy,$priznak)
		$cestaKobr = "obr/str/".$obsah["url"]."/pidi/";
		echo "<table>\n";
			//echo $cestaKobr;
			while($fotka = $fotky->fetch()){
				$foto = getimagesize("../".$cestaKobr.$fotka["soubor"].".jpg");
				echo "<form action=\"akce/zapsat.php?upravit=st_fotky&kde=" . $fotka["cis"]. "&odkud=" . $_GET["sekce"] . "&jaka=" . $obsah["cis"] . "\" method=\"post\">\n<tr><td>";
				echo "<img src=\"".CESTA."/".$cestaKobr.$fotka["soubor"].".jpg\" ".$foto[3]." alt=\"" . $fotka["popisek"]  . "\"></td>";
				echo "<td><label>Popisek</label><textarea name=\"popisek\">" . $fotka["popisek"] . "</textarea><td>";
				echo "<td><label>Datum</label><input type=\"date\" name=\"kdy\" value=\"" . substr($fotka["kdy"],0,10). "\"<td>";
				echo "<td><label>Kdy (slovně)</label><input type=\"text\" name=\"nekdy\" value=\"" . $fotka["nekdy"] . "\"<td>";
				echo "<td><label>Autor</label><input type=\"text\" name=\"autor\" value=\"" . $fotka["autor"] . "\"<td>";
				echo "<td class=\"smazat\"><a href=\"akce/zapsat.php?upravit=st_fotky&kuprave=" . $fotka["cis"] . "&jak=zobr&odkud=" . $_GET["sekce"] . "&jaka=" . $obsah["cis"] . "&pryc=\" title=\"odstranit\">smazat</a></td>";
				echo "<td><input type=\"hidden\" name=\"zobr\" value=\"1\">\n<input type=\"submit\" value=\"upravit\" /></td></tr>\n</form>\n";
			}
			echo "</table>\n<hr>\n";		
	}else{
			// cestaKfotkam($nazev,$nahradni= NULL,$kdy,$priznak)
		$cestaKobr = "../obr/str/".$obsah["url"]."/pidi/";
		$pfotky = glob($cestaKobr."*.jpg"); /*náběr názvů souborů včetně fotek, aby se getimgsize mělo na co ptát*/
		foreach($pfotky as $pfotka){
			$nazev = str_replace(".jpg", "", str_replace("/", "", strrchr($pfotka, "/"))); 
			$zapis = "INSERT INTO st_fotky (soubor) VALUES ('" .$nazev . "')";	
			dibi::query($zapis);
			$zapis2 = "INSERT INTO st_foto_str (cis_fotky, cis_str) VALUES ('" .dibi::insertId(). "','" . $obsah["cis"] . "')";	
			dibi::query($zapis2);
			$zapis3 = "UPDATE st_str SET [fotky] = '1' WHERE [cis] = ".$obsah["cis"];	
			dibi::query($zapis3);
		}
	}
}elseif($_GET["sekce"] == "lidi"){
	$obsah= dibi::fetch("SELECT cis, url, fotky
		FROM [lidi]
		WHERE [cis] = %i",$_GET["kuprave"]);
	if(!is_null($obsah["fotky"])){
		$fotky = dibi::query("SELECT st_fotky.cis, st_fotky.soubor, st_fotky.popisek, st_fotky.autor, st_fotky.kdy, st_fotky.nekdy, st_fotky.zobr
			FROM [st_foto_lidi]
			LEFT JOIN [st_fotky] ON st_fotky.cis = st_foto_lidi.cis_fotky
			WHERE st_foto_lidi.cis_lidi= %i", $obsah["cis"]);
		echo "<div class=\"pfota\">\n";
		//cestaKfotkam($sekce,$nazev,$nahradni= NULL,$kdy,$priznak)
		$cestaKobr = "obr/o-sobe/".$obsah["url"]."/pidi/";
		echo "<table>\n";
			//echo $cestaKobr;
			while($fotka = $fotky->fetch()){
				$foto = getimagesize("../".$cestaKobr.$fotka["soubor"].".jpg");
				echo "<form action=\"akce/zapsat.php?upravit=st_fotky&kde=" . $fotka["cis"]. "&odkud=" . $_GET["sekce"] . "&jaka=" . $obsah["cis"] . "\" method=\"post\">\n<tr><td>";
				echo "<img src=\"".CESTA."/".$cestaKobr.$fotka["soubor"].".jpg\" ".$foto[3]." alt=\"" . $fotka["popisek"]  . "\"></td>";
				echo "<td><label>Popisek</label><textarea name=\"popisek\">" . $fotka["popisek"] . "</textarea><td>";
				echo "<td><label>Datum</label><input type=\"date\" name=\"kdy\" value=\"" . substr($fotka["kdy"],0,10). "\"<td>";
				echo "<td><label>Kdy (slovně)</label><input type=\"text\" name=\"nekdy\" value=\"" . $fotka["nekdy"] . "\"<td>";
				echo "<td><label>Autor</label><input type=\"text\" name=\"autor\" value=\"" . $fotka["autor"] . "\"<td>";
				echo "<td class=\"smazat\"><a href=\"akce/zapsat.php?upravit=st_fotky&kuprave=" . $fotka["cis"] . "&jak=zobr&odkud=" . $_GET["sekce"] . "&jaka=" . $obsah["cis"] . "&pryc=\" title=\"odstranit\">smazat</a></td>";
				echo "<td><input type=\"hidden\" name=\"zobr\" value=\"1\">\n<input type=\"submit\" value=\"upravit\" /></td></tr>\n</form>\n";
			}
			echo "</table>\n<hr>\n";
	}else{
			// cestaKfotkam($nazev,$nahradni= NULL,$kdy,$priznak)
		$cestaKobr = "../obr/str/".$obsah["url"]."/pidi/";
		$pfotky = glob($cestaKobr."*.jpg"); /*náběr názvů souborů včetně fotek, aby se getimgsize mělo na co ptát*/
		foreach($pfotky as $pfotka){
			$nazev = str_replace(".jpg", "", str_replace("/", "", strrchr($pfotka, "/"))); 
			$zapis = "INSERT INTO st_fotky (soubor) VALUES ('" .$nazev . "')";	
			dibi::query($zapis);
			$zapis2 = "INSERT INTO st_foto_lidi (cis_fotky, cis_lidi) VALUES ('" .dibi::insertId(). "','" . $obsah["cis"] . "')";	
			dibi::query($zapis2);
			$zapis3 = "UPDATE lidi SET [fotky] = '1' WHERE [cis] = ".$obsah["cis"];	
			dibi::query($zapis3);
		}
	}
}
echo "</div>\n"
?>
