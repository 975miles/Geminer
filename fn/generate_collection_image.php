<?php
function generate_collection_image($collection_id, $fill_page = false) {
    global $dbh;
    $sth = $dbh->prepare("SELECT data FROM collections WHERE id = ?");
    $sth->execute([$collection_id]);
    $results = $sth->fetchAll(PDO::FETCH_ASSOC);
    $collection_data = $results[0]['data'];
    ?>
<script>
    (async scriptElement => {
        await gemsInfo;
        let collectionData = JSON.parse("<?=$collection_data?>");
        let height = collectionData.length;
        let width = collectionData[0].length;
        let canvas = $(`<canvas width=${width} height=${height}></canvas>`)[0];
        let context = canvas.getContext("2d");
        let imageData = context.createImageData(width, height);
        for (let y=0; y<height; y++) {
            for (let x=0; x<width; x++) {
                let pixelindex = (y * width + x) * 4;

                let tileColour = hexToRgb("#"+gemsInfo[collectionData[y][x]].colour);

                console.log(tileColour);

                imageData.data[pixelindex] = tileColour.r;
                imageData.data[pixelindex+1] = tileColour.g;
                imageData.data[pixelindex+2] = tileColour.b;
                imageData.data[pixelindex+3] = 255;
            }
        }
        context.putImageData(imageData, 0, 0);
        scriptElement.replaceWith(`<img class="collection-img<?=($fill_page ? " fill-page" : "")?>" src="${canvas.toDataURL()}">`);
    })($(document.currentScript));
</script>
    <?php
}

function get_pfp_collection_id($user_id) {
    global $dbh;
    $sth = $dbh->prepare("SELECT id FROM collections WHERE is_pfp = 1 AND by = ?");
    $sth->execute([$user_id]);
    $results = $sth->fetchAll(PDO::FETCH_ASSOC);
    return $results[0]['id'];
}

function generate_pfp_collection($user_id) {
    generate_collection_image(get_pfp_collection_id($user_id));
}