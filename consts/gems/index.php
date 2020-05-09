<?php
class Gem {
    public function __construct (
            //The gem's name
            $name = "Gemeric",
            
            //The colour of the gem when used in a collection
            $colour = "white",
            
            //The chance of finding a vein of this gem per vein found
            $chance = 1,
            
            //The amount of millipixels you can get from one vein of the gem
            $quantity = 1
        ) {
        $this->name = $name;
        $this->colour = $colour;
        $this->chance = $chance;
        $this->quantity = $quantity;
    }
}

$gems_json = json_decode(file_get_contents(__DIR__."/gems.json", true));
$all_gems = Array();
foreach ($gems_json as $id=>$gem_info) {
    $all_gems[$id] = new Gem(
        $name = $gem_info->name,
        $colour = $gem_info->colour,
        $chance = $gem_info->chance,
        $quantity = $gem_info->quantity
    );
}