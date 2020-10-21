<?php
require_once $_SERVER['DOCUMENT_ROOT']."/../start.php";
require_once $_SERVER['DOCUMENT_ROOT']."/../consts/gems.php";
require_once $_SERVER['DOCUMENT_ROOT']."/../consts/locations.php";
require_once $_SERVER['DOCUMENT_ROOT']."/../consts/pickaxe.php";
require_once $_SERVER['DOCUMENT_ROOT']."/../consts/parts.php";
require_once $_SERVER['DOCUMENT_ROOT']."/../consts/materials.php";
require_once $_SERVER['DOCUMENT_ROOT']."/../consts/crate_rarities.php";

function has_mod($modifier) {
    global $modifiers;
    return in_array($modifier, $modifiers);
}

if (!$is_logged_in)
    die("\"must be logged in\"");

if (isset($_GET['pick'])) {
    $pick_id = intval($_GET['pick']);
    if ($pick_id == 0) {
        $modifiers = Array();
        $level = 0;
        $durability = INF;
        $uses = 0;
        $strength = 0.25;
        $roughness = 0.5;
        $luck = 0;
        $weight = 500;
    } else {
        $sth = $dbh->prepare("SELECT id, handle, head, binding, modifiers, uses FROM pickaxes WHERE id = ? AND owner = ?");
        $sth->execute([$pick_id, $user['id']]);
        $picks_found = $sth->fetchAll();
        if (count($picks_found) > 0) {
            $pick = $picks_found[0];

            $uses = $pick['uses'];

            if ($pick['modifiers'] == "")
                $modifiers = Array();
            else
                $modifiers = explode(",", $pick['modifiers']);
            foreach($modifiers as $key => $modifier)
                $modifiers[$key] = intval($modifier);

            $level = 0;
            foreach ($part_types as $part_name => $part_type)
                $level += $materials[$pick[$part_name]]->level;
            if (has_mod(9))
                $level += $level_up_amount;
            if (has_mod(4))
                $level -= $paper_level_debuff;
            if ($level < 0)
                $level = 0;

            //!!!
            $durability = $materials[$pick['head']]->durability * $materials[$pick['binding']]->hardness * $materials[$pick['handle']]->toughness;
            if ($durability < 0)
                $durability = 1;
            if (has_mod(8))
                $durability *= $reinforcement_mult;
            $durability = round($durability);

            $weight = 0;
            foreach ($part_types as $part_name => $part_type)
                $weight += $materials[$pick[$part_name]]->weight * $part_type->weightModifier;
            if (has_mod(0))
                $weight *= $band_weight_mult;
            if (has_mod(1))
                $weight *= $wings_weight_mult;
            if (has_mod(8))
                $weight *= $reinforcement_weight_mult;
            if ($weight < 0)
                $weight = 0;
            $weight = round($weight);

            $strength = $materials[$pick['head']]->strength;
            if (has_mod(5))
                $strength *= $drill_strength_mult;
            if (has_mod(6))
                $strength *= $bayonet_strength_mult;

            //!!!
            $roughness = $materials[$pick['handle']]->roughness;
            if (has_mod(3))
                $roughness *= $soft_grip_mult;

            //!!!
            $luck = $materials[$pick['binding']]->luck;
            if (has_mod(7))
                $luck *= $clover_luck_mult;
        } else
            die("\"That pickaxe doesn't exist.\"");
    }

    if (isset($_GET['times'])) {
        $times_to_mine = intval($_GET['times']);
        if ($times_to_mine < 1)
            die("\"You need to mine at least one time!\"");
    } else
        $times_to_mine = 1;
    
    if ($user['energy'] < $mining_energy_cost * $times_to_mine)
        die("\"You don't have enough energy to do that.\"");
    
    if (($durability - $uses) < $times_to_mine)
        die("\"That pickaxe doesn't have enough durability to mine that many times.\"");
    
    $location = $locations[$user['location']];
    
    $max_number = 0;
    foreach ($location->gems as $gem) {
        $max_number += $gem->chance;
    };

    $vein_amount = 0;
    $times_energised = 0;
    $energy_used = $mining_energy_cost * $times_to_mine;
    $times_hurt = 0;
    $gem_amounts = Array();
    $crates_found = [];

    for ($x = 0; $x < $times_to_mine; $x++) {
        $new_veins = floor(mt_rand(1, PHP_INT_MAX)/PHP_INT_MAX*(((500 - $weight) / 25) + 1));
        if ($new_veins < 1)
            $new_veins = 1;
        $vein_amount += $new_veins;

        if (has_mod(2) and mt_rand(0, PHP_INT_MAX)/PHP_INT_MAX < $energy_grip_chance) {
            $energy_used -= $energy_grip_gain;
            $times_energised++;
        }
    }
    
    for ($x = 0; $x < $vein_amount; $x++) {
        do {
            $number = mt_rand(1, $max_number);

            foreach ($location->gems as $gem) {
                $number -= $gem->chance;
        
                if ($number <= 0) {
                    break;
                }
            };
        } while ($gem->level > $level);
        
        $amount = round($gem->quantity * log(mt_rand(1, mt_getrandmax()) / mt_getrandmax(), 2) * -1 * $strength);
        if ($amount < 1)
            $amount = 1;

        if (mt_rand(0, PHP_INT_MAX)/PHP_INT_MAX < $roughness) {
            $energy_used += $hurt_energy_loss;
            $times_hurt++;
        }

        if (mt_rand(0, PHP_INT_MAX)/PHP_INT_MAX < $luck) {
            $max_chance = 100;
            $number = mt_rand(1, $max_chance);
            foreach ($crate_rarities as $rarity_id => $rarity) {
                $number -= $rarity->chance;
        
                if ($number <= 0) {
                    break;
                }
            };
            $energy_used += $hurt_energy_loss;
            $times_hurt++;
            array_push($crates_found, $rarity_id);
        }
        
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
        ->execute([$times_to_mine, $energy_used, $user['id']]);

    $uses += $times_to_mine;
    if (($durability - $uses) <= 0) {
        $dbh->prepare("DELETE FROM pickaxes WHERE id = ?")
            ->execute([$pick['id']]);
    } else if ($pick_id != 0) {
        $dbh->prepare("UPDATE pickaxes SET uses = uses + ? WHERE id = ?")
            ->execute([$times_to_mine, $pick['id']]);
    }

    foreach ($crates_found as $crate_found) {
        $dbh->prepare("INSERT INTO crates (rarity, owner) VALUES (?, ?)")
            ->execute([$crate_found, $user['id']]);
    }
    
    echo json_encode(Array(
        "veins" => $vein_amount,
        "timesHurt" => $times_hurt,
        "timesEnergised" => $times_energised,
        "crates" => $crates_found,
        "gems" => $gem_amounts
    ));
} else
    die("\"pick id was not provided\"");