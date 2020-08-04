async function createBoard() {
    Konva.hitOnDragEnabled = true;

    const stageWidth = boardGems[0].length;
    const stageHeight = boardGems.length;

    var stage = new Konva.Stage({
        container: 'board',
        width: stageWidth,
        height: stageHeight,
        draggable: true
    });

    function fitStageIntoParentContainer() {
        let containerWidth = document.querySelector('#stage-parent').offsetWidth;
        let containerHeight = window.innerHeight - 200;
        let containerSize = (containerHeight > containerWidth ? containerWidth : containerHeight)
        var scale = (containerHeight < containerWidth ? containerHeight : containerWidth) / stageWidth;

        stage.width(stageWidth * scale);
        stage.height(stageHeight * scale);
        stage.scale({ x: scale, y: scale });
        if (layer) {
            const nativeCtx = layer.getContext()._context;
            nativeCtx.webkitImageSmoothingEnabled = false;
            nativeCtx.mozImageSmoothingEnabled = false;
            nativeCtx.imageSmoothingEnabled = false;
        }
        stage.draw();
    }

    fitStageIntoParentContainer();
    window.addEventListener('resize', fitStageIntoParentContainer);

    const scaleBy = 0.97;
    stage.on('wheel', (e) => {
        e.evt.preventDefault();
        var oldScale = stage.scaleX();

        var pointer = stage.getPointerPosition();

        var mousePointTo = {
            x: (pointer.x - stage.x()) / oldScale,
            y: (pointer.y - stage.y()) / oldScale,
        };

        var newScale =
            e.evt.deltaY > 0 ? oldScale * scaleBy : oldScale / scaleBy;

        stage.scale({ x: newScale, y: newScale });

        var newPos = {
            x: pointer.x - mousePointTo.x * newScale,
            y: pointer.y - mousePointTo.y * newScale,
        };
        stage.position(newPos);
        stage.batchDraw();
    });

    var mousePos = {x: 0, y: 0};
    stage.on('mousemove', e => {
        let transform = layer.getAbsoluteTransform().copy();
        transform.invert();
        let newPos = (transform.point(layer.getStage().getPointerPosition()));
        mousePos = {
            x: Math.floor(newPos.x),
            y: Math.floor(newPos.y)
        };
    });


    var layer = new Konva.Layer();

    let img = new Image();
    img.onload = () => {
        layer.add(new Konva.Image({
            x: 0,
            y: 0,
            image: img,
            width: stageWidth,
            height: stageHeight,
        }));
        layer.draw();
    }
    img.src = await genCollectionImage(boardGems);
    stage.add(layer);
    const nativeCtx = layer.getContext()._context;
    nativeCtx.webkitImageSmoothingEnabled = false;
    nativeCtx.mozImageSmoothingEnabled = false;
    nativeCtx.imageSmoothingEnabled = false;
    $("#board").find("canvas").css("background-color", "#00000045");


    var tooltipLayer = new Konva.Layer();

    var tooltip = new Konva.Label({
        opacity: 0.6,
        visible: false,
        listening: false,
    });

    tooltip.add(new Konva.Tag({
        fill: 'black',
        pointerDirection: 'down',
        pointerWidth: 1,
        pointerHeight: 1,
        lineJoin: 'round',
        shadowColor: 'black',
    }));

    tooltip.add(new Konva.Text({
        text: '',
        fontFamily: 'Calibri',
        fontSize: 1,
        padding: 1,
        fill: 'white',
    }));

    tooltipLayer.add(tooltip);
    stage.add(tooltipLayer);

    stage.on('mouseover mousemove dragmove', function(evt) {
        if (evt.target === stage)
        return;
        var node = evt.target;
        if (boardGems[mousePos.y] == null || boardGems[mousePos.y][mousePos.x] == null)
            return;
        //console.log(gemsInfo[boardGems[mousePos.y][mousePos.x]]);
        if (node) {
        // update tooltip
            tooltip.position({
                x: mousePos.x+0.5,
                y: mousePos.y
            });
            tooltip
                .getText()
                .text(`${gemsInfo[boardGems[mousePos.y][mousePos.x]].name}\nx: ${mousePos.x}\ny: ${mousePos.y}`)
                .fontSize((18/stage.scaleX() < 0.6 ? 0.6 : 18/stage.scaleX()))
                .padding(5/stage.scaleX());
            tooltip
                .getTag()
                .pointerWidth(10/stage.scaleX())
                .pointerHeight(10/stage.scaleX());
            tooltip.show();
            tooltipLayer.batchDraw();
            setTimeout((x, y) => {
                if (boardGems[y][x] == -1)
                    return;
                let text = tooltip.getText();
                if (!text.attrs.text.includes("user:")) {
                    $.getJSON(`/api/get/placement-info?board=${boardId}&x=${x}&y=${y}`, result => {
                        if (result == null)
                            return;
                        if (mousePos.x != x || mousePos.y != y)
                            return;
                        if (!text.attrs.text.includes("Placed by:")) {
                            console.log(text.attrs.text+"\nPlaced by: "+result);
                            console.log(text);
                            text.text(text.attrs.text+"\nPlaced by: "+result);
                            tooltipLayer.draw();
                        }
                    })
                }
            }, 1000, mousePos.x, mousePos.y);
        }
    });

    stage.on('mouseout', function(evt) {
        tooltip.hide();
        tooltipLayer.draw();
    });



    function getGemOption(gem) {
        return $(`<option style="color: #${gem.colour}" value="${gem.id}">${gem.name} - you have ${(userGems[gem.id]/1000).toFixed(3)}px</option>`)
    }

    if (loggedIn) {
        await sortedGems;
        let select = $("#gemSelect");
        for (let gem of sortedGems) {
            select.append(getGemOption(gem));
        };

        stage.on('click', e => {
            if (e.evt.button == 0 && boardGems[mousePos.y] != null && boardGems[mousePos.y][mousePos.x] != null) {
                if (select.val() == "none")
                    return;
                let gemId = Number($("#gemSelect").val());
                let newGemAmount = Number(userGems[gemId]) - 1000;
                if (newGemAmount < 0)
                    return showInfo("You need 1px of a gem to place it.");
                let newMoneyAmount = Number(user.money) - placePrice;
                if (newMoneyAmount < 0)
                    return showInfo("You need "+displayMoney(placePrice)+" to place a gem.");
                $.post("/api/do/put-on-board.php", {
                    board: boardId,
                    x: mousePos.x,
                    y: mousePos.y,
                    gem: gemId
                }, result => {
                    if (JSON.parse(result)) {
                        userGems[gemId] = newGemAmount;
                        user.money = newMoneyAmount;
                        $("[value="+gemId+"]").replaceWith(getGemOption(gemsInfo[gemId]));
                        select.val(String(gemId));
                        $("#financeDropdown").html(displayMoney(user.money));

                        boardGems[mousePos.y][mousePos.x] = gemId;
                        layer.add(new Konva.Rect({
                            x: mousePos.x,
                            y: mousePos.y,
                            width: 1,
                            height: 1,
                            fill: "#"+gemsInfo[gemId].colour
                        }));
                        layer.draw();
                        tooltip.hide();
                        tooltipLayer.draw();
                    } else
                        showInfo("Something went wrong placing the gem.");
                });
            }
        });
    }
}
createBoard();