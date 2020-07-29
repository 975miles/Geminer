<?php
require_once $_SERVER['DOCUMENT_ROOT']."/../start.php";
require_once $_SERVER['DOCUMENT_ROOT']."/../consts/cosmetics/backgrounds.php";
require_once $_SERVER['DOCUMENT_ROOT']."/../consts/cosmetics/navbarbgs.php";
require_once $_SERVER['DOCUMENT_ROOT']."/../consts/birthstones.php";
require_once $_SERVER['DOCUMENT_ROOT']."/../fn/user_button.php";
require_auth();
gen_top("Editing profile...");

$min_length = ($user['is_admin'] ? 1 : ($user['is_premium'] ? $min_username_length_premium : $min_username_length_free));
$max_length = ($user['is_premium'] ? $max_username_length_premium : $max_username_length_free);

if (isset($_POST['name'], $_POST['tag_style'], $_POST['profile_background'], $_POST['birth_month'])) {
    $_POST['tag_style'] = intval($_POST['tag_style']);
    $_POST['profile_background'] = intval($_POST['profile_background']);
    $_POST['birth_month'] = intval($_POST['birth_month']);
    if (!isset($_POST['navbar_backgrounds']))
        show_info("You need to select at least one navbar background.");
    else if (mb_strlen($_POST['name']) < $min_length)
        show_info("Your username must be at least $min_length characters long.", "Username too short");
    else if (mb_strlen($_POST['name']) > $max_length)
        show_info("Your username must be at most $max_length characters long.", "Username too long");
    else if (!valid_username($_POST['name']))
        show_info("Your username must contain only the following characters: <code>$valid_username_characters</code>", "Invalid character");
    else if (user_exists($_POST['name']) and strtolower($_POST['name']) !== strtolower($user['name']))
        show_info("There already exists a user with that username.", "Username taken");
    else if (!array_key_exists($_POST['tag_style'], $tag_styles))
        show_info("That tag background doesn't exist.");
    else if ($tag_styles[$_POST['tag_style']]->premium and !$user['is_premium'])
        show_info("You need to be premium to use that tag background.");
    else if (!array_key_exists($_POST['tag_font'], $tag_fonts))
        show_info("That tag doesn't exist.");
    else if ($tag_fonts[$_POST['tag_font']]->premium and !$user['is_premium'])
        show_info("You need to be premium to use that tag.");
    else if (!array_key_exists($_POST['profile_background'], $profile_backgrounds))
        show_info("That background doesn't exist.");
    else if ($profile_backgrounds[$_POST['profile_background']]->premium and !$user['is_premium'])
        show_info("You need to be premium to use that background.");
    else if (!is_array($_POST['navbar_backgrounds']))
        show_info("The navbar backgrounds weren't submitted properly");
    else if ($_POST['birth_month'] != 0 and !array_key_exists($_POST['birth_month'], $birthstones))
        show_info("That month doesn't exist.");
    else {
        $selected_navbar_backgrounds = Array();
        foreach($_POST['navbar_backgrounds'] as $navbar_background) {
            $navbar_background = intval($navbar_background);
            if (in_array($navbar_background, $selected_navbar_backgrounds))
                throw_error("You've put the same background more than once.");
            if (!array_key_exists($navbar_background, $navbar_backgrounds))
                throw_error("That navbar background doesn't exist");
            if ($navbar_backgrounds[$navbar_background]->premium and !$user['is_premium'])
                throw_error("You need to be premium to use that navbar background.");
            array_push($selected_navbar_backgrounds, $navbar_background);
        }
        $dbh->prepare("UPDATE users SET name = ?, tag_style = ?, tag_font = ?, profile_background = ?, navbar_backgrounds = ?, birthstone = ? WHERE id = ?")->execute([$_POST['name'], $_POST['tag_style'], $_POST['tag_font'], $_POST['profile_background'], json_encode($selected_navbar_backgrounds), $_POST['birth_month'], $user['id']]);
        redirect("/profile");
    }
}
?>
<h1>Edit Profile</h1>
<form action="" method="post" autocomplete="off">
    <hr>
    <label class="lead" for="username">Username:</label>
    <input id="username" type="text" name="name" class="form-control" placeholder="username" minlength="<?=$min_length?>" maxlength="<?=$max_length?>" value="<?=$user['name']?>" onchange="$('.user-button-username').html(this.value)">
    <hr>
    <label class="lead">Tag background:</label>
    <br>
    <div id="tagBackgrounds">
        <?php
        foreach ($tag_styles as $tag_style_id => $tag_style) {
            if ($tag_style->premium and !$user['is_premium'])
                continue;
        ?>
        <div class="form-check form-check-inline">
            <input class="form-check-input" type="radio" onchange="changeTags('fonts', '<?=$tag_style->bg?>', <?=$tag_style->light ? "true" : "false"?>)" name="tag_style" id="tag_style_<?=$tag_style_id?>" value="<?=$tag_style_id?>"<?=$tag_style_id == $user['tag_style'] ? " checked" : ""?>>
            <label for="tag_style_<?=$tag_style_id?>">
                <?=user_button($user['id'], true, null, "div", $tag_style_id)?>
            </label>
        </div>
        <?php } ?>
    </div>
    <?php if (!$user['is_premium']) { ?>
    <p><a href="/premium">Get premium</a> to access more styles.</p>
    <?php } ?>
    <hr>
    <label class="lead">Tag font:</label>
    <br>
    <div id="tagFonts">
        <?php
        foreach ($tag_fonts as $tag_font_id => $tag_font) {
            if ($tag_font->premium and !$user['is_premium'])
                continue;
        ?>
        <div class="form-check form-check-inline">
            <input class="form-check-input" type="radio" onchange="changeTags('bgs', '<?=str_replace("'", "\'", $tag_font->style)?>')" name="tag_font" id="tag_font_<?=$tag_font_id?>" value="<?=$tag_font_id?>"<?=$tag_font_id == $user['tag_font'] ? " checked" : ""?>>
            <label for="tag_font_<?=$tag_font_id?>">
                <?=user_button($user['id'], true, null, "div", false, $tag_font_id)?>
            </label>
        </div>
        <?php } ?>
    </div>
    <?php if (!$user['is_premium']) { ?>
    <p><a href="/premium">Get premium</a> to access more fonts.</p>
    <?php } ?>
    <hr>
    <label class="lead">Profile background:</label>
    <p>
        This background will show everywhere for you (except pages which belong to someone else)
        <br>
        For others, this background will show on your profile, your collections, <?=$user['is_premium'] ? "your announcements," : ""?> and your messages to them.
    </p>
    <br>
    <?php
    foreach ($profile_backgrounds as $profile_background_id => $profile_background) {
        if ($profile_background->premium and !$user['is_premium'])
            continue;
    ?>
    <div class="form-check form-check-inline">
        <input class="form-check-input" type="radio" onchange="changeBackground(`<?=$profile_background->style?>`)" name="profile_background" id="profile_background_<?=$profile_background_id?>" value="<?=$profile_background_id?>"<?=$profile_background_id == $user['profile_background'] ? " checked" : ""?>>
        <label for="profile_background_<?=$profile_background_id?>">
            <span class="bg-displayer" style="background: <?=$profile_background->bgshort?>;"></span>
        </label>
    </div>
    <?php } if (!$user['is_premium']) { ?>
    <p><a href="/premium">Get premium</a> to access more backgrounds.</p>
    <?php } ?>
    <hr>
    <label class="lead">Navbar backgrounds:</label>
    <p>
        On every page load, a random background will be chosen out of the ones you've checked here to be the navbar's background. This will only show for you.<br>
        You must select at least one.<br>
        Click on a background to preview it.<br><br>
        <button class="btn btn-secondary" type="button" onclick="toggleNavbarBackgrounds(true)">Check All</button>
        <button class="btn btn-secondary" type="button" onclick="toggleNavbarBackgrounds(false)">Uncheck All</button>
    </p>
    <br>
    <div id="navbarBackgrounds">
    <?php
    $current_navbar_backgrounds = json_decode($user['navbar_backgrounds']);
    foreach ($navbar_backgrounds as $navbar_background_id => $navbar_background) {
        if ($navbar_background->premium and !$user['is_premium'])
            continue;
    ?>
    <div class="form-check form-check-inline">
        <input class="form-check-input" type="checkbox" name="navbar_backgrounds[]" id="navbar_background_<?=$navbar_background_id?>" value="<?=$navbar_background_id?>"<?=in_array($navbar_background_id, $current_navbar_backgrounds) ? " checked" : ""?>>
        <label>
            <span class="bg-displayer"  onclick="changeNavbar(`<?=$navbar_background->style?>`, <?=$navbar_background->dark ? "true" : "false"?>)" style="height: 40px; background: <?=$navbar_background->style?>;"></span>
        </label>
    </div>
    <?php } ?>
    </div>
    <?php if (!$user['is_premium']) { ?>
    <p><a href="/premium">Get premium</a> to access more backgrounds.</p>
    <?php } ?>
    <hr>
    <label>Birthstone</label>
    <select class="form-control" name="birth_month">
        <option value="0">(Don't show my birth month on my profile)</option>
        <?php
        for ($i = 1; $i < count($birthstones); $i++) {
            $birthstone = $birthstones[$i];
            ?>
            <option value="<?=$i?>"<?=$user['birthstone'] == $i ? " selected" : ""?>><?=$birthstone->gem->name?> (<?=$birthstone->month?>)</option>
        <?php } ?>
    </select>
    <hr>
    <button type="submit" class="btn btn-lg btn-primary">Submit</button>
</form>

<script>
    function changeTags(action, css, light = null) {
        let div, attr;
        if (action == "fonts") {
            div = "#tagFonts";
            attr = "background";
        } else if (action == "bgs") {
            div = "#tagBackgrounds";
            attr = "font-family";
            css = css.substring(13, css.length - 1)
        }
        div = $(div);
        div.children().each((i,e)=>{$(e.children[1].children[0]).css(attr, css)});
        if (light !== null) {
            if (light)
                div.children().each((i,e)=>{$(e.children[1].children[0]).addClass("btn-custom-light")});
            else
                div.children().each((i,e)=>{$(e.children[1].children[0]).removeClass("btn-custom-light")});
        }
        //$("#tagFonts").children().each((i,e)=>{$(e.children[1].children[0]).css("background", "linear-gradient( 153.4deg,  rgba(160,250,141,1) 25.4%, rgba(253,217,182,1) 59% )")});
    }

    function changeBackground(bg) {
        $("body").find("style").html(`body {background: ${bg}}`);
    }

    var navbar = $(".navbar");

    function changeNavbar(bg, dark) {
        navbar.css({"background": bg});
        navbar.removeClass("navbar-light")
        navbar.removeClass("navbar-dark")
        navbar.addClass("navbar-"+(dark ? "dark" : "light"))
    }

    function toggleNavbarBackgrounds(checked) {
        $('#navbarBackgrounds').find('.form-check-input').each((i, e)=>$(e).prop('checked', checked));
    }

    $("body").append("<style>");
</script>

<?php gen_bottom(); ?>