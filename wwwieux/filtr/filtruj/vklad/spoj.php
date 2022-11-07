<?php
$spoj = mysql_connect('mysql', "kt.krviktotr.cz", "8121992pikkrvik");
if (!$spoj){
die("Nespojil jsem se s databází, Cyrile: " . mysql_error());
}
$zvol_db = mysql_select_db("kt_krviktotr_cz", $spoj);
if (!zvol_db){
die("Nepovedl se výběr z databáze, Cyrile: " . mysql_error());
}
mysql_query("SET CHARACTER SET utf8");
?>