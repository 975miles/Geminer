<?php
require_once $_SERVER['DOCUMENT_ROOT']."/../start.php";
$now = time();
echo $now - ($now % $energy_regeneration_interval) + $energy_regeneration_interval;