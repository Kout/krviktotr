<?php
for($rok = letos();$rok >= 2011;$rok--){
	$od = $rok."-01-01";
	$do = $rok."-12-31";
	echo "<a name=\"rok-" . $rok . "\"></a><h1>" . $rok . "</h1>";
	$novinky = dibi::query("SELECT novinky.cis, novinky.rubrika, novinky.nazev, novinky.datum, novinky.kdy, novinky.obsah, novinky.fotky
		FROM [novinky]
		WHERE [datum] >= %d", $od, "AND [datum] <= %d",$do,"
		AND [zobr] IS NOT NULL
		ORDER BY [datum] DESC");
	while ($novinka =  $novinky->fetch()){
		echo "<div class=\"clanek\">\n<div class=\"nadClankem\">\n<a name=\"" . mile_url($novinka["nazev"]) . "\"></a>\n";
		echo "<p class=\"datum\">";
		if(is_null($novinka["kdy"])){
			echo date("j. n. 'y", strtotime($novinka["datum"]));
		}else{
			echo $novinka["kdy"];
		}
		echo "</p>\n";
		echo "<h1>". $novinka["nazev"];
		if(isset($novinka["rubrika"])){
			echo "<span class=\"rubrika\">".$novinka["rubrika"]."</span>\n</h1>\n</div>\n";
		}
		echo "<div class=\"text\">";   
		echo $novinka["obsah"];
		echo "</div>\n";
		if(!is_null($novinka["fotky"])){
			$fotky = dibi::query("SELECT st_fotky.cis, st_fotky.soubor, st_fotky.popisek, st_fotky.autor, st_fotky.kdy, st_fotky.nekdy, st_fotky.zobr
				FROM [st_foto_novinky]
				LEFT JOIN [st_fotky] ON st_fotky.cis = st_foto_novinky.cis_fotky
				WHERE st_foto_novinky.cis_novinky = %i", $novinka["cis"],
					" ORDER BY st_fotky.soubor");
			echo "<div class=\"pfota\">\n";
			//cestaKfotkam($sekce,$nazev,$nahradni= NULL,$kdy,$priznak)
			$cestaKobr = cestaKfotkam("novinky",$novinka["nazev"],NULL,$novinka["datum"],$novinka["rubrika"])."/pidi/";
	//echo $cestaKobr;
			while($fotka = $fotky->fetch()){
				$foto = getimagesize($cestaKobr.$fotka["soubor"].".jpg");				
				$cesta = CESTA."/".str_replace("/pidi/", "/obr/", $cestaKobr) . $fotka["soubor"]. ".jpg";
				$popisek = "";
				if(is_null($fotka["nekdy"])){
					$popisek .= $fotka["kdy"] ? datum(strtotime($fotka["kdy"])):"";
				}else{
					$popisek .= $fotka["nekdy"];
				}			
				$popisek .= $fotka["popisek"] ?  " <span class=\"rude\">|</span> " . $fotka["popisek"]:"";
				$popisek .= $fotka["autor"] ?  " <span class=\"rude\">|</span> " . $fotka["autor"]:"";	
				$altitle = prosty_text($popisek);
				echo "<a href=\"".$cesta."\" title=\"".$altitle."\" class=\"sada-" . $novinka["cis"] . "";
				echo (is_null($fotka["zobr"]) ? " skryt" : "");
				echo "\">";
				if($popisek <> ""){
					echo "<div class=\"skryt\">".$popisek."</div>";					
				}
				echo "<img src=\"".CESTA."/".$cestaKobr.$fotka["soubor"].".jpg\" ".$foto[3]." alt=\"" . $altitle  . "\"></a>\n";
			}			
			echo "<script type=\"text/javascript\">\n$('a.sada-".$novinka["cis"]."').colorbox({current: '{current}. obrázek z {total}', top: '5%', rel: '".$novinka["cis"]."'});\n</script>\n";
			echo "</div>\n<hr>\n";
		}
		echo "</div>\n";
	}
}
echo "<script type=\"text/javascript\">
$('.pfota a').colorbox({current: '{current}. obrázek z {total}', top: '10%', title: function(){
	var titel = $(this).children('.skryt').html();
	return titel;
}});\n
</script>";
?>