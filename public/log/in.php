<?php
require_once $_SERVER['DOCUMENT_ROOT']."/../start.php";

if ($is_logged_in)
    redirect_back();

function getLoginCode() { //Get a code from the identificator host.
    global $identificator_host;
    redirect("$identificator_host/login?redirect_uri=" . ((isset($_SERVER['HTTPS']) and $_SERVER['HTTPS'] === 'on') ? "https" : "http") . "://$_SERVER[HTTP_HOST]".$_SERVER['REQUEST_URI']);
} 
if (!isset($_GET['code'])) //If there is no code in the url query,
    getLoginCode(); //get one.    
else { //If no code has been generated yet,
    $user_logging_in = json_decode(@file_get_contents("$identificator_host/api/auth?code=$_GET[code]")) //Get the user info from the identificator host.
        or die("The identificator host couldn't be reached."); //If a connection to the Identificator host couldn't be made, stop the script and tell the user.
    if (isset($user_logging_in->error)) { //If the identificator host sends an error:
        switch ($user_logging_in->error) {
            case "Invalid code.": //If the supplied login code was invalid,
                getLoginCode(); //get a new working one.
                break;
            default: //If the error was anything else,
                die($user_logging_in->error); //stop the script and print the error.
        }
    }
}

$id = $user_logging_in->id;

$sth = $dbh->prepare("SELECT * FROM users WHERE identificator_id = ?");
$sth->execute([$id]);
$results = $sth->fetchAll(PDO::FETCH_ASSOC);
if (count($results) == 0) {
    function gen_unused_name() {
        global $name;
        global $default_username_length;
        global $valid_username_characters;
        
    }

    $name = strtolower($user_logging_in->name);
    if (is_null($name) or mb_strlen($name) > $max_username_length or mb_strlen($name) < $min_username_length_free or !valid_username($name) or user_exists($name))
        do {
            $default_username_length = 6;
            $name = "";
            for ($i = 0; $i < $default_username_length; $i++) {
                $name .= $valid_username_characters[mt_rand(0, mb_strlen($valid_username_characters) - 1)];
            }
        } while (user_exists($name));

    //$min_username_length_free

    $dbh->prepare("INSERT INTO users (identificator_id, name) VALUES (?, ?)")
        ->execute([$id, $name]);

    $user_id_found = $dbh->lastInsertId();
    $_GET['redirect_back_to'] = "/about/welcome.php";
} else
    $user_id_found = $results[0]['id'];

$_SESSION['user'] = $user_id_found;

redirect_back();