<?php
require_once $_SERVER['DOCUMENT_ROOT']."/../start.php";
require_once $_SERVER['DOCUMENT_ROOT']."/../consts/pickaxe.php";
header('Content-Type: text/javascript');
$consts = [
    ["hurtEnergyLoss", $hurt_energy_loss],
    ["baseModifiers", $base_minimum_modifiers],
    ["paperModifiers", $paper_additional_modifiers],
    ["paperLevelDebuff", $paper_level_debuff],
    ["bandsMultiplier", $band_weight_mult],
    ["wingsMultiplier", $wings_weight_mult],
    ["energyGripGainChance", $energy_grip_chance],
    ["energyGripEnergyGain", $energy_grip_gain],
    ["softGripMultiplier", $soft_grip_mult],
    ["drillMultipler", $drill_strength_mult],
    ["bayonetMultiplier", $bayonet_strength_mult],
    ["cloverMultiplier", $clover_luck_mult],
    ["reinforcementMultiplier", $reinforcement_mult],
    ["reinforcementWeightMultiplier", $reinforcement_weight_mult],
    ["levelUpAmount", $level_up_amount]
];
foreach ($consts as $const) {
?>
const <?=$const[0]?> = <?=$const[1]?>;
<?php } ?>