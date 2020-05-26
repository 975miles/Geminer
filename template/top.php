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
            var loggedIn = <?=$is_logged_in ? "true" : "false"?>;
            var user = <?=$is_logged_in ? json_encode($user) : "null"?>;
        </script>

        <title><?=$page_info['title']?></title>
        <?php if (!is_null($page_info['description'])) { ?>
            <meta name="description" content="<?=$page_info['description']?>">
        <?php } ?>
    </head>
    <body>
        <noscript>
            This site requires javascript enabled. Dont trust us? Check the <a href="<?=$repo_url?>">source code</a>.
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
                </ul>
                <?php if ($is_logged_in) { ?>
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
                <?php } else { ?>
                    <a class="btn btn-outline-dark" href="/log/in.php">
                        Login
                    </a>
                <?php } ?>
            </div>
        </nav>

        <div class="container-fluid">