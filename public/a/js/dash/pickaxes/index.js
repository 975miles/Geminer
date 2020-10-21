(async () => {
    await materials;
    for (let i in myPicks) {
        let pick = myPicks[i];
        let newParts = {};
        for (let partName of parts) {
            newParts[partName] = new Part(partName, pick[partName]);
            await newParts[partName].promise;
        }
        myPicks[i] = new Pickaxe(newParts.handle, newParts.head, newParts.binding, pick.modifiers, pick.uses);
        for (let attr of ["id", "name", "starred"])
            myPicks[i][attr] = pick[attr];
    }
    for (let i in myPicks) {
        let pick = myPicks[i];
        let item = $(`<button class="inventory-item${pick.starred == "1" ? " starred" : ""}" onclick="displayPick(${i})" data-toggle="tooltip" data-html="true" title="${pick.name}">`);
        item.tooltip();
        item.append($(`<img class="inventory-item-image pixels" src="${await pick.render()}">`));
        $("#inventory").append(item);
    }
})();

async function displayPick(index) {
    let pick = myPicks[index];
    let info = `<img class="pixels" width="200" src="${await pick.render()}">
    <p>${pick.stats}<br><br>Parts:`;
    for (let partName of parts)
        info += `<br><a data-toggle="tooltip" data-html="true" title="<img class='pixels' src='${await pick[partName].render()}' width='39'><br>${pick[partName].stats}">${pick[partName].gem.name} ${partName}</a>`;
    info += `<br><br>Modifiers:`;
    if (pick.modifiers.length > 0)
        for (let i of pick.modifiers)
            info += `<br><a data-toggle="tooltip" data-html="true" title="<img class='pixels' src='/a/i/pickaxe/modifier-items/${i}.png' width='39'><br>${modifierDescriptions[i][1]}">${modifierDescriptions[i][0]}</a>`;
    else
        info += "<br>none";
    info += `<p>
<hr>
<button class="btn btn-sm btn-primary" id="toggleStarred" onclick="star(${index})">${pick.starred == "1" ? "Uns" : "S"}tar this pickaxe</button>
<br><br>
<div class="input-group">
<input id="newPickName" class="form-control" value="${pick.name}" maxlength="${maxNameLength}"></input>
<div class="input-group-append">
<button class="btn btn-outline-secondary" onclick="rename(${index})">Rename</button>
</div>
</div>`;
    await showInfo(info, pick.name);
    $("#infoBoxContents").find("a").tooltip();
}

function toggleShowed() {
    $(".inventory-item").hide();
    if ($("#starredOnlyCheck").prop("checked")) {
        $(".starred").show();
    } else {
        $(".inventory-item").show();
    }
}

function star(index) {
    let pick = myPicks[index];
    console.log(index);
    $.post("star", {
        id: pick.id
    }, result => {
        console.log(result);
        if (result == "true") {
            createToast(`${pick.starred == "1" ? "Uns" : "S"}tarred &quot;${pick.name}&quot;.`);
            pick.starred = pick.starred == "1" ? "0" : "1";
            let pickElem = $($("#inventory").children()[index]);
            if (pickElem.hasClass("starred")) {
                pickElem.removeClass("starred");
                if ($("#starredOnlyCheck").prop("checked"))
                    pickElem.hide();
            } else {
                pickElem.addClass("starred");
                if ($("#starredOnlyCheck").prop("checked"))
                    pickElem.show();
            }
            $("#toggleStarred").html(`${pick.starred == "1" ? "Uns" : "S"}tar this pickaxe`);
        } else
            createToast("Could not star the pickaxe."); 
    });
}

function rename(index) {
    let pick = myPicks[index];
    let oldName = pick.name;
    let newName = $("#newPickName").val();
    $.post("rename", {
        id: pick.id,
        name: newName
    }, result => {
        switch (result) {
            case "true":
                createToast(`Pickaxe &quot;${oldName}&quot; renamed to &quot;${newName}&quot;.`);
                pick.name = newName;
                $("#infoBoxTitle").html(newName);
                let inventoryItem = $($("#inventory").children()[index]);
                inventoryItem.attr("data-original-title", newName);
                inventoryItem.tooltip();
                break;

            case "false":
                createToast("Something went wrong renaming the pickaxe.");
                break;

            default:
                createToast(result);
        }
    });
}