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
        redirect("/log/in?redirect_back_to=".$_SERVER['REQUEST_URI']);
}

function restrict_to($group = "admin") {
    global $dbh;
    global $is_logged_in;
    global $user;
    if ($is_logged_in) {
        if ($user["is_$group"])
            return; //The user has access
    }

    require_once $_SERVER['DOCUMENT_ROOT']."/../pages/restricted.php";
    die();
}

function redirect_back($fallback = "/") {
    if (isset($_GET['redirect_back_to']))
        redirect($_GET['redirect_back_to']);
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

function valid_username($username) {
    global $valid_username_characters;
    foreach(str_split($username) as $username_character)
        if(strpos($valid_username_characters, $username_character) === false)
            return false;

    return true;
}

function user_exists($username) {
    global $dbh;
    global $user;

    $sth = $dbh->prepare("SELECT * FROM users WHERE name = ?");
    $sth->execute([$username]);
    $results = $sth->fetchAll(PDO::FETCH_ASSOC);
    if (count($results) == 0)
        return false;
    else
        return true;
}

function show_info($error="Unknown error occurred.", $title="Error!") {
    ?>
    <script>
        $(document).ready(()=>showInfo("<?=$error?>", "<?=$title?>")); 
    </script>
    <?php
}

function gen_top($title = "GEMiner", $description = null) {
    global $is_logged_in;
    global $user;
    global $repo_url;
    $page_info = Array (
        'title' => $title,
        'description' => $description,
    );
    require_once $_SERVER['DOCUMENT_ROOT']."/../template/top.php";
}

function gen_bottom() {
    require_once $_SERVER['DOCUMENT_ROOT']."/../template/bottom.php";
    die();
}