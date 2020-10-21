<?php
$size_min = 1;
$size_max = 256;
if (!isset($_GET['size']))
    die("query variable \"size\" must be provided");
$looking_for = intval($_GET['size']);
if ($looking_for < $size_min)
    die("there aren't sizes less than ".$size_min);
if ($looking_for > $size_max)
    die("there aren't sizes more than ".$size_max);

for ($id = 0; $id < PHP_INT_MAX; $id++) {
    srand($id);
    $board_size = rand($size_min, $size_max);
    //$place_price = rand($board_place_min_price, $board_place_max_price);
    if ($board_size == $looking_for)
        die(print_r($id));
    srand(-$id);
    $board_size = rand($size_min, $size_max);
    if ($board_size == $looking_for)
        die(-$id);
}
echo "not found";
?>