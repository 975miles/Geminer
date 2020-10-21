function showEnergyCost() {
    $("#miningEnergyCost").html($("#timesToMine").val() * miningEnergyCost)
}

function setXp() {
    var level = getLevel();
    $("#levelNum").html(level.level);
    currentPercentage = level.shiftsInCurrentLevel / level.shiftsToNextLevel * 100;
    $("#currentXp").css("width", currentPercentage + "%");
    $("#currentXp").html(level.shiftsInCurrentLevel + "xp");
    $("#xpLeft").css("width", (100 - currentPercentage) + "%");
    $("#xpLeft").html((level.shiftsToNextLevel - level.shiftsInCurrentLevel) + "xp to level " + (level.level + 1));
}

function setPickTooltip(pickElem) {
    let index = pickElem.index();
    let title;
    if (index == 0) {
        title = "Fists";
    } else {
        let pick = starredPicks[index];
        title = `${pick.name}<br><br>${pick.stats}`;
    }
    pickElem.attr("data-original-title", title);
    pickElem.tooltip();
}

$(document).ready(async ()=>{
    setXp();
    showEnergyCost();

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
            let gemAmountDisplay = `<p style="display:inline-block">${await displayGem(i.id)}${i.name}: <span class="gem_${i.id}_amount">${user[i.id]}</span>mpx&nbsp&nbsp&nbsp&nbsp&nbsp&nbsp</p>`;
            allGems.append(gemAmountDisplay);
            if (currentLocationGems.includes(i.id)) 
                currentMineGems.append(gemAmountDisplay);
        }
        currentMineGems.append("<hr>")
        currentMineGems.append("<p>All gems:</p>")
        $("#gems").html(div.html());
    });

    await materials;
    for (let i in starredPicks) {
        let pick = starredPicks[i];
        let newParts = {};
        for (let partName of parts) {
            newParts[partName] = new Part(partName, pick[partName]);
            await newParts[partName].promise;
        }
        starredPicks[i] = new Pickaxe(newParts.handle, newParts.head, newParts.binding, pick.modifiers, pick.uses);
        for (let attr of ["id", "name", "starred"])
            starredPicks[i][attr] = pick[attr];
    }
    starredPicks.unshift(null);
    for (let pick of starredPicks) {
        let item = $(`<button class="inventory-item" onclick="mine($(this))" data-toggle="tooltip" data-html="true" title="aaa">`);
        item.tooltip();
        item.append($(`<img class="inventory-item-image pixels" src="${pick == null ? "/a/i/fists.png" : await pick.render()}">`));
        $("#inventory").append(item);
        setPickTooltip(item);
    }
});

var crateRarities = new Promise((res, rej) => {
    $.getJSON("/a/data/crates/rarities.json", data => {
        crateRarities = data;
        res();
    });
});

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

async function mine(pickElem) {
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
        let index = pickElem.index();
        let pickID = (index == 0 ? 0 : starredPicks[index].id);

        $.get(
            {
                url: `/api/do/mine?pick=${pickID}&times=${timesToMine}`,
                success: async data => {
                    data = JSON.parse(data);
                    if (typeof data == "string")
                        return showInfo(data);

                    let output = "";

                    let pick = starredPicks[index];
                    if (pick != null) {
                        let pickElem = $($("#inventory").children()[index]);
                        pick.used += timesToMine;
                        if (pick.durabilityLeft <= 0) {
                            output += `Your pickaxe broke. RIP "${pick.name}".<hr>`;
                            starredPicks.splice(index, 1);
                            pickElem.tooltip('dispose');
                            pickElem.remove();
                        } else
                            setPickTooltip(pickElem);
                    }

                    output += `You mined ${data.veins} veins.<hr>`;
                    
                    if (data.timesHurt > 0) {
                        let energyLoss = data.timesHurt * hurtEnergyLoss
                        output += `You got hurt ${data.timesHurt} time${data.timesHurt == 1 ? "" : "s"} and lost ${energyLoss}<img class="energy-icon" src="/a/i/energy.png" alt="energy">.<hr>`;
                        user.energy -= energyLoss;
                    }
                    if (data.timesEnergised > 0) {
                        output += `Your energising grip energised you ${data.timesEnergised} times and you gained ${data.timesEnergised * energyGripEnergyGain}<img class="energy-icon" src="/a/i/energy.png" alt="energy">!<hr>`
                    }
                    if (data.crates.length > 0) {
                        await crateRarities;
                        output += `You found ${data.crates.length == 1 ? "a crate" : "crates"}!<br><a href="/dash/crates">`;
                        for (let i of data.crates) {
                            output += `<img src="/a/i/crate.png" style="filter:${crateRarities[i].filter}" width="45">`;
                        }
                        output += "</a><hr>";
                    }
                    for (i in data.gems) {
                        output += `${data.gems[i]}mpx of ${await displayGem(i, "sm")}${gemsInfo[i].name}<br>`
                        $(`.gem_${i}_amount`).each((index, e) => {
                            e = $(e);
                            $({ Counter: Number(e.html()) }).animate({ Counter: Number(e.html()) + data.gems[i] }, {
                                duration: 200,
                                easing: 'swing',
                                step: now => e.html(Math.ceil(now))
                            });
                        });
                    }

                    let previousLevel = getLevel().level;
                    user.shifts_completed += timesToMine;
                    let title = "Shift";
                    if (timesToMine == 1)
                        title += " #" + user.shifts_completed;
                    else
                        title += "s #" + (user.shifts_completed - (timesToMine - 1)) + "-" + user.shifts_completed
                    
                    showInfo(output, title);
                    user.energy -= miningEnergyCost * timesToMine;
                    $("#energyAmount").html(user.energy);
                    
                    let currentLevel = getLevel().level;
                    setXp();

                    while (previousLevel++ < currentLevel) {
                        $("#energyMax").html(Number($("#energyMax").html()) + maxEnergyPerLevel);
                        maxEnergy += maxEnergyPerLevel;
                        await $.getJSON("/a/data/level-rewards.json", levelRewards => {
                            let message = `You've reached level ${previousLevel}!`;
                            let reward = levelRewards[previousLevel];
                            if (reward != null)
                                message += "<hr>"+reward;
                            createToast(message, "Level up!");
                        });
                    }
                }
            }
        );
    }, i * slideDelay);
}