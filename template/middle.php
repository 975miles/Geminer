</head>
<body>
    <noscript>
        This site requires javascript enabled.
    </noscript>

    <nav class="navbar sticky-top navbar-expand-lg navbar-<?=$navbar_background->dark ? "dark" : "light"?>" style="background: <?=$navbar_background->style?>">
        <div class="dropdown">
            <a class="navbar-brand dropdown-toggle" id="mainNavbarDropdown" role="button" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                <img src="/a/i/logo256.png" height="40" alt="Geminer">
            </a>
            <div class="dropdown-menu" aria-labelledby="mainNavbarDropdown">
                <a class="dropdown-item" href="/">Home</a>
                <a class="dropdown-item" href="/announcements">Announcements</a>
                <a class="dropdown-item" href="/contact">Contact</a>
                <a class="dropdown-item" href="<?=$repo_url?>"><img src="/a/i/github.png"></a>
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
                        <a class="dropdown-item" href="/collection/leaderboard">Top</a>
                        <a class="dropdown-item" href="/collection/random">Random</a>
                        <?php if ($is_logged_in) { ?>
                        <a class="dropdown-item" href="/profile?user=<?=$user['name']?>">Yours</a>
                        <a class="dropdown-item" href="/collection/create">Create new</a>
                        <?php } ?>
                        <a class="dropdown-item" href="/collection/imgconverter">Image converter</a>
                    </div>
                </li>
                <li class="nav-item dropdown">
                    <a class="nav-link dropdown-toggle" id="collectionsDropdown" role="button" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                    Gem boards
                    </a>
                    <div class="dropdown-menu" aria-labelledby="collectionsDropdown">
                        <a class="dropdown-item" href="/board?id=0">Main</a>
                        <a class="dropdown-item" href="/board/top">Top</a>
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
                    <a class="dropdown-item" href="/dash/mining">Go to mine</a>
                    <a class="dropdown-item" href="/dash/location">All of the mines</a>
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
                    <a class="dropdown-item" href="/finance/sell-gems">TVWIEMWYTGI</a>
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
                    <?php if (!$user['read_notifications'] or !$user['read_announcements']) {?>
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
                    <a class="dropdown-item" href="/log/out?redirect_back_to=<?=$_SERVER['REQUEST_URI']?>">Logout</a>
                </div>
            </div>
            <?php } else { ?>
            <a class="btn btn-outline-dark" href="/log/in?redirect_back_to=<?=$_SERVER['REQUEST_URI']?>">
                Login
            </a>
            <?php } ?>
        </div>
    </nav>

    <div id="toastArea" class="position-absolute w-100 d-flex flex-column p-4" style="padding: 0 !important">
        <div class="toast hide" id="toastElem" role="alert" aria-live="assertive" aria-atomic="true" data-autohide="false">
            <div class="toast-header">
                <img class="mr-2" height="16" src="/a/i/logo256.png">
                <strong class="mr-auto toast-title"></strong>
                <button type="button" class="ml-2 mb-1 close" data-dismiss="toast" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="toast-body text-dark">
                Hello, world! This is a toast message.
            </div>
        </div>
    </div>

    <div class="container-fluid" id="root">