var alloy;

async function amountDisplayer(gem, id) {
    return $(`<p><span id="${id}"></span>mpx of ${await displayGem(gem)}${gemsInfo[gem].name} (you have ${user[gem]}mpx)</p>`);
}

function updateAmounts() {
    let batchAmount = Number($("#batchAmount").val());
    for (let i in alloy.contents)
        $(`#content${i}Amount`).html(batchAmount*alloy.contents[i].amount);
    $("#produceAmount").html(batchAmount*alloy.produces);
}

if (!query.hasOwnProperty("alloy") || isNaN(Number(query.alloy)))
    showInfo("Alloy is not set. Go back to choose one.");
else
    $.getJSON("/a/data/alloys.json", async alloys => {
        await gemsInfo;
        alloy = alloys[Number(query.alloy)];
        if (alloy == undefined)
            showInfo("That alloy doesn't exist.");
        else {
            let usingDiv = $("#use");
            const chooseText = "(Choose a number of batches)";
            for (let i in alloy.contents)
                usingDiv.append(await amountDisplayer(alloy.contents[i].gem, `content${i}Amount`));

            $("#produce").append(await amountDisplayer(alloy.gem, "produceAmount"));
            updateAmounts();
        }
    });