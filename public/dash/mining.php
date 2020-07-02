<?php
require_once $_SERVER['DOCUMENT_ROOT']."/../start.php";
gen_top("The Geminer mines", "You can mine gems here");
require_auth();
require_once $_SERVER['DOCUMENT_ROOT']."/../consts/locations.php";
?>

<h1>The Mine</h1>
<p>You're currently in <b><?=$locations[$user['location']]->name?></b>. <a href="/dash/location.php">Move</a></p>

<hr>
<div style="display: inline-block">
    <button class="btn btn-secondary" onclick="mine();">Mine</button>
    <input id="timesToMine" style="max-width:4em" type="number" name="amount" min="1" max="1000000000" value="1" onchange="$('#miningEnergyCost').html(this.value * miningEnergyCost)">
    <p style="display: inline-block">times (costs <span id="miningEnergyCost"><?=$mining_energy_cost?></span> <img src="/a/i/energy.png" class="energy-icon">)</p>
</div>
<hr>

<div class="container-fluid rounded border border-dark">
    <br>
    <h3>Your gems</h3>
    <hr>
    <div id="gems"><div class="spinner-border" role="status"><span class="sr-only"></span></div></div>
    <hr>
    <a class="btn btn-primary" href="/finance/sell-gems.php">Sell gems</a>
</div>

<script>
    $(document).ready(async ()=>
        $.getJSON("/a/data/locations.json", async locations => {
            await sortedGems;
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
            for (i of sortedGems) {
                let gemDiv = (currentLocationGems.includes(i.id) ? currentMineGems : otherGems);
                $(gemDiv).append(`<p${(currentLocationGems.includes(i.id) ? "" : " style=\"display:inline-block\"")}>${await displayGem(i.id)}${i.name}: <span id="gem_${i.id}_amount">${user[i.id]}</span><span id="gem_${i.id}_unit">mP</span>&nbsp&nbsp&nbsp&nbsp&nbsp&nbsp</p>`);
            }
            $("#gems").html(div);
        })
    );

    async function mine() {
        await gemsInfo;
        let timesToMine = Number($("#timesToMine").val());
        $.get(
            {
                url: "/api/do/mine.php?times="+timesToMine,
                success: async data => {
                    data = JSON.parse(data);
                    if (typeof data == "string")
                        return showInfo(data);
                    
                    let output = "";
                    for (i in data) {
                        output += `You got ${data[i]}mP of ${await displayGem(i, "sm")}${gemsInfo[i].name}!<br>`
                        let amountDisplay = $(`#gem_${i}_amount`);
                        amountDisplay.html(Number(amountDisplay.html()) + data[i]);
                    }
                    user.shifts_completed += timesToMine;
                    let title = "Yields of shift";
                    if (timesToMine == 1)
                        title += " #" + user.shifts_completed;
                    else
                        title += "s #" + (user.shifts_completed - (timesToMine - 1)) + "-" + user.shifts_completed
                    showInfo(output, title);
                    user.energy -= miningEnergyCost * timesToMine;
                    $("#energyAmount").html(user.energy);
                    //$("body").append();
                }
            }
        );
    }
</script>

<?php gen_bottom(); ?>