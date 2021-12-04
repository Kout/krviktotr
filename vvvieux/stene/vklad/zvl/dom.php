<img src="obr/animace/obr.gif" alt="autorský humor" width="276" height="20">
<div>
	<h1>Zveme vás</h1>
		 <div>
		<?php 
			$cesta = "obr/hp/uvodni.jpg";
			$altitle = "Pozvánka na exkluzivní oslavu 20 let Krvik Totr"; //náhradní text, když se obrázek nenačte + bublina po přejetí myši
			if($obr = @getimagesize($cesta)){ //odstranit @!!!!!!!!!!!!!!!!!!!!!!!!!
				echo "<a href=\"kdy-hrajeme#20-let-krvik-totr-2012-12-08-20let_kampa1\" title=\"Rezrvace vstupenek a informace o představení\"><img src=\"".$cesta."\"".$obr[3]." alt=\"".$altitle."\" title=\"".$altitle."\"></a>";			
			}
		 ?>		
		 <!-- <img width="200" height="100" alt="zkušební obrázek"> -->
			<br><h2>Velkosrdečně vás zveme <br>k oslavě 20 let Krvik Totr!</h2>
			<p>Neopakovatelný zážitek, který se zopakuje právě jen po ty dva prosincové večery, kdy to před 20 lety začalo &ndash; a tak neváhejte a <a href="kdy-hrajeme#20-let-krvik-totr-2012-12-08-20let_kampa1" title="Rezrvace vstupenek a informace o představení">přijďte s námi oslavit kulatiny</a>!</p>
			<p>Nečekejte ale &bdquo;the best of&ldquo; &ndash; oprášili a přeleštili jsme zapomenuté scénky a písničky podle našeho gusta a napsali pár nových. Čeká na vás syrová krvikototrská poetika, nelaskavý humor a temná společenská absurdita... Zkrátka: <h2>Večer plný nepohody...</h2></p> 
		 </div>
</div>
<div>
	<h1>Příště hrajeme</h1>
	<?php 
	$Vystoupeni = dibi::query("SELECT st_vystup.cis AS cislo, st_vystup.nazev AS vystup, st_vystup.priznak, st_vystup.kdy, st_vystup.nekdy, st_saly.nazev AS sal, st_saly.nadnazev, st_saly.mesto, st_saly.ctvrt, st_saly.adresa, st_saly.kudytam, st_saly.pozn,st_saly.www, st_inseminace.cis, st_inseminace.nazev AS kus, st_inseminace.podtitul, st_inseminace.co
		FROM [st_vystup]
		LEFT JOIN [st_vystup_saly] ON st_vystup_saly.cis_vystup = st_vystup.cis
		LEFT JOIN [st_saly] ON st_vystup_saly.cis_salu = st_saly.cis
		LEFT JOIN [st_vystup_inseminace] ON st_vystup_inseminace.cis_vystup = st_vystup.cis
		LEFT JOIN [st_inseminace] ON st_vystup_inseminace.cis_inseminace = st_inseminace.cis
		WHERE st_vystup.kdy >= NOW()
		AND [st_vystup.zobr] IS NOT NULL
		ORDER BY st_vystup.kdy
		LIMIT 3"); //počet vypisovaných vystoupení
	if(count($Vystoupeni) > 0){
		echo "<ul>\n";
		while ($vystoupeni =  $Vystoupeni->fetch()){
			if(!isset($vystoupeni["vystup"])){
				$nazev = $vystoupeni["kus"];
			}else{
				$nazev = $vystoupeni["vystup"];
			}
			$kdy = substr($vystoupeni["kdy"], 0,10);
			if(!is_null($vystoupeni["priznak"])){
				$priznak = "-".$vystoupeni["priznak"];
			}else{
				$priznak = "";
			}		
			echo "<li class=\"datum\">";
			if(is_null($vystoupeni["nekdy"])){
				echo esli_vypis(date("j. n. 'y", (strtotime(substr($vystoupeni["kdy"], 0, 10)))),"","","");
			}else{
				echo esli_vypis($vystoupeni["nekdy"],"","","");
			}
			echo "<h2><a href=\"kdy-hrajeme#".mile_url($nazev)."-" . $kdy . $priznak . "\">".$nazev."</a></h2>";					
			echo $vystoupeni["mesto"]." (".$vystoupeni["sal"].")";			
			echo "\n</li>\n";
		}
		echo "</ul>\n";
	}
	?>
</div>
<div>
	<h1>Nejnovější novinky</h1>
	<?php 
	$rok = letos();
	$od = $rok."-01-01";
	$do = $rok."-12-31";	
	$novinky = dibi::query("SELECT novinky.cis, novinky.rubrika, novinky.perex, novinky.nazev, novinky.datum, novinky.kdy, novinky.obsah, novinky.fotky
		FROM [novinky]
		WHERE [datum] >= %d", $od, "AND [datum] <= %d",$do,"
		AND [zobr] IS NOT NULL
		ORDER BY [datum] DESC
		LIMIT 3");
	if(count($novinky) > 0){
		echo "<ul>\n";
		while ($novinka =  $novinky->fetch()){
			echo "<li class=\"datum\">";
			if(is_null($novinka["kdy"])){
				echo date("j. n. 'y", strtotime($novinka["datum"]));
			}else{
				echo $novinka["kdy"];
			}		
			echo "<h2><a href=\"novinky#".mile_url($novinka['nazev'])."\">". $novinka["nazev"]."</a></h2>";
			if(!is_null($novinka["perex"])){
				echo $novinka["perex"];				
			}else{
				echo $novinka["rubrika"];
			}
			echo "</li>\n";
		}		
		echo "</ul>\n";
	}
	?>
</div>