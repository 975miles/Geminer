function changeType() {
    let type = $("#productType").val();
    $("#productAmount")[type == "premium" ? "hide" : "show"]();
    $("#gemSelect")[type == "gem" ? "show" : "hide"]();
    $("#crateTypeSelect")[type == "crate" ? "show" : "hide"]();
}

changeType();

(async () => {
    await sortedGems;
    for (let gem of sortedGems)
        $("#gemSelect").append($(`<option value=${gem.id} style="color: #${gem.colour}">${gem.name}</option>`));
})();

$.getJSON("/a/data/crates/rarities.json", rarities => {
    for (let i in rarities)
        $("#crateTypeSelect").append($(`<option value=${i}>${rarities[i].name}</option>`));
});