<?php
//The DSN to connect to a database with PDO.
$pdo_dsn = "sqlite:".__DIR__."/../data.db";

//The URL to the git repository of the app.
$repo_url = "https://github.com/975miles/GEMiner";

//The URL to the issue page for bugs and suggestions.
$issue_url = "$repo_url/issues";

//The email of someone running the app, if any inquiries are needed o.O.
$contact_email = "(email address not set up yet)";

//The URL of a page where you can buy / get premium codes.
$premium_purchase_url = "data:,Code%20purchasing%20has%20not%20yet%20been%20set%20up.%20Go%20to%20our%20contact%20page%20and%20contact%20us%20if%20you%20really%20need%20premium%20right%20now%2C%20we%20might%20be%20able%20to%20get%20it%20for%20you.";

$max_collection_name_length = 32;

$valid_username_characters = "abcdefghijklmnopqrstuvwxyz0123456789";

//The amount of seconds between each time everyone's energy regenerates by one.
$energy_regeneration_interval = 3600;

//how much energy is used to mine
$mining_energy_cost = 1;

//how much energy is used to move to another location
$moving_energy_cost = 25;

$mine_storage_limit_free = 100;
$mine_storage_limit_premium = 10000;

$collection_storage_limit_free = 35;
$collection_storage_limit_premium = 250;

$max_marketplace_listings_free = 25;
$max_marketplace_listings_premium = 200;