<?php gen_top("Geminer - restricted page"); ?>

<h1>No.</h1>
<h3>This page is restricted.</h3>
<p>
    You must 
    <?php if ($group == "admin") { ?>
    have admin rights
    <?php } else if ($group == "premium") { ?>
    have a premium account
    <?php } ?>
     to access this page.<br>
    If you already do, are you logged in?
</p>

<?php gen_bottom(); ?>