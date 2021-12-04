<?php
function over_dotaz($vyber) {
if (!$vyber){
           die("Dotaz do databáze selhal, Cyrile: " . mysql_error());
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
    if (isset ($_GET['odd']) && !isset ($_GET['podstr'])) {
              echo "";
    }elseif (isset ($_GET['podstr']) && !isset ($_GET['pod_podstr'])){
              echo "../";
    } elseif (isset ($_GET['pod_podstr']) && !isset($_GET["ceho"])){ //$ceho je případ výpisů jednotlivých počinů, lidí atp.
              echo "../../";
    }elseif (isset ($_GET['ceho'])){
              echo "../../../";
    }
  }
function zde(){
    global $vysl;
    $dotaz = "SELECT nadhlava.cis
        FROM nadhlava
        WHERE nadhlava.url = '".$_GET["odd"]."'";
	dotaz($dotaz);
	$zde = mysql_fetch_assoc($vysl);
    return $zde["cis"];
}
function bok(){
    global $vysl;
    if(isset($_GET["podstr"])){
        $bokde = "obr/bok/".$_GET["podstr"].".png";
    }else{
        $dotaz = "SELECT podhlava.url
                FROM nad_podhlava
                LEFT JOIN podhlava ON nad_podhlava.cis_podhlava = podhlava.cis
                WHERE nad_podhlava.cis_nadhlava = ".zde()."
                AND nad_podhlava.poradi = '1'";
        dotaz($dotaz);
        $kde_hledat = mysql_fetch_assoc($vysl);
        $bokde = "obr/bok/".$kde_hledat["url"].".png";
    }
      if(file_exists($bokde)){
      $boky = getimagesize($bokde);
      echo "<img src=\"";
      uroven_vys();
      echo "str/";
      echo $_GET["odd"]."/".$bokde;
      echo "\" ";
      echo $boky[3];
      echo " alt=\"";
      echo $_GET["podstr"];
      echo "\" id=\"bok\">\n";
    }  
}


function pod_podhlava(){
    global $vysl;
    if(isset($_GET["podstr"])){
        $dotaz = "SELECT podhlava.cis
                    FROM podhlava
                    WHERE podhlava.url = '".$_GET["podstr"]."'";
        dotaz($dotaz);
        $zde = mysql_fetch_assoc($vysl);
        $dotaz = "SELECT pod_podhlava.cis
                FROM pod_pod
                LEFT JOIN pod_podhlava ON pod_podhlava.cis = pod_pod.cis_pod_podhlava
                WHERE pod_pod.cis_podhlava = '".$zde["cis"]."'";
        dotaz($dotaz);
        $pod_podhlava = (mysql_num_rows($vysl));
        return $pod_podhlava;
   }
}


function mile_url ($stranka) {
setlocale(LC_CTYPE, 'cs_CZ.utf-8');
    $url = $stranka;
    $url = preg_replace('~[^\\pL0-9_]+~u', '-', $url);
    $url = trim($url, "-");
    $url = iconv("utf-8", "us-ascii//TRANSLIT", $url);
    $url = strtolower($url);
    $url = preg_replace('~[^-a-z0-9_]+~', '', $url);
    return $url;
}
function odd(){
   echo "<span class=\"oddelitko\">|</span>";
}

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


?>

