<?php
require_once $_SERVER['DOCUMENT_ROOT']."/../start.php";
restrict_to("admin");
gen_top();
?>
<h1>Admin actions</h1>
<hr>
<a class="btn btn-primary" href="dbm/">Manage the database</a>
<hr>
<a class="btn btn-primary" href="announce">Make an announcement</a>
<hr>
<form class="form-inline "action="generate-premium-codes.php" method="get">
    <button class="btn btn-primary" type="submit">Generate</button>
    <input class="form-control" type="number" name="amount" min="1" max="1000" value="1">
    premium code(s)
</form>
<?php gen_bottom(); ?>