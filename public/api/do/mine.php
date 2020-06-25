<?php
require_once $_SERVER['DOCUMENT_ROOT']."/../start.php";
require_once $_SERVER['DOCUMENT_ROOT']."/../consts/gems.php";
require_once $_SERVER['DOCUMENT_ROOT']."/../consts/locations.php";

if (!$is_logged_in)
    die("\"must be logged in\"");

if (isset($_GET['times'])) {
    $times_to_mine = intval($_GET['times']);
    if ($times_to_mine < 1)
        die("\"You need to mine at least one time!\"");
} else
    $times_to_mine = 1;

if ($user['energy'] < $mining_energy_cost * $times_to_mine)
    die("\"You don't have enough energy to do that.\"");

$location = $locations[$user['location']];

$max_number = 0;
foreach ($location->gems as $gem) {
    $max_number += $gem->chance;
};
$vein_amount = 0;
for ($x = 0; $x < $times_to_mine; $x++)
    $vein_amount += mt_rand(1, 9);

//$veins = Array();
$gem_amounts = Array();

for ($x = 0; $x < $vein_amount; $x++) {
    $number = mt_rand(1, $max_number);

    foreach ($location->gems as $gem) {
        $number -= $gem->chance;

        if ($number <= 0) {
            break;
        }
    };
    
    $amount = round($gem->quantity * log(mt_rand(1, mt_getrandmax()) / mt_getrandmax(), 2) * -1);
    if ($amount <= 0)
        $amount = 1;
    
    /*$veins[$x] = Array(
        'gem' => $gem->id,
        'amount' => $amount
    );*/
    
    if (!array_key_exists($gem->id, $gem_amounts))
        $gem_amounts[$gem->id] = 0;
    $gem_amounts[$gem->id] += $amount;
}

foreach ($gem_amounts as $gem_id => $amount)
    $dbh->prepare("UPDATE users SET `$gem_id` = `$gem_id` + ? WHERE id = ?")
        ->execute([$amount, $user['id']]);

$dbh->prepare("UPDATE users SET shifts_completed = shifts_completed + ?, energy = energy - ? WHERE id = ?")
    ->execute([$times_to_mine, $mining_energy_cost * $times_to_mine, $user['id']]);

echo json_encode($gem_amounts);