<?php
require_once $_SERVER['DOCUMENT_ROOT']."/../start.php";
gen_top("Get in touch about GEMiner", "Ways to contact us");
?>

<h1>Contact</h1>

<h3>Found a bug?</h3>
<p>Make an issue for it at the <a href="<?=$issue_url?>">git repository</a>.</p>

<h3>Want to request a feature?</h3>
<p>Make an issue for it at the <a href="<?=$issue_url?>">git repository</a>.</p>

<h3>Want to get in touch about something else?</h3>
<p>Email us at <code><?=$contact_email?></code>.</p>

<?php gen_bottom() ?>