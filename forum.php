<?php
include_once 'includes/functions.php';

// If the forum is specified in the url, select that one. Else default to 1, the public forum
$forumId = (isset($_GET['forum']))? $_GET['forum']: 1;
$forumDetails = mysqli_fetch_array(mysqli_query($db, "SELECT * FROM forums WHERE id = $forumId LIMIT 1")); // Get the forum to check the permissions

// Check that the requested forum exists and that the user has permission to view it.
if (!$forumDetails || (!$loggedIn && $forumDetails['perms'] > 0)) {
    header("Location: page/404");
}

// Set user's forum id for likes and edits. If already set, update it's expire time to a year from now
$usersForumId = (isset($_COOKIE['usersForumId']))? $_COOKIE['usersForumId']: md5(uniqid());
setcookie('usersForumId', $usersForumId, time()+60*60*24*365, '/'); // Update/Set

// User is trying to edit a post. Show edit form
if (isset($_GET['edit'])){
    $postId = $_GET['edit'];
    $postQuery = mysqli_query($db, "SELECT * FROM `forum_posts` WHERE `id` = $postId LIMIT 1");
    $post = mysqli_fetch_array($postQuery, MYSQL_ASSOC);
    // Make sure user has permission to edit
    if ($userPosition == 'Webmaster' || $usersForumId == $post['users_forum_id']){
        $forumId = $post['forum'];
        $forumUser = $post['sender'];
        $forumMessage = $post['message'];
        
        // Display form
        addHeader();
        include 'templates/edit_forum_form.php';
        addFooter();
        die();
    }
    else{
        header("Location: ../../forum/".$post['forum']);
    }
}

// User is trying to delete a post. Set the post's `forum` field to 0, the deleted forum
if (isset($_GET['delete'])) {
    $postId = $_GET['delete'];
    $postQuery = mysqli_query($db, "SELECT * FROM `forum_posts` WHERE `id` = $postId LIMIT 1");
    $post = mysqli_fetch_array($postQuery, MYSQL_ASSOC);
    // Make sure user has permission to edit
    if ($userPosition == 'Webmaster' || $usersForumId == $post['users_forum_id']){
        // Move post and it's replies to deleted forum
        mysqli_query($db, "UPDATE forum_posts SET forum = 0 WHERE id = $postId LIMIT 1");
        mysqli_query($db, "UPDATE forum_posts SET forum = 0 WHERE parent_id = $postId");
        
        if (mysqli_error($db))
            exit(mysqli_error($db));
    }
    // If no permission or when succeeded, go back to forum page
    header("Location: ../../forum/".$post['forum']);
}

// Check if an auto gen'd message should be added to public forum
$autoPostTimer = time() - 60*60*24; // seconds in 24hours
$autoPostQuery = mysqli_query($db, "SELECT * FROM forum_posts WHERE forum = 1 ORDER BY id DESC LIMIT 1");
$autoPostLastPost = mysqli_fetch_array($autoPostQuery);

// If the time of the last post was more than 24 hours ago, add auto gen'd post
if ($autoPostTimer > $autoPostLastPost['post_time'] && $forumId == 1) {
    // Get a random photo from the punishment photos
    // $randomIdSQL = "SELECT FLOOR( MAX(id) * RAND()) FROM `photo_punishment` WHERE `used` < 2"; // Gets max id from photos in that event, multiplies it by rand() number between 0 and 1, floor() rounds down to an int
    // $punishmentSQL = "SELECT id,img FROM `photo_punishment` WHERE `used` < 2  AND `id` >= ($randomIdSQL) ORDER BY `id` LIMIT 1";
    // $punishmentPhoto = mysqli_fetch_array(mysqli_query($db, $punishmentSQL), MYSQLI_ASSOC);
    // $autoMessage = "
    //     ************************** AUTOMATICALLY GENERATED MESSAGE **************************
        
    //     Pay attention to me or I will show you more Cian...
        
    //     https://ucdtramp.com/images/punishment_photos/".$photo['img'];

    // // Update the used count of this photo
    // mysqli_query($db, "UPDATE `photo_punishment` SET `used` = `used`+1 WHERE `id`='".$photo['id']."'");

    // $pickupLines = array();
    // $randomPickupLine = $pickupLines[mt_rand(0, count($pickupLines))];
    // $autoMessage = "Pickup line #".$selected_pickup." - ".$pickupLines[$selected_pickup];

    // $forumUser = "The ladies man";
    // $forumMessage = mysqli_real_escape_string($db, htmlentities($autoMessage));
    // $postTime = $autoPostTimer;

    // // Add autogen'd post
    // $addPostSQL = "INSERT INTO forum_posts (forum, users_forum_id, sender, parent_id, post_time, message, ipaddress) VALUES ('1', '0', '$forumUser', 0, $postTime, '$forumMessage', '00000000')";
    // if (!mysqli_query($db, $addPostSQL))
    //     die(json_encode(array('error' => mysqli_error($db))));
}


// Get start for pagination. It's artifically set +1 for prettier urls
$paginationPage = (isset($_GET['paginationPage'])) ? $_GET['paginationPage'] : 1;
$paginationStartIndex = ($paginationPage -1) * $forumDetails['posts_per_page'];

// Get posts for the page
$posts = mysqli_query($db, "SELECT * FROM forum_posts WHERE forum = $forumId AND parent_id = 0 ORDER BY id DESC 
    LIMIT ".$forumDetails['posts_per_page']." OFFSET $paginationStartIndex");

// Used in the javascript to find new posts. TODO: Get id from post elements #id
$newestPostsId = mysqli_fetch_array(mysqli_query($db, "SELECT MAX(id) FROM forum_posts"))['MAX(id)']; 

// Get a value for the name forum field
$username = '';
if (isset($_COOKIE['user'])) {
    $username = $_COOKIE['user'];
} else if (isset($_COOKIE['Milk'])) {
    $username = $_COOKIE['Milk'];
}

// Page variables
$title = $forumDetails['title'];
$description = "Where it's at";
addHeader();
?>

<div class="forum-header">
    <?php
    if ($forumId == '404') {
        echo'
            <div class="forum-header-404">
                <h1>Welcome to the 404 Forum</h1>
                <p>This is a place where you can make a website suggestion or correction and it goes straight to the webmaster. It\'s purple so you know it\'s not the regular forum. The music that you might be hearing, I cant get it out of my head, now it\'s in yours too.</p>
                <audio src="//ucdtramp.com/files/ZeldaTP_Menu_Select_Screen.mp3" loop autoplay controls></audio>
            </div>
            <style>
                .post-header {
                    border-bottom-color: purple;
                }
            </style>
            ';
    }
    else if ($forumId == '1') {
        // Check for a forum notice set in the comittee section
        $noticeContents = mysqli_fetch_array(mysqli_query($db, "SELECT pagecontent FROM pages WHERE pageurl='forumnotice'"))['pagecontent'];
        if ($noticeContents != '') {
            echo '
                <div class="alert forum-notice" title="Notice!">'.
                    $noticeContents.'
                </div>';
        }
        // Check for a poll made in the last 3 days
        $poll = mysqli_fetch_array(mysqli_query($db, "SELECT `id`,`created`,`question` FROM `polls` WHERE `show_on_forum` = 1 ORDER BY `polls`.`id` DESC LIMIT 1 "), MYSQL_ASSOC);
        // Set time to that day to, 00h:00m, first thing in the morning so that the poll will disappear at midnight
        $pollDatetime = new DateTime('@'.$poll['created']);
        $pollDatetime->setTime(0,0); 
        $pollTimestamp = $pollDatetime->getTimestamp();

        // NUmber of seconds in 3 days, take away the time since the poll was created
        $timeLeft =  (60*60*24*3) - (time() - $pollTimestamp);
        
        if($timeLeft > 0){
            echo '
                <div class="alert forum-notice" title="Notice!">
                    <p>
                        <img src="images/emoji/pstar.gif" alt="Pstar">
                        Theres a new poll! 
                        <img src="images/emoji/pstar.gif" alt="Pstar">
                        <small style="cursor:pointer" title="This will disappear when the time is up" class="pull-right">'.seconds_to_time($timeLeft).'</small>
                    </p>
                    <a href="manage_polls?poll='.$poll['id'].'">'.$poll['question'].'</a>
                </div>';
        }
    }
    // used on commapps.php page to show how many posts user hasnt seen
    else if ($forumId == '2') {
        mysqli_query($db, "UPDATE committee_users SET commforum='".time()."' WHERE user='".$_COOKIE["user"]."'");
    }

    // Bug image that link to 404 forum
    if ($forumId != '404') {
        echo '
        <a class="bug-404 animated fadeIn" href="forum/404" title="404 Forum">
            <img src="images/pages/forum/bug.png">
        </a>';
    }
    ?>
</div> <!-- forum header -->

<form class="form-horizontal post-form" onsubmit="return false;" data-parentid="0">
    <div class="form-group forum-post-inputs row">
        <div class="col-sm-3 col-md-2">
            <input type="text" class="form-control forum-user" placeholder="Mr. Smith" value="<?= $username ?>" >
            <input type="hidden" class="js-forumId" value="<?= $forumId ?>">
            <input type="hidden" class="js-newestPostsId" value="<?= $newestPostsId ?>">
        </div>
        <div class="col-sm-9 col-md-10">
            <textarea class="form-control forum-message" rows="2" placeholder="What's on your mind?"></textarea>
        </div>
    </div>
    <!-- Buttons -->
    <div class="form-group row">
        <div class="col-sm-3 col-md-2">
            <button type="submit" class="form-control btn btn-primary btn-post">
                Post
            </button>
        </div>
        <div class="col-sm-9 col-md-10">
            <button type="button" class="btn btn-default btn-toggle-emoji" data-toggle="collapse" data-target="#emoji-picker" aria-expanded="false" aria-controls="emoji-picker">:)</button>
            <!-- <button type="button" class="btn btn-default" data-toggle="collapse" data-target="#jquery-uploader" aria-expanded="false" aria-controls="jquery-uploader"><i class="glyphicon glyphicon-plus "></i> <span class="btn-toggle-upload"></span></button> -->
            <button type="button" class="btn btn-default pull-right" data-toggle="collapse" data-target="#formatting-help" aria-expanded="false" aria-controls="formatting-help"><span class="btn-toggle-formatting"></span></button>
            <a class="btn btn-default pull-right a-link-stats" href="forum_stats.php"><span class="btn-link-stats"></span></a>
            <button type="button" class="btn btn-default pull-right btn-toggle-push animated" data-toggle="collapse" data-target="#push-notifications" aria-expanded="false" aria-controls="push-notifications">
                <img src="images/pages/forum/mario-block-btn.png" alt="Bonus">
            </button>
        </div>
    </div>
</form>

<!-- Dropdowns for emoji, file uploader and help-->
<div class="row">
    <div class="col-xs-12 well collapse" id="emoji-picker">
        <?php include 'templates/emoji_picker.php'; ?>
    </div>
    <div class="col-xs-12 collapse well" id="push-notifications" style="padding:1em;">
        <p>
            If you would like to recieve notifications about new forum posts, click the toggle below. This feature is only available in <strong>Chrome</strong> 42 or higher and is not yet supported at all on iOS.
        </p>
        <div class="material-checkbox">
            <input type="checkbox" id="material-checkbox" class="material-checkbox__box hidden" disabled/>
            <label for="material-checkbox" class="material-checkbox__label"></label>
            <span class="material-checkbox__message" style="padding-left:1em;">Enable push notifications</span>

            <button class="btn btn-sm btn-warning pull-right animated btn-send-me-push">Test</button>
        </div>
        <div class="material-checkbox__error"></div>
    </div>
    <div class="col-xs-12 collapse well" id="jquery-uploader">
        <!-- The file upload form used as target for the file upload widget -->
        <form id="fileupload" action="" method="POST" enctype="multipart/form-data">
            <!-- Redirect browsers with JavaScript disabled to the origin page -->
            <noscript><input type="hidden" name="redirect" value="https://blueimp.github.io/jQuery-File-Upload/"></noscript>
            <!-- The fileupload-buttonbar contains buttons to add/delete files and start/cancel the upload -->
            <div class="row fileupload-buttonbar">
                <div class="col-xs-12 btn-group">
                    <!-- The fileinput-button span is used to style the file input field as button -->
                    <span class="btn btn-success fileinput-button">
                        <i class="glyphicon glyphicon-plus"></i>
                        <span>Add</span>
                        <input type="file" name="files[]" multiple>
                    </span>
                    <button type="submit" class="btn btn-primary start">
                        <i class="glyphicon glyphicon-upload"></i>
                        <span>Start</span>
                    </button>
                    <button type="reset" class="btn btn-warning cancel">
                        <i class="glyphicon glyphicon-ban-circle"></i>
                        <span>Cancel</span>
                    </button>
                    <button type="button" class="btn btn-danger delete">
                        <i class="glyphicon glyphicon-trash"></i>
                        <span>Delete</span>
                    </button>
                    <!-- <input type="checkbox" class="toggle"> -->
                    <!-- The global file processing state -->
                    <!-- <span class="fileupload-process"></span> -->
                </div>
                <!-- The global progress state -->
                <div class="col-xs-12 fileupload-progress fade">
                    <!-- The global progress bar -->
                    <div class="progress progress-striped active" role="progressbar" aria-valuemin="0" aria-valuemax="100">
                        <div class="progress-bar progress-bar-success" style="width:0%;"></div>
                    </div>
                    <!-- The extended global progress state -->
                    <div class="progress-extended">&nbsp;</div>
                </div>
            </div>
            <!-- The table listing the files available for upload/download -->
            <table role="presentation" class="table table-striped"><tbody class="files"></tbody></table>
        </form>
    </div>
    <div class="collapse" id="formatting-help">
        <?php include 'templates/formatting_help.php'; ?>
    </div>
</div>

<hr>

<div class="new-post-notification animated"><!-- 'Scroll to' posts appear in here --></div>
<audio id="notification-sound">
    <source src="files/sounds/smb_jump.mp3" type="audio/mpeg">
    <source src="files/sounds/smb_jump.wav" type="audio/wav">
</audio>

<div class="row" id="posts-container">
    <?php
    // Loop and print out each forum post
    while ($post = mysqli_fetch_array($posts, MYSQL_ASSOC)) {
        // Turns the sql array into a properly formated post array
        $post = formatPost($post);
        $postHtml = post2HTML($post);
        // Get all the replies for this post
        $repliesHtml = '';
        $postReplies = mysqli_query($db, "SELECT * FROM forum_posts WHERE parent_id='" . $post['id'] . "' AND forum='$forumId' ");
        while ($reply = mysqli_fetch_array($postReplies)) {
            $repliesHtml .= post2HTML(formatPost($reply));
        }

        // Print a forum post and all its replies
        echo '
        <div class="col-xs-12 forum-post" id="'. $post['id'] .'">'.
            $postHtml .'
            <div class="post-replies">'.
                $repliesHtml .'
            </div>
            <div class="post-footer">
                <!-- For reply box -->
                <button type="button" class="btn-link btn-reply" data-click="reply">reply</button>
                <form action="" class="form-horizontal reply-form" data-parentid="'. $post['id'] .'"></form>
            </div>
        </div> <!-- forum-post -->';
    }
    ?>
</div> <!-- .row #posts-container -->

<hr>

<?php

$all_posts = mysqli_query($db, "SELECT * FROM forum_posts WHERE `forum` = '$forumId' AND `parent_id` = 0");
$num_posts = mysqli_num_rows($all_posts);
$num_pages = ceil($num_posts / $forumDetails['posts_per_page']);

$paginationHtml = '';
for ($i = 1; $i < $num_pages; $i++) {
    if ($i == $paginationPage)
        $paginationHtml .= '<li class="active"><a href="forum/'.$forumId.'/page/'.$i.'">'.$i.'</a></li>';
    else
        $paginationHtml .= '<li><a href="forum/'.$forumId.'/page/'.$i.'">'.$i.'</a></li>';
}
?>

<div class="row">
    <div class="col-xs-12" id="jump_page">
        <span>Jump to a page:</span>
        <nav>
          <ul class="pagination">
            <li class="<?= ($paginationPage == 0)? 'disabled': ''; ?>">
              <a href="forum/<?= $forumId ?>/page/<?= ($paginationPage - 1) ?>" aria-label="Previous">
                <span aria-hidden="true">&laquo;</span>
              </a>
            </li>
            <?= $paginationHtml ?>
            <li class="<?= ($paginationPage == $num_pages)? 'disabled': ''; ?>">
              <a href="forum/<?= $forumId ?>/page/<?= ($paginationPage + 1) ?>" aria-label="Next">
                <span aria-hidden="true">&raquo;</span>
              </a>
            </li>
          </ul>
        </nav>
    </div>
</div>
<?php
addFooter();
?>
<!-- Files for forum live undates and push notifications -->
<script src="dist/js/jquery.timeago.js"></script>
<script src="dist/js/jquery.titleAlert.js"></script>
<script src="dist/js/push.js"></script>
<script>
    $(document).ready(function () {
        Forum.init();
    });
</script>

<!-- Files for the emoji picker -->
<!-- Turns all the shortnames into images -->
<script src="dist/js/emojione.js"></script>
<!-- Puts the images into the page -->
<script src="dist/js/emoji.js"></script>
