<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title><?= $title ;?></title>
    <meta name="description" content="<?= $description ?>">

    <link rel="apple-touch-icon" sizes="57x57" href="//ucdtramp.com/images/favicon/apple-touch-icon-57x57.png">
    <link rel="apple-touch-icon" sizes="60x60" href="//ucdtramp.com/images/favicon/apple-touch-icon-60x60.png">
    <link rel="apple-touch-icon" sizes="72x72" href="//ucdtramp.com/images/favicon/apple-touch-icon-72x72.png">
    <link rel="apple-touch-icon" sizes="76x76" href="//ucdtramp.com/images/favicon/apple-touch-icon-76x76.png">
    <link rel="apple-touch-icon" sizes="114x114" href="//ucdtramp.com/images/favicon/apple-touch-icon-114x114.png">
    <link rel="apple-touch-icon" sizes="120x120" href="//ucdtramp.com/images/favicon/apple-touch-icon-120x120.png">
    <link rel="apple-touch-icon" sizes="144x144" href="//ucdtramp.com/images/favicon/apple-touch-icon-144x144.png">
    <link rel="apple-touch-icon" sizes="152x152" href="//ucdtramp.com/images/favicon/apple-touch-icon-152x152.png">
    <link rel="apple-touch-icon" sizes="180x180" href="//ucdtramp.com/images/favicon/apple-touch-icon-180x180.png">
    <link rel="icon" type="image/png" href="//ucdtramp.com/images/favicon/favicon-32x32.png" sizes="32x32">
    <link rel="icon" type="image/png" href="//ucdtramp.com/images/favicon/favicon-194x194.png" sizes="194x194">
    <link rel="icon" type="image/png" href="//ucdtramp.com/images/favicon/favicon-96x96.png" sizes="96x96">
    <link rel="icon" type="image/png" href="//ucdtramp.com/images/favicon/android-chrome-192x192.png" sizes="192x192">
    <link rel="icon" type="image/png" href="//ucdtramp.com/images/favicon/favicon-16x16.png" sizes="16x16">
    <link rel="manifest" href="manifest.json">
    <meta name="mobile-web-app-capable" content="yes">
    <meta name="apple-mobile-web-app-title" content="UCDTC">
    <meta name="application-name" content="UCDTC">
    <meta name="msapplication-TileColor" content="#2b5797">
    <meta name="msapplication-TileImage" content="//ucdtramp.com/images/favicon/mstile-144x144.png">
    <!-- <meta name="theme-color" content="#1971ef"> Android colour -->
    <?= ($_SERVER['SERVER_NAME'] == 'localhost')?
        '<BASE href="/red.ucdtc/">':
        '<BASE href="//ucdtramp.com/refined/">';
    ?>

    <!-- Facebook meta info -->
    <meta property="og:site_name" content="UCD Trampoline Club"/>
    <meta property="og:image" content="//ucdtramp.com/images/favicon/apple-touch-icon-180x180.png"/>
    <meta property="og:title" content="<?= $title ?>"/>
    <meta property="og:description" content="<?= $description ?>"/>        

    <!-- CSS Links -->
    <!-- <link rel="stylesheet" href="//maxcdn.bootstrapcdn.com/bootstrap/3.3.4/css/bootstrap.min.css"> -->
    <link href="dist/css/bootstrap.css" rel="stylesheet">
    <link href="dist/css/main.css" rel="stylesheet">
    <link href="dist/css/desktop.css" rel="stylesheet" media="(min-width: 768px)">
    <!-- Some css in footer -->

    <!-- jQuery (necessary for Bootstrap's JavaScript plugins) -->
    <!-- At the top and not the bottom with all the other js because of js in page['content'] in page.php and this was the easiest solution -->
    <script src="dist/js/jquery-1.11.3.min.js"></script>
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
        
        <div class="header-logo">
            <img class="animated zoomInDown animation-delay-5" src="images/backgrounds/men.png" alt="UCDTC Evolutoion logo">
        </div>
    </header>

    <!-- Contains everything -->
    <div class="background-container">
        <!-- Clicking this scolls the page to the navbar -->
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
                    <li><a href="index" title="Home Page">News</a></li>
                    <li class="dropdown">
                        <a href="about" class="dropdown-toggle" data-toggle="dropdown" aria-expanded="false">
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
                    <li><a href="events">Events</a></li>
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
                            <li><a href="page/squad">Squad</a></li>
                            <li><a href="manage_polls.php?show=all">Polls</a></li>
                        </ul>
                    </li>                    
                    <li><a href="page/contact" title="Self explanatory really">Contact</a></li>
                  </ul>
                </div><!-- /.navbar-collapse -->
              </div><!-- /.container-fluid -->
            </nav>

            <div class="content-padding">    