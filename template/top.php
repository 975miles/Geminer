<!doctype html>
<html lang="en">
    <head>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">

        <link rel="icon" href="/a/i/logo256.png" type="image/x-icon">

        <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.0/css/bootstrap.min.css">
        <link rel="stylesheet" href="/a/css/stylesheet.css">

        <script src="https://code.jquery.com/jquery-3.5.1.min.js"></script>
        <script src="https://cdn.jsdelivr.net/npm/popper.js@1.16.0/dist/umd/popper.min.js"></script>
        <script src="https://stackpath.bootstrapcdn.com/bootstrap/4.5.0/js/bootstrap.min.js"></script>
        <script src="/a/js/geminer.js"></script>
        <script>
            const energyRegenerationInterval = <?=$energy_regeneration_interval?>;
            const miningEnergyCost = <?=$mining_energy_cost?>;
            const loggedIn = <?=$is_logged_in ? "true" : "false"?>;
            var user = <?=$is_logged_in ? json_encode($user) : "null"?>;
            if (user)
                user.shifts_completed = Number(user.shifts_completed);
            const maxEnergy = <?=$is_logged_in ? ($user['is_premium'] ? $energy_storage_limit_premium : $energy_storage_limit_free) : "null"?>;
            const currencySymbol = "<?=$currency_symbol?>";
        </script>

        <title><?=$page_info['title']?></title>
        <?php if (!is_null($page_info['description'])) { ?>
        <meta name="description" content="<?=$page_info['description']?>">
        <?php } ?>
        <?php if ($is_logged_in) echo user_background($user['id']); ?>