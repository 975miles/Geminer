<?php
require_once $_SERVER['DOCUMENT_ROOT']."/../start.php";
gen_top("Placing gems in the cast...");
require_auth();
require_once $_SERVER['DOCUMENT_ROOT']."/../consts/alloys.php";
require_once $_SERVER['DOCUMENT_ROOT']."/../fn/gem_displayer.php";

function create() {
    global $dbh;
    global $alloy_recipes;
    global $user;
    
    $amount = intval($_POST['amount']);
    $alloy = intval($_GET['alloy']);
    
    if (!array_key_exists($alloy, $alloy_recipes))
        return show_info("That alloy recipe doesn't exist.");
    else if ($amount > 1000000000 or $amount < 1)
        return show_info("You can't make that many batches.");
    else {
        $alloy_recipe = $alloy_recipes[$alloy];
        $gems_used = Array();

        foreach ($alloy_recipe->contents as $content) {
            $gem_amount = $content->amount * $amount;
            $gems_used[$content->gem] = $gem_amount;
            if ($user[$content->gem] < $gem_amount)
                return show_info("You don't have enough \"+await displayGem(".$content->gem.")+gemsInfo[".$content->gem."].name+\" to do this.");
        }

        foreach ($gems_used as $gem_using => $amount_using)
            $dbh->prepare("UPDATE users SET `".$gem_using."` = `".$gem_using."` - ? WHERE id = ?")
                ->execute([$amount_using, $user['id']]);

        $dbh->prepare("INSERT INTO alloy_casts (owner, gem, amount, time_started) VALUES (?, ?, ?, ?)")
            ->execute([$user['id'], $alloy_recipe->gem, ($alloy_recipe->produces * $amount), time()]);

        redirect("/dash/alloying");
    }
}

if (isset($_POST['amount'], $_GET['alloy'])) {
    create();
}
?>

<h1>Cast gems:</h1>
<form action="" method="post">
    <label>Batches:</label>
    <div class="input-group mb-2" style="max-width: 200px">
        <input class="form-control" id="batchAmount" type="number" name="amount" min="1" max="1000000000" value="1" onchange="updateAmounts()">
    </div>
    <p>This will use:</p>
    <div id="use"></div>
    <p>To produce:</p>
    <div id="produce"></div>
    <p>And will take <?=$alloy_cast_time_string?>.</p>
    <button class="btn btn-primary" type="submit">Start alloying for <?=display_money($alloy_cast_price)?>.</button>
</form>
<script src="/a/js/dash/alloying/create.js"></script>
<?php gen_bottom(); ?>