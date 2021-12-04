<?php
echo "<a href=\"#";
echo ($str == "" ? "doma" : "stat");
echo "\" title=\"přeskočit na obsah\" class=\"skryt\">přeskočit na obsah</a>\n";
echo "<ul id=\"nav\">\n";
$navigace = dibi::query("SELECT cis, title, nadpis, nazev, url
	FROM [st_str]
	WHERE [zobr] = '1'
	AND [poradi] IS NOT NULL
	AND [rodic] IS NULL
	ORDER BY [poradi]");
while($nav = $navigace->fetch()){
	echo "\n\t<li><a href=\"".CESTA."/";
	echo $nav["url"];
	echo "\" title=\"";
	if(is_null($nav["title"])){
		if(is_null($nav["nadpis"])){
			echo $nav["nazev"];
		}else{
			echo $nav["nadpis"];
		}
	}else{
		echo $nav["title"];
	}
	echo "\"";
	if(isset($nav["url"])){
		trida_tu($str,$nav["url"]);
	}
	echo ">" . dopln_nbsp($nav["nazev"]) . "</a>";
	echo "</li>\n";
}

?>
<li id="schovka"><a href="http://krviktotr.cz/index-vieux.htm" target="_blank" title="schovka">schovka</a></li>
</ul>
