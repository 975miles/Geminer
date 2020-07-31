<?php
$function_dir = __DIR__."/fn";
require_once "$function_dir/place_collection_image.php";
require_once $_SERVER['DOCUMENT_ROOT']."/../consts/cosmetics/tag_styles.php";
require_once $_SERVER['DOCUMENT_ROOT']."/../consts/cosmetics/tag_fonts.php";
require_once $_SERVER['DOCUMENT_ROOT']."/../consts/cosmetics/backgrounds.php";
require_once $_SERVER['DOCUMENT_ROOT']."/../consts/cosmetics/navbarbgs.php";

function redirect($url) {
    gen_top();
    ?>
<script id="redirect">
    var redirectURL = "<?=$url?>";
    window.location.replace(redirectURL);
</script>
    <?php
    gen_bottom();
}

function require_auth() {
    global $is_logged_in;
    if ($is_logged_in)
        return;
    else
        require_once $_SERVER['DOCUMENT_ROOT']."/../pages/authneeded.php";   
}

function restrict_to($group = "admin") {
    global $dbh;
    global $is_logged_in;
    global $user;
    
    if ($is_logged_in and $user["is_$group"])
        return; //The user has access

    require_once $_SERVER['DOCUMENT_ROOT']."/../pages/restricted.php";
    die();
}

function redirect_back($fallback = "/") {
    if (isset($_GET['redirect_back_to']))
        redirect(urldecode($_GET['redirect_back_to']));
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

    $sth = $dbh->prepare("SELECT * FROM users WHERE name = ? COLLATE NOCASE");
    $sth->execute([$username]);
    $results = $sth->fetchAll(PDO::FETCH_ASSOC);
    if (count($results) == 0)
        return false;
    else
        return true;
}

function user_background($user_id) {
    global $profile_backgrounds;
    return $profile_backgrounds[get_user_by_id($user_id)['profile_background']]->style_tag();
}

function display_money($amount, $extra_decimals = 0, $include_currency_symbol = true) {
    global $currency_symbol;
    return number_format($amount/100, 2 + $extra_decimals).($include_currency_symbol ? $currency_symbol : "");
}

function show_info($error="Unknown error occurred.", $title="Error!") {
    gen_top();
    ?>
    <script>
        $(document).ready(()=>showInfo("<?=$error?>", "<?=$title?>")); 
    </script>
    <?php
}

function throw_error($error="Unknown error occurred.", $title="Error!") {
    show_info($error, $title);
    gen_bottom();
}

function hover_about($info) {
    ?><span class="badge badge-secondary" data-toggle="tooltip" title="<?=htmlentities($info)?>">?</span><?php
}

function gen_very_top($title = null, $description = null) {
    global $is_logged_in;
    global $user;
    global $energy_regeneration_interval;
    global $currency_symbol;
    global $mining_energy_cost;
    global $energy_storage_limit_free;
    global $energy_storage_limit_premium;
    $title = ($title == null ? "Geminer" : "Geminer - $title");
    $page_info = Array (
        'title' => $title,
        'description' => $description,
    );
    require_once $_SERVER['DOCUMENT_ROOT']."/../template/top.php";
}

function gen_middle() {
    global $is_logged_in;
    global $user;
    global $currency_symbol;
    global $repo_url;
    global $tag_styles;
    global $tag_fonts;
    global $navbar_backgrounds;
    $selected_navbar_backgrounds = json_decode($user['navbar_backgrounds']);
    $navbar_background = $navbar_backgrounds[($is_logged_in ? $selected_navbar_backgrounds[array_rand($selected_navbar_backgrounds)] : 0)];
    require_once $_SERVER['DOCUMENT_ROOT']."/../template/middle.php";
}

function gen_top($title = null, $description = null) {
    gen_very_top($title, $description);
    gen_middle();
}

function gen_bottom() {
    require_once $_SERVER['DOCUMENT_ROOT']."/../template/bottom.php";
    die();
}

//https://gist.github.com/jasny/2000705
function linkify($value, $protocols = array('http', 'mail'), array $attributes = array()) {
    // Link attributes
    $attr = '';
    foreach ($attributes as $key => $val) {
        $attr .= ' ' . $key . '="' . htmlentities($val) . '"';
    }
    
    $links = array();
    
    // Extract existing links and tags
    $value = preg_replace_callback('~(<a .*?>.*?</a>|<.*?>)~i', function ($match) use (&$links) { return '<' . array_push($links, $match[1]) . '>'; }, $value);
    
    // Extract text links for each protocol
    foreach ((array)$protocols as $protocol) {
        switch ($protocol) {
            case 'http':
            case 'https':   $value = preg_replace_callback('~(?:(https?)://([^\s<]+)|(www\.[^\s<]+?\.[^\s<]+))(?<![\.,:])~i', function ($match) use ($protocol, &$links, $attr) { if ($match[1]) $protocol = $match[1]; $link = $match[2] ?: $match[3]; return '<' . array_push($links, "<a $attr href=\"$protocol://$link\">$link</a>") . '>'; }, $value); break;
            case 'mail':    $value = preg_replace_callback('~([^\s<]+?@[^\s<]+?\.[^\s<]+)(?<![\.,:])~', function ($match) use (&$links, $attr) { return '<' . array_push($links, "<a $attr href=\"mailto:{$match[1]}\">{$match[1]}</a>") . '>'; }, $value); break;
            case 'twitter': $value = preg_replace_callback('~(?<!\w)[@#](\w++)~', function ($match) use (&$links, $attr) { return '<' . array_push($links, "<a $attr href=\"https://twitter.com/" . ($match[0][0] == '@' ? '' : 'search/%23') . $match[1]  . "\">{$match[0]}</a>") . '>'; }, $value); break;
            default:        $value = preg_replace_callback('~' . preg_quote($protocol, '~') . '://([^\s<]+?)(?<![\.,:])~i', function ($match) use ($protocol, &$links, $attr) { return '<' . array_push($links, "<a $attr href=\"$protocol://{$match[1]}\">{$match[1]}</a>") . '>'; }, $value); break;
        }
    }
    
    // Insert all link
    return preg_replace_callback('/<(\d+)>/', function ($match) use (&$links) { return $links[$match[1] - 1]; }, $value);
}