async function buyCrate(rarityId) {
    await rarities;
    rarity = rarities[rarityId];
    if (user.money < rarity.price)
        return showInfo(`You need ${displayMoney(rarity.price)} to buy that crate!`);
    $.post("/api/do/buy-crate", {
        rarity: rarityId,
    }, data => {
        crateId = JSON.parse(data);
        if (typeof crateId == "number") {
            myCrates.push({
                id: crateId,
                rarity: rarityId
            });
            updateCrates();
            user.money -= rarity.price;
            $("#financeDropdown").html(displayMoney(user.money));
            showInfo(`You bought a ${rarity.name} crate!`, "Success!");
        } else showInfo(crateId);
    });
}

(async () => {
    await rarities;
    for (let i in rarities) {
        let rarity = rarities[i];
        $("#crateShop").append($(`
            <div class="card">
                <img class="card-img-top" src="/a/i/crate.png" style="filter:${rarity.filter}">
                <div class="card-body">
                    <h5 class="card-title">${rarity.name} crate</h5>
                    <button class="btn btn-primary" onClick="buyCrate(${i})">${displayMoney(rarity.price, -2)}</button>
                </div>
            </div>
        `));
    }
})();