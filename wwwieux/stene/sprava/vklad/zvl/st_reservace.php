<?php	
if(isset($jak)){
	if($jak == "vypis"){
		$naber = dibi::query("SELECT [st_reservace.*], [st_vystup.nazev] AS vystup, [st_vystup.kdy], [st_inseminace.nazev] AS kus
				FROM [" . $co . "]
				LEFT JOIN [st_vystup] ON [st_reservace.vystup] = [st_vystup.cis]
				LEFT JOIN [st_vystup_inseminace] ON st_vystup_inseminace.cis_vystup = st_vystup.cis
				LEFT JOIN [st_inseminace] ON st_vystup_inseminace.cis_inseminace = st_inseminace.cis
				WHERE [" . $co . ".kdy] IS NOT NULL
				ORDER BY [" . $co . ".kdy] DESC");	
		echo "<table class=\"table table-striped\">\n<caption>Reservace</caption>\n<tr><th>kdo</th><th>email</th><th>kolik</th><th>kdy</th><th>představení</th><th>smazat</th></tr>\n";
		while($vypis = $naber->fetch()){
			if(!isset($vypis["vystup"])){
				$nazev = $vypis["kus"];
			}else{
				$nazev = $vypis["vystup"];
			}
			echo "<tr>
			<td>".$vypis["meno"]."</td>
			<td>" . $vypis["emajl"] . "</td>
			<td class=\"cisloVTabulce\">" . $vypis["kolik"] . "&times;</td>
			<td class=\"cisloVTabulce\">".datum(strtotime($vypis["kdy"]))." v&nbsp;".substr($vypis["kdy"], 11,5)."</td>
			<td>" . $nazev . "</td>
			<td class=\"smazat\"><a href=\"akce/zapsat.php?upravit=" . $co . "&kuprave=" . $vypis["cis"] . "&jak=kdy&pryc=\" title=\"odstranit\">smazat</a></td>
			</tr>\n";
			unset($clenstvi);
		}
		echo "</table>";
	}
}
?>