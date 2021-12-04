<?php
function trida_tu ($co, $sCim){
    if ($co == $sCim){
        echo " class=\"tu\"";
    }
}
//výpis titulku, pokud není vypiš nadpis
function titulek($title, $nadpis){
	if(is_null($title) || $title == ""){
		echo $nadpis;
	}else{
		echo $title;
    }
}
function zvl($url,$vklad){
    if(is_null($vklad)){
        $zvl = "vklad/zvl/".$url.".php";
    }else{
        $zvl = "vklad/zvl/".$vklad.".php";
    }
    if(file_exists($zvl)){
        include $zvl;
    }
}
//výpis textů
function dopln_nbsp($text){
	$typotext=preg_replace("|(\s[svzkuoiSVZKAUOI])\s|", "$1&nbsp;", $text); 
	return($typotext);
}
function mb_ucfirst($text){ 
     $text[0] = mb_strtoupper($text[0]); 
     return $text; 
}
function esli_pis($co, $tag, $endtag){
	if(!is_null($co)){
		echo $tag.dopln_nbsp($co).$endtag;
	}
}
function esli_vypis($co,$nadpis,$uroven,$odstavec = false){
	if(isset($co)){
		echo "\n<h".$uroven.">".$nadpis."</h".$uroven.">\n";
		echo ($odstavec ? "<p>":"");
        echo dopln_nbsp($co)."\n";
		echo ($odstavec ? "</p>\n":"");        
	}
}
function html_cut($s, $limit)
{
    static $empty_tags = array('area', 'base', 'basefont', 'br', 'col', 'frame', 'hr', 'img', 'input', 'isindex', 'link', 'meta', 'param');
    $length = 0;
    $tags = array(); // dosud neuzavřené značky
    for ($i=0; $i < strlen($s) && $length < $limit; $i++) {
        switch ($s{$i}) {

        case '<':
            // načtení značky
            $start = $i+1;
            while ($i < strlen($s) && $s{$i} != '>' && !ctype_space($s{$i})) {
                $i++;
            }
            $tag = strtolower(substr($s, $start, $i - $start));
            // přeskočení případných atributů
            $in_quote = '';
            while ($i < strlen($s) && ($in_quote || $s{$i} != '>')) {
                if (($s{$i} == '"' || $s{$i} == "'") && !$in_quote) {
                    $in_quote = $s{$i};
                } elseif ($in_quote == $s{$i}) {
                    $in_quote = '';
                }
                $i++;
            }
            if ($s{$start} == '/') { // uzavírací značka
                $tags = array_slice($tags, array_search(substr($tag, 1), $tags) + 1);
            } elseif ($s{$i-1} != '/' && !in_array($tag, $empty_tags)) { // otevírací značka
                array_unshift($tags, $tag);
            }
            break;

        case '&':
            $length++;
            while ($i < strlen($s) && $s{$i} != ';') {
                $i++;
            }
            break;

        default:
            $length++;
            while ($i+1 < strlen($s) && ord($s[$i+1]) > 127 && ord($s[$i+1]) < 192) {
                    $i++;
            }  

        }
    }
    $s = substr($s, 0, $i);
    if ($tags) {
        $s .= "&hellip;</" . implode("></", $tags) . ">";
    }else{
        $s .= "&hellip;";  
    }
    return $s;
}
//galerie
function naber_obr($obrazarna, $obrAlt = NULL){
    $obraz = "obr/galerie/" . $obrazarna. "/";
	$obraz .= "pidi/";
	$fotky = glob($obraz."*.jpg");
	if(!is_null($obrAlt)){
        $fotky["alt"] =  $obrAlt . " - náhled";
    }else{
        $fotky["alt"] = "náhled obrázku ".$obrUrl."ze složky ".$obrazarna;
    }
	return $fotky;
}
//obecné
function pre($pole){
	echo "<pre>";
	print_r($pole);
	echo "</pre>";
}
function mile_url($stranka) {
    setlocale(LC_CTYPE, 'cs_CZ.utf-8');
    $url = $stranka;
    $url = preg_replace('~[^\\pL0-9_]+~u', '-', $url);
    $url = trim($url, "-");
    $url = iconv("utf-8", "ascii//TRANSLIT", $url);
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
/*----------------------- správa ----------------------*/
function optionlist($options, $selected = array(), $not_values = false) {
/** Převod pole na HTML volby
* @param array pole, ze kterých budou vytvořeny volby
* @param array skalární hodnota nebo pole s označenými prvky
* @param bool zajistí, že <option> nebudou obsahovat atribut value
* @return string řetězec s HTML volbami
* @copyright Jakub Vrána, http://php.vrana.cz/
*/
    $return = "";
    if (!is_array($selected)) {
        $selected = array($selected);
    }
    foreach ($options as $key => $val) {
        $checked = in_array(($not_values ? $val : $key), $selected);
        $return .= '<option' . ($not_values ? '' : ' value="' . htmlspecialchars($key) . '"') . ($checked ? ' selected="selected"' : '') . '>' . htmlspecialchars($val) . "</option>\n";
    }
    return $return;
}

//vstup do DtB přes TinyMCE
function ocista($vstup){
    if(!isset($vstup) || $vstup == "" || $vstup == "0" || is_null($vstup)){
        $ocisteno = 'NULL';
    }else{
        $ocisteno = "" . (($vstup)) . "";
    }
	return $ocisteno;
}
//správa - výpis navigace
function odrazky($pole, $klic){
    $odrazka = "<li><a href=\"?jak=vypis&upravit=" . $klic . "\" title=\"spravovat " . $pole . "\"";
    if(isset($_GET["upravit"]) && $_GET["upravit"] == $klic){
        $odrazka .= " class=\"tu\"";
    }
    $odrazka .= ">" . $pole . "</a></li>";
    echo $odrazka;
}
//správa - výpis polí úprav
function putInput($zDtB, $nazvy, $co, $klic, $size = 60, $typ = false, $cols=100, $rows=35){
    $input = "<label for=\"na-" . $klic . "\">".$nazvy[$klic]."</label>\n";
    if($co == "input"){
        $input .= "<input type=\"".($typ ? $typ:"text")."\" name=\"" . $klic . "\" id=\"na-" . $klic . "\" size=\"". ($size ? $size:60) ."\" value=\"" . $zDtB[$klic] . "\"><br>\n";
    }elseif($co == "text"){
        $input .= "<textarea name=\"" . $klic . "\" id=\"na-" . $klic . "\" cols=\"" . ($cols ? $cols:100) . "\" rows=\"" . ($rows ? $rows:35) . "\">" . $zDtB[$klic] . "</textarea><br>\n";        
    }
    return $input;
}
//tlačítko 'zpět' (opakuje se proto do fce, kvůli přehlednosti)
function tlacitko(){
    if(isset($_GET["kuprave"])||isset($_GET["nova"])){
        echo "<a href=\"./?jak=vypis&upravit=" . $_GET["upravit"] . "\" class='tlacitko'>zpět na výpis</a>";
    }
}
?>