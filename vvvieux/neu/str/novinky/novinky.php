<?php 
include '../../vklad/hlava.php';
?><div id="napln"><?php 
if (!isset($_GET["podstr"])){
	$rok = getdate();
}else{
	$rok["year"] = $_GET["podstr"];
}
$dotaz = "SELECT rubrika, nazev, datum, kdy, obsah
		FROM novinky
		WHERE datum
		BETWEEN '".mysql_real_escape_string($rok["year"])."-01-01'
		AND '".mysql_real_escape_string($rok["year"])."-12-31'
		ORDER BY datum DESC
	  ";
dotaz($dotaz);
if (mysql_num_rows($vysl) > 0){
	while ($novinka = mysql_fetch_array($vysl)){
	echo "<div class=\"nadpisy\">";
	echo "<p class=\"datum\">";
	if(is_null($novinka["kdy"])){
		echo date("j. n. Y", strtotime($novinka['datum']));
	}else{
		echo $novinka["kdy"];
	}
	echo "</p>".odd()."<h1 id=\"";
	echo mile_url($novinka['nazev']);
	echo "\">";
	echo $novinka['nazev'];
	echo "</h1>";
	if(!is_null($novinka["rubrika"])){
		odd();
		echo "<p class=\"rubrika\">";
		echo $novinka['rubrika'];
		echo "</p>";
	}
	echo "</div><div class=\"napln\">";   
	echo $novinka['obsah'];
	echo "</div>";
	}
}else{
	echo "<div class=\"nadpisy\"><p class=\"datum\">";
	echo $rok["year"];
	echo "</p>".odd()."<h1>Žádné novinky se nenašly, Cyrile.</h1></div>";
}
echo " </div>\n</div>\n";

//pro bok nejlépe funkce 'objekt', pač je všude na jedno brdo, i když tady s tim $rokem přes getdate.....?
bok();
echo "<div id=\"vycet\">\n<ul>"; //týž dotaz, výsledek se zobrazí vlevo ve výčtu
dotaz($dotaz);
while ($novinka = mysql_fetch_array($vysl)){
echo "<li class=\"datum\">";
if(is_null($novinka["kdy"])){
		echo date("j. n. Y", strtotime($novinka['datum']));
	}else{
		echo $novinka["kdy"];
	}
odd();
echo "<p class=\"rubrika\">";
echo $novinka['rubrika'];
echo "</p><br>";
echo "<a href=\"#";
echo mile_url($novinka['nazev']);
echo "\">";   
echo $novinka['nazev'];
echo "</a></li>";
}
echo "\n</ul>\n</div>\n</div>";

include '../../vklad/pata.php';
?>