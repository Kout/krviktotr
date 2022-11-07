<?php
if (isset ($_GET['str'])){
$str = $_GET['str'];
}
else{
$str = "svaz";
}
$vklad = "vklad/hlava.php";
include $vklad;
?>
<ul>
<?php
$vklad = "vklad/pod/pod-";
$vklad .= $str;
$vklad .= ".php";
include $vklad;
?>
</ul>		
</div>
<div id="stat">
<?php
$vklad = "vklad/stat/stat-";
$vklad .= $str;
$vklad .= ".php";
include $vklad;
?>	
</div>
<?php
include "vklad/pata.php";
?>