<!doctype html>
<html lang="en">
    <head>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">

        <link rel="icon" href="/a/i/logo256.png" type="image/x-icon">

        <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.0/css/bootstrap.css">
        <link rel="stylesheet" href="/a/css/stylesheet.css">

        <script src="https://code.jquery.com/jquery-3.5.1.js"></script>
        <script src="https://cdn.jsdelivr.net/npm/popper.js@1.16.0/dist/umd/popper.min.js"></script>
        <script src="https://stackpath.bootstrapcdn.com/bootstrap/4.5.0/js/bootstrap.min.js"></script>
        <script src="/a/js/geminer.js"></script>
        <script>
            const energyRegenerationInterval = <?=$energy_regeneration_interval?>;
            const miningEnergyCost = <?=$mining_energy_cost?>;
            const loggedIn = <?=$is_logged_in ? "true" : "false"?>;
            var user = <?=$is_logged_in ? json_encode($user) : "null"?>;
            user.shifts_completed = Number(user.shifts_completed);
            const maxEnergy = <?=$is_logged_in ? ($user['is_premium'] ? $energy_storage_limit_premium : $energy_storage_limit_free) : "null"?>;
            const currencySymbol = "<?=$currency_symbol?>";
        </script>

        <title><?=$page_info['title']?></title>
        <?php if (!is_null($page_info['description'])) { ?>
        <meta name="description" content="<?=$page_info['description']?>">
        <?php } ?>
        <?php if ($is_logged_in) echo user_background($user['id']); ?>
    </head>
    <body>
        <noscript>
            This site requires javascript enabled.
        </noscript>

        <nav class="navbar sticky-top navbar-expand-lg navbar-light">
            <div class="dropdown">
                <a class="navbar-brand dropdown-toggle" id="mainNavbarDropdown" role="button" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                    <img src="/a/i/logo256.png" height="40" alt="Geminer">
                </a>
                <div class="dropdown-menu" aria-labelledby="mainNavbarDropdown">
                    <a class="dropdown-item" href="/">Home</a>
                    <a class="dropdown-item" href="/contact.php">Contact</a>
                </div>
            </div>
            <button class="navbar-toggler" type="button" data-toggle="collapse" data-target="#navbarSupportedContent" aria-controls="navbarSupportedContent" aria-expanded="false" aria-label="Toggle navigation">
                <span class="navbar-toggler-icon"></span>
            </button>

            <div class="collapse navbar-collapse" id="navbarSupportedContent">
                <ul class="navbar-nav mr-auto">
                    <li class="nav-item dropdown">
                        <a class="nav-link dropdown-toggle" id="collectionsDropdown" role="button" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
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
                </ul>
                <?php if ($is_logged_in) { ?>
                
                <div class="dropdown">
                    <a class="btn btn-sm btn-dark dropdown-toggle text-white" id="energyDisplay" style="margin-right: 1em" role="button" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                            <span id="energyAmount"><div class="spinner-border spinner-border-sm" role="status"><span class="sr-only"></span></div></span>
                            /
                            <span id="energyMax"><div class="spinner-border spinner-border-sm" role="status"><span class="sr-only"></span></div></span> 
                            <img src="/a/i/energy.png" class="energy-icon" alt="energy">
                    </a>
                    <div class="dropdown-menu" aria-labelledby="dashDropdown">
                        <a class="dropdown-item" href="/dash/mining.php">Go to mine</a>
                        <a class="dropdown-item" href="/dash/location.php">All of the mines</a>
                        <div class="dropdown-divider"></div>
                        <span class="dropdown-item" id="timeUntilNextRegeneration">loading...</span>
                        <span class="dropdown-item" id="timeUntilFullRegeneration">loading...</span>
                    </div>
                </div>
                <script>
                    function toTime(n) {
                        let output = "";
                        if (n > 86400)
                            output += Math.floor(n / 86400)+":";
                        if (n > 3600)
                            output += (Math.floor((n / 3600) % 24)).toString().padStart(2, "0")+":";
                        output += (Math.floor((n / 60) % 60)).toString().padStart(2, "0")+":"+(n % 60).toString().padStart(2, "0");
                        return output;
                    }

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
                                            if (user.energy < maxEnergy)
                                                user.energy++;
                                            $("#energyAmount").html(user.energy);
                                        }
                                        $('#timeUntilNextRegeneration').html("Next regeneration: "+toTime(secondsUntilNextEnergyRegeneration));
                                        secondsUntilFull = user.energy < maxEnergy ? ((energyRegenerationInterval * (maxEnergy - user.energy - 1)) + secondsUntilNextEnergyRegeneration) : 0;
                                        $('#timeUntilFullRegeneration').html("Full regeneration: "+toTime(secondsUntilFull));
                                    }, 1000);
                                }
                            });
                        }
                    });
                </script>
                
                <div class="dropdown">
                    <a class="btn btn-sm btn-dark dropdown-toggle text-white" style="margin-right: 1em" id="financeDropdown" role="button" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                    <?=display_money($user['money'])?>
                    </a>
                    <div class="dropdown-menu" aria-labelledby="financeDropdown">
                        <a class="dropdown-item" href="/finance/marketplace">Marketplace</a>
                        <a class="dropdown-item" href="/finance/sell-gems.php">TVWIEMWYTGI</a>
                    </div>
                </div>
                <div class="dropdown">
                    <?php $tag_style = $tag_styles[$user['tag_style']]; ?>
                    <a class="btn <?=$tag_style->get_classes()?> dropdown-toggle" style="<?=$tag_style->get_style().$tag_fonts[$user['tag_font']]->style?>" role="button" id="userDropdown" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                        <?=generate_pfp_collection($user['id'])?>
                        <?=$user['name']?>
                        <?php if (!$user['read_notifications'] or !$user['read_announcements']) {?>
                        <span class="badge badge-danger">!</span>
                        <?php } ?>
                    </a>
                    <div class="dropdown-menu dropdown-menu-right" aria-labelledby="userDropdown">
                        <a class="dropdown-item" href="/profile?user=<?=$user['name']?>">Profile</a>
                        <a class="dropdown-item" href="/dash/notifications">
                        Notifications
                        <?php if (!$user['read_notifications']) {?>
                        <span class="badge badge-danger">!</span>
                        <?php } ?>
                        </a>
                        <a class="dropdown-item" href="/announcements.php">
                        Announcements
                        <?php if (!$user['read_announcements']) {?>
                        <span class="badge badge-danger">!</span>
                        <?php } ?>
                        </a>
                        <?php if ($user['is_admin']) { ?>
                        <a class="dropdown-item" href="/admin">Admin actions</a>
                        <?php } ?>
                        <?php if (!$user['is_premium']) { ?>
                        <a class="dropdown-item" href="/premium">Upgrade to a premium account</a>
                        <?php } ?>
                        <div class="dropdown-divider"></div>
                        <a class="dropdown-item" href="/log/out.php?redirect_back_to=<?=$_SERVER['REQUEST_URI']?>">Logout</a>
                    </div>
                </div>
                <?php } else { ?>
                <a class="btn btn-outline-dark" href="/log/in.php?redirect_back_to=<?=$_SERVER['REQUEST_URI']?>">
                    Login
                </a>
                <?php } ?>
            </div>
        </nav>

        <div class="container-fluid" id="root">