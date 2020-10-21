$.getJSON("/a/data/alloys.json", async alloys => {
    await gemsInfo;
    let div = $("#alloys")
    for (let i in alloys) {
        let alloy = alloys[i];
        let alloyCard = $('<div class="card">');
        alloyCard.append($(`<img class="card-img-top" src="/a/i/gem/${alloy.gem}.png">`));
        alloyCardBody = $('<div class="card-body"></div>');
        alloyCardBody.append(`<h5 class="card-title">${await displayGem(alloy.gem)}${gemsInfo[alloy.gem].name} (${alloy.produces}mpx)</h5>`);
        for (j in alloy.contents) {
            let gem = alloy.contents[j];
            alloyCardBody.append('<hr style="margin:0">');
            alloyCardBody.append(`<span>${j != 0 ? "+" : "="}${await displayGem(gem.gem, "sm")}${gemsInfo[gem.gem].name} (${gem.amount}mpx)</span>`);
        }
        
        alloyCardBody.append('<hr style="margin-top:0">');
        alloyCardBody.append(`<a class="btn btn-primary" href="create?alloy=${i}">Choose</a>`)
        
        alloyCard.append(alloyCardBody);
        div.append(alloyCard);
    }
});