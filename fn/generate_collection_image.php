<?php
function generate_collection_image($collection_id, $fill_page = false) {
    global $dbh;
    $sth = $dbh->prepare("SELECT data FROM collections WHERE id = ?");
    $sth->execute([$collection_id]);
    $results = $sth->fetchAll(PDO::FETCH_ASSOC);
    $collection_data = $results[0]['data'];
    ?>
<script>genCollectionImage($(document.currentScript), "<?=$collection_data?>"<?=$fill_page ? ", true" : ""?>);</script>
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