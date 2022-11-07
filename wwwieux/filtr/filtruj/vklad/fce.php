<?php

function over_dotaz($vysldek) { 
if (!$vysldek){
  die("Nespojili jsme se s databází: &bdquo;" . mysql_error());
  }
  }
function dotaz($dotaz){
  global $spoj;
  global $vysl;
  $vysl= mysql_query($dotaz, $spoj);
  over_dotaz($vysl);
  return($vysl);          
		  }
		  
function uroven_vys() {
	if (isset ($_GET['str']) && !isset ($_GET['podstr'])) {
#              echo "../";
	}  
	elseif (isset ($_GET['podstr'])){
			  echo "../";
	}
  }
/*
  function cs_x_en ($cs, $en) {
		  if ($_GET['jaz'] == 'CS'){
			  $vysl = $cs;
			  }
			  else {
			  $vysl = $en;
			  }
		  return $vysl;
}  
function trida_tu ($co, $sCim){
if ($co == $sCim){
			  echo " class=\"tu\"";
			  }
}*/
function mile_url($stranka) {
setlocale(LC_CTYPE, 'cs_CZ.utf-8');
	$url = $stranka;
	$url = preg_replace('~[^\\pL0-9_]+~u', '-', $url);
	$url = trim($url, "-");
	$url = iconv("utf-8", "us-ascii//TRANSLIT", $url);
	$url = strtolower($url);
	$url = preg_replace('~[^-a-z0-9_]+~', '', $url);
	return $url;
}

function datum($platnost){   
  $en = array("January","February","March","April","May","June","July","August","September","October","November","December");  
  $cs = array("ledna","února","března","dubna","května","června","července","srpna","září","října","listopadu","prosince");   
  $datum = str_replace($en, $cs, date("j. F Y", $platnost)); 
  return $datum; 
}    

/*function vem_obory() {
  global $spoj; 
	$dotaz =  "SELECT * 
			 FROM obory 
			 ORDER BY poradi ASC";
  $obor = mysql_query($dotaz, $spoj);
  over_dotaz($obor);
  return $obor;
} 
function vem_stranky(){
  global $spoj; 
	$dotaz =  "SELECT * 
			 FROM stranky 
			 ORDER BY poradi ASC";
  $stranka = mysql_query($dotaz, $spoj);
  over_dotaz($stranka);
  return $stranka;
}
//function vem_str_dle_oboru($obor_cis)
//{ global $spoj; 
//	$dotaz =  "SELECT * 
//               FROM stranky 
//              WHERE cis_oboru={$obor_cis}
//               ORDER BY poradi ASC";
//    $str = mysql_query($dotaz, $spoj);
//  over_dotaz($str);
//   return $str;
//  }


	  
}  */
function obr_nadpis($nadpis, $vel){
$abeceda = array(
	"(c)" => "<img src=\"obr/abeceda/copy.png\" alt=\"©\" width=\"".$vel."\" height=\"".$vel."\" />\n",
	"#" => "<img src=\"obr/abeceda/_cerv.png\" alt=\" \" width=\"".$vel."\" height=\"".$vel."\" />\n",
	"." => "<img src=\"obr/abeceda/tecka.png\" alt=\".\" width=\"".$vel."\" height=\"".$vel."\" />\n",
	"," => "<img src=\"obr/abeceda/carka.png\" alt=\",\" width=\"".$vel."\" height=\"".$vel."\" />\n",
	"!" => "<img src=\"obr/abeceda/vykricnik.png\" alt=\".\" width=\"".$vel."\" height=\"".$vel."\" />\n",
	"?" => "<img src=\"obr/abeceda/otaznik.png\" alt=\".\" width=\"".$vel."\" height=\"".$vel."\" />\n",
	"-" => "<img src=\"obr/abeceda/-.png\" alt=\"-\" width=\"".$vel."\" height=\"".$vel."\" />\n",
	" " => "<img src=\"obr/abeceda/_.png\" alt=\" \" width=\"".$vel."\" height=\"".$vel."\" />\n",
	"a" => "<img src=\"obr/abeceda/a.png\" alt=\"a\" width=\"".$vel."\" height=\"".$vel."\" />\n",
	"b" => "<img src=\"obr/abeceda/b.png\" alt=\"b\" width=\"".$vel."\" height=\"".$vel."\" />\n",
	"c" => "<img src=\"obr/abeceda/c.png\" alt=\"c\" width=\"".$vel."\" height=\"".$vel."\" />\n",
	"d" => "<img src=\"obr/abeceda/d.png\" alt=\"d\" width=\"".$vel."\" height=\"".$vel."\" />\n",
	"e" => "<img src=\"obr/abeceda/e.png\" alt=\"e\" width=\"".$vel."\" height=\"".$vel."\" />\n",
	"f" => "<img src=\"obr/abeceda/f.png\" alt=\"f\" width=\"".$vel."\" height=\"".$vel."\" />\n",
	"g" => "<img src=\"obr/abeceda/g.png\" alt=\"g\" width=\"".$vel."\" height=\"".$vel."\" />\n",
	"h" => "<img src=\"obr/abeceda/h.png\" alt=\"h\" width=\"".$vel."\" height=\"".$vel."\" />\n",
	"i" => "<img src=\"obr/abeceda/i.png\" alt=\"i\" width=\"".$vel."\" height=\"".$vel."\" />\n",
	"j" => "<img src=\"obr/abeceda/j.png\" alt=\"j\" width=\"".$vel."\" height=\"".$vel."\" />\n",
	"k" => "<img src=\"obr/abeceda/k.png\" alt=\"k\" width=\"".$vel."\" height=\"".$vel."\" />\n",
	"l" => "<img src=\"obr/abeceda/l.png\" alt=\"l\" width=\"".$vel."\" height=\"".$vel."\" />\n",
	"m" => "<img src=\"obr/abeceda/m.png\" alt=\"m\" width=\"".$vel."\" height=\"".$vel."\" />\n",
	"n" => "<img src=\"obr/abeceda/n.png\" alt=\"n\" width=\"".$vel."\" height=\"".$vel."\" />\n",
	"o" => "<img src=\"obr/abeceda/o.png\" alt=\"o\" width=\"".$vel."\" height=\"".$vel."\" />\n",
	"p" => "<img src=\"obr/abeceda/p.png\" alt=\"p\" width=\"".$vel."\" height=\"".$vel."\" />\n",
	"q" => "<img src=\"obr/abeceda/q.png\" alt=\"q\" width=\"".$vel."\" height=\"".$vel."\" />\n",
	"r" => "<img src=\"obr/abeceda/r.png\" alt=\"r\" width=\"".$vel."\" height=\"".$vel."\" />\n",
	"s" => "<img src=\"obr/abeceda/s.png\" alt=\"s\" width=\"".$vel."\" height=\"".$vel."\" />\n",
	"t" => "<img src=\"obr/abeceda/t.png\" alt=\"t\" width=\"".$vel."\" height=\"".$vel."\" />\n",
	"u" => "<img src=\"obr/abeceda/u.png\" alt=\"u\" width=\"".$vel."\" height=\"".$vel."\" />\n",
	"v" => "<img src=\"obr/abeceda/v.png\" alt=\"v\" width=\"".$vel."\" height=\"".$vel."\" />\n",
	"w" => "<img src=\"obr/abeceda/w.png\" alt=\"w\" width=\"".$vel."\" height=\"".$vel."\" />\n",
	"x" => "<img src=\"obr/abeceda/x.png\" alt=\"x\" width=\"".$vel."\" height=\"".$vel."\" />\n",
	"y" => "<img src=\"obr/abeceda/y.png\" alt=\"y\" width=\"".$vel."\" height=\"".$vel."\" />\n",
	"z" => "<img src=\"obr/abeceda/z.png\" alt=\"z\" width=\"".$vel."\" height=\"".$vel."\" />\n",
   "á" => "<img src=\"obr/abeceda/aa.png\" alt=\"á\" width=\"".$vel."\" height=\"".$vel."\" />\n",
   "č" => "<img src=\"obr/abeceda/cc.png\" alt=\"č\" width=\"".$vel."\" height=\"".$vel."\" />\n",
   "ď" => "<img src=\"obr/abeceda/dd.png\" alt=\"ď\" width=\"".$vel."\" height=\"".$vel."\" />\n",
   "é" => "<img src=\"obr/abeceda/ee.png\" alt=\"é\" width=\"".$vel."\" height=\"".$vel."\" />\n",
  "ě" => "<img src=\"obr/abeceda/eee.png\" alt=\"ě\" width=\"".$vel."\" height=\"".$vel."\" />\n",
   "í" => "<img src=\"obr/abeceda/ii.png\" alt=\"í\" width=\"".$vel."\" height=\"".$vel."\" />\n",
   "ň" => "<img src=\"obr/abeceda/nn.png\" alt=\"ň\" width=\"".$vel."\" height=\"".$vel."\" />\n",
   "ó" => "<img src=\"obr/abeceda/oo.png\" alt=\"ó\" width=\"".$vel."\" height=\"".$vel."\" />\n",
   "ř" => "<img src=\"obr/abeceda/rr.png\" alt=\"ř\" width=\"".$vel."\" height=\"".$vel."\" />\n",
   "š" => "<img src=\"obr/abeceda/ss.png\" alt=\"š\" width=\"".$vel."\" height=\"".$vel."\" />\n",
   "ť" => "<img src=\"obr/abeceda/tt.png\" alt=\"ť\" width=\"".$vel."\" height=\"".$vel."\" />\n",
   "ú" => "<img src=\"obr/abeceda/uu.png\" alt=\"ú\" width=\"".$vel."\" height=\"".$vel."\" />\n",
   "ů" => "<img src=\"obr/abeceda/uo.png\" alt=\"ů\" width=\"".$vel."\" height=\"".$vel."\" />\n",
   "ý" => "<img src=\"obr/abeceda/yy.png\" alt=\"ý\" width=\"".$vel."\" height=\"".$vel."\" />\n",
   "ž" => "<img src=\"obr/abeceda/zz.png\" alt=\"ž\" width=\"".$vel."\" height=\"".$vel."\" />\n",
		  );
$sekana = strtr($nadpis, $abeceda);
print_r($sekana);
}
?>