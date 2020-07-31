<?php
require_once $_SERVER['DOCUMENT_ROOT']."/../start.php";
gen_top();
?>

<h1>Login needed</h1>
<p>You must be logged in to access this page. <a class="btn btn-primary" href="/log/in?redirect_back_to=<?=urlencode($_SERVER['REQUEST_URI'])?>">Login</a></p>

<?php gen_bottom(); ?>