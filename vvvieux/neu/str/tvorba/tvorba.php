<?php 
$co = array('scenky' => 'scénka', 'pisne' => 'písnička', 'povidky' => 'povídka', 'blbinky' => 'blbinka', 'hry' => 'hra');
include '../../vklad/hlava.php';

echo "<div id=\"napln\">\n<h2>#napln</h2>\n<p>Ještě je tu třeba nasekat řazení výpisu dle data/ABC a zvolit výchozí. Možná si dle cookie pamatovat poslední zvolené. Nebo použít session???</p></div>";
if(!isset($_GET["podstr"])){
	$coze = "hry";
}else{
	$coze = mysql_real_escape_string($_GET["podstr"]);
}
if(isset($_GET["pod_podstr"])){
	$where = "url = '".mysql_real_escape_string($_GET["pod_podstr"])."' AND co LIKE '%".$co[$coze]."%'";
}else{
	$where = "kdy = 
			(SELECT MAX(kdy)
					FROM pociny
					WHERE kdy < NOW()
					AND co LIKE '%".$co[$coze]."%')
		";//LIKE, pač třeba Rozcvička (Vlk) je scénkou i naším rádiem, a tudíž jsou k mání dvě hodnoty oddělené čárkou...
}
if($_GET["podstr"] != "nahravky-publikace"){
	$dotaz = "SELECT nazev, kdy, url
			FROM pociny
			WHERE ".$where; 
}else{
	$dotaz = "SELECT nazev, kdy, url
			FROM sada
			WHERE url = '".mysql_real_escape_string($_GET["pod_podstr"])."'";
}

dotaz($dotaz);
$pocin = mysql_fetch_assoc($vysl);
echo "<div id=\"nadpis\">\n<p class=\"datum\">";
echo (substr($pocin["kdy"], 0, 4));
echo "</p>".odd()."<h1>";
echo $pocin["nazev"];
echo "</h1>\n</div>";
/*echo $dotaz;
echo "<br>odd: ";
echo $_GET["odd"];
echo "<br>podstr: ";
echo $_GET["podstr"];
echo "<br>pod_podstr: ";
echo $_GET["pod_podstr"];
echo "<br>ceho: ";
echo $_GET["ceho"];
echo "<br>";
uroven_vys();*/
if($_GET["podstr"] == "hry" || $_GET["podstr"] == "nahravky-publikace"){
	include 'vklad/'.$_GET["podstr"].'.php';
}else{
	include 'vklad/ostatni.php';
}
bok();
?><div id="vycet">
    <ul>
  <?php 
$dotaz = "SELECT nazev, url, kdy
		FROM pociny
		WHERE co LIKE '%".$co[$coze]."%'
		ORDER BY nazev ASC
		";
if($_GET["podstr"] != "nahravky-publikace"){
	$dotaz = "SELECT nazev, kdy, url
		FROM pociny
		WHERE co LIKE '%".$co[$coze]."%' 
		ORDER BY nazev ASC
		";
}else{
	$dotaz = "SELECT nazev, kdy, url
		FROM sada
		WHERE jaka = 'album'
		ORDER BY nazev ASC
		";
}
dotaz($dotaz);
while ($vypis = mysql_fetch_assoc($vysl)){
	echo "<li><a href=\"";
	if (!isset($_GET["pod_podstr"])){
		echo $_GET["podstr"]."/";
	}
	echo $vypis["url"].".kt\">".$vypis["nazev"]."</a>";
	odd();
	echo (substr($vypis["kdy"], 0, 4))."</li>";
}
    ?></ul>
      </div></div><?php
include '../../vklad/pata.php';
?>