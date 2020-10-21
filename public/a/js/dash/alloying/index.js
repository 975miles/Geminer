(async () => {
    await gemsInfo;
    let div = $("#casts");

    if (casts.length > 0) {
        for (let i of casts) {
            for (let j of ["amount", "gem", "time_started"])
                i[j] = Number(i[j]);

            let cardWrapper = $("<div class=\"card\">");
            div.append(cardWrapper);
            let card = $("<div class=\"card-body\">");
            cardWrapper.append(card);
            card.append($(`<h6 class="card-title">${i.amount}mpx of ${await displayGem(i.gem)}${gemsInfo[i.gem].name}</h6>`));
            card.append($("<hr>"));
            card.append($(`<div><p class="claim"><span class="spinner-border spinner-border-sm" role="status"><span class="sr-only">Loading...</span></span></p> <button class="btn btn-outline-primary" onclick="speedUp(${i.id})">Speed up for ${speedUpPrice} <img src="/a/i/energy.png" class="energy-icon" alt="energy"></button></div>`));
        }

        function updateTimes() {
            div.children().each((i, e) => {
                $(e).find(".claim").each((i2, e2) => {
                    let secondsLeft = (casts[i].time_started + castTime) - Math.round(Date.now() / 1000);
                    if (secondsLeft >= 0) {
                        $(e2).html(`Ready in ${timeLeft(secondsLeft)}.`);
                    } else {
                        $(e2).parent().replaceWith(`<button class="btn btn-success" onclick="claim(${casts[i].id}, ${i}, this)">Claim</button>`);
                    }
                    timeLeft(secondsLeft);
                });
            });
        }

        setInterval(updateTimes, 1000);
    } else
        div.append($("<p>You don't have any casts.</p>"));
})();

function claim(id, index, btn) {
    $.post("claim", {cast: id}, async success => {
        if (success == "true") {
            let cast = casts[index];
            showInfo(`You got ${cast.amount}mpx of ${await displayGem(cast.gem)}${gemsInfo[cast.gem].name}!`, "Alloying finished!");
            $(btn).replaceWith($("<p>Claimed!</p>"));
        } else
            showInfo("Something went wrong claiming the alloy.");
    });
}

function speedUp(id) {
    $.post("speed-up", {cast: id}, success => {
        if (success == "true") {
            window.location.reload();
        } else
            showInfo(success);
    });
}

//casts[0].time_started = (Math.round(Date.now() / 1000)) - castTime + 5;