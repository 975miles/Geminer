<?php
require_once $_SERVER['DOCUMENT_ROOT']."/../start.php";
require_auth();
require_once $_SERVER['DOCUMENT_ROOT']."/../fn/real_gem_amounts.php";
require_once $_SERVER['DOCUMENT_ROOT']."/../consts/gems.php";

$real_gem_amounts = get_real_gem_amounts();

$max_marketplace_listings = ($user['is_premium'] ? $max_marketplace_listings_premium : $max_marketplace_listings_free);
$sth = $dbh->prepare("SELECT COUNT(*) FROM marketplace_listings WHERE user = ?");
$sth->execute([$user['id']]);
if ($sth->fetchColumn() >= $max_marketplace_listings)
    throw_error("You've reached your maximum limit of concurrent marketplace listings ($max_marketplace_listings).");
else if (isset( $_POST['gem'], $_POST['amount'])) {
    $amount = intval($_POST['amount']);
    $gem = intval($_POST['gem']);
    if ($amount < 1 or $amount > 1000000000)
        show_info("You can't throw in that much!");
    else if (!array_key_exists($gem, $all_gems))
        show_info("That gem... doesn't exist..?");
    else if ($user[$gem] < $amount)
        show_info("You don't have enough of that gem");
    else {
        $profit = $all_gems[$gem]->value * $amount;
        $dbh->prepare("UPDATE users SET `$gem` = `$gem` - ?, money = money + ? WHERE id = ?")
            ->execute([$amount, $profit, $user['id']]);
        $user['money'] += $profit;
        show_info("You throw ${amount}mP of ".$all_gems[$gem]->name." into the volcano, and it inexplicably erupts ".display_money($profit)."!");
    }
}

gen_top("TVWIEMWYTGI", "The volcano which erupts money when you throw in gems");
?>

<h1>TVWIEMWYTGI</h1>
<p class="lead">The volcano which inexplicably erupts money when you throw gems in</p>
<div class="form-group">
    <form action="" method="post">
        <label for="gemSelect">Gem to throw:</label>
        <select class="form-control" id="gemSelect" name="gem" onchange="showProfit()">
            <option value="none">Select a gem</option>
        </select>

        <label>Amount of the gem:</label>
        <div class="input-group mb-2" style="max-width: 200px">
            <input class="form-control" id="gemAmountInput" type="number" name="amount" min="0" max="1000000000" value="1" onchange="showProfit()">
            <div class="input-group-prepend">
                <div class="input-group-text">mP</div>
            </div>
        </div>

        <button class="btn btn-lg btn-primary" type="submit">Throw</button>
    </form>
</div>

<p>Throwing in <span id="gemAmount"><i>(select...)</i></span>mP of <span id="gemName"><i>(select...)</i></span> would make the volcano erupt <span id="profitAmount"><i>(select...)</i></span>.</p>

<br>
<script>
    var gemAmounts = JSON.parse("<?=json_encode($real_gem_amounts)?>");

    async function showProfit() {
        await gemsInfo;
        let gem = $("#gemSelect").val();
        let amount = Number($("#gemAmountInput").val());
        if (gem != "none") {
            gem = gemsInfo[gem];
            $("#gemAmount").html(amount);
            $("#gemName").html(await displayGem(gem.id, "sm")+" "+gem.name);
            $("#profitAmount").html(displayMoney(gem.value * amount));
        }
    }

    $(document).ready(async ()=>{
        await sortedGems;
        for (gem of sortedGems)
            $("#gemSelect").append($(`<option style="color:#${gem.colour}" value=${gem.id}>${gem.name} (${displayMoney(gem.value)}/mP) - you have ${gemAmounts[gem.id]}mP</option>`));
    });
</script>

<?php gen_bottom(); ?>