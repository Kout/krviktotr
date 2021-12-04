<hr>
</div>
<div id="hlava">
<ul><a href="filtr.flt"><img src="obr/abeceda/_.png" alt=" " width="25" height="25"	 /></a>
<?php
$dotaz = "SELECT adr, nadpis
			FROM str
			WHERE adr <> 'filtr'";
dotaz($dotaz);
while($menu = mysql_fetch_array($vysl)){
	echo "<li><h".
	($menu["adr"] == $_GET["str"] ? 1 : 2).
	">";
	if($menu["adr"] != $_GET["str"]){
		echo "<a href=\"";
		echo $menu["adr"];
		echo ".flt\" title=\"";
		echo $menu["nadpis"];
		echo "\">";
		echo $menu["nadpis"];
		echo "</a>";
	}else{
		echo $menu["nadpis"];
	}
	echo "</h".
	($menu["adr"] == $_GET["str"] ? 1 : 2)
	."></li>";
}
?><li><h2><a href="#" title="">f</a></h2></li>
</ul>
</div>
<div id="pata"><?php
obr_nadpis("(c)#filtruj.cz ", 20);
?>

</div>
</body>
</html>
<?php
if (isset($spoj)){
 mysql_close($spoj);
} 
?>
