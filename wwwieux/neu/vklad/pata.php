       <div id="hlava">
      <div id="nadhlava">
            <ul>
          <li><h2><a href="./<?php
		uroven_vys();
	  ?>"><img src="<?php
		  uroven_vys(); //změnit na / tj. kořenový, jakmile nahrajeme 
		  ?>obr/KT_logo.png" width="19px" height="10px" border="0"></a></h2></li><?php 
		  //nadhlava = nejvyšší úroveň menu, patrná vždy a všude stejná
	$dotaz = "SELECT nadhlava.nazev,nadhlava.url
			FROM nadhlava
			WHERE poradi > 1
			ORDER BY poradi";
	dotaz($dotaz);
	while($nadhlava = mysql_fetch_assoc($vysl)){
		echo "<li><h2><a href=\"";
		uroven_vys();	
		if (isset($_GET["odd"]) == "novinky"){
			echo "../neu/"; /*odstranit, či upravit po spuštění => směrovat do kořenového adresáře prostě*/
		}
		if($nadhlava["url"] <> ""){
			echo $nadhlava["url"];
			echo ".kt\">";
		}
		echo $nadhlava["nazev"];
		echo "</a></h2></li>";
		odd();
		echo "\n";
	}
?></ul>
      </div>
      <div id="podhlava"><ul><?php /*druhý řádek horního menu, liší se pochopitelně*/
	$dotaz = "SELECT podhlava.nazev,podhlava.url
			FROM nad_podhlava
			LEFT JOIN podhlava ON podhlava.cis = nad_podhlava.cis_podhlava
			WHERE nad_podhlava.cis_nadhlava = '".zde()."'
			ORDER BY nad_podhlava.poradi";
	dotaz($dotaz);
	while($podhlava = mysql_fetch_assoc($vysl)){
		echo "<li><h3><a href=\"";
		uroven_vys();
		if ($_GET["odd"] == "novinky" || $_GET["podstr"] == "lide"){
			echo "../neu/"; /*odstranit, či upravit po spuštění => směrovat do kořenového adresáře prostě*/
		}
		echo $_GET["odd"]."/".$podhlava["url"];
		echo ".kt\">";
		if ($_GET["odd"] == "novinky"){ //zkrátit na dva znaky u roků 2010 => 10, 1996 => 96
		    echo substr($podhlava['nazev'], 2,2);
		}else {
			echo $podhlava['nazev'];
		} 
		echo "</a></h3></li>";
		odd();
	}
?></ul>    
</div><?php
if(isset($_GET["podstr"])){
	$dotaz = "SELECT podhlava.cis
				FROM podhlava
				WHERE podhlava.url = '".$_GET["podstr"]."'";
	dotaz($dotaz);
	$zde = mysql_fetch_assoc($vysl);
	$dotaz = "SELECT pod_podhlava.nazev,pod_podhlava.url
			FROM pod_pod
			LEFT JOIN pod_podhlava ON pod_podhlava.cis = pod_pod.cis_pod_podhlava
			WHERE pod_pod.cis_podhlava = '".$zde["cis"]."'";
	dotaz($dotaz);
	if(mysql_num_rows($vysl) > 0){
		echo "<div id=\"pod-podhlava\" style=\"display: block;\">\n<ul>\n";
		while($pod_podhlava = mysql_fetch_assoc($vysl)){
			echo "<li><h4><a href=\"";
			uroven_vys();
			if ($_GET["odd"] == "novinky" || $_GET["podstr"] == "lide"){
				echo "../neu/"; /*odstranit, či upravit po spuštění => směrovat do kořenového adresáře prostě*/
			}
			echo $_GET["odd"]."/".$_GET["podstr"]."/".$pod_podhlava["url"];
			echo ".kt\">";
			if ($_GET["podstr"] == "dejepis" || $_GET["podstr"] == "odehrano" ){ //zkrátit na dva znaky u roků 2010 => 10, 1996 => 96
				echo substr($pod_podhlava['nazev'], 2,2);
			}else {
				echo $pod_podhlava['nazev'];
			} 
			echo "</a></h4></li>";
			odd();
			echo "\n";
		}
		echo "</ul>\n</div>";
	}
}
?>

    </div>
      
  </body>
</html>
<?php //odpojení od DB
if (isset($spoj)){
 mysql_close($spoj);
 } 
?>