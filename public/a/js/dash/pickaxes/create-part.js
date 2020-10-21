async function visualisePart() {
    let part = await new Part($("#partType").val(), Number($("#material").val()));
    $("#partImg").attr("src", await part.render());
    $("#partCost").html(`Creating this will use ${part.type.price/1000}px of ${await displayGem(part.materialId)}${gemsInfo[part.materialId].name} - you have ${user[part.materialId]/1000}px.`);
    $("#partInfo").html("");
    $("#partInfo").append(part.stats);
}

(async () => {
    for (let part of parts)
        $("#partType").append($(`<option>${part}</option>`));
    await sortedGems;
    for (let gem of sortedGems)
        $("#material").append($(`<option style="color:#${gem.colour}" value="${gem.id}">${gem.name}</option>`));
    await partTypes;
    await materials;
    visualisePart();
})();