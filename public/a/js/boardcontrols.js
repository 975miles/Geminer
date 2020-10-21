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

    function getDistance(p1, p2) {
    return Math.sqrt(Math.pow(p2.x - p1.x, 2) + Math.pow(p2.y - p1.y, 2));
    }

    function getCenter(p1, p2) {
        return {
            x: (p1.x + p2.x) / 2,
            y: (p1.y + p2.y) / 2,
        };
    }
    var lastCenter = null;
    var lastDist = 0;

    stage.on('touchmove', function (e) {
        e.evt.preventDefault();
        var touch1 = e.evt.touches[0];
        var touch2 = e.evt.touches[1];

        if (touch1 && touch2) {
            if (stage.isDragging()) {
                stage.stopDrag();
            }

            var p1 = {
                x: touch1.clientX,
                y: touch1.clientY,
            };
            var p2 = {
                x: touch2.clientX,
                y: touch2.clientY,
            };

            if (!lastCenter) {
                lastCenter = getCenter(p1, p2);
                return;
            }
            var newCenter = getCenter(p1, p2);

            var dist = getDistance(p1, p2);

            if (!lastDist) {
            lastDist = dist;
            }

            var pointTo = {
                x: (newCenter.x - stage.x()) / stage.scaleX(),
                y: (newCenter.y - stage.y()) / stage.scaleX(),
            };

            var scale = stage.scaleX() * (dist / lastDist);

            stage.scaleX(scale);
            stage.scaleY(scale);

            // calculate new position of the stage
            var dx = newCenter.x - lastCenter.x;
            var dy = newCenter.y - lastCenter.y;

            var newPos = {
                x: newCenter.x - pointTo.x * scale + dx,
                y: newCenter.y - pointTo.y * scale + dy,
            };

            stage.position(newPos);
            stage.batchDraw();

            lastDist = dist;
            lastCenter = newCenter;
        }
    });

    stage.on('touchend', function () {
        lastDist = 0;
        lastCenter = null;
    });

    var mousePos = {x: 0, y: 0};
    stage.on('mousemove touchstart touchmove', e => {
        let transform = layer.getAbsoluteTransform().copy();
        transform.invert();
        let newPos = (transform.point(layer.getStage().getPointerPosition()));
        mousePos = {
            x: Math.floor(newPos.x),
            y: Math.floor(newPos.y)
        };
    });


    var layer = new Konva.Layer();

    const nativeCtx = layer.getContext()._context;
    nativeCtx.webkitImageSmoothingEnabled = false;
    nativeCtx.imageSmoothingEnabled = false;
    $(layer.getCanvas()._canvas).css("background-color", "#00000045");

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

    var gridLayer = new Konva.Layer();
    for (let i = 1; i < stageWidth; i++) {
        let coords = [[i, 0, i, stageHeight], [0, i, stageWidth, i]];
        for (let j of coords)
            gridLayer.add(new Konva.Line({
                points: j,
                stroke: 'black',
                strokeWidth: 0.1
            }));
    }
    stage.add(gridLayer);

    toggleGrid = () => (gridLayer.visible() ? gridLayer.hide() : gridLayer.show());

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
                if (mousePos.x != x || mousePos.y != y)
                    return;
                let text = tooltip.getText();
                if (!text.attrs.text.includes("user:")) {
                    $.getJSON(`/api/get/placement-info?board=${boardId}&x=${x}&y=${y}`, result => {
                        if (result == null)
                            return;
                        if (mousePos.x != x || mousePos.y != y)
                            return;
                        if (!text.attrs.text.includes("Placed by:")) {
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
        return $(`<option style="color: #${gem.colour}" value="${gem.id}">${gem.name} - you have ${(user[gem.id]/1000).toFixed(3)}px</option>`)
    }

    function placeGem(gem, x, y) {
        return new Promise((res, rej) => {
            if (boardGems[y][x] == gem)
                return;
            boardGems[y][x] = gem;
            layer.add(new Konva.Rect({
                x: x,
                y: y,
                width: 1,
                height: 1,
                fill: "#"+gemsInfo[gem].colour
            }));
            layer.draw();
        })
    }

    if (loggedIn) {
        await sortedGems;
        let select = $("#gemSelect");
        for (let gem of sortedGems) {
            select.append(getGemOption(gem));
        };

        stage.on('click tap', e => {
            if ((e.evt.type == "touchend" || e.evt.button == 0) && boardGems[mousePos.y] != null && boardGems[mousePos.y][mousePos.x] != null) {
                if (select.val() == "none")
                    return;
                let gemId = Number($("#gemSelect").val());
                let newGemAmount = Number(user[gemId]) - 1000;
                if (newGemAmount < 0)
                    return showInfo("You need 1px of a gem to place it.");
                let newMoneyAmount = Number(user.money) - placePrice;
                if (newMoneyAmount < 0)
                    return showInfo("You need "+displayMoney(placePrice)+" to place a gem.");
                let newLoc = {
                    x: mousePos.x,
                    y: mousePos.y
                }
                $.post("/api/do/put-on-board", {
                    board: boardId,
                    x: newLoc.x,
                    y: newLoc.y,
                    gem: gemId
                }, result => {
                    if (JSON.parse(result)) {
                        user[gemId] = newGemAmount;
                        user.money = newMoneyAmount;
                        $("[value="+gemId+"]").replaceWith(getGemOption(gemsInfo[gemId]));
                        select.val(String(gemId));
                        $("#financeDropdown").html(displayMoney(user.money));

                        placeGem(gemId, newLoc.x, newLoc.y);
                        tooltip.hide();
                        tooltipLayer.draw();
                    } else
                        showInfo("Something went wrong placing the gem.");
                });
            }
        });
    }

    function getNewGems() {
        if ($("#autoUpdateCheck").prop("checked"))
            $.getJSON("/api/get/board_placements?board="+boardId+"&from="+fromTime, data => {
                fromTime = data.time;
                for (let i of data.placements)
                    placeGem(i.gem, i.x, i.y);
                newGems();
            });
        else
            newGems();
    }

    const getNewGemsInterval = 4000;
    function newGems() {
        setTimeout(getNewGems, getNewGemsInterval);
    }
    newGems();

    
    fitStageIntoParentContainer();
    toggleGrid();
}
var toggleGrid;
createBoard();