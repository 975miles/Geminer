<?php
require_once $_SERVER['DOCUMENT_ROOT']."/../start.php";
require_once $_SERVER['DOCUMENT_ROOT']."/../fn/generate_redemption_code.php";
restrict_to("admin");

if (isset($_POST['number'], $_POST['amount'], $_POST['gemId'], $_POST['crateType'], $_POST['product'])) {
    $number = intval($_POST['number']);
    $amount = intval($_POST['amount']);
    if ($number < 1 or $number > 1000)
        die("number (query variable) must be 1 - 1000");

    $code_data = Array(
        "product" => $_POST['product'],
        "amount" => $amount,
    );

    switch ($_POST['product']) {
        case "premium":
            break;
        
        case "energy":
            break;
        
        case "money":
            break;
        
        case "gem":
            $code_data['gem'] = intval($_POST['gemId']);
            break;
        
        case "crate":
            $code_data['crate'] = intval($_POST['crateType']);
            break;

        default:
            die("That's not a valid product.");
    }

    $data_json = json_encode($code_data);

    $codes = Array();
    for ($i = 0; $i < $number; $i++) {
        $codes[$i] = generate_redemption_code();
        $dbh->prepare("INSERT INTO codes (code_hash, data) VALUES (?, ?)")->execute([hash("sha512", $codes[$i]), $data_json]);
    }

    
    gen_top();
    ?>

    <h1>Redemption codes</h1>
    <i>The following codes have just been generated and added to the database - they are now usable by anyone who has them. SAVE THEM NOW - they've been encrypted, and if you decide you don't need these, you won't be able to find them.</i>
    <hr>
    <?php foreach ($codes as $code) { ?>
    <code><?=$code?></code>
    <br>
<?php }
} else
    die("a post variable is missing");
gen_bottom(); ?>