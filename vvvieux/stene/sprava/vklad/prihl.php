<?php 
session_start();
if(isset($_GET["odhl"])){
    unset($_SESSION["prihl"]);
}
if (isset($_POST["auth_login"])) {
    if (mysql_result(mysql_query("SELECT COUNT(*) FROM st_upravy WHERE meno = '" . mysql_real_escape_string($_POST["auth_login"]) . "' AND veslo = '" . sha1($_POST["auth_heslo"]) . "'"), 0)) {
        session_regenerate_id(); // ochrana před Session Fixation
        $_SESSION["prihl"] = true;
        header("Location: ./");
    }
}
include "vklad/hlava.php";
if (!isset($_SESSION["prihl"])) {
    if (isset($_POST["auth_login"])) {
        echo "<p>Neplatné přihlašovací údaje.</p>\n";
    }
    echo "<form action='' method='post' class='prihl'>\n<fieldset><legend>Přihlásit se do správy</legend>\n";
    echo "<p>Login: <input name='auth_login' maxlength='30' /></p>\n";
    echo "<p>Heslo: <input type='password' name='auth_heslo' /></p>\n";
    echo "<p><input type='submit' value='Přihlásit' /></p>\n";
    echo "</fieldset>\n</form>\n";
    exit;
}else{
    $odhlaska = "<form action=\"".CESTA."/sprava/?odhl=0\" method=\"post\" title=\"Už je hotovo? Tak zatím!\">\n<input type=\"submit\" value=\"odhlásit\" />\n</form>";
}
?>