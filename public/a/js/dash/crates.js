function updateCrates() {
    inv.empty();
    if (myCrates.length > 0) {
        for (let i in myCrates) {
            let crate = myCrates[i]
            let rarity = rarities[crate.rarity];
            let item = $(`<button class="inventory-item" onclick="openCrate(${i})" data-toggle="tooltip" title="${rarity.name}">`);
            item.tooltip();
            item.append($(`<img class="inventory-item-image" src="/a/i/crate.png" style="filter:${rarity.filter}">`));
            inv.append(item);
        }
    } else {
        inv.append($("<p style=\"color: white\">You don't have any crates.</p>"));
    }
}

async function openCrate(index) {
    showInfo("Wait...", "Opening a crate...", false);
    await rarities;
    let crate = myCrates[index];
    $.post("", {
        id: crate.id
    }, async reward => {
        console.log(reward);
        reward = JSON.parse(reward);
        console.log(reward);
        if (reward == false)
            return showInfo("Something went wrong opening the crate. Maybe try again after reloading?");

        let crateRarity = rarities[crate.rarity];
        let rewardImg, rewardText;
        switch (reward.type) {
            case "modifier":
                rewardImg = `/a/i/pickaxe/modifier-items/${reward.data}.png`;
                rewardText = `The ${modifierDescriptions[reward.data][0].toLowerCase()} modifier`;
                break;

            case "money":
                rewardImg = "/a/i/coin.png";
                rewardText = displayMoney(reward.data);
                user.money += reward.data;
                $("#financeDropdown").html(displayMoney(user.money));
                break;

            case "gems":
                await gemsInfo;
                let gem = gemsInfo[reward.data.gem];
                rewardImg = `/a/i/gem/${gem.id}.png`;
                rewardText = `${reward.data.amount}px of ${await displayGem(gem.id)}${gem.name}`;
                $("#financeDropdown").html(displayMoney(user.money));
                break;

            case "pickaxe":
                let pickParts = {};
                for (let i of parts) {
                    pickParts[i] = new Part(i, reward.data[i]);
                    await pickParts[i].promise;
                }
                let pick = new Pickaxe(pickParts.handle, pickParts.head, pickParts.binding);
                rewardImg = await pick.render();
                rewardText = `A pickaxe named "${reward.data.name}"`;
                break;

            case "crate":
                let newCrateRarity = rarities[reward.data.rarity];
                rewardImg = `/a/i/crate.png" style="filter:${newCrateRarity.filter}`;
                rewardText = `A${newCrateRarity.name[0] == "E" ? "n" : ""} ${newCrateRarity.name.toLowerCase()} crate`;
                myCrates.push(reward.data);
                break;
        }
        let output = `<center><h2>${rewardText}!</h2></center><div class="swirly" style="filter:${crateRarity.filter}"></div><img class="swirly-contents" src="${rewardImg}">`;

        showInfo(output, `You opened a${crateRarity.name[0] == "E" ? "n" : ""} ${crateRarity.name.toLowerCase()} crate.`)

        myCrates.splice(index, 1);
        let itemElem = $(inv.children()[index]);
        itemElem.tooltip('dispose');
        itemElem.animate({width: 0, margin: -0.75, opacity: 0}, 500, () => {
            updateCrates();
        });
    });
}

var inv = $("#inventory");
var rarities = new Promise((res, rej) => $.getJSON("/a/data/crates/rarities.json", data => {
    rarities = data;
    updateCrates();
    res();
}));