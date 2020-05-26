function loadGems(includeEmptite=true) {
    return new Promise((res, rej) => 
        $.getJSON("/a/data/gems.json", data => {
            if (includeEmptite)
                data["-1"] = {
                    "id": -1,
                    "name": "emptite",
                    "colour": "f7f6cd",
                    "chance": 5,
                    "quantity": 100
                };
            res(data)
        })
    );
}

var gemsInfo = new Promise(async (res, rej) => {
    let tempGemsInfo = await loadGems();
    res();
    gemsInfo = tempGemsInfo;
});

function showInfo(error="Unknown error occurred.", title="Error!") {
    $("#infoBoxTitle").html(title);
    $("#infoBoxContents").html(error);
    $("#infoBox").modal(); //show modal on page
}

$(document).ready(()=>{
    $("[data-toggle=\"tooltip\"]").tooltip(); //enable bootstrap tooltips
    $(".unix-ts").each((i, e)=>{
        $(e).html(new Date($(e).html()*1000).toDateString());
    });
    $("tooltipcontent").each((i, e) => {
        $("#"+$(e).attr("for")).attr("data-original-title", e.innerHTML);
        $(e).remove();
    });
});

//stole this from https://stackoverflow.com/questions/5623838/rgb-to-hex-and-hex-to-rgb oops
function hexToRgb(hex) {
    var result = /^#?([a-f\d]{2})([a-f\d]{2})([a-f\d]{2})$/i.exec(hex);
    return result ? {
        r: parseInt(result[1], 16),
        g: parseInt(result[2], 16),
        b: parseInt(result[3], 16)
    } : null;
}