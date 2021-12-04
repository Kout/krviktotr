<!DOCTYPE HTML>
<!--[if lt IE 7 ]><html class="ie ie6" lang="cs"> <![endif]-->
<!--[if IE 7 ]><html class="ie ie7" lang="cs"> <![endif]-->
<!--[if IE 8 ]><html class="ie ie8" lang="cs"> <![endif]-->
<!--[if (gte IE 9)|!(IE)]><!--><html lang="cs"> <!--<![endif]-->
<head>
<meta charset="utf-8">
<link rel="stylesheet/less" href="vzhled/vzhled-st.less">
<script src="skrypti/less.js"></script>
<script src="skrypti/jquery.js"></script>
<title></title>
</head>
<body>
<img src="obr/Stenatko-je-mrtve.jpg" width="500" height="707" alt="Štěňátko je mrtvé - plakát">
<div id="stat">
<h1>Velkosrdečně vás zveme na premiéru naší další autorské hry!</h1>
<p>V pondělí 2. dubna v 19,30 v <a href="http://www.divadlokampa.cz/clanky/kde-nas-najdete-a-jak-se-k-nam-dostanete.html">Divadle Kampa</a></p>
<a href="#" class="reserve">Rezervace vstupenek zde!</a>
<div class="reserve">
<form action="" method="post"><br>
<fieldset><legend>Rezervace na představení Krvik Totr <span class="pozn">(všechny položky jsou povinné)</span></legend>
<input name="web" id="web">
<label for="meno">Na jméno</label><br>
<input name="meno" id="meno"><br>
<label for="kdo">Váš e-mail <span class="pozn">(na který přijde potvrzení)</span></label><br>
<input name="kdo" id="kdo" size="25" maxlength="64"><br>
<label for="kolik">Počet lístků</label><br>
<input type="number" min="1" max="999" name="kolik" id="kolik" value="1"><br>
<input type="hidden" name="vystup" value="6">
<input type="submit" value="Rezervovat">
<button>Anebo pryč</button>
</fieldset>
</form>
</div>
<br>
<h2>Krvik Totr: Štěňátko je mrtvé (čb)</h2>
<p>Podle komedie Martina Friče ,,Slečinka" z roku 1934.</p>
<blockquote>Madame zemřela. Od její smrti se v pensionu opakované výpadky elektrického proudu opakují. Faktury jsou k nezaplacení. V Bubenči mlha, že by psa nevyhnal. Snad TGM něco vymyslí... A navíc - pořád je tu štěňátko!</blockquote>
<p>V pořadí pátou autorskou hru uvádí soubor Krvik Totr v 20. roce své existence. Ve zpětném pohledu je vidět, že jsme vždy inklinovali k ,,retru 20. let", do doby, kdy se jiná autorská dvojice V+W chlámala při psaní stejně radostně jako my. A tentokrát o první republice přímo hrajeme. Po třech sezónách s úspěšnou hrou Svázaná, v níž hrála čistě maskulinní sestava, opět rozšiřujeme soubor o ženský element. </p>
</div>
<script type="text/javascript" src="./skrypti/RESquery.js"></script>
<?php
if(isset($_GET["zapis"])){
	echo "<script type=\"text/javascript\">";
	if($_GET["zapis"] == 1){
		echo "alert('Rezervace odeslána. Pokud vám nepřijde potvrzovací email, napište znovu na info@krviktotr.cz');";
	}elseif($_GET["zapis"] == 0){
		echo "alert('Rezervace se nepovedla. Zkuste to prosím znovu, nebo si o lístky napište na info@krviktotr.cz');";
	}
	echo "</script>";
}
?>

</body>
</html>