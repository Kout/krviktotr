<?php
$spoj = mysql_connect("localhost", "db_krviktot", "sql38krv73to84"); /*sudo dkpg-reconfigure mysql-server-5.1*/
if (!$spoj){
die("Nespojil jsem se s databazi, Cyrile: " . mysql_error());
}
$zvol_db = mysql_select_db("allset_krviktot", $spoj);
if (!$zvol_db){
die("Nepovedl se výběr z databáze, Cyrile: " . mysql_error());
}
mysql_query("SET CHARACTER SET utf8");
?>