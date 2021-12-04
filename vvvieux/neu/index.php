<?php 
include 'vklad/hlava.php';
?>

<div><h2><?php odd(); ?>zveme vás</h2>
<img src="obr/svazana-070610.jpg" width="300" height="212">
</div>
<div>autorské divadlo<?php odd(); ?>autorský humor
<img src="obr/KT_logo-neu-360px.png" width="360" height="186">
autorské herectví<?php odd(); ?>autorské autorství
</div>
<div><h2><?php odd(); ?>kdy hrajeme</h2><?php
$rok = getdate();
$dotaz = "SELECT predstaveni.nazev, predstaveni.datum, predstaveni.kdy, mista.mesto AS kde
	FROM predstaveni
	LEFT JOIN mista ON predstaveni.kde = mista.cis
	WHERE datum
	BETWEEN '".mysql_real_escape_string($rok["year"])."-01-01'
	AND '".mysql_real_escape_string($rok["year"])."-12-31'
	AND datum > NOW()
	ORDER BY datum DESC";
dotaz($dotaz);
echo "<ul class=\"vypis\">";
    while($predstaveni = mysql_fetch_assoc($vysl)){
        echo "<li class=\"datum\"><b>";
        if(is_null($predstaveni["kdy"])){
            echo date("j. n. Y", strtotime($predstaveni["datum"]));
        }else{
            echo $predstaveni["kdy"];
        }
        echo "</b>";
        odd();
        echo $predstaveni["kde"];
		odd();
		echo "<a href=\"zive.kt\">".$predstaveni["nazev"]."</a>";
        echo "</li>";
    }
    echo "</ul>";
?></div>
<div><h2><?php odd(); ?>poslední novinky</h2><?php

$dotaz = "SELECT rubrika, nazev, datum, kdy, obsah
	FROM novinky
	WHERE datum
	BETWEEN '".$rok["year"]."-01-01'
	AND '".$rok["year"]."-12-31'
	ORDER BY datum DESC
	LIMIT 8
	";	
echo "<ul class=\"vypis\">";
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
echo "</p>";
odd();
echo "<a href=\"novinky.kt#";
echo mile_url($novinka['nazev']);
echo "\">";   
echo $novinka['nazev'];
echo "</a></li>";
}
echo "\n</ul>\n";
?></div>

<img src="str/doma/obr/bok/doma.png" width="25" height="166" alt="" id="bok">
</div><!-- konec #stat -->
</div><!-- konec #telo -->
  <p id="pata">&copy; Krvik Totr Limity 2009</p>  
<?php

include 'vklad/pata.php';
?>