<?php
function redirect($url) {
    header("Location: $url");
    die();
}

function require_auth() {
    global $is_logged_in;
    if ($is_logged_in)
        return;
    else
        redirect("/log/in?redirect_back_to=".$SERVER['REQUEST_URI']);
}

function redirect_back($fallback = "/") {
    if (isset($GET['redirect_back_to']))
        redirect($GET['redirect_back_to']);
    else
        redirect($fallback);
}

function get_user_by_id($user_id) {
    global $dbh;
    $sth = $dbh->prepare("SELECT * FROM users WHERE id = ?");
    $sth->execute([$user_id]);
    $results = $sth->fetchAll(PDO::FETCH_ASSOC);
    if (count($results) == 0)
        return null;
    else
        return $results[0];
}

function gen_top($title = "GEMiner", $description = null) {
    global $is_logged_in;
    global $user;
    $page_info = Array (
        'title' => $title,
        'description' => $description,
    );
    require_once $_SERVER['DOCUMENT_ROOT']."/../page/top.php";
}

function gen_bottom() {
    require_once $_SERVER['DOCUMENT_ROOT']."/../page/bottom.php";
}