<dl>
<?php
echo "<h2><a href=\"" . $jazyky["nov"][$jaz] . "\" title=\"" . $jazyky["nowiny"][$jaz] . "\">" . ucfirst($jazyky["nov"][$jaz]) . "</a></h2>";
$noviny = dibi::query("SELECT cis, nazev_" . $jaz . " AS nazev, obsah_" . $jaz . " AS obsah, kdy
	FROM [noviny]
	WHERE [kdy] IS NOT NULL
	AND [obsah_" . $jaz . "] IS NOT NULL
	ORDER BY [kdy] DESC
	LIMIT 2");
if(count($noviny) > 0){
	while($novina = $noviny->fetch()){
		if(!is_null($novina["nazev"]) && !is_null($novina["obsah"])){
			echo "<dt>" . $novina["nazev"] . "</dt>";
			echo "<dd>" . dopln_nbsp(html_cut(preg_replace("~</?p>~", "", $novina["obsah"]),60));
			echo " <a href=\"" . CESTA . "/" . $jaz . "/" . $novina["cis"] . "-" . $jazyky["nov"][$jaz] . "\" title=\"" . $novina["nazev"] . "\">" . $jazyky["vic"][$jaz] . "</a><p>" . datum(strtotime($novina["kdy"])) . "</p></dd>";
		}
	}
}else{
	echo "<i>" . $jazyky["nov0"][$jaz] . "</i>";
}
?>
</dl>