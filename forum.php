<?php
include_once 'includes/functions.php';
include_once 'includes/functions_forum.php';

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
        $title="Edit Post";
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
    header("Location: ../../forum");
}

// Check if an auto generated message should be added to public forum
$autoGenTimer = time() - 60*60*24; // the time 24 hours ago in seconds
$autoGenLastPost = mysqli_fetch_array(mysqli_query($db, "SELECT post_time FROM forum_posts WHERE forum = 1 ORDER BY id DESC LIMIT 1"), MYSQLI_ASSOC);

// If the time of the last post was more than 24 hours ago, add auto gen'd post
if ($autoGenTimer > $autoGenLastPost['post_time'] && $forumId == 1) {
    // Get a random photo from the punishment photos
    // This doesn't work any more $randomIdSQL = "SELECT FLOOR( MAX(id) * RAND()) FROM `photo_punishment` WHERE `used` < 2 AND `id` > 364"; // Gets max id from photos in that event, multiplies it by rand() number between 0 and 1, floor() rounds down to an int
    // $punishmentSQL = "SELECT id,img FROM `photo_punishment` WHERE `used` < 2  AND `id` >= ($randomIdSQL) ORDER BY `id` LIMIT 1";
    $punishmentSQL = "SELECT id,img FROM `photo_punishment` WHERE `used` < 2 AND `id` > 0 ORDER BY `id` LIMIT 1";
    $punishmentPhoto = mysqli_fetch_array(mysqli_query($db, $punishmentSQL), MYSQLI_ASSOC);
    $autoMessage = "
        ************************** AUTOMATICALLY GENERATED MESSAGE **************************
        It's been 24 hours again. Here's a photo you've never seen before.
		https://ucdtramp.com/images/punishment_photos/".$punishmentPhoto['img'];

    // Update the used count of this photo
    mysqli_query($db, "UPDATE `photo_punishment` SET `used` = `used`+1 WHERE `id`=".$punishmentPhoto['id']);

    // $pickupLines = array();
    // $randomPickupLine = $pickupLines[mt_rand(0, count($pickupLines))];
    // $autoMessage = "Pickup line #".$selected_pickup." - ".$pickupLines[$selected_pickup];

    $forumUser = "THE FORUM";
    $forumMessage = mysqli_real_escape_string($db, htmlentities($autoMessage));
    $postTime = time();

    // Add autogen'd post
    $addPostSQL = "INSERT INTO forum_posts (forum, users_forum_id, sender, parent_id, post_time, message, ipaddress) VALUES ('1', '0', '$forumUser', 0, $postTime, '$forumMessage', '00000000')";
    if (!mysqli_query($db, $addPostSQL))
        die(json_encode(array('error' => mysqli_error($db))));
}


// Get start for pagination. It's artificially set +1 for prettier urls
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
    if ($forumId == '1') {
        // Check for a forum notice set in the comittee section
        $noticeContents = mysqli_fetch_array(mysqli_query($db, "SELECT pagecontent FROM pages WHERE pageurl='forumnotice'"))['pagecontent'];
        if ($noticeContents != '') {
            if ($loggedIn) {
                $noticeContents .= '<a href="https://ucdtramp.com/committee?edit_forum_notice=true#forum-notice-me-please" class="pull-right">Edit</a>';
            }
            echo '
                <div class="alert forum-notice" title="Notice!">'.
                    $noticeContents.'
                </div>
                <audio id="yeah" src="files/sounds/yeah.mp3"></audio>
                <script>
                    function playSound(){
                        document.getElementById("yeah").load();
                        document.getElementById("yeah").play();
                    }
                </script>
                ';
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
                    <small style="cursor:pointer" title="This will disappear when the time reaches 0" class="pull-right">'.seconds_to_time($timeLeft).'</small>
                    <img src="images/emoji/normal-smilies/pstar.gif" alt="Pstar">
                    New Poll!
                    <img src="images/emoji/normal-smilies/pstar.gif" alt="Pstar">
                    <a href="polls/'.$poll['id'].'">'.$poll['question'].'</a>

                </div>';
        }
    }
    else if ($forumId == '404') {
        echo'
            <div class="forum-header-404">
                <h1>The 404rum</h1>
                <p>Something wrong with the website? Make a suggestion or correction and it goes straight to the webmaster.</p>
                <p>The music that you might be hearing, I cant get it out of my head, now it\'s in yours too.</p>
                <audio style="max-width:100%;" src="//ucdtramp.com/files/ZeldaTP_Menu_Select_Screen.mp3" loop autoplay controls></audio>
            </div>
            <style>
                .post-header {
                    border-bottom-color: #BA55D3;
                }
                .btn-primary, .btn-primary[disabled] {
                    background-color: #6c33b7;
                    border-color: #48168a;
                }
            </style>
            ';
    }
    // used on commapps.php page to show how many posts user hasn't seen
    else if ($forumId == '2') {
        mysqli_query($db, "UPDATE committee_users SET commforum='".time()."' WHERE user='".$_COOKIE["user"]."'");
    }

    // Bug image that links to 404 forum
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
            <input type="text" class="form-control forum-user" placeholder="Mr(s). Smith" value="<?= $username ?>" >
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
            <button type="submit" class="form-control btn btn-primary btn-post ladda-button" data-style="slide-up">
                <span class="ladda-label">Post</span>
            </button>
        </div>
        <div class="col-sm-9 col-md-10 js-buttons">
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

<!-- Dropdowns for emoji, file uploader (not implemented (yet?)) and help-->
<div class="row">
    <div class="col-xs-12 well collapse" id="emoji-picker">
        <?php include 'templates/emoji_picker.php'; ?>
    </div>
    <div class="col-xs-12 collapse well" id="push-notifications" style="padding:1em;">
        <p>
            If you would like to receive notifications about new forum posts, click the toggle below. This feature is only available in <strong>Chrome</strong> 42 or higher and is not yet supported at all on iOS.
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

<?php
    // Helping out Rosie

    // Days since last auto forum post
    $lastAutoGendPostTimestamp = mysqli_fetch_array(mysqli_query($db, "SELECT post_time FROM forum_posts WHERE users_forum_id = '0' ORDER BY id DESC LIMIT 1"), MYSQLI_ASSOC);
    $secsSinceLastAutoGendPost = time() - $lastAutoGendPostTimestamp['post_time'];
    $daysSinceLastAutoGendPost = floor($secsSinceLastAutoGendPost/86400);
    echo "<span title='This doesn\'t auto update, sorry.'>It's been ".$daysSinceLastAutoGendPost." days since the last automatic forum message. There's ";

    // Time to live
    $secsSinceLastAutoGendPost = $autoGenLastPost['post_time'] - time();
    $daysSinceLastAutoGendPost = floor($secsSinceLastAutoGendPost/86400);
    $hours = floor(($secsSinceLastAutoGendPost-$daysSinceLastAutoGendPost*86400)/(60 * 60));
    $min = floor(($secsSinceLastAutoGendPost-($daysSinceLastAutoGendPost*86400+$hours*3600))/60);
    $second = $secsSinceLastAutoGendPost - ($daysSinceLastAutoGendPost*86400+$hours*3600+$min*60);

    if($hours > 0) echo $hours." hours ";
    echo $min." minutes to post or it will reset to 0.</span>";

?>

<hr>

<div class="new-post-notification animated"><!-- 'Scroll to' posts appear in here --></div>
<audio id="notification-sound">
    <source src="files/sounds/smb_jump.mp3" type="audio/mpeg">
    <source src="files/sounds/smb_jump.wav" type="audio/wav">
</audio>

<div class="row" id="posts-container">
    <?php
    // Loop and print out each forum post
    while ($postResult = mysqli_fetch_array($posts, MYSQL_ASSOC)) {
        // Turns the sql array into a properly formatted post array
        $post = mysql2AssocArray($postResult);
        $postHtml = post2HTML($post);
        // Get all the replies for this post
        $repliesHtml = '';
        $postReplies = mysqli_query($db, "SELECT * FROM forum_posts WHERE parent_id='" . $post['id'] . "' AND forum='$forumId' ");
        while ($reply = mysqli_fetch_array($postReplies)) {
            $repliesHtml .= post2HTML(mysql2AssocArray($reply));
        }

        // Print a forum post and all its replies
        //<p style="cursor:pointer;" onclick="$(this).next().slideToggle()">'[-]'</p>
        //<p style="cursor:pointer;" onclick="$(this).next().slideToggle()">click</p>
        //<div class="row" id="posts-container" style="overflow: hidden; display: block;">
        echo parentPost2HTML($post['id'], $postHtml, $repliesHtml);

    }
    ?>
</div> <!-- .row #posts-container -->

<hr>

<?php

$all_posts = mysqli_query($db, "SELECT * FROM forum_posts WHERE `forum` = '$forumId' AND `parent_id` = 0");
$num_posts = mysqli_num_rows($all_posts);
$num_pages = ceil($num_posts / $forumDetails['posts_per_page']);

$paginationHtml = '';
for ($i = 1; $i < $num_pages+1; $i++) {
    if ($i == $paginationPage)
        $paginationHtml .= '<li class="active"><a href="forum/'.$forumId.'/page/'.$i.'">'.$i.'</a></li>';
    else
        $paginationHtml .= '<li><a href="forum/'.$forumId.'/page/'.$i.'">'.$i.'</a></li>';
}
?>

<div class="row">
    <div class="col-xs-12" id="jump_page">
        <span>Jump to a page:</span>
        <nav class="forum-pagination">
          <ul class="pagination">
            <?php
                if ($paginationPage == 1){
                    echo '
                    <li class="disabled">
                      <span aria-label="Previous" style="background-color: #eee;">
                        <span aria-hidden="true">&laquo;</span>
                      </span>
                    </li>';
                }
                else{
                    echo '
                    <li>
                      <a href="forum/'.$forumId.'/page/'. ($paginationPage - 1) .'" aria-label="Previous">
                        <span aria-hidden="true">&laquo;</span>
                      </a>
                    </li>';
                }
                echo $paginationHtml;

                if ($paginationPage == $num_pages){
                    echo '
                    <li class="disabled">
                      <span aria-label="Next" style="background-color: #eee;">
                        <span aria-hidden="true">&raquo;</span>
                      </span>
                    </li>';
                }
                else{
                    echo '
                    <li>
                      <a href="forum/'.$forumId.'/page/'. ($paginationPage + 1) .'" aria-label="Next">
                        <span aria-hidden="true">&raquo;</span>
                      </a>
                    </li>';
                }
            ?>
          </ul>
        </nav>
    </div>
</div>
<?php
addFooter();
?>
<!-- Files for forum live updates and push notifications -->
<script src="js/libs/jquery.timeago.js"></script>
<script src="js/libs/jquery.titleAlert.js"></script>
<script src="js/push.js"></script>
<script>
    $(document).ready(function () {
        Forum.init();
    });
</script>

<!-- Files for the emoji picker -->
<!-- Turns all the shortnames into images -->
<script src="js/libs/emojione.js"></script>
<!-- Puts the images into the page -->
<script src="js/emoji.js"></script>
<script type="text/javascript">
if ( window.addEventListener ) {
  var state = 0, konami = [38,38,40,40,37,39,37,39,66,65];
  window.addEventListener("keydown", function(e) {
    if ( e.keyCode == konami[state] ) state++;
    else state = 0;
    if ( state == 10 )
      window.location = "https://ucdtramp.com/findwaynegame/finddwayne.html";  //you can write your own code here
    }, true);
}
</script>
