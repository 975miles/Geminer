<?php
require_once $_SERVER['DOCUMENT_ROOT']."/../consts/gems.php";
class Birthstone {
    public function __construct($month, $gem) {
        global $all_gems;
        $this->month = $month;
        $this->gem = $all_gems[$gem];
    }
};

$birthstones = [
    null,
    new Birthstone("January", 44),
    new Birthstone("February", 3),
    new Birthstone("March", 46),
    new Birthstone("April", 2),
    new Birthstone("May", 6),
    new Birthstone("June", 45),
    new Birthstone("July", 0),
    new Birthstone("August", 4),
    new Birthstone("September", 1),
    new Birthstone("October", 48),
    new Birthstone("November", 8),
    new Birthstone("December", 47)
];