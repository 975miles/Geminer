(async () => {
    await gemsInfo;

    let gimpPalette = "GIMP Palette\nName: Geminer\n#\n";
    let pdnPalette = ";paint.net Palette File\n;Palette Name: Geminer\n;Colors: 256\n";
    let colours = [];
    let colourDiv = $("#colours");
    for (let i = 0; i < 16; i++) {

        let row = [];
        for (let k = -1; k < 15; k++) {
            let g = gemsInfo[(i*16)+k];
            row.push(g.id);

            let c = hexToRgb("#"+g.colour);

            colourDiv.append($(`<span>${await displayGem(g.id)}${await displayGem(g.id, null, false)}${g.name}: #${g.colour} (${c.r} ${c.g} ${c.b})<br></span>`));

            for (let j in c)
                c[j] = String(c[j]).padStart(3, " ");
            gimpPalette += `${c.r} ${c.g} ${c.b} ${g.name}\n`;
            pdnPalette += `FF${g.colour}\n`;
        }
        colours.push(row);
    }
    $("#gimpPalette").attr("href", "data:text/plain,"+encodeURIComponent(gimpPalette));
    $("#pdnPalette").attr("href", "data:text/plain,"+encodeURIComponent(pdnPalette));
    $("#paletteImage").attr("src", await genCollectionImage(colours));
})();