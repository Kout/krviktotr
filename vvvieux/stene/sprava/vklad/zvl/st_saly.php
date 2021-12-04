<?php
if(isset($jak)){
	if($jak == "vypis"){
		$novinky = dibi::query("SELECT cis, nazev
			FROM [st_saly]
			WHERE [zobr] IS NOT NULL
			ORDER BY [nazev] ASC ");
		echo "<table class=\"table table-striped\">\n<caption>Úpravy inscenací</caption>\n<tr><th>název</th><th>odstranit</th></tr>\n";
		echo "<tr><td colspan=\"3\"><a href=\"?jak=upravit&upravit=" . $co . "&kuprave=&nova=\" title=\"přidat zprávu\" class=\"nova\">nový sál</a></td></tr>\n";	
		while($hra = $novinky->fetch()){
			echo "<tr>
			<td><a href=\"?jak=upravit&upravit=" . $co . "&kuprave=".$hra["cis"]."\" title=\"upravit údaje\">".$hra["nazev"]."</a></td>
			<td class=\"smazat\"><a href=\"akce/zapsat.php?upravit=" . $co . "&kuprave=" . $hra["cis"] . "&jak=zobr&pryc=\" title=\"odstranit\">smazat</a></td>
			</tr>\n";
		}
		echo "</table>";
	}elseif($jak == "upravit"){
		$popisky = array(
			"nazev" => "Název sálu",
			"nadnazev" => "Nadnázev",
			"mesto" => "Město",
			"ctvrt" => "Čtvrť",
			"adresa" => "Adresa",
			"kudytam" => "Kudytam",
			"pozn" => "Poznámka",
			"www" => "www"
		);
		$hra = dibi::fetch("SELECT *
			FROM [st_saly]
			WHERE [cis] = %i",$_GET["kuprave"]);
		echo "<form action=\"akce/zapsat.php?upravit=" . $co . "&podle=cis&kde=".$hra["cis"]."\" method=\"post\"><fieldset><legend>" . $hra["nazev"] . "</legend>\n";
		if(isset($_GET["nova"])){
			echo "<input type=\"hidden\" name=\"nieuw\">\n";
		}
		echo "<br><input type=\"submit\" value=\"změnit\" class='horni'><br>\n";
	
//putInput($zDtB, $nazvy, $co, $klic, $size = 60, $typ = false, $cols=100, $rows=35)	
		echo putInput($hra, $popisky, "input", "nazev");
		echo putInput($hra, $popisky, "input", "nadnazev");
		echo putInput($hra, $popisky, "input", "mesto");
		echo putInput($hra, $popisky, "input", "ctvrt");
		echo putInput($hra, $popisky, "input", "adresa");
		echo putInput($hra, $popisky, "input", "www");
		echo putInput($hra, $popisky, "text", "kudytam",NULL,NULL,100,10);
		echo putInput($hra, $popisky, "text", "pozn",NULL,NULL,100,10);
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
