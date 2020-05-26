<?php
require_once $_SERVER['DOCUMENT_ROOT']."/../start.php";
gen_top("A gem collection");
require_once $_SERVER['DOCUMENT_ROOT']."/../fn/get_collection.php";
require_once $_SERVER['DOCUMENT_ROOT']."/../fn/user_button.php";
?>

<h1><?=htmlentities($collection['name'])?></h1>
<p>by <?php user_button($collection['by']); ?></p>
<?php generate_collection_image($collection['id'], true); ?>
<?php if ($is_logged_in and $collection['by'] == $user['id']) { ?>
<h2>This collection is yours.</h2>
<a href="/collection/edit.php?id=<?=$_GET['id']?>" class="btn btn-primary">Edit</a>
<?php } ?>
<?php gen_bottom(); ?>