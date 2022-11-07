<?php
include 'vklad/fce.php';
include 'vklad/spoj.php';
?><!DOCTYPE HTML>
<html lang="cs">
<head>
<meta charset="utf-8">
<link rel="stylesheet" href="vzhled/vzhled-zarchlivu.css">
<script src="skrypti/jquery.js"></script>
<title></title>
</head>
<body>
<form action="" method="get">
<input type="submit" value="vytáhnout">
<select name="mesic"><?php
$mesice = array("leden","únor","březen","duben","květen","červen","červenec","srpen","září","říjen","listopad","prosinec");   
echo optionlist($mesice, $selected = $_GET["mesic"]);
?></select>
</form>
<?php
if(!isset($_GET["mesic"])){
	$mesic = "01";
	$_GET["mesic"] = 0;
}else{
	$mesic = $_GET["mesic"] + 1;
	if($mesic < 10){
		$mesic = "0".$mesic;
	}
}
$dotaz = "SELECT nazev, datum, kdy
		FROM novinky
		WHERE datum LIKE '%-" . $mesic . "-%'
		ORDER BY DAY(datum), datum ASC";
dotaz($dotaz);
if (mysql_num_rows($vysl) > 0){
	echo "<table><caption>Výpis novinek za " . $mesice[$_GET["mesic"]] . "</caption>\n";
	echo "<tr><th>novinka</th><th>datum</th><th>kdy (uvedeno-li)</th></tr>\n";
	while ($novinka = mysql_fetch_array($vysl)){
		echo "<tr><td><a href=\"novinky/" . substr($novinka["datum"], 0, 4) . ".kt#" . mile_url($novinka["nazev"]) . "\" title=\"na novinku\" tareget=\"_blank\">" . $novinka["nazev"] . "</a></td><td>" . date("j. n. Y", strtotime($novinka['datum'])) . "</td><td>" . $novinka["kdy"] . "</td></tr>";
	}
	echo "</table>\n";
}
//odpojení od DB
if (isset($spoj)){
 mysql_close($spoj);
 }
echo "<script type=\"text/javascript\">
$('select').change(function (){
    $(this).closest('form').submit();
});
</script>";
?>
 
</body>
</html>