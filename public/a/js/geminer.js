function loadGems(includeEmptite=true) {
    return new Promise((res, rej) => 
        $.getJSON("/a/data/gems.json", data => {
            if (includeEmptite)
                data["-1"] = {
                    "id": -1,
                    "name": "emptite",
                    "type": "image",
                    "colour": "f2e3ce"
                };
            res(data)
        })
    );
}

var gemsInfo = new Promise(async res => {
    let tempGemsInfo = await loadGems();
    gemsInfo = tempGemsInfo;
    res();
});

var sortedGems = new Promise(async res => {
    await gemsInfo;
    let tempGemsInfo = [...gemsInfo];
    sortedGems = tempGemsInfo.sort((a, b) => (a.name < b.name) ? -1 : (a.name > b.name) ? 1 : 0);
    res();
});

function showInfo(error="Unknown error occurred.", title="Error!", removable = true) {
    $("#infoBoxTitle").html(title);
    $("#infoBoxContents").html(error);
    footerExists = $("#infoBox").find(".modal-footer").length > 0 ? true : false;
    let backdrop;
    if (removable) {
        backdrop = "true";
        if (!footerExists)
            $("#infoBox").find(".modal-content").append($(`<div class="modal-footer"><button class="btn btn-secondary" type="button" data-dismiss="modal">Dismiss (esc)</button></div>`));
    } else {
        backdrop = "static";
        if (footerExists)
            $("#infoBox").find(".modal-footer").remove();
    }
    //$("#infoBox").attr("data-backdrop", backdrop);
    $("#infoBox").modal(); //show modal on page
    $("#infoBox").data("bs.modal")._config.backdrop = backdrop;
}

//stole this from https://stackoverflow.com/questions/5623838/rgb-to-hex-and-hex-to-rgb oops
function hexToRgb(hex) {
    var result = /^#?([a-f\d]{2})([a-f\d]{2})([a-f\d]{2})$/i.exec(hex);
    return result ? {
        r: parseInt(result[1], 16),
        g: parseInt(result[2], 16),
        b: parseInt(result[3], 16)
    } : null;
}

function replaceScript(scriptTag, html) {
    scriptTag.replaceWith(html);
};

async function genCollectionImage(data, fillPage = false) {
    await gemsInfo;
    data = JSON.parse(data);
    let height = data.length;
    let width = data[0].length;
    let canvas = $(`<canvas width=${width} height=${height}></canvas>`)[0];
    let context = canvas.getContext("2d");
    let imageData = context.createImageData(width, height);
    for (let y=0; y<height; y++) {
        for (let x=0; x<width; x++) {
            let pixelindex = (y * width + x) * 4;

            let tileColour = hexToRgb("#"+gemsInfo[data[y][x]].colour);

            imageData.data[pixelindex] = tileColour.r;
            imageData.data[pixelindex+1] = tileColour.g;
            imageData.data[pixelindex+2] = tileColour.b;
            imageData.data[pixelindex+3] = 255;
        }
    }
    context.putImageData(imageData, 0, 0);
    return canvas.toDataURL();
}

async function placeCollectionImage(scriptTag, data, fillPage = false) {
    replaceScript(scriptTag, `<img class="collection-img${(fillPage ? " fill-page" : "")}" src="${await genCollectionImage(data, fillPage)}">`);
}

async function displayGem(gemId, size=null) {
    await gemsInfo;
    let gem = gemsInfo[gemId];

    let elemClass = "gem-displayer";
    if (size != null)
        elemClass += " gem-displayer-" + size;

    let style = `background-color: #${gem.colour};background-image: url(/a/i/gem/${gem.id}.png);`;

    return `<span class="${elemClass}" style="${style}"></span>`;
}

function displayMoney(amount, extraDecimals = 0, round = "round") {
    let mult = 10 ** (extraDecimals);
    return String(Math[round](amount*mult)/(mult*100))+currencySymbol;
}

$.extend({
    getQueryParameters : function(str = window.location.search) {
        return str.replace(/(^\?)/,'').split("&").map(function(n){return n = n.split("="),this[n[0]] = n[1],this}.bind({}))[0];
    }  
});

$(document).ready(()=>{
    $(".unix-ts").each((i, e)=>{
        $(e).html(new Date($(e).html()*1000).toDateString());
    });
    $("tooltipcontent").each((i, e) => {
        $("#"+$(e).attr("for")).attr("data-original-title", e.innerHTML);
        $(e).remove();
    });
    if (window.history.replaceState)
        window.history.replaceState(null, null, window.location.href);
    $("[data-toggle=\"tooltip\"]").tooltip(); //enable bootstrap tooltips
});