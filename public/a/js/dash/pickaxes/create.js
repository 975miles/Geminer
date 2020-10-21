var newPickParts = {
    modifiers: []
};

for (let partName of parts)
    newPickParts[partName] = new Part(partName, -1)

async function visualisePick() {
    let partsNeeded = [];
    for (let partName of parts)
        if (newPickParts[partName].materialId == -1)
            partsNeeded.push(partName);
    
    let modifiers = [];
    for (let i of newPickParts.modifiers)
        modifiers.push(Number(myModifiers[i].type));
    modifiers.sort()

    let pickaxe = new Pickaxe(newPickParts.handle, newPickParts.head, newPickParts.binding, modifiers.join(","));
    $("#pickaxeImg").attr("src", await pickaxe.render());
    $("#pickaxeTitle").html(`${pickaxe.head.gem.name} pickaxe`);
    let pickaxeStats;
    let maxModifiers = modifiersAllowed();
    if (partsNeeded.length > 0) {
        $("#submitButton").attr("disabled", "");
        pickaxeStats = `This pickaxe isn't ready. It needs: ${partsNeeded.join(", ")}.`;
    } else {
        if (pickaxe.modifiers.length > maxModifiers)
            $("#submitButton").attr("disabled", "");
        else
            $("#submitButton").removeAttr("disabled");
        pickaxeStats = pickaxe.stats;
        pickaxeStats += `<br><br>This pickaxe is using ${pickaxe.modifiers.length}/${maxModifiers} modifier slots.`;
    }
    $("#pickaxeStats").html(pickaxeStats);
}


function modifiersAllowed() {
    let limit = baseModifiers;
    for (let i of newPickParts.modifiers)
        if (myModifiers[i].type == 4)
            limit += paperModifiers;
    return limit;
}

(async () => {
    await gemsInfo;


    for (let i of [...parts, "parts", "modifiers"]) {
        $("#partFilterSelect").append($(`<option>${i}</option>`));
    }

    if (myParts.length > 0 || myModifiers.length > 0) {
        for (let i in myParts) {
            let oldPart = myParts[i];
            let part = new Part(oldPart.type, Number(oldPart.material));
            await part.promise;
            part.id = oldPart.id;

            let partButton = $(`<button class="inventory-item part-parts part-${part.typeName}" index="${i}" onclick="selectPart($(this))" data-toggle="tooltip" data-html="true">`);
            let title = `${part.gem.name} ${part.typeName}<br>level: ${part.material.level}<br>weight: ${part.weight}`;
            for (let i of part.type.stats)
                title += `<br>${i}: ${part.material[i]}`;
            partButton.attr("title", title)
            partButton.tooltip();
            new Promise(async (res, rej) => {
                partButton.append($(`<img class="inventory-item-image pixels" src="${await part.render()}">`));
                res();
            });
            $("#inventory").append(partButton);
            
            myParts[i] = part;
        }

        for (let i in myModifiers) {
            let modifier = myModifiers[i];
            let desc = modifierDescriptions[modifier.type];
            let modifierButton = $(`<button class="inventory-item part-modifiers" index="${i}" onclick="selectPart($(this))" data-toggle="tooltip" data-html="true" title="${desc[0]}:<br>${desc[1]}">`);
            modifierButton.tooltip();
            modifierButton.append($(`<img class="inventory-item-image pixels" src="/a/i/pickaxe/modifier-items/${modifier.type}.png">`));
            $("#inventory").append(modifierButton);
        }
    } else
        $("#inventory").append('<p class="text-light">You don\'t have any pickaxe parts.</p>');
    visualisePick();
})();

async function selectPart(partElem) {
    let index = Number(partElem.attr("index"));
    if (partElem.hasClass("part-modifiers")) {
        let modifier = myModifiers[index];
        if (newPickParts.modifiers.includes(index)) {
            partElem.removeClass("selected");
            newPickParts.modifiers.splice(newPickParts.modifiers.indexOf(index), 1);
        } else {
            for (let i of newPickParts.modifiers)
                if (myModifiers[i].type == modifier.type) {
                    $(`.part-modifiers[index="${i}"]`).removeClass("selected")
                    newPickParts.modifiers.splice(newPickParts.modifiers.indexOf(i), 1);
                }
            partElem.addClass("selected");
            newPickParts.modifiers.push(index);
        }
        //if (partElem.hasClass("selected")) {
        //} else {
        //}
    } else {
        let part = myParts[index];
        if (partElem.hasClass("selected")) {
            partElem.removeClass("selected");
            newPickParts[part.typeName] = new Part(part.typeName, -1);
        } else {
            $(`.part-${part.typeName}`).removeClass("selected");
            partElem.addClass("selected");
            newPickParts[part.typeName] = part;
        }
    }
    visualisePick();
}

function filterParts(type) {
    let f;
    if (type == "none")
        f = (i, e) => $(e).show();
    else
        f = (i, e) => $(e)[$(e).hasClass(`part-${type}`) ? "show" : "hide"]();

    $("#inventory").children().each(f);
}

function submit() {
    let modifiers = [];
    for (let i of newPickParts.modifiers)
        modifiers.push(Number(myModifiers[i].id));

    let postData = {
        modifiers: modifiers.join(",")
    };

    for (let partName of parts)
        postData[partName] = newPickParts[partName].id;
    console.log(postData);
    $.post("", postData, result => {
        console.log(result);
        if (result == "true")
            window.location.replace("/dash/pickaxes");
        else
            showInfo("Something went wrong creating the pickaxe.");
    });
}