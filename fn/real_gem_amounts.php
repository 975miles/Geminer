<?php
require_once __DIR__."/../consts/gems.php";
function get_real_gem_amounts($exclude = 0) {
    global $dbh;
    global $all_gems;
    global $user;
    $sth = $dbh->prepare("SELECT data FROM collections WHERE by = ? AND id != ?");
    $sth->execute([$user['id'], $exclude]);
    $results = $sth->fetchAll(PDO::FETCH_ASSOC);

    $real_gem_amounts = Array();
    foreach (array_keys($all_gems) as $gem_id) {
        $real_gem_amounts[$gem_id] = intval($user[$gem_id]);
    }

    foreach ($results as $collection) {
        $collection_data = json_decode($collection['data'], true);
        foreach ($collection_data as $row)
            foreach ($row as $tile)
                if ($tile != -1)
                    $real_gem_amounts[$tile] -= 1000;
    }

    return $real_gem_amounts;
}