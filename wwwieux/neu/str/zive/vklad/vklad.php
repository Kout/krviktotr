          <div id="napln">
        <h2>#napln</h2>
        <p>Kecy</p><?php
        if ($bylo_bude == "odehrano"){
            echo "Jsou tu pod_podstr, čili jako ve vokt??, proměnná \$čeho</br>Hm, ale jak to udělat s jednoznačností URL, která musí jasně říct, co nabrat z DB, přičemž název 'Svázaná' se hraje víckrát a současně lze hrát v jednom datu dvojáka (a nedej bože trojáka). Vypadá to na zvláštní sloupec v tabulce 'URL'. Ale ráno moudřejší večera.<br>Pak teprv půjde nabírat patřičné údaje pro stránku (nadpis a další).";
            $kdy = "<";
        }else{
            $kdy = ">";
        }
?></div>
  
   
       
           
         <div id="nadpis">
             <p class="datum">5.&nbsp;12&nbsp;09</p><span class="oddelitko">|</span><h1>Svázaná</h1><span class="oddelitko">|</span><p>Česká Lípa<span class="oddelitko">|</span>Klub Progres</p>
         </div>
         <div id="obsah">
             <ul>
             <li><h5><a href="../doma/KT-doma.php">kdy a kde</a></h5></li><span class="oddelitko">|</span>
             <li><h5><a href="../doma/KT-doma.php">rezervace</a></h5></li><span class="oddelitko">|</span>
             <li><h5><a href="../doma/KT-doma.php">citát</a></h5></li><span class="oddelitko">|</span>
             <li><h5><a href="../doma/KT-doma.php">plakát</a></h5></li><span class="oddelitko">|</span>
             <li><h5><a href="../doma/KT-doma.php">anotace</a></h5></li>
             <hr>
             <li><h5><a href="../doma/KT-doma.php">více o hře</a></h5></li><span class="oddelitko">|</span>
             <li><h5><a href="../doma/KT-doma.php">foto/mp3/video</a></h5></li>
             </ul>
         </div>
         </div><?php
      bok();
      ?><div id="vycet"><?php
    if (!isset($_GET["pod_podstr"])){
        $rok = getdate();
    }else{
        $rok["year"] = $_GET["pod_podstr"];
    }
    $dotaz = "SELECT predstaveni.nazev, predstaveni.datum, predstaveni.kdy, predstaveni.vyznam, mista.mesto AS kde
            FROM predstaveni
            LEFT JOIN mista ON predstaveni.kde = mista.cis
            WHERE datum
            BETWEEN '".mysql_real_escape_string($rok["year"])."-01-01'
            AND '".mysql_real_escape_string($rok["year"])."-12-31'
            AND datum ".$kdy." NOW()
            ORDER BY datum DESC";
    dotaz($dotaz);
    echo "<ul>";
    while($predstaveni = mysql_fetch_assoc($vysl)){
        echo "<li><a href=\"#\">";
        if(is_null($predstaveni["kdy"])){
            echo date("j. n. Y", strtotime($predstaveni["datum"]));
        }else{
            echo $predstaveni["kdy"];
        }
        echo "</a>";
        odd();
        echo $predstaveni["kde"]."<br>";
        echo $predstaveni["nazev"];
        if(!is_null($predstaveni["vyznam"])){
            echo " <em>(".$predstaveni["vyznam"].")</em>";
        }
        echo "</li>";
    }
    echo "</ul>";
      ?></div>
      
</div>