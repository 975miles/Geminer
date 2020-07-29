<?php
require_once $_SERVER['DOCUMENT_ROOT']."/../start.php";
require_once $_SERVER['DOCUMENT_ROOT']."/../consts/locations.php";
gen_top($is_logged_in ? $locations[$user['location']]->name : "The mines", "You can mine gems here");
require_auth();
require_once $_SERVER['DOCUMENT_ROOT']."/../fn/real_gem_amounts.php";
?>

<h1>The Mine</h1>
<p>You're currently in <b><?=$locations[$user['location']]->name?></b>. <a href="/dash/location">Move</a></p>

<hr>
<div style="display: inline-block">
    <button class="btn btn-secondary" onclick="mine();">Mine</button>
    <input id="timesToMine" style="max-width:4em" type="number" name="amount" min="1" max="1000000000" value="1" onchange="$('#miningEnergyCost').html(this.value * miningEnergyCost)">
    <p style="display: inline-block">times (costs <span id="miningEnergyCost"><?=$mining_energy_cost?></span> <img src="/a/i/energy.png" class="energy-icon">)</p>
    <div class="form-check form-check-inline">
        <input class="form-check-input" type="checkbox" value="true" id="animationCheck" checked>
        <label class="form-check-label" for="animationCheck">
            Play animation
        </label>
    </div>
</div>
<hr>

<div class="container-fluid rounded border border-dark">
    <br>
    <h3>Your gems</h3>
    <hr>
    <div id="gems"><div class="spinner-border" role="status"><span class="sr-only"></span></div></div>
    <hr>
    <a class="btn btn-primary" href="/finance/sell-gems" style="margin-bottom: 0.8em">Sell gems</a>
</div>

<script>
    var gemAmounts = JSON.parse("<?=json_encode(get_real_gem_amounts())?>");

    $(document).ready(async ()=>
        $.getJSON("/a/data/locations.json", async locations => {
            await sortedGems;
            var currentLocationGems = [];
            for (i of locations[user.location].gems)
                currentLocationGems.push(i.id);
            let div = $("<div>");
            let currentMineGems = $("<div id=\"currentMineGems\">");
            currentMineGems.append("<p><button class=\"btn btn-sm btn-secondary\" onclick=\"$('#currentMineGems').slideUp()\">Hide</button>   Gems available in the current mine:</p>")
            div.append(currentMineGems);
            let allGems = $("<div>");
            div.append(allGems);
            for (i of sortedGems) {
                let gemAmountDisplay = `<p style="display:inline-block">${await displayGem(i.id)}${i.name}: <span class="gem_${i.id}_amount">${gemAmounts[i.id]}</span>mpx&nbsp&nbsp&nbsp&nbsp&nbsp&nbsp</p>`;
                allGems.append(gemAmountDisplay);
                if (currentLocationGems.includes(i.id)) 
                    currentMineGems.append(gemAmountDisplay);
            }
            currentMineGems.append("<hr>")
            currentMineGems.append("<p>All gems:</p>")
            $("#gems").html(div.html());
        })
    );

    const miningSlides = [
        {
            img: "/a/i/minecart1.png",
            title: "Travelling to mine..."
        },
        {
            img: "/a/i/loading.png",
            title: "Mining..."
        },
        {
            img: "/a/i/minecart2.png",
            title: "Returning from mine..."
        }
    ];
    const defaultSlideDelay = 1500;
    function showSlide(slideNum) {
        let slide = miningSlides[slideNum];
        showInfo("<img src=\""+slide.img+"\" style=\"width:5em\">", slide.title, false);
    }

    async function mine() {
        await gemsInfo;
        let timesToMine = Number($("#timesToMine").val());
        if (Number($('#miningEnergyCost').html()) > user.energy)
            return showInfo("You don't have enough energy to do that.", "Hey, wait a minute!");
        
        let slideDelay = $("#animationCheck").prop("checked") ? defaultSlideDelay : 0;
        let i;
        for (i = 0; i < miningSlides.length; i++) {
            setTimeout(showSlide.bind(null, i), i * slideDelay);
        }
        
        setTimeout(()=>{
            $.get(
                {
                    url: "/api/do/mine.php?times="+timesToMine,
                    success: async data => {
                        data = JSON.parse(data);
                        if (typeof data == "string")
                            return showInfo(data);
                        
                        let output = "";
                        for (i in data) {
                            output += `${data[i]}mpx of ${await displayGem(i, "sm")}${gemsInfo[i].name}<br>`
                            $(`.gem_${i}_amount`).each((index, e) => {
                                e = $(e);
                                $({ Counter: Number(e.html()) }).animate({ Counter: Number(e.html()) + data[i] }, {
                                    duration: 200,
                                    easing: 'swing',
                                    step: now => e.html(Math.ceil(now))
                                });
                            });
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
                    }
                }
            );
        }, i * slideDelay);
    }
</script>

<?php gen_bottom(); ?>