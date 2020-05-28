<?php
require_once $_SERVER['DOCUMENT_ROOT']."/../start.php";
gen_top("The GEMiner mines", "You can mine gems here");
require_auth();
require_once $_SERVER['DOCUMENT_ROOT']."/../consts/locations.php";
?>

<h1>The Mine</h1>
<p>You're currently in <b><?=$locations[$user['location']]->name?></b>. <a href="/dash/location.php">Move</a></p>

<hr>
<p>
    <button class="btn btn-secondary" onclick="mine()">Mine</button>
    (costs <?=$mining_energy_cost?> 
    <img src="/a/i/energy.png" class="energy-icon">
    )
</p>
<hr>

<div class="container-fluid rounded bg-danger">
    <br>
    <h3>Your gems</h3>
    <hr>
    <div id="gems"><div class="spinner-border" role="status"><span class="sr-only"></span></div></div>
</div>

<script>
    $(document).ready(async ()=>
        $.getJSON("/a/data/locations.json", async locations => {
            await gemsInfo;
            var currentLocationGems = [];
            for (i of locations[user.location].gems)
                currentLocationGems.push(i.id);
            let div = $("<div>");
            let currentMineGems = $("<div>");
            div.append("<p>Gems available in the current mine:</p>")
            div.append(currentMineGems);
            div.append("<hr>")
            let otherGems = $("<div>");
            div.append("<p>Other gems:</p>")
            div.append(otherGems);
            for (i of Object.keys(user)) {
                if (!isNaN(Number(i))) {
                    let gemDiv = (currentLocationGems.includes(Number(i)) ? currentMineGems : otherGems);
                    $(gemDiv).append(`<div class="colour-displayer colour-displayer-sm" style="background:#${gemsInfo[i].colour}">`)
                    let gem_amount_p = $(`<p>${gemsInfo[i].name}: <span id="gem_${gemsInfo[i].id}_amount">${user[i]}</span><span id="gem_${gemsInfo[i].id}_unit">mP</p>"`);
                    $(gemDiv).append(gem_amount_p);
                }
            }
            $("#gems").html(div);
        })
    );

    async function mine() {
        await gemsInfo;
        $.get(
            {
                url: "/api/do/mine.php",
                success: data => {
                    data = JSON.parse(data);
                    if (typeof data == "string")
                        return showInfo(data);
                    
                    let output = "";
                    for (i of data) {
                        output += `You got ${i.amount}mP of ${gemsInfo[i.gem].name}!<br>`
                        let amountDisplay = $(`#gem_${i.gem}_amount`);
                        amountDisplay.html(Number(amountDisplay.html()) + i.amount);
                    }
                    showInfo(output, "Yields!");
                    $("#energyAmount").html(Number($("#energyAmount").html()) - miningEnergyCost);
                    //$("body").append();
                }
            }
        );
    }
</script>

<?php gen_bottom(); ?>