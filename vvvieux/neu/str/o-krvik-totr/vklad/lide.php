<?php
$dotaz = "SELECT *
		FROM lidi
		WHERE url = '".mysql_real_escape_string($_GET["ceho"])."'";	
dotaz($dotaz);
$lidi = mysql_fetch_assoc($vysl);
echo "<div id=\"napln\">\n<div id=\"nadpis\">\n<h1><span>";
echo $lidi["jmeno"];
if ($lidi["2_jm"]){
	echo $lidi["2_jm"];  //if 3_jm použít tak, aby příjmení figurovalo ve výčtu, ale v nadpise tak, jak chce Petr =>upravit zde a ve o-krvik-totr.php
	echo "</span> ";
}else{
	echo "</span> ";
}
echo $lidi["prijmi"];
if ($lidi["3_jm"]){
	echo $lidi["3_jm"];  //if 3_jm použít tak, aby příjmení figurovalo ve výčtu, ale v nadpise tak, jak chce Petr =>upravit zde a ve o-krvik-totr.php
	#echo "</span> ";
}/*else{
	echo "</span> ";
}*/
echo "</h1>\n</div>";
/*echo "<br>odd: ";
echo $_GET["odd"];
echo "<br>podstr: ";
echo $_GET["podstr"];
echo "<br>pod_podstr: ";
echo $_GET["pod_podstr"];
echo "<br>kdo: ";
echo $_GET["ceho"];
echo "<br>";
uroven_vys();*/
?><h6>Kudy ke KT</h6><p><?php
echo $lidi["kudy"];
?></p><h6>Bio</h6><?php
echo $lidi["o"];

$dotaz = "SELECT predstaveni.nazev, predstaveni.kdy
		FROM predstaveni_lidi
		LEFT JOIN predstaveni ON predstaveni.cis = predstaveni_lidi.cis_predstaveni
		WHERE predstaveni_lidi.cis_lidi = '".mysql_real_escape_string($lidi["cis"])."'
		ORDER BY predstaveni.kdy ASC";
dotaz($dotaz);
if(mysql_num_rows($vysl) > 0){
	echo "<h6>V představeních</h6>\n<ul>";
	while($predstaveni = mysql_fetch_assoc($vysl)){
		echo "<li>".$predstaveni["nazev"]." <em>(".date("j. n. Y", strtotime($predstaveni["kdy"])).")</em></li>";
	}
	echo "</ul>";
}

/*Živé role*/
$dotaz_role = "SELECT postavy.jmeno, pociny.nazev
		FROM postavy_lidi
		LEFT JOIN postavy ON postavy.cis = postavy_lidi.cis_postavy
		LEFT JOIN pociny ON postavy.cis_pocinu = pociny.cis
		WHERE postavy_lidi.cis_lidi = '".mysql_real_escape_string($lidi["cis"])."'";
$dotaz = "AND postavy.jak = 'zive'
		OR postavy_lidi.cis_lidi = '".mysql_real_escape_string($lidi["cis"])."' AND postavy.jak = 'studio,zive'
		ORDER BY postavy.jmeno ASC
		";
dotaz($dotaz_role.$dotaz);
if(mysql_num_rows($vysl) > 0){
	echo "<h6>Živé role</h6>\n<ul>";
	while($role = mysql_fetch_assoc($vysl)){
		echo "<li>".$role["jmeno"]." <em>(".$role["nazev"].")</em></li>";
	}
	echo "</ul>";
}
/*Studiové role*/
$dotaz = "AND postavy.jak = 'studio' 
		OR postavy_lidi.cis_lidi = '".mysql_real_escape_string($lidi["cis"])."' AND postavy.jak = 'studio,zive'
		ORDER BY postavy.jmeno ASC
		";
dotaz($dotaz_role.$dotaz);
if(mysql_num_rows($vysl) > 0){
	echo "<h6>Studiové role</h6>\n<ul>";
	while($role = mysql_fetch_assoc($vysl)){
		echo "<li>".$role["jmeno"]." <em>(".$role["nazev"].")</em></li>";
	}
	echo "</ul>";
}
/*Role na tělo*/
$dotaz = "AND postavy.na_telo IS NOT NULL
		ORDER BY postavy.jmeno ASC
		";
dotaz($dotaz_role.$dotaz);
if(mysql_num_rows($vysl) > 0){
	echo "<h6>Role napsané na tělo</h6>\n<ul>";
	while($telo = mysql_fetch_assoc($vysl)){
		echo "<li>".$telo["jmeno"]." <em>(".$telo["nazev"].")</em></li>";
	}
	echo "</ul>";
}
?>
<h6>Foto naživo</h6>
<h6>Foto momentky</h6>
<?php 
/*mp3 a video*/
$dotaz = "SELECT pociny.nazev 
		FROM zaznamy_lidi 
		LEFT JOIN zaznamy ON zaznamy.cis = zaznamy_lidi.cis_zaznamu 
		LEFT JOIN pociny ON zaznamy.cis_pocinu = pociny.cis
		WHERE zaznamy_lidi.cis_lidi = '".mysql_real_escape_string($lidi["cis"])."'
		ORDER BY pociny.nazev ASC
		";
dotaz($dotaz);
if(mysql_num_rows($vysl) > 0){
	echo "<h6>Mp3/video</h6>\n<ul>";
	while($zaznam = mysql_fetch_assoc($vysl)){
		echo "<li>".$zaznam["nazev"]."</li>";
	}
	echo "</ul>";
}
?><h6>Sólo</h6>
<h6>Diskuse</h6>
