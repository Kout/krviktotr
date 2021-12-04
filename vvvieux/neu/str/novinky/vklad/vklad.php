        <div id="napln">
             
             
<?php 
if (!isset($_GET["podstr"])){
  $rok = getdate();
  $_GET["podstr"] = $rok["year"];
}
$dotaz = "SELECT rubrika, nazev, datum, obsah
	  FROM novinky
      WHERE datum
      BETWEEN '".mysql_real_escape_string($_GET["podstr"])."-01-01'
      AND '".mysql_real_escape_string($_GET["podstr"])."-12-31'
	  ";	
dotaz($dotaz);
while ($novinka = mysql_fetch_array($vysl)){
echo "<div class=\"nadpisy\">";
echo "<p class=\"datum\">";
echo date("j. m. Y", strtotime($novinka['datum']));
echo "</p><span class=\"oddelitko\">|</span><h1 id=\"";
echo mile_url($novinka['nazev']);
echo "\">";
echo $novinka['nazev'];
echo "</h1><span class=\"oddelitko\">|</span><p>";
echo $novinka['rubrika'];
echo "</p></div><div class=\"napln\">";   
echo $novinka['obsah'];
echo "</div>";
}
echo " </div>\n</div>\n";

//pro bok nejlépe funkce 'objekt', pač je všude na jedno brdo, i když tady s tim $rokem přes getdate.....?
$bokde = "obr/bok/".$_GET["podstr"].".png";
  if(file_exists($bokde)){
  $boky = getimagesize($bokde);
  echo "<img src=\"../str/";
  echo $_GET["odd"]."/".$bokde;
  echo "\" ";
  echo $boky[3];
  echo " alt=\"";
  echo $_GET["podstr"];
  echo "\" id=\"bok\">\n";
}
echo "<div id=\"vycet\">\n<ul>"; //týž dotaz, výsledek se zobrazí vlevo ve výčtu
dotaz($dotaz);
while ($novinka = mysql_fetch_array($vysl)){
echo "<li class=\"datum\">";
echo date("j. m. Y", strtotime($novinka['datum']));
echo "<span class=\"oddelitko\">|</span><p class=\"rubrika\">";
echo $novinka['rubrika'];
echo "</p><br>";
echo "<a href=\"#";
echo mile_url($novinka['nazev']);
echo "\">";   
echo $novinka['nazev'];
echo "</a></li>";
}
?></ul>
   
      </div>
</div>