<?php
require_once $_SERVER['DOCUMENT_ROOT']."/../start.php";
require_once $_SERVER['DOCUMENT_ROOT']."/../fn/generate_redemption_code.php";
require_auth();

if (isset($_POST['code'])) {
    $code_hash = hash("sha512", trim($_POST['code']));
    $sth = $dbh->prepare("SELECT * FROM codes WHERE code_hash = ?");
    $sth->execute([$code_hash]);
    $result = $sth->fetch();
    if ($result) {
        $code_data = json_decode($result['data']);
        switch ($code_data->product) {
            case "premium":
                if ($user['is_premium'])
                    die("You're already premium. The premium code was not used.");

                $dbh->prepare("UPDATE users SET is_premium = 1, date_became_premium = ? WHERE id = ?")->execute([time(), $user['id']]);

                $codes_on_upgrade = [
                    '{"product":"money","amount":10000}',
                    '{"product":"money","amount":25000}',
                    '{"product":"money","amount":50000}',
                    '{"product":"money","amount":100000}',
                    '{"product":"money","amount":500000}',
                    '{"product":"energy","amount":50}',
                    '{"product":"energy","amount":100}',
                    '{"product":"energy","amount":150}',
                    '{"product":"energy","amount":200}',
                    '{"product":"energy","amount":250}',
                    '{"product":"crate","crate":0,"amount":30}',
                    '{"product":"crate","crate":1,"amount":20}',
                    '{"product":"crate","crate":2,"amount":10}',
                    '{"product":"crate","crate":3,"amount":6}',
                    '{"product":"crate","crate":4,"amount":3}',
                ];

                foreach ($codes_on_upgrade as $code_creating) {
                    $code = generate_redemption_code();
                    $dbh->prepare("INSERT INTO codes (code_hash, data) VALUES (?, ?)")->execute([hash("sha512", $code), $code_creating]);
                    $dbh->prepare("INSERT INTO owned_codes (owner, code_id, code) VALUES (?, ?, ?)")->execute([$user['id'], $dbh->lastInsertId(), $code]);
                }

                break;
            
            case "energy":
                $dbh->prepare("UPDATE users SET energy = energy + ? WHERE id = ?")->execute([$code_data->amount, $user['id']]);
                $user['energy'] += $code_data->amount;
                break;
            
            case "money":
                $dbh->prepare("UPDATE users SET money = money + ? WHERE id = ?")->execute([$code_data->amount, $user['id']]);
                $user['money'] += $code_data->amount;
                break;
            
            case "gem":
                $dbh->prepare("UPDATE users SET `".$code_data->gem."` = `".$code_data->gem."` + ? WHERE id = ?")->execute([$code_data->amount, $user['id']]);
                break;
            
            case "crate":
                for ($i = 0; $i < $code_data->amount; $i++)
                    $dbh->prepare("INSERT INTO crates (rarity, owner) VALUES (?, ?)")->execute([$code_data->crate, $user['id']]);
                break;

            default:
                die();
        }

        $dbh->prepare("DELETE FROM codes WHERE id = ?")->execute([$result['id']]);
        $dbh->prepare("DELETE FROM owned_codes WHERE code_id = ?")->execute([$result['id']]);
        
        switch ($code_data->product) {
            case "premium":
                redirect("/premium/welcome");
                break;
            
            case "energy":
                show_info("You regained ".$code_data->amount."<img src=\\\"/a/i/energy.png\\\" class=\\\"energy-icon\\\" alt=\\\"energy\\\">", "You've been re-energised!");
                break;
            
            case "money":
                show_info("You've been paid ".display_money($code_data->amount)."!", "You're a little bit richer!");
                break;
            
            case "gem":
                require_once $_SERVER['DOCUMENT_ROOT']."/../consts/gems.php";
                show_info("You got ".$code_data->amount."mpx of \"+await displayGem(".$code_data->gem.")+\"".$all_gems[$code_data->gem]->name."!", "You got gems!");
                break;
            
            case "crate":
                require_once $_SERVER['DOCUMENT_ROOT']."/../consts/crate_rarities.php";
                show_info("You got ".$code_data->amount." ".strtolower($crate_rarities[$code_data->crate]->name)." crates!", "You got crates!");
                break;
        }
    } else
        show_info("Your redemption code was invalid.", "Unknown code");
}

gen_top("Code redemption", "Redeem a Geminer code.");
?>

<h1>Redeem</h1>
<p><a href="/premium">Need a Geminer premium code?</a></p>
<?php
if ($user['is_premium']) {
    $sth = $dbh->prepare("SELECT COUNT(1) FROM owned_codes WHERE owner = ?");
    $sth->execute([$user['id']]);
    if ($sth->fetchColumn() > 0) {
        ?>
            <p>You have some of <a href="/premium/my-codes">your codes</a> left!</p>
        <?php
    }
}
?>
<form action="" method="post" autocomplete="off">
    <input type="text" name="code" class="form-control" placeholder="Enter your redemption code here" value="<?php if (isset($_GET['code'])) echo $_GET['code']; else if (isset($_POST['code'])) echo $_POST['code']; ?>">
    <button type="submit" class="btn btn-lg btn-primary">Submit</button>
</form>

<?php gen_bottom(); ?>