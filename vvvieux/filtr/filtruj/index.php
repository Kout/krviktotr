<?php
include "./vklad/hlava.php";
if(!isset($_GET["str"])){
  $_GET["str"] = "filtr";
 }

$dotaz = "SELECT vklad, nadpis, obsah
          FROM str
        WHERE adr = '".$_GET["str"]."'";
dotaz($dotaz);    
$vklad = mysql_fetch_assoc($vysl);
if(file_exists("vklad/str/".$vklad["vklad"].".php")){
include "vklad/str/".$vklad["vklad"].".php";
}else{
echo "<h3>";
obr_nadpis($vklad["nadpis"]." ", 25);
echo "</h3>";
echo $vklad["obsah"];
}
?><!--<div id="vycet">
#vycet
 </div>-->
     
<?php
include "./vklad/pata.php";
?>