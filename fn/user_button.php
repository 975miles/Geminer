<?php
require_once $_SERVER['DOCUMENT_ROOT']."/../consts/cosmetics/tag_styles.php";
require_once $_SERVER['DOCUMENT_ROOT']."/../consts/cosmetics/tag_fonts.php";
function user_button($user_id, $show_pfp = true, $size = null, $element = "a", $force_style = false, $force_font = false) {
    global $dbh;
    global $tag_styles;
    global $tag_fonts;
    $user_found = get_user_by_id($user_id);
    $tag_style = $tag_styles[($force_style !== false ? $force_style : $user_found['tag_style'])];
    $tag_font = $tag_fonts[($force_font !== false ? $force_font : $user_found['tag_font'])];
    ?>
<<?=$element?> href="/profile?user=<?=htmlentities($user_found['name'])?>" class="btn <?=is_null($size) ? "" : "btn-$size"?> <?=$tag_style->get_classes()?>" style="<?=$tag_style->get_style().$tag_font->style?>">
    <?php if ($show_pfp) place_collection_image(get_pfp_collection_id($user_id)); ?>
    <span class="user-button-username"><?=htmlentities($user_found['name'])?></span>
</<?=$element?>>
    <?php
}