<?php
//Info which shows on the Geminer homepage
$installation_info = "Welcome to Geminer!";

$identificator_host = "https://identificator.xyz";

//The URL to the code of the website.
$repo_url = "https://github.com/975miles/Geminer";

//The URL to the issue page for bugs and suggestions.
$issue_url = "https://github.com/975miles/Geminer/issues";

//The email of people/a person running the website, if any inquiries or support are needed o.O.
$contact_email = "(email address not set up yet)";

//The URL of a page where you can buy / get premium codes.
$premium_purchase_url = "data:,Code%20purchasing%20has%20not%20yet%20been%20set%20up.%20Go%20to%20our%20contact%20page%20and%20contact%20us%20if%20you%20really%20need%20premium%20right%20now%2C%20we%20might%20be%20able%20to%20get%20it%20for%20you.";

//Extra features for premium that will be added to the list at /premium
$extra_premium_features = [
    "A special role in the Discord server",
    "Access to the premium channel in the Discord server",
];

$max_collection_name_length = 32;

$valid_username_characters = "abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ0123456789-_~*^!|:;()[]{}";

$currency_symbol = "₲";

//The amount of seconds between each time everyone's energy regenerates by one.
$energy_regeneration_interval = 3600;

//how much energy is used to mine
$mining_energy_cost = 1;

//how much energy is used to move to another location
$moving_energy_cost = 10;

$max_veins_per_mine_free = 5;
$max_veins_per_mine_premium = 11;

//for tvwiemwytgi
$free_sell_divisor = 2;

$energy_storage_limit_free = 100;
$energy_storage_limit_premium = 10000;

$collection_storage_limit_free = 15;
$collection_storage_limit_premium = 250;

$max_marketplace_listings_free = 10;
$max_marketplace_listings_premium = 100;

//the maximum username length is 16, so both of these must be at most 16 and at least 1, to prevent an empty username
$min_username_length_free = 6;
$min_username_length_premium = 2;

$max_username_length_free = 12;
$max_username_length_premium = 16;

//This only accounts for messages, announcements that should be shown will always be shown.
//Messages from the system will bypass this limit.
$max_inbox_size_free = 125;
$max_inbox_size_premium = 500;
$max_inbox_size_admin = 100000; //this is high because when admins have to help users they'll need to verify them

$max_messages_per_person_free = 5;
$max_messages_per_person_premium = 10;

$max_sent_message_length_free = 500;
$max_sent_message_length_premium = 2000;
