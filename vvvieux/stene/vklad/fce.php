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
function esli_vypis($co,$znacka= "",$pred = NULL, $za = NULL){
	if(!is_null($co)){
        $vypis = "";
        if($znacka != ""){
            $vypis .= "\n<".$znacka.">";
        }
        if(!is_null($pred)){
            $vypis .= $pred;
        }
        $vypis .= dopln_nbsp($co);
        if(!is_null($za)){
            $vypis .= $za;
        }
        if($znacka != ""){
            $vypis .= "</".$znacka.">\n";
        }
        return trim($vypis);
	}else{
        return false;
    }
}
function prosty_text($text){
    $text = strip_tags($text);
    $text = str_replace(" \"", "&bdquo;", $text);
    $text = str_replace("\" ", "&ldquo;", $text);
    return $text;
}
function obsah($nadpis,$obsah,$zvl=NULL,$url=NULL,$vklad=NULL){
    /* ------------- vložení případného speciálního souboru, který načítá z DtB -----------------  
    @nadpis stránky
    @obsah je obsah stránky
    @zvl pozice případného vkládaného souboru
    @url je url stranky, vkládaný soubor se jmenuje shodně
    @vklad pokud nenulový je toto název hledaného souboru
    */
    
    if(!is_null($zvl)){
        if($zvl == 'pred'){ //Vkládá se před obsah z DtB, obsah z DtB (nadpis + obsah) se potlačí
            zvl($url,$vklad);
        }elseif ($zvl == 'za') { //Vkládá se za obsah z DtB    
            echo (esli_vypis($nadpis,"","<h1>","</h1>"));        
            if(!is_null($obsah)){
                echo dopln_nbsp($obsah);
            }
            zvl($url,$vklad);
        }
    }else{        
        echo (esli_vypis($nadpis,"","<h1>","</h1>"));        
        if(!is_null($obsah)){
            echo dopln_nbsp($obsah);
        }            
    }
}
//výpis premiér, derniér (příp. naposledy) v tvorbě
function premDerniery($co,$typ,$nadpis){
    $naber = array("SELECT st_vystup.cis, st_vystup.nazev AS vystup, st_vystup.kdy, st_vystup.nekdy, st_vystup.priznak, st_saly.nazev AS sal, st_saly.nadnazev, st_saly.mesto, st_inseminace.nazev AS kus
        FROM [st_vystup_inseminace]
        LEFT JOIN [st_vystup] ON [st_vystup_inseminace.cis_vystup] = [st_vystup.cis]
        LEFT JOIN [st_vystup_saly] ON [st_vystup_saly.cis_vystup] = [st_vystup.cis]
        LEFT JOIN [st_inseminace] ON st_vystup_inseminace.cis_inseminace = st_inseminace.cis
        LEFT JOIN [st_saly] ON [st_vystup_saly.cis_salu] = [st_saly.cis]");
    if($typ == "premiera"){
        $where = "WHERE [st_vystup.kdy] = (
            SELECT MIN([st_vystup.kdy]) 
            FROM [st_vystup_inseminace]
            LEFT JOIN [st_vystup] ON [st_vystup_inseminace].[cis_vystup] = [st_vystup].[cis]
            WHERE [st_vystup_inseminace].[cis_inseminace] = 6
            AND [st_vystup.zobr] IS NOT NULL
            )
        AND [st_vystup_inseminace.cis_inseminace]";
    }else{
        $where = "WHERE [st_vystup_inseminace.cis_vystup]";
    }
    array_push($naber,$where," = %i",$co,"AND [st_vystup.zobr] IS NOT NULL");
    $vystup = dibi::fetch($naber);
    if(!isset($vystup["vystup"])){
        $nazev = $vystup["kus"];
    }else{
        $nazev = $vystup["vystup"];
    }
    $kdy = substr($vystup["kdy"], 0,10);
    if(!is_null($vystup["priznak"])){
        $priznak = "-".$vystup["priznak"];
    }else{
        $priznak = "";
    }
    $vypis = date("j. n. 'y",(strtotime(substr($vystup["kdy"], 0, 10))));
    // $vypis .= ";
    $vypis .= " (".$vystup["mesto"].", ".$vystup["sal"].")";
    echo esli_vypis($vypis,"", "<h4>" . $nadpis . " </h4><p><a href=\"kdy-hrajeme#".mile_url($nazev)."-" . $kdy . $priznak . "\" title=\"přejít na představení\">","</a></p>");
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
//cesty k fotkám
function cestaKfotkam($sekce,$nazev,$nahradni = NULL,$kdy,$priznak = NULL){
    $cesta = "obr/".$sekce."/";
    if(!is_null($nahradni)){
        $naz = $nahradni;
    }else{
        $naz = $nazev;
    }
    if(!is_null($kdy)){
        $cesta .= substr($kdy,0,10)."-";
    }
    $cesta .= mile_url($naz);
    if(!is_null($priznak)){
        $cesta .= "-".mile_url($priznak);
    }
    return $cesta;
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
function letos(){
    $letos = getdate();
    $letos = $letos["year"];
    return $letos;
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
        $ocisteno = NULL;
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
function putInput($zDtB, $nazvy, $co, $klic, $size = 60, $typ = false, $cols=100, $rows=15){
    $input = "<label for=\"na-" . $klic . "\">".$nazvy[$klic]."</label>\n";
    if($co == "input"){
        $input .= "<input class=\"input-xxlarge\" type=\"".($typ ? $typ:"text")."\" name=\"" . $klic . "\" id=\"na-" . $klic . "\" size=\"". ($size ? $size:60) ."\" value=\"";
        if($typ == "date"){
            $input .= substr($zDtB[$klic],0,10);
        }else{
            $input .= $zDtB[$klic];
        }
        $input .= "\"><br>\n";
    }elseif($co == "text"){
        $input .= "<textarea class=\"input-xxlarge\" name=\"" . $klic . "\" id=\"na-" . $klic . "\" cols=\"" . ($cols ? $cols:100) . "\" rows=\"" . ($rows ? $rows:35) . "\">" . $zDtB[$klic] . "</textarea><br>\n";        
    }
    return $input;
}
//tlačítko 'zpět' (opakuje se, proto do fce, kvůli přehlednosti)
function tlacitko(){
    if(isset($_GET["upravit"])){
        if($_GET["upravit"] != "fotky"){
            if(isset($_GET["kuprave"])||isset($_GET["nova"])){
                echo "<a href=\"./?jak=vypis&upravit=" . $_GET["upravit"] . "\" class='tlacitko btn pull-right'>zpět na výpis</a>";
            }
        }else{
                echo "<a href=\"./?jak=upravit&upravit=" . $_GET["sekce"] . "&kuprave=" . $_GET["kuprave"] . "\" class='tlacitko btn'>zpět na výpis</a>";
        }
    }
}
// vloží rozbalovací výpis dostupných výstupů k dané inscenaci
function vyznamne($co){
        global $hra, $popisky;
        $vystup = dibi::query("SELECT [st_vystup.cis], [st_vystup.kdy], [st_saly.nazev] AS sal
                FROM [st_vystup_inseminace]
                LEFT JOIN [st_vystup] ON [st_vystup_inseminace.cis_vystup] = [st_vystup.cis]
                LEFT JOIN [st_vystup_saly] ON [st_vystup_saly.cis_vystup] = [st_vystup.cis]
                LEFT JOIN [st_saly] ON [st_vystup_saly.cis_salu] = [st_saly.cis]
                WHERE [st_vystup.zobr] IS NOT NULL
                AND [st_vystup_inseminace.cis_inseminace] = %i",$hra["cis"],
                "ORDER BY [st_vystup.kdy] ASC");
        $vystupy = array();
        while($vyst = $vystup->fetch()){
            $vystupy[$vyst["cis"]] = $vyst["sal"]." ".datum(strtotime($vyst["kdy"]));
        }
        echo "<label for=\"na-".$co."\">" . $popisky[$co] . "</label>\n";
        echo "<select name=\"".$co."\">\n";
//optionlist($options, $selected = array(), $not_values = false)        
        echo "<option value=\"0\">hrajem, hrajem dál</option>";
        echo optionlist($vystupy,$hra[$co]);
        echo "</select><br>\n"; 
}
$GLOBALS["clenstvi"] = array(
    "soubor" => "Soubor",
    // "divadilna" => "Hráli s námi",
    // "hoste" => "Jako host",
    // "diky" => "Děkujeme za pomoc",
); 
$GLOBALS["dilo"] = array(
    "na-repertoaru" => array(
        "nadpis" => "Na repertoáru",
        "where" => "[hrajese] = 'na-repertoaru'",
    ),
    "po-derniere" => array(
        "nadpis" => "Po derniéře",
        "where" => "[hrajese] = 'po-derniere'",
    ),
);
?>