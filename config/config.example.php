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
$max_pick_name_length = 48;

$valid_username_characters = "abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ0123456789-_~*^!|:;()[]{}";

$currency_symbol = "₲";

//The amount of seconds between each time everyone's energy regenerates by one.
$energy_regeneration_interval = 600;

//how much energy is used to mine
$mining_energy_cost = 5;

//how much energy is used to move to another location
$moving_energy_cost = 3;

$base_shifts_per_level = 1;

$energy_storage_per_level = 10;

$alloy_cast_price = 1000;
$alloy_cast_time = 86400;
$alloy_cast_time_string = "24 hours";

$alloy_cast_speed_up_energy_price = 1;

$board_place_min_price = 1;
$board_place_max_price = 200;

$energy_storage_limit_free = 250;
$energy_storage_limit_premium = 1500;

$collection_storage_limit_free = 10;
$collection_storage_limit_premium = 1000;

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
$max_inbox_size_admin = 1000; //make this a bit high if admins will have to help users

$max_messages_per_person_free = 5;
$max_messages_per_person_premium = 10;

$max_sent_message_length_free = 500;
$max_sent_message_length_premium = 2000;