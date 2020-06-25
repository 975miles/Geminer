<?php
require_once $_SERVER['DOCUMENT_ROOT']."/../start.php";
gen_top("Get in touch about Geminer", "Ways to contact us");
?>

<h1>Contact</h1>
<p>If you want to report a bug or request a feature, <a href="<?=$issue_url?>">make an issue at the git repository</a>. Alternatively, you can just edit <a href="<?=$repo_url?>">the code</a> yourself and make a pull request if you think you could fix/make the bug/feature yourself.</p>
<p>Email us at <a href="mailto:<?=$contact_email?>"><?=$contact_email?></a> for any account related issues or other enquiries.</p>

<?php gen_bottom() ?>