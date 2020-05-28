<!doctype html>
<html lang="en">
    <head>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">

        <link rel="icon" href="/a/i/logo256.png" type="image/x-icon">

        <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.4.1/css/bootstrap.min.css">
        <link rel="stylesheet" href="/a/css/stylesheet.css">

        <script src="https://code.jquery.com/jquery-3.4.1.js"></script>
        <script src="https://cdn.jsdelivr.net/npm/popper.js@1.16.0/dist/umd/popper.min.js"></script>
        <script src="https://stackpath.bootstrapcdn.com/bootstrap/4.4.1/js/bootstrap.min.js"></script>
        <script src="/a/js/geminer.js"></script>
        <script>
            const energyRegenerationInterval = <?=$energy_regeneration_interval?>;
            const miningEnergyCost = <?=$mining_energy_cost?>;
            const loggedIn = <?=$is_logged_in ? "true" : "false"?>;
            var user = <?=$is_logged_in ? json_encode($user) : "null"?>;
            const maxEnergy = <?=$is_logged_in ? ($user['is_premium'] ? $mine_storage_limit_premium : $mine_storage_limit_free) : "null"?>;
        </script>

        <title><?=$page_info['title']?></title>
        <?php if (!is_null($page_info['description'])) { ?>
        <meta name="description" content="<?=$page_info['description']?>">
        <?php } ?>
    </head>
    <body>
        <noscript>
            This site requires javascript enabled.
        </noscript>

        <nav class="navbar navbar-expand-lg navbar-light">
            <a class="navbar-brand" href="/">
                <img src="/a/i/logo256.png" height="40" alt="GEMiner">
            </a>
            <button class="navbar-toggler" type="button" data-toggle="collapse" data-target="#navbarSupportedContent" aria-controls="navbarSupportedContent" aria-expanded="false" aria-label="Toggle navigation">
                <span class="navbar-toggler-icon"></span>
            </button>

            <div class="collapse navbar-collapse" id="navbarSupportedContent">
                <ul class="navbar-nav mr-auto">
                    <li class="nav-item">
                        <a class="nav-link" href="/contact.php">Contact</a>
                    </li>
                    <li class="nav-item dropdown">
                        <a class="nav-link dropdown-toggle" href="#" id="financeDropdown" role="button" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                        Finance
                        </a>
                        <div class="dropdown-menu" aria-labelledby="financeDropdown">
                            <a class="dropdown-item" href="/marketplace">Marketplace</a>
                            <a class="dropdown-item" href="/tvwemwytig.php">TVWEMWYTIG</a>
                        </div>
                    </li>
                    <li class="nav-item dropdown">
                        <a class="nav-link dropdown-toggle" href="#" id="collectionsDropdown" role="button" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                        Collections
                        </a>
                        <div class="dropdown-menu" aria-labelledby="collectionsDropdown">
                            <a class="dropdown-item" href="/collection/leaderboard.php">Top collections</a>
                            <?php if ($is_logged_in) { ?>
                            <a class="dropdown-item" href="/profile?user=<?=$user['name']?>">Your collections</a>
                            <a class="dropdown-item" href="/collection/create.php">Create a new collection</a>
                            <?php } ?>
                        </div>
                    </li>
                    <li class="nav-item dropdown">
                        <a class="nav-link dropdown-toggle" href="#" id="dashDropdown" role="button" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                        Mining
                        </a>
                        <div class="dropdown-menu" aria-labelledby="dashDropdown">
                            <a class="dropdown-item" href="/dash/mining.php">Go to mine</a>
                            <a class="dropdown-item" href="/dash/location.php">All of the mines</a>
                        </div>
                    </li>
                </ul>
                <?php if ($is_logged_in) { ?>
                <div class="btn btn-sm btn-dark" id="energyDisplay" style="margin-right: 2em" data-toggle="popover" data-trigger="hover" title="Time until next regeneration" data-content="(loading...)">
                    <span id="energyAmount"><div class="spinner-border spinner-border-sm" role="status"><span class="sr-only"></span></div></span>
                    /
                    <span id="energyMax"><div class="spinner-border spinner-border-sm" role="status"><span class="sr-only"></span></div></span> 
                    <img src="/a/i/energy.png" class="energy-icon" alt="energy">
                </div>
                <script>
                    $("#energyAmount").html(user.energy);
                    $("#energyMax").html(maxEnergy);
                    $.get({
                        url: "/api/get/next-energy-regeneration.php",
                        success: data => {
                            var nextEnergyRegeneration = Number(data);
                            $.get({
                                url: "/api/get/server-time.php",
                                success: data => {
                                    var serverTime = Number(data);
                                    var secondsUntilNextEnergyRegeneration = nextEnergyRegeneration - serverTime;
                                    setInterval(() => {
                                        if (--secondsUntilNextEnergyRegeneration == 0) {
                                            secondsUntilNextEnergyRegeneration = energyRegenerationInterval;
                                            if (Number($("#energyAmount").html()) < maxEnergy)
                                                $("#energyAmount").html(Number($("#energyAmount").html()) + 1);
                                        }
                                        $('#energyDisplay').attr("data-content", `${(Math.floor(secondsUntilNextEnergyRegeneration / 60)).toString().padStart(2, "0")}:${(secondsUntilNextEnergyRegeneration % 60).toString().padStart(2, "0")}`);
                                        $('#energyDisplay').data('bs.popover').setContent();
                                    }, 1000);
                                }
                            });
                        }
                    });
                </script>
                <div class="dropdown">
                    <a class="btn btn-dark text-light dropdown-toggle" role="button" id="userDropdown" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                        <?=generate_pfp_collection($user['id'])?>
                        <?=$user['name']?>
                        <?php if (!$user['read_announcements']) {?>
                        <span class="badge badge-danger">!</span>
                        <?php } ?>
                    </a>
                    <div class="dropdown-menu dropdown-menu-right" aria-labelledby="userDropdown">
                        <a class="dropdown-item" href="/profile?user=<?=$user['name']?>">Profile</a>
                        <a class="dropdown-item" href="/notifications.php">
                        Notifications
                        <?php if (!$user['read_announcements']) {?>
                        <span class="badge badge-danger">!</span>
                        <?php } ?>
                        </a>
                        <?php if (!$user['is_premium']) { ?>
                        <a class="dropdown-item" href="/premium">Upgrade to a premium account</a>
                        <?php } ?>
                        <div class="dropdown-divider"></div>
                        <a class="dropdown-item" href="/log/out.php">Logout</a>
                    </div>
                </div>
                <?php } else { ?>
                <a class="btn btn-outline-dark" href="/log/in.php">
                    Login
                </a>
                <?php } ?>
            </div>
        </nav>

        <div class="container-fluid">