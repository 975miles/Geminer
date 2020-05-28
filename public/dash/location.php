<?php
require_once $_SERVER['DOCUMENT_ROOT']."/../start.php";
gen_top("The GEMiner page template's title", "this is the page description");

if (isset($_POST['location']) and $is_logged_in and $user['location'] != $_POST['location']) {
    require_once $_SERVER['DOCUMENT_ROOT']."/../consts/locations.php";
    if ($user['energy'] < $moving_energy_cost)
        show_info("You don't have enough <img src=\"/a/i/energy.png\" class=\"energy-icon\"> to do that.");
    else if (!array_key_exists($_POST['location'], $locations))
        show_info("That's not a valid location.");
    else {
        $dbh->prepare("UPDATE users SET location = ?, energy = energy - ? WHERE id = ?")
            ->execute([$_POST['location'], $moving_energy_cost, $user['id']]);
        redirect('/dash/mining.php');
    }
}
?>

<h1>Locations</h1>
<p>
    You're currently in <b><span id="currentLocation"><span class="spinner-border spinner-border-sm" role="status"><span class="sr-only"></span></span></span></b>.
    <a href="/dash/mining.php">Mine</a>
</p>

<p class="lead">
    It costs <?=$moving_energy_cost?> <img src="/a/i/energy.png" class="energy-icon"> to move to another location.
</p>

<style>
    .card {
        width: 18rem;
        margin-bottom: 1em;
        display: inline-block;
        vertical-align: top;
    }
</style>

<div id="locations">
    <div class="spinner-border" role="status"><span class="sr-only"></span></div>
</div>

<script>
    $.getJSON("/a/data/locations.json", async data => {
        await gemsInfo;
        //$("#locations");
        let div = $("<div></div>");
        for (i of data) {
            let locationCard = $('<div class="card">');
            locationCard.append($(`<img class="card-img-top" src="/a/i/location/${i.id}.png">`));
            locationCardBody = $('<div class="card-body"></div>');
            locationCardBody.append(`<h5 class="card-title">${i.name}</h5>`);
            let totalGemChance = 0;
            for (gem of i.gems) 
                totalGemChance += gem.chance;
            for (gem of i.gems) {
                locationCardBody.append('<hr>');
                locationCardBody.append(`<div class="colour-displayer colour-displayer-sm" style="background:#${gemsInfo[gem.id].colour}">`);
                let percentage = 100 * gem.chance / totalGemChance;
                locationCardBody.append(`<span>${gemsInfo[gem.id].name} (${percentage.toFixed(2)}%)</span>`);
            }
            locationCardBody.append('<hr>');
            if (loggedIn) {
                if (user.location != i.id)
                    locationCardBody.append(`<form action="" method="post"><button class="btn btn-primary" name="location" value=${i.id}>Go here</button></form>`);
                else
                    locationCardBody.append('<button class="btn btn-primary" disabled>You are here</button>')
            }
            
            locationCard.append(locationCardBody);
            div.append(locationCard);
        }
        
        $("#locations").html(div);

        $("#currentLocation").html(data[user.location].name);
    });
</script>

<?php gen_bottom(); ?>