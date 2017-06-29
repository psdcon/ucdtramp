<?php
include_once 'includes/functions.php';
$title = 'UCD Trampoline Club';
$description = 'Welcome to the UCDTC website. Here you can find out what the club is about and what we get up to.
Why not drop us a post on the Forum or say hi to Ask a Tramp? (Don\'t say hi to Ask a Tramp)';
addHeader();
?>
    <div class="row full-width gallery js-flickity" data-flickity-options='{ "setGallerySize": false, "autoPlay": 5000, "wrapAround": true }'>
        <div class="gallery-cell">
            <div class="slider-caption slider-transition">Fresher's Night 2016 - Fresh to the Graaaave!</div>
            <img src="//ucdtramp.com/photos/freshersnight1617/preview/freshersnight1617x800.jpg" alt="intervarsities1516" />
        </div>
        <div class="gallery-cell">
            <div class="slider-caption slider-transition">The BBQ - It was barbecool</div>
            <img src="//ucdtramp.com/photos/bbq1516/preview/bbq1516x800.jpg" alt="ISTO 1415" />
        </div>
        <div class="gallery-cell">
            <div class="slider-caption slider-transition">Bouncy ball - Not a lot of bouncing, plenty of bawlin</div>
            <img src="//ucdtramp.com/photos/bouncyball1516/preview/bouncyball1516x800.jpg" alt="ISTO 1415" />
        </div>
    </div>
    <hr class="hidden-xs">

    <div class="row">
        <div class="col-xs-12 col-md-push-6 col-md-6">
            <div class="row">
                <div class="col-xs-12">
                    <h4><strong>We socialise</strong></h4>
                    <div class="social-icons flex-container">
                        <a href="https://www.facebook.com/UCDTC" class="social-icon soi-facebook animated fadeInDown animation-delay-2"><i class="fa fa-facebook"></i></a>
                        <a href="https://instagram.com/ucdtrampoline/" class="social-icon soi-instagram animated fadeInDown animation-delay-3"><i class="fa fa-instagram"></i></a>
                        <a href="https://twitter.com/ucdtramp" class="social-icon soi-twitter animated fadeInDown animation-delay-4"><i class="fa fa-twitter"></i></a>
                        <a href="https://www.youtube.com/user/ucdtramp" class="social-icon soi-youtube animated fadeInDown animation-delay-5"><i class="fa fa-youtube-play"></i></a>
                    </div>
                    <div class="animated fadeInDown animation-delay-1" style="text-align:center;padding-top:1em">
                        <img style="max-width:100%;width:170px" src="images/pages/homepage/snapcode.svg" alt="Snapchat">
                    </div>
                </div>

                <!-- News -->
                <div class="col-xs-12">
                    <hr class="visible-xs-block">
                    <h3 class="news-header"><strong>Club News</strong></h3>
                    <?php
                    if($loggedIn){
                        echo '
                        <h4>
                            <a href="manage_news.php?action=add">
                                + Add News
                            </a>
                        </h4>
                        ';
                    }
                    // Get all the news that're in use
                    $newsQuery = "SELECT *,DATE_FORMAT(datetime,'%a %D %b') as formatted_date FROM news_items WHERE inuse = '1' ORDER BY id DESC LIMIT 5";
                    $newsResult = mysqli_query($db, $newsQuery);
                    if(mysqli_num_rows($newsResult) == 0){
                        echo '
                        <div class="news-item">
                            <div class="news-item__heading">
                                <span class="news-item__title">No News</span>
                            </div>
                            <p class="news-item__content">I\'d tell you to check again tomorrow but you should go outside and live your life instead.</p>
                        </div>';
                    }
                    // Loop through each item and print
                    while($newsItem = mysqli_fetch_array($newsResult, MYSQL_ASSOC)){
                        echo '
                        <div class="news-item" id="news_'.$newsItem['id'].'">
                            <div class="news-item__heading">
                                <span class="news-item__title">'.
                                    smilify($newsItem["title"], NULL).'
                                </span>
                                <small class="news-item__details">'.
                                    $newsItem["formatted_date"].' by '.$newsItem["username"].' ';
                                    if($loggedIn){
                                        echo'
                                        <a title="Edit" style="margin-left:1em;" href="manage_news.php?action=Edit&newsId='.$newsItem["id"].'">
                                            <i class="fa fa-pencil"></i> Edit
                                        </a>';
                                    }
                        echo '
                                </small>
                            </div>
                            <p class="news-item__content">'.smilify($newsItem["text"], NULL).'</p>
                        </div><!--/news item-->';
                    }?>
                </div> <!-- news column -->
            </div> <!-- notes and news row -->
        </div>  <!-- notes and news column -->
        <div class="col-xs-12 col-md-pull-6 col-md-6">
                <div class="row">
                <!-- Training Times -->
                <div class="col-xs-12 col-sm-6">
                    <hr class="visible-xs-block">
                    <h4><strong>Training Times</strong></h4>

                    <p>
                        <strong>Trampolining</strong> <br>
                        Tuesday 8pm - 10pm <br>
                        Thursday 5pm - 7pm <br>
                    </p>

                    <p>
                        <strong>Gymnastics</strong> <br>
                        Monday 1pm - 3pm <br>
                        Tuesday 9am - 11pm <br>
                        Thursday 10am - 12pm <br>
                        <a href="https://www.facebook.com/groups/773175236080856/">Join the fb group here</a>
                    </p>
                    <p>
                        Trainings take place in <a href="https://www.google.com/maps/place/UCD+Sport+and+Fitness/@53.308112,-6.228166,17z/data=!3m1!4b1!4m2!3m1!1s0x4867093667320733:0x792c4381232c6b96!6m1!1e1">UCD's Sport Centre </a>
                    </p>
                </div>
                <!-- Upcoming events -->
                <div class="col-xs-12 col-sm-6">
                    <h4><strong>Upcoming events</strong></h4>
                    <p>
                        <strong>November</strong> <br>
                        6th In House Comp  <br>
                        18th - 20th Intervarsities <br>
                    </p>
                    <p>
                        <strong>December</strong> <br>
                        TBD Christmas Fun Day <br>
                    </p>
                    <p>
                        Any questions or suggestions,<br>
                        talk to <a href="https://ucdtramp.com/page/contact">Michael</a>.
                    </p>
                </div>
            </div>
            <hr class="visible-xs-block">
            <h3><strong>Greetings</strong></h3>
            <p>
                Hello there and welcome to the UCD Trampoline club website!  <br>
                Before we begin I'd like to addresss a few FAQs: <br>
                <em><strong>No</strong></em>, we don't just jump up and down on a trampoline. <br>
                <em><strong>Yes</strong></em>, you can join with no experience. <br>
                <em><strong>No</strong></em>, no you don't have to wear tight lycra (but it helps) <br>
                <em><strong>Yes</strong></em>, this is a real sport. It's been in the Olympics since Sydney 2000. <br>
                <em><strong>No</strong></em>, you can't "do a flip" until you've worked up to the skill. <br>
                <em><strong>Yes</strong></em>, it's only €15 for a whole years membership!  <br>
            </p>
            <p><?=smilify("Since the dawn of time man has looked :eyes: to the sky :partly_sunny:, at the birds :whale2: and the bees :bee: and the pterodactyls :dragon:, with one dream; to <span title=\"#theMoonLandingsWereFaked\">fly <span class=\"emojione-1F680\">🚀</span></span>.",null)?> Here in the UCD Trampoline club, we help you fly up... and back down again and back up in a repeated fashion, usually wearing some kind of lycra with white socks on the most beautifully pointed toes, all this fuelled by your own massive muscles! <span style="cursor:pointer;" onclick="playSound();" onmouseenter="playSound();"><i class="fa fa-volume-up"></i> YEEEAAHHH!</span></p>
            <audio id="yeah" src="files/sounds/yeah.mp3"></audio>
            <script>
                function playSound(){
                    document.getElementById('yeah').load();
                    document.getElementById('yeah').play();
                }
            </script>
            <p>Trampolining is a unique sport that combines cardio, co-ordination, core strength, flexibility, balance and stamina and wait for it, you won't believe this one; it's fun <?=smilify(':tramp:',null);?>. With 6 olympic standard trampolines and experienced coaches available for <strong>one on one coaching</strong> at every training session, the odds are definitely in your favour. </p>

            What do you want in a sports club?
            <ul class="greetings-list">
                <li>Regular training times like every <strong>Tuesday 8-10</strong> and <strong>Thursdays 5-7</strong>?</li>
                <li>A club unaffected by the weather? Indoors!</li>
                <li>A chance to talk to some people? What else would you be doing when you're waiting for your turn!</li>
                <li>A club with a formidable background? <strong>UCD Club of the year 2011</strong> and Intervarsities winners the last 4 years running!</li>
            </ul>

            <p>If you're here looking at the website, you're obviously interested. You'll go far my friend <span class="emojione-1F463" title=":footprints:">👣</span>. Take a look around the site, we're quite proud of it. You'll find details on the history of the club, pictures and reports from our (many) trips away on the <a href="events">Events</a> page, our coaches and committee on the <a href="about">About</a> page and games and videos and everything else on the <a href="everythingelse">EE</a> page. It's all just a click away...</p>

            <p>
                Yours with reckless abandon, <br><em>El Crapitan Rosie</em>
            </p>

            <h4><strong>Note from the Webmaster</strong></h4>
            <p>WELCOME! I hope you like the site. If you don't, Paul will weep a Mississippi of tears ... </p>
            <p title="Old and/or stupid people">Naturally for the best experience you should probably <strong>use Google Chrome</strong> as your browser but come on, who doesn't these days eh?!</p>
            <p>If you have any feedback, suggestions, secrets, adoration or ideas, head over to the <a href="forum/404">404 Forum</a> and we'll see what we can do.</p>

            <p>
                Yours exclusively,<br>
                <i>Jason</i>
                <br><br>
                (and Paul)
            </p>

            <h4><strong>History of the club</strong></h4>
            <p>The UCD Trampoline Club was set up by Andrew Cahill in the 1980's; the era of the A-Team, Gummi Bears, Air Wolf, Jem, Teddy Ruxpin, Brave Star and the Samurai Pizza Cats. The club is equally as cool as any of these things.</p>
        </div>
    </div>


<?php
addFooter();
?>
<link rel="stylesheet" href="css/flickity.css">
<script src="js/libs/flickity.min.js"></script>
