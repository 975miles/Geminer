<?php
require_once $_SERVER['DOCUMENT_ROOT']."/../start.php";
$identificator_host = "https://identificator.xyz";

if ($is_logged_in)
    redirect_back();

function getLoginCode() { //Get a code from the identificator host.
    global $identificator_host;
    redirect("$identificator_host/login?redirect_uri=" . (isset($_SERVER['HTTPS']) && $_SERVER['HTTPS'] === 'on' ? "https" : "http") . "://$_SERVER[HTTP_HOST]".$_SERVER['REQUEST_URI']);
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

$id = strval($user_logging_in->id);

$sth = $dbh->prepare("SELECT * FROM users WHERE identificator_id = ?");
$sth->execute([$id]);
$results = $sth->fetchAll(PDO::FETCH_ASSOC);
if (count($results) == 0) {
    $dbh->prepare("INSERT INTO users (identificator_id) VALUES (?)")->execute([$id]);

    $sth = $dbh->prepare("SELECT id FROM users WHERE identificator_id = ?");
    $sth->execute([$id]);
    $user_found = $sth->fetchAll(PDO::FETCH_ASSOC)[0];
} else
    $user_found = $results[0];

$_SESSION['user'] = $user_found['id'];

redirect_back();