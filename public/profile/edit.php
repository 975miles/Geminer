<?php
require_once $_SERVER['DOCUMENT_ROOT']."/../start.php";
require_auth();
gen_top("GEMiner - edit profile");


if (isset($_POST) and isset($_POST['name'])) {
    $min_length = ($user['is_admin'] ? 1 : ($user['is_premium'] ? 3 : 5));
    $max_length = 16;
    if (mb_strlen($_POST['name']) < $min_length) {
        show_info("Your username must be at least $min_length characters long.", "Username too short");
    } else if (mb_strlen($_POST['name']) > $max_length) {
        show_info("Your username must be at most $max_length characters long.", "Username too long");
    } else if (!valid_username($_POST['name'])) {
        show_info("Your username must contain only the following characters: <code>$valid_username_characters</code>", "Invalid character");
    } else if (user_exists($_POST['name']) and $_POST['name'] !== $user['name']) {
        show_info("There already exists a user with that username.", "Username taken");
    } else {
        $dbh->prepare("UPDATE users SET name = ? WHERE id = ?")->execute([$_POST['name'], $user['id']]);
        redirect("/profile");
    }
}
?>

<h1>Edit Profile</h1>
<form action="" method="post" autocomplete="off">
    <label for="username">Username:</label>
    <input id="username" type="text" name="name" class="form-control" placeholder="username" value="<?=$user['name']?>">
    <button type="submit" class="btn btn-lg btn-primary">Submit</button>
</form>

<?php gen_bottom(); ?>