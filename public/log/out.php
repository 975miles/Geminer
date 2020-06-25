<?php
require_once $_SERVER['DOCUMENT_ROOT']."/../start.php";
session_destroy();
redirect_back();