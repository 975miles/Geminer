<?php
require_once $_SERVER['DOCUMENT_ROOT']."/../start.php";
restrict_to("premium");
gen_top();
?>

<h1>Thank you!</h1>
<p>Thanks for supporting us by buying Geminer premium! Your account now has access to all the features described <a href="/premium">here</a>.</p>
<p class="lead">Here are some things you can do right now:</p>

<ul>
    <li>Take advantage of all your beautiful new tag styles by <a href="/profile/edit">editing your profile</a></li>
    <?php
    $sth = $dbh->prepare("SELECT id FROM collections WHERE by = ? AND type = 3");
    $sth->execute([$user['id']]);
    $new_collection_id = dechex($sth->fetchColumn());
    ?>
    <li>Edit your new <a href="/collection/view?id=<?=$new_collection_id?>">massive collection</a></li>
</ul>