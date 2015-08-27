
<?php
include_once('includes/functions.php');

$title = 'Gallery';
$description = "See photos from all our major events. Watch out for that smoulder dough";
addHeader();

// Show images for all events on the gallery home
if (!isset($_REQUEST['eventname'])) {
    echo '
    <div class="gallery-header">
        <strong>Welcome to the Photo Gallery</strong> 

        (Videos can be found on our <a href="youtubevids">Youtube Vids</a> page where the routines are grouped by person or head straight to our <a href="http://www.youtube.com/user/ucdtramp" target="_blank">Youtube Channel</a>)
    </div>';

    // Show each year
    $years = mysqli_query($db, "SELECT * FROM photo_years ORDER BY id DESC");
    while ($year = mysqli_fetch_array($years, MYSQL_ASSOC)) {
        $yearTitle = htmlentities($year["description"]);
        echo '
        <hr>
        <div>
            <h3>'.$yearTitle.'</h3>
        </div>

        <div class="gallery-grid flex-container">';
        // Show each event in the year          
        $eventsQuery = mysqli_query($db, "SELECT * FROM photo_events where category='".$year['id']."' ORDER BY id DESC");
        while ($event = mysqli_fetch_array($eventsQuery, MYSQL_ASSOC)) {
            $albumComments = mysqli_query($db, "SELECT * FROM photo_comments WHERE event='".$event['filename']."'");
            $commentsCount = mysqli_num_rows($albumComments);

            $eventFolder = htmlentities($event['filename']);
            $eventName = $event['name'];

            $eventLink = "gallery/".$eventFolder;
            $thumbnailSrc = "//ucdtramp.com/photos/$eventFolder/preview/{$eventFolder}x800.jpg";
            $thumbnailSrcset = "//ucdtramp.com/photos/$eventFolder/preview/{$eventFolder}x200.jpg 200w,
                                //ucdtramp.com/photos/$eventFolder/preview/{$eventFolder}x400.jpg 400w,
                                //ucdtramp.com/photos/$eventFolder/preview/{$eventFolder}x800.jpg 800w";
            echo ' 
            <a class="gallery-grid-item" href="'.$eventLink.'" title="'.$eventName.'">
                <img class="gallery-grid-item__thumb" srcset="'.$thumbnailSrcset.'" src="'.$thumbnailSrc.'" alt="'.$eventFolder.' thumbnail">
                <div class="gallery-grid-item__caption">'.
                    $eventName.'
                    <span class="gallery-grid-item__caption-comments">'.
                        $commentsCount.' <i class="fa fa-comments-o"></i>
                    </span>
                </div>
            </a>';
        }
        echo "</div>";
    }

    addFooter();
} 



// Grid of photos for Event
else if (isset($_REQUEST['eventname'])) {
    $events = mysqli_query($db, "SELECT * FROM photo_events WHERE filename='".$_REQUEST['eventname']."'");
    $event = mysqli_fetch_array($events, MYSQL_ASSOC);
    $eventID = $event['id'];
    // Get all the photos from this event
    $photos = mysqli_query($db, "SELECT * FROM photos WHERE event='$eventID'");
    $photoCount = mysqli_num_rows($photos);

    // Comment forum stuff
    $username = '';
    if (isset($_COOKIE['user'])) {
        $username = $_COOKIE['user'];
    } else if (isset($_COOKIE['Milk'])) {
        $username = $_COOKIE['Milk'];
    }
?>
    <!--Album Heading with date and num of images-->
    <h1 class="event-title"><?= htmlentities($event["name"]) ?></h1>    
    <p class="event-info clearfix">
        Created on: <?= date('F Y', $event['created']); ?><br>
        Number of images: <?= $photoCount ?>
    </p>
    <p>
        <!-- Description of the event. Some have youtube iframe embeds -->
        <br>
        <?= ($event["description"]) ?>
    </p>
    <hr>
    <a href="https://youtu.be/OuipLscRGN0"></a>
    
    <!--Comment pannel is hidden until a fancybox opens--> 
    <div class="comments-pannel">
        <form class="form-horizontal comment-form" onsubmit="return false;">
            <div class="form-group row comment-form-inputs">
                <div class="col-xs-12">
                    <input type="text" class="form-control comment-user" placeholder="Mr. Smith" <?= 'value="'.$username.'"'; ?>>
                    <input type="hidden" id="gallery-event-name" value="<?= $_REQUEST['eventname'] ?>">
                </div>
                <div class="col-xs-12">
                    <textarea class="form-control comment-message" rows="2" placeholder="Comment..."></textarea>
                </div>
            </div>
            <!-- Buttons -->
            <div class="form-group row">
                <div class="col-xs-12 comment-error"></div>
                <div class="col-xs-12">
                    <button type="submit" class="form-control btn btn-primary btn-comment">Post</button>
                    <button type="button" class="form-control btn btn-default btn-showHide-form">Write Comment</button>                    
                </div>
            </div>
        </form>

        <div class="comments-image-index">
            Image <span id="current-image">...</span> of <?= $photoCount ?>
        </div>
        <hr style="margin-top: 10px;margin-bottom: 10px">
        <!-- <div class="comments-loading">Loading...</div> -->
        <div class="comments-container"></div>
    </div>

    <!--Show all images in a flexible contrainer so spacing between photos is even-->
    <div class="flex-container">
<?php
    
    $eventFolder = rawurlencode(htmlentities($event['filename']));
    while ($photo = mysqli_fetch_array($photos, MYSQL_ASSOC)) {
        $commentsCount = mysqli_fetch_array(mysqli_query($db, "SELECT count(1) AS c FROM photo_comments WHERE photoid='".$photo['id']."' AND event='".$_REQUEST['eventname']."'"))['c'];
        $commentsHtml = ($commentsCount > 0)? '<span class="gallery-event-commnent-count">'.$commentsCount.'</span>': '';
        $thumbImageURL = "//ucdtramp.com/photos/$eventFolder/thumbnails/".rawurlencode($photo['thumbnail']);
        $bigImageURL = "//ucdtramp.com/photos/$eventFolder/".rawurlencode($photo['filename']);

        echo '
        <a class="swipebox-link gallery-event-thumb" href="'.$bigImageURL.'" data-photoid="'.$photo['id'].'"
            style="background-image:url(\''.$thumbImageURL.'\');">'.
            $commentsHtml.'
        </a>';
    }
    echo'
    </div> <!--flex-->';

    addFooter();
    ?>
    <link rel="stylesheet" href="dist/css/swipebox.css">
    <script src="dist/js/jquery.swipebox.js"></script>
    <script src="dist/js/jquery.timeago.js"></script>
    <script src="dist/js/gallery.js"></script>

    <?php
    // If GET image is set, load that image
    if (isset($_GET['image'])) { 
        echo '
        <script>
            $( document ).ready(function() {
                Gallery.openImage('.$_GET['image'].');
            });
        </script>';
    }
}
?>

<style>
    /*Lightbox stuff*/
    /*mobile*/
    #swipebox-container {
        width: 100%;
        height: 70%;
    }

    /*desktop*/
    @media (min-width: 768px) {
        
        #swipebox-container {
            width: 80%;
            height: 100%;
        }
    }

</style>