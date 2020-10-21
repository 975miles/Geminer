<?php
require_once $_SERVER['DOCUMENT_ROOT']."/../start.php";
gen_top();
foreach (scandir(".") as $filename)
    if(substr($filename, -4) == ".png")
        echo "<img src=".$filename.">";
gen_bottom();