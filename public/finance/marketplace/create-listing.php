<?php
require_once $_SERVER['DOCUMENT_ROOT']."/../start.php";
gen_top("Geminer - creating a marketplace listing...");
require_auth();
require_once $_SERVER['DOCUMENT_ROOT']."/../fn/real_gem_amounts.php";
require_once $_SERVER['DOCUMENT_ROOT']."/../consts/gems.php";

$real_gem_amounts = get_real_gem_amounts();

function validate() {
    global $dbh;
    global $all_gems;
    global $real_gem_amounts;
    global $user;
    $type = intval($_POST['type']);
    $amount = intval($_POST['amount']);
    $price = intval(floatval($_POST['price'])*100);
    if ($amount < 1 or $amount > 1000000000 or $price < 0 or $price > 1000000000) return;
    $gem = intval($_POST['gem']);
    if (!array_key_exists($gem, $all_gems)) return;
    if ($type == 0) { //if selling
        if ($real_gem_amounts[$gem] < $amount) return;
        $dbh->prepare("UPDATE users SET `$gem` = `$gem` - ? WHERE id = ?")
            ->execute([$amount, $user['id']]);
    } else if ($type == 1) { //if buying
        if ($user['money'] < $price) return;
        $dbh->prepare("UPDATE users SET money = money - ? WHERE id = ?")
            ->execute([$price, $user['id']]);
    } else return;
    $sth = $dbh->prepare("INSERT INTO marketplace_listings (user, type, gem, amount, price) VALUES (?, ?, ?, ?, ?)");
    $sth->execute([$user['id'], $type, $gem, $amount, $price]);
    redirect("/finance/marketplace/listing?id=".dechex($dbh->lastInsertId()));
}

$max_marketplace_listings = ($user['is_premium'] ? $max_marketplace_listings_premium : $max_marketplace_listings_free);
$sth = $dbh->prepare("SELECT COUNT(*) FROM marketplace_listings WHERE user = ?");
$sth->execute([$user['id']]);
if ($sth->fetchColumn() >= $max_marketplace_listings)
    throw_error("You've reached your maximum limit of concurrent marketplace listings ($max_marketplace_listings).");
else if (isset($_POST['type'], $_POST['gem'], $_POST['amount'], $_POST['price'])) {
    validate();
    //run this if validation doesnt exit i.e. the info is incorrect
    show_info("Something went wrong, try again?");
}
?>

<h1>Create a marketplace listing</h1>
<div class="form-group">
    <form action="" method="post">
        <label>Type of listing:</label>
        <select class="form-control" name="type">
            <option value="0">Selling</option>
            <option value="1">Buying</option>
        </select>

        <label for="gemSelect">Gem:</label>
        <select class="form-control" id="gemSelect" name="gem">
            <option value="none">Select a gem</option>
        </select>

        <label>Amount of the gem:</label>
        <div class="input-group mb-2" style="max-width: 200px">
            <input class="form-control" type="number" name="amount" min="0" max="1000000000" value="1">
            <div class="input-group-prepend">
                <div class="input-group-text">mP</div>
            </div>
        </div>

        <label>Price:</label>
        <div class="input-group mb-2" style="max-width: 200px">
            <input class="form-control" type="number" name="price" min="0.01" max="10000000" value="0.01" step="0.01">
            <div class="input-group-prepend">
                <div class="input-group-text"><?=$currency_symbol?></div>
            </div>
        </div>
    </form>
</div>

<br>
<button onclick="submit()" class="btn btn-lg btn-primary">Create</button>
<p>
    If you're buying gems, the money will immediately be taken out of your account.
    <br>
    If you're selling gems, the gems will immediately be taken out of your account.
</p>

<script>
    var gemAmounts = JSON.parse("<?=json_encode($real_gem_amounts)?>");

    $(document).ready(async ()=>{
        await sortedGems;
        for (gem of sortedGems)
            $("#gemSelect").append($(`<option style="color:#${gem.colour}" value=${gem.id}>${gem.name} - you have ${gemAmounts[gem.id]}mP</option>`));
    });

    function submit() {
        let values = {};
        $("form :input").each((i, e)=>{values[e.name] = $(e).val()});
        console.log(values);
        if (values.gem == "none")
            return showInfo("No gem has been selected");
        
        if (values.type == "0") { //if selling
            if (gemAmounts[values.gem] < Number(values.amount))
                return showInfo("You don't have enough of that gem to sell that amount.");
        } else { //if buying
            if (user.money < Number(values.price))
                return showInfo("You don't have enough money to pay that.");
        }
        
        $("form").submit();
    }
</script>

<?php gen_bottom(); ?>