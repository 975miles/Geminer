<?php
require_once $_SERVER['DOCUMENT_ROOT']."/../start.php";
require_auth();
require_once $_SERVER['DOCUMENT_ROOT']."/../consts/gems.php";

if (isset($_POST['id'])) {
    $id = intval($_POST['id']);
    $sth = $dbh->prepare("SELECT * FROM collections WHERE id = ?");
    $sth->execute([$id]);
    $collection = $sth->fetch();
    if ($collection and $collection['by'] == $user['id'] and !$collection['is_pfp']) {
        $collection_data = json_decode($collection['data']);
        foreach ($collection_data as $row)
            foreach ($row as $tile)
                if ($tile != -1)
                    $user[$tile] += 1000;
        
        foreach ($all_gems as $gem => $gem_data)
            $dbh->prepare("UPDATE users SET `".$gem."` = ? WHERE id = ?;")
                ->execute([$user[$gem], $user['id']]);
        
        $dbh->prepare("DELETE FROM collections WHERE id = ?")->execute([$id]);
        $dbh->prepare("DELETE FROM collection_ratings WHERE collection = ?")->execute([$id]);
        redirect("/collection/view?id=".dechex($id));
    } else
        throw_error("you don't have permission to delete that collection");
} else 
    throw_error("id of collection to delete is not set");