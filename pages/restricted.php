<?php gen_top("GEMiner - restricted page"); ?>

<h1>No.</h1>
<h3>This page is restricted.</h3>
<p>
    You must 
    <?php if ($group == "admin") { ?>
    be an admin
    <?php } else if ($group == "premium") { ?>
    have a premium account
    <?php } ?>
     to access this page.
</p>

<?php gen_bottom(); ?>