<?php 
include '../vklad/fce.php';
include '../vklad/spoj.php';
$dotaz = "SELECT cis, datum
FROM `novinky` 
WHERE datum NOT LIKE '%-%'
ORDER BY cis ASC
LIMIT 0 , 300";
dotaz($dotaz);
while($datum = mysql_fetch_array($vysl)){
//	echo $datum["datum"];
//	echo " => ";
	echo (substr($datum["datum"],-4));

	if(strpos(substr($datum["datum"],-9,2), ' ')){
//		echo "-1cif";
		echo "-0";
		echo (substr($datum["datum"],-7,1));
	}else{
//		echo "-2cif";
		echo "-";
		echo (substr($datum["datum"],-8,2));
	}
	
	if(strpos(substr($datum["datum"],-12,2), '.')){
		echo "-0";
		echo (substr($datum["datum"],-12,1));
	}else{
		echo "-";
		echo (substr($datum["datum"],-12,2));
	}
//	echo " :";
//	echo $datum["cis"];
	echo "<br>";
}
include '../vklad/odpoj.php';
?>