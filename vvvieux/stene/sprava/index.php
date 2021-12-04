<?php
include_once "../vklad/dibi.php";
include "../vklad/spoj.php";
include "vklad/prihl.php";

if(isset($_GET["upr"])){
	if($_GET["upr"] == 0){
		echo "<script type=\"text/javascript\">alert('Změny se nepovedlo provést. Zkuste to znovu, nebo dejte vědět správci.');</script>";
	}elseif($_GET["upr"] == 1){
		echo "<script type=\"text/javascript\">alert('Provedeno dle rozkazu.');</script>";
	}
}
include  "../vklad/fce.php";
$oupravy = array(
	"st_str" => "Stránky",
//	"obedy" => "Denní jídelní lístky",
	"novinky" => "Noviny",
	"st_vystup" => "Vystoupení",
	"st_inseminace" => "Inscenace",
	"lidi" => "Lidi",
	"st_saly" => "Sály",
	"st_reservace" => "Reservace",
);
echo "<ul class=\"nav navbar nav-tabs\">\n";
echo "<li><a href=\"./\" title=\"zpátky na začátek\"></a></li>";
array_walk($oupravy, "odrazky");
echo "<li>" . $odhlaska . "</li>";
echo "<hr>";
echo "</ul>\n";
if(!isset($_GET["upravit"])){
	$co = "st_str";
	$jak = "vypis";
}else{
	$co = $_GET["upravit"];
	$jak = $_GET["jak"];
}
tlacitko();
include "vklad/zvl/".$co.".php";
tlacitko();

?><script type="text/javascript">
$('textarea').blur(function(){
	tinyMCE.triggerSave();
});
$('form').submit(function(){
	tinyMCE.triggerSave();
});
</script><?php
//pre($_GET);
include "vklad/pata.php";
?>