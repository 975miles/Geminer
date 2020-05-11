<?php
require_once $_SERVER['DOCUMENT_ROOT']."/../start.php";
require_once $_SERVER['DOCUMENT_ROOT']."/../consts/gems/index.php";

if (!$is_logged_in)
    die("\"must be logged in\"");

$max_number = 0;
foreach ($all_gems as $gem) {
    $max_number += $gem->chance;
};

$vein_amount = mt_rand(1, 9);

$veins = Array();

for ($x = 0; $x < $vein_amount; $x++) {
    $number = mt_rand(1, $max_number);

    foreach ($all_gems as $gem_id=>$gem) {
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
        'gem' => $gem_id,
        'amount' => $amount
    );
    
    $dbh->prepare("UPDATE users SET `$gem_id` = `$gem_id` + ? WHERE id = ?")
        ->execute([$amount, $user['id']]);
}

$dbh->prepare("UPDATE users SET shifts_completed = shifts_completed + 1 WHERE id = ?")
    ->execute([$user['id']]);

echo json_encode($veins);