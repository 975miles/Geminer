<?php
require_once $_SERVER['DOCUMENT_ROOT']."/../start.php";
require_auth();
require_once $_SERVER['DOCUMENT_ROOT']."/../consts/collection-types.php";
gen_top("Creating a collection...");

$max_collection_amount = ($user['is_premium'] ? $collection_storage_limit_premium : $collection_storage_limit_free);

$sth = $dbh->prepare("SELECT COUNT(1) FROM collections WHERE by = ?");
$sth->execute([$user['id']]);
$collection_amount = $sth->fetchColumn();
if ($collection_amount >= $max_collection_amount)
    throw_error(($user['is_premium'] ? "You can't have more than $max_collection_amount collections." : "You've reached your maximum of $max_collection_amount collections. Want more? <a href='/premium'>Upgrade to premium</a>."));

if (isset($_POST['name']) and isset($_POST['type'])) {
    $_POST['type'] = intval($_POST['type']);
    if (mb_strlen($_POST['name']) > $max_collection_name_length)
        show_info("Collection name must be at most $max_collection_name_length characters.");
    else if (mb_strlen($_POST['name']) <= 0)
        show_info("Collection name can't be empty.");
    else if (!array_key_exists($_POST['type'], $collection_types))
        show_info($_POST['type']." isn't a valid collection size.");
    else if ($_POST['type'] == 3)
        show_info("You can't create new giant collections.");
    else {
        $empty_collection = Array();
        $collection_type = $collection_types[$_POST['type']];
        for ($row = 0; $row < $collection_type->height; $row++) {
            $collection_row = Array();
            for ($column = 0; $column < $collection_type->width; $column++) {
                array_push($collection_row, -1);
            }
            array_push($empty_collection, $collection_row);
        }

        $dbh->prepare("INSERT INTO collections (type, name, by, created_at, data) VALUES (?, ?, ?, ?, ?)")
            ->execute([$_POST['type'], $_POST['name'], $user['id'], time(), json_encode($empty_collection)]);

        redirect("/collection/edit?id=".dechex($dbh->lastInsertId()));
    }
}
?>

<h1>Create a new collection</h1>
<p>You currently have <?=$collection_amount?> collection<?=$collection_amount == 1 ? "" : "s"?> out of your maximum of <?=$max_collection_amount?>.<?php if (!$user['is_premium']) { ?> <a href="/premium">Upgrade to premium</a> to increase this maximum.<?php } ?></p>
<form action="" method="post">
    <label>Collection name:</label>
    <input type="text" name="name" class="form-control" value="My Epic Collection" maxlength=<?=$max_collection_name_length?>>
    <label>Size:</label>
    <select class="form-control" name="type" id="typeSelect"></select>
    <button type="submit" class="btn btn-lg btn-primary">Create</button>
</form>

<hr>
<h2>Sizes</h2>
<div id="sizes"></div>

<script>
const types = [
    {
        name: "Square (standard)",
        img: "GEAAABhAQMAAAD8yF3gAAAABlBMVEUAAAD///+l2Z/dAAAAHklEQVQ4y2NABbnbbsMRGm9U5ajKUZWjKkdV4lYJAJeV/hAX6SbbAAAAAElFTkSuQmCC"
    },
    {
        name: "Landscape (standard)",
        img: "HkAAABJAQMAAAAjThunAAAABlBMVEUAAAD///+l2Z/dAAAAHElEQVQ4y2PAALnbbiMjTIFRLaNaRrWMahncWgC1Qt4wOmc7+QAAAABJRU5ErkJggg=="
    },
    {
        name: "Portrait (standard)",
        img: "EkAAAB5AQMAAABPx54yAAAABlBMVEUAAAD///+l2Z/dAAAAHUlEQVQ4y2NAArnbbkMQMnNUwaiCUQWjCkYVwAAAxijeMAQTpZkAAAAASUVORK5CYII="
    },
    null,
    {
        name: "Square (small)",
        img: "DEAAAAxAQMAAABJUtNfAAAABlBMVEUAAAD///+l2Z/dAAAAFUlEQVQY02OAgdxtt4EITo+K01ccAMl3f4G4gNG+AAAAAElFTkSuQmCC"
    },
    {
        name: "Square (tiny)",
        img: "BkAAAAZAQMAAAD+JxcgAAAABlBMVEUAAAD///+l2Z/dAAAAEUlEQVQI12MAg9xttyEEfbkA0iYf4RW25YUAAAAASUVORK5CYII="
    },
    {
        name: "Landscape (small)",
        img: "D0AAAAlAQMAAADLKXNcAAAABlBMVEUAAAD///+l2Z/dAAAAFklEQVQY02OAg9xtt0FoA5wxKkWqFACP3npZnfsXEAAAAABJRU5ErkJggg=="
    },
    {
        name: "Portrait (small)",
        img: "CUAAAA9AQMAAAAQ1TK2AAAABlBMVEUAAAD///+l2Z/dAAAAFklEQVQY02OAgNxtt3M3QMhREdqJAADJFnw5S7/64AAAAABJRU5ErkJggg=="
    },
]
$.getJSON("/a/data/collection-types.json", sizes => {
    for (let i = 0; i < types.length; i++) {
        let type = types[i];
        let size = sizes[i];
        if (type == null)
            continue;

        let typeDisplay = `${type.name} - ${size.width}px*${size.height}px`;
        $("#sizes").append($(`<div><label>${typeDisplay}</label><br><img src="data:image/png;base64,iVBORw0KGgoAAAANSUhEUgAAA${type.img}"><br><br></div>`));
        $("#typeSelect").append($(`<option value="${i}">${typeDisplay}</option>`));
    }
});
</script>

<?php gen_bottom(); ?>