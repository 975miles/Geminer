<?php
require_once $_SERVER['DOCUMENT_ROOT']."/../start.php";
restrict_to("admin");
gen_top();

$available_chars = "ABCDEFGHJKLMNPQRSTUVWXYZ23456789";
$code_length = 64;

$available_char_amount = mb_strlen($available_chars);

if (isset($_GET['amount'])) {
    $amount = (int)($_GET['amount']);
    if ($amount < 1 or $amount > 1000) {
        ?>
        <p><code>amount</code> (query variable) must be 1 - 1000</p>
        <?php
        gen_bottom();
    }

    $codes = Array();
    for ($i = 0; $i < $amount; $i++) {
        $code = "";
        for ($k = 0; $k < $code_length; $k++) {
            $code .= $available_chars[mt_rand(0, $available_char_amount - 1)];
        }
        $codes[$i] = $code;
        $dbh->prepare("INSERT INTO codes (code_hash) VALUES (?)")->execute([hash("sha512", $code)]);
    }
    ?>

    <h1>Premium codes</h1>
    <i>The following codes have just been generated and added to the database - they are now usable by anyone who has them. SAVE THEM NOW - they've been encrypted, and if you decide you don't need these, you won't be able to find them.</i>
    <hr>
    <?php foreach($codes as $code) { ?>
    <code><?=$code?></code>
    <br>
<?php }
} else { ?>
<p>The query variable <code>amount</code> (how many codes will be generated) must be set.</p>
<?php }
gen_bottom(); ?>