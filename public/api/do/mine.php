<?php
require_once $_SERVER['DOCUMENT_ROOT']."/../start.php";
require_once $_SERVER['DOCUMENT_ROOT']."/../consts/gems.php";
require_once $_SERVER['DOCUMENT_ROOT']."/../consts/locations.php";

if (!$is_logged_in)
    die("\"must be logged in\"");

if ($user['energy'] < $mining_energy_cost)
    die("\"you don't have any mines left\"");

$location = $locations[$user['location']];

$max_number = 0;
foreach ($location->gems as $gem) {
    $max_number += $gem->chance;
};

$vein_amount = mt_rand(1, 9);

$veins = Array();

for ($x = 0; $x < $vein_amount; $x++) {
    $number = mt_rand(1, $max_number);

    foreach ($location->gems as $gem) {
        $number -= $gem->chance;

        if ($number <= 0) {
            break;
        }
    };
    
    $power = 0;
    $change_direction = mt_rand(0, 1);

    while (mt_rand(0, 1) == 1) {
        if ($change_direction == 1)
            $power++;
        else
            $power--;
    }

    $add_amount = (mt_rand() / mt_getrandmax()) - 0.5;
    $amount_multiplier = 2**($power+$add_amount);
    $amount = $gem->quantity * $amount_multiplier;
    $amount = round($amount);
    if ($amount <= 0)
        $amount = 1;
    
    $veins[$x] = Array(
        'gem' => $gem->id,
        'amount' => $amount
    );
    
    $dbh->prepare("UPDATE users SET `$gem->id` = `$gem->id` + ? WHERE id = ?")
        ->execute([$amount, $user['id']]);
}

//whyyyyyyyyyyyyyyy doesnt sqlite have +=
$dbh->prepare("UPDATE users SET shifts_completed = shifts_completed + 1, energy = energy - ? WHERE id = ?")
    ->execute([$mining_energy_cost, $user['id']]);

echo json_encode($veins);