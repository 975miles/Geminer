<?php
require_once $_SERVER['DOCUMENT_ROOT']."/../start.php";
require_once $_SERVER['DOCUMENT_ROOT']."/../fn/user_button.php";

$board_id = (isset($_GET['id']) ? intval($_GET['id']) : 0);
srand($board_id);
$board_size = rand(1, 256);
$place_price = rand($board_place_min_price, $board_place_max_price);
gen_top("Board ".$board_id, "A gem board of size ".$board_size."px*".$board_size."px");

$time = time();
$board_gems = [];
for ($y = 0; $y < $board_size; $y++) {
    $row = [];
    for ($x = 0; $x < $board_size; $x++)
        array_push($row, -1);
    array_push($board_gems, $row);
}
$sth = $dbh->prepare("SELECT x, y, gem FROM board_placements WHERE board = ?");
$sth->execute([$board_id]);
$placements = $sth->fetchAll();
foreach ($placements as $placement) {
    $board_gems[$placement['y']][$placement['x']] = $placement['gem'];
}
?>

<h3>Gem board of id <?=$board_id?> is <?=$board_size?>px*<?=$board_size?>px</h3>
<div id="stage-parent">
    <div id="board"></div>
</div>
<div class="custom-control custom-checkbox custom-control-inline">
    <input class="custom-control-input" type="checkbox" value="true" id="autoUpdateCheck" checked>
    <label class="custom-control-label" for="autoUpdateCheck">
        Auto-update
    </label>
</div>
<button class="btn btn-sm btn-primary" onclick="toggleGrid()">Toggle gridlines</button>
<button class="btn btn-sm btn-primary" onclick="createBoard()">Re-render board</button>
<?php if ($is_logged_in) { ?>
<hr>
<div class="form-group">
    <label>Gem to place</label>
    <select class="form-control" id="gemSelect">
        <option value="none">(none)</option>
    </select>
    <p>Click a pixel on the board to place it. This costs <?=display_money($place_price)?> and 1px of the gem.</p>
</div>
<?php } ?>
<hr>
<h3>Top contributors</h3>
<?php
$sth = $dbh->prepare("SELECT user, COUNT(1) AS placements FROM board_placements WHERE board = ? GROUP BY user ORDER BY placements DESC LIMIT 25");
$sth->execute([$board_id]);
$n = 0;
foreach ($sth->fetchAll() as $placer) {
    $n++;
    ?>
    <p><?=$n?>: <?=user_button($placer['user'])?> with <?=$placer['placements']?> gem<?=$placer['placements'] == 1 ? "" : "s"?></p>
<?php } ?>


<script src="https://unpkg.com/konva@7.0.3/konva.min.js"></script>
<script>
var boardId = <?=$board_id?>;
var boardGems = <?=json_encode($board_gems)?>;
var placePrice = <?=$place_price?>;
var fromTime = <?=$time?>;
</script>
<script src="/a/js/boardcontrols.js"></script>

<?php gen_bottom(); ?>