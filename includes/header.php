<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title><?= $title ;?></title>
    <meta name="description" content="<?= $description ?>">

    <!-- When using localhost, set the base to be the name of the wamp/www/foler ucdtramp -->
    <?= ($_SERVER['SERVER_NAME'] == 'ucdtramp')?
        '<BASE href="/">':
        '<BASE href="//ucdtramp.com/">';
    ?>

    <!-- Favicons -->
    <link rel="apple-touch-icon" sizes="180x180" href="images/favicon/apple-touch-icon.png">
    <link rel="icon" type="image/png" sizes="32x32" href="images/favicon/favicon-32x32.png">
    <link rel="icon" type="image/png" sizes="16x16" href="images/favicon/favicon-16x16.png">
    <link rel="mask-icon" href="images/favicon/safari-pinned-tab.svg" color="#5bbad5">

    <link rel="manifest" href="/manifest.json">
    <meta name="theme-color" content="#1974f2"> <!-- Android colour -->
    <meta name="mobile-web-app-capable" content="yes">
    <meta name="apple-mobile-web-app-title" content="UCDTC">
    <meta name="application-name" content="UCDTC">

    <!-- Facebook meta info -->
    <meta property="og:site_name" content="UCD Trampoline Club"/>
    <meta property="og:image" content="images/favicon/android-chrome-512x512.png"/>
    <meta property="og:title" content="<?= $title ?>"/>
    <meta property="og:description" content="<?= $description ?>"/>

    <!-- CSS Links -->
    <!-- <link rel="stylesheet" href="//maxcdn.bootstrapcdn.com/bootstrap/3.3.4/css/bootstrap.min.css"> -->
    <link href="css/bootstrap.css" rel="stylesheet">
    <link href="css/main.css" rel="stylesheet">
    <link href="css/desktop.css" rel="stylesheet" media="(min-width: 768px)">
    <?= ($theme)? '<link href="themes/'.$theme.'/theme.css" rel="stylesheet">': "";?>
    <!-- Some css in footer -->

    <!-- jQuery (necessary for Bootstrap's JavaScript plugins) -->
    <!-- Included here rather in footer.php in case of js in page['content'] in page.php and this was the easiest solution -->
    <script src="js/libs/jquery-1.11.3.min.js"></script>
    <!-- <script src="https://ajax.googleapis.com/ajax/libs/jquery/1.11.2/jquery.min.js"></script> -->
</head>

<body>
    <header>
        <a class="header-UCD-crest" href="http://www.ucd.ie/" target="_blank"><img src="images/backgrounds/UCDlogo.jpg" alt="UCD Crest"></a>
        <?php
        // If not logged in, include the login form. Otherwise show the logged in buttons
        if(!$loggedIn){
            include './templates/login_modal.html';
            echo '<button type="button" class="btn btn-default header-login-btns" data-toggle="modal" data-target="#login-modal">Login</button>';
        }
        else{
            echo '
            <div class="btn-group header-login-btns" role="group" aria-label="Loggedin Actions">
              <a href="includes/process_login" class="btn btn-primary">Logout</a>
              <a href="committee" class="btn btn-default">Committee</a>
            </div>';
        }
        ?>
        <div class="header-logo" style="position: relative;">
            <?php
                // if ($theme)
                //     echo '<img style="width:20%" class="header-logo-image ghost" src="themes/'.$theme.'/ghost.png" alt="UCDTC Ghost">';
                // else
                    echo '<img class="header-logo-image animated zoomInDown animation-delay-5" src="images/backgrounds/men.png" alt="UCDTC Evolutoion logo" style="position: absolute;top: 0;">';
            ?>
        </div>
    </header>

    <!-- Contains everything -->
    <div class="background-container">
        <!-- Clicking this scrolls the page to the navbar -->
        <span class="scrollToNav" title="Scroll to Navbar"></span>
        <!-- Both background elements -->
        <div class="background-sides"></div>
        <div class="background-wood"></div>

        <!-- Contains navbar and content -->
        <div class="content" id="content">

            <nav class="navbar-yellow">
              <div class="container-nav-yellow">
                <!-- Brand and toggle get grouped for better mobile display -->
                <div class="navbar-header">
                    <button type="button" class="navbar-toggle collapsed" data-toggle="showhide" data-target="#navbar-main-showhide" aria-expanded="false">
                        <span class="sr-only">Toggle navigation</span>
                        <span class="icon-bar"></span>
                        <span class="icon-bar"></span>
                        <span class="icon-bar"></span>
                    </button>
                    <span class="navbar-brand">UCD Trampoline Club</span>
                </div>

                <!-- Collect the nav links, forms, and other content for toggling -->
                <div class="collapse navbar-collapse" id="navbar-main-showhide">
                  <ul class="nav navbar-nav navbar-ul-yellow">
                    <li><a href="news" title="Home Page">News</a></li>
                    <li class="dropdown">
                        <a href="about" title="" class="dropdown-toggle" data-toggle="dropdown" aria-expanded="false">
                            About <span class="caret"></span>
                        </a>
                        <ul class="dropdown-menu" role="menu">
                            <li><a href="about">About</a></li>
                            <li role="separator" class="divider"></li>
                            <li><a href="about#fitness">Fitness</a></li>
                            <li><a href="about#social">Social</a></li>
                            <li><a href="about#committee">Committee</a></li>
                            <li><a href="about#coaches">Coaches</a></li>
                        </ul>
                    </li>
                    <li><a href="events" title="Past Events and their Reports">Events</a></li>
                    <li>
                        <a href="forum" title="Where it's at!" style="position:relative;">FORUM
                            <span class="lastPostTime"></span>
                        </a>
                    </li>
                    <li><a href="gallery" title="Photo Gallery">Gallery</a></li>
                    <li class="dropdown">
                        <a href="everythingelse" title="Everything Else" class="dropdown-toggle" data-toggle="dropdown" aria-expanded="false">
                            EE <span class="caret"></span>
                        </a>
                        <ul class="dropdown-menu" role="menu">
                            <li><a href="everythingelse">Everything Else</a></li>
                            <li role="separator" class="divider"></li>
                            <li><a href="tariff">Tariff</a></li>
                            <li><a href="askatramp">Ask A Tramp</a></li>
                            <li><a href="youtubevids">Youtube Vids</a></li>
                            <li><a href="page/squad">Squad</a></li>
                            <li><a href="polls">Polls</a></li>
                        </ul>
                    </li>
                    <li><a href="page/contact" title="Talk to us! Please...">Contact</a></li>
                  </ul>
                </div><!-- /.navbar-collapse -->
              </div><!-- /.container-fluid -->
            </nav>

            <div class="content-padding">
