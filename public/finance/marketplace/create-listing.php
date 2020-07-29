<?php
require_once $_SERVER['DOCUMENT_ROOT']."/../start.php";
gen_top("Creating a marketplace listing...");
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
    $price = intval(floatval($_POST['price'])*100000);
    if ($amount < 1 or $amount > 1000000000 or $price < 0 or $price > 1000000000000) return;
    $gem = intval($_POST['gem']);
    if (!array_key_exists($gem, $all_gems)) return;
    switch ($type) {
        case 0:
            $price = floor($price / 1000);
            //fallthrough
        case 2:
            if ($real_gem_amounts[$gem] < $amount) return;
            $dbh->prepare("UPDATE users SET `$gem` = `$gem` - ? WHERE id = ?")
                ->execute([$amount, $user['id']]);
            break;

        case 1:
            $price = floor($price / 1000);
            if ($user['money'] < $price) return;
            $dbh->prepare("UPDATE users SET money = money - ? WHERE id = ?")
                ->execute([$price, $user['id']]);
            break;

        case 3:
            $full_price = ceil(($price / 1000) * $amount);
            if ($user['money'] < $full_price)
                return;
            $dbh->prepare("UPDATE users SET money = money - ? WHERE id = ?")
                ->execute([$full_price, $user['id']]);
            break;

        default:
            return;
    }
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
        <select class="form-control" id="listingType" name="type" onchange="updateForm()">
            <option value="none">Select a listing type</option>
            <option value="1">Buying</option>
            <option value="3">Collecting</option>
            <option value="0">Selling</option>
            <option value="2">Shop</option>
        </select>

        <label for="gemSelect">Gem:</label>
        <select class="form-control" id="gemSelect" name="gem" onchange="updateForm()">
            <option value="none">Select a gem</option>
        </select>

        <div id="listingNumbers" style="display: none;">
            <label id="gemAmountDescriber"></label>
            <div class="input-group mb-2" style="max-width: 200px">
                <input class="form-control" id="gemAmount" type="number" name="amount" min="0" max="1000000000" value="1" onchange="updateForm()">
                <div class="input-group-prepend">
                    <div class="input-group-text">mpx</div>
                </div>
            </div>

            <label id="priceDescriber"></label>
            <div class="input-group mb-2" style="max-width: 200px">
                <input class="form-control" id="price" type="number" name="price" min="0.01" max="10000000" value="0.01" step="0.01" onchange="updateForm()">
                <div class="input-group-prepend">
                    <div class="input-group-text"><?=$currency_symbol?></div>
                </div>
            </div>

            <p id="priceInfo"></p>
        </div>
    </form>
</div>

<button onclick="submit()" class="btn btn-lg btn-primary">Create</button>

<script>
    var gemAmounts = JSON.parse("<?=json_encode($real_gem_amounts)?>");

    $(document).ready(async ()=>{
        await sortedGems;
        for (gem of sortedGems)
            $("#gemSelect").append($(`<option style="color:#${gem.colour}" value=${gem.id}>${gem.name} - ${gemAmounts[gem.id]}mpx</option>`));
    });

    function fullPrice(up, string = true) {
        let price = (Math[up ? "ceil" : "floor"](Number($("#gemAmount").val())*(Number($("#price").val()*100)))/100)
        if (string)
            price = price.toFixed(2)+currencySymbol;
        return price
    }

    async function updateForm() {
        await gemsInfo;
        let listingType = Number($("#listingType").val());
        let gem = gemsInfo[$("#gemSelect").val()] || null;
        
        let type = [
            [2, "Amount of the gem to sell", "Price", "The gems you've specified will be taken from your account immediately after creating this listing."],
            [2, "Amount of the gem to buy", "What you'll pay for it", "The price you've specified will be taken from your account immediately after creating this listing."],
            [5, "Gem stock", "What you'll pay per mpx", `If all of your stock gets bought, you'll get at least ${fullPrice(false)}. The gem stock you've specified will be taken from your account immediately after creating this listing.`],
            [5, "Amount of the gem to collect", "Price per mpx", `You'll have to pay ${fullPrice(true)} to start collecting this gem. This amount will be deducted from your account immediately after creating this listing.`],
        ][listingType];

        if (type != null) {
            type[0] = 10**type[0];
            $("#price").attr("max", 1000000000 / type[0])
            $("#price").attr("min", 1 / type[0])
            $("#price").attr("step", 1 / type[0])
            $("#gemAmountDescriber").html(type[1]+":");
            $("#priceDescriber").html(type[2]+":");
            $("#priceInfo").html(type[3]);
            $("#listingNumbers").slideDown();
        } else
            $("#listingNumbers").slideUp();
    };

    function submit() {
        let values = {};
        $("form :input").each((i, e)=>{values[e.name] = $(e).val()})
        if (values.gem == "none")
            return showInfo("No gem has been selected");
        
        switch (Number(values.type)) {
            case 2:
            case 0:
                if (gemAmounts[values.gem] < Number(values.amount))
                    return showInfo("You don't have enough of that gem to sell that amount.");
                break;
            
            case 1:
                if (Number(user.money) < Number(values.price))
                    return showInfo("You don't have enough money to pay that.");
                break;

            case 3:
                if (Number(user.money) < fullPrice(true))
                    return showInfo("You don't have that much money.");
                break;

            default:
                return showInfo("No listing type has been selected");
        }
        
        $("form").submit();
    }
</script>

<?php gen_bottom(); ?>