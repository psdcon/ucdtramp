<?php
include_once 'includes/functions.php';
$title = 'Upcoming and Past Events';
$description = "Find infomation about our upcoming events and see the Reports from past competitions and trips";
addHeader();

// Pads year with 0 if necessary
function padYear($yearVar){
    return ($yearVar < 10)? '0'.$yearVar: $yearVar;
}

// Spit out a timeline at the top of the page
// If the month is september or more, year is 2015. Else year is 2014
$yearStart = (date('n') > 9)? date("y"): date("y") - 1;
$years = '';    
for ($year = $yearStart; $year > 4; $year = $year - 1) {
    $year = padYear($year);
    $nextYear = padYear($year +1);
    $years .= '
        <a class="timeline-link animated fadeIn" href="events#20'.$year.'" title="20'.$year.' - 20'.$nextYear.'">
            <span class="dot"></span><span>20'.$year.'</span>
        </a>';
}
// Both rows contain all years. Every second on is hidden by css
echo '
<div class="flex-container rotate anticlockwise">'.$years.' </div>
<div class="timeline"></div>
<div class="flex-container rotate clockwise">'.$years.'</div>';

// TODO show these photos somewhere...
$groupPhotos = ['group0405.jpg','group0506.jpg','group0708.jpg','group0809-2.jpg','group0809-3.jpg','group0809.jpg','group0910.jpg','group1011.jpg','group1112-2.jpg','group1112-3.jpg','group1112.jpg','group1213.jpg','xmas-group.jpg'];

// Spit out all the info for each year
for ($year = $yearStart; $year > 3; $year--) {
    $year = padYear($year);
    $nextYear = padYear($year + 1);
    $pageYear = $year.$nextYear; //0304
    
    // Print year heading
    echo '
    <div class="row" id="20'.$year.'">
        <div class="col-xs-12">
            <hr>
            <h3>20'.$year.' - 20'.$nextYear.'</h3>
        </div>';

    // Get all the page links
    $types = array('competition','result','report','msc','committee'); // mysql catagory
    $competition = ''; $result = ''; $report = ''; $msc = ''; $committee = ''; // holds html links

    foreach ($types as $type) {
        $pagesSQL = "SELECT * FROM `pages` WHERE `readperm` = 0 AND `year` = '$pageYear' AND `type` = '$type' ORDER BY `type` ASC, `year` ASC";        
        $pages = mysqli_query($db, $pagesSQL);
        while ($page = mysqli_fetch_array($pages)) {
            // If the page is msc, it wont have an event name
            $pageLink = ($page['eventname'] == '')? $page['pagetitle'] : $page['eventname'];
            // $$type takes the $type string and uses the string as a var name
            $$type .= '<a href="page/'.$page['pageurl'].'">'.$pageLink.'</a><br>';
        }
    }

    // Get thumbnails for each event
    // Get the photo year details
    $photoHtml = '';
    $photoYear = mysqli_query($db, "SELECT `id` FROM `photo_years` WHERE `name` = '$pageYear' LIMIT 1"); 
    $photoYearId = mysqli_fetch_array($photoYear, MYSQLI_ASSOC)['id'];
    // Get the events in that year
    $eventsDetails = mysqli_query($db, "SELECT * FROM `photo_events` WHERE `category` = $photoYearId ORDER BY `id` DESC");
    while ($thisEvent = mysqli_fetch_array($eventsDetails, MYSQLI_ASSOC)) {
        $eventId = $thisEvent['id'];
        $eventFolder = htmlentities($thisEvent['filename']);

        // Get a random photo from each event
        $randomIdSQL = "SELECT FLOOR( MAX(id) * RAND()) FROM `photos` WHERE `event` = $eventId"; // Gets max id from photos in that event, multiplies it by rand() number between 0 and 1, floor() rounds down to an int
        $thumbnailSQL = "SELECT id,thumbnail FROM `photos` WHERE `event` = $eventId AND `id` >= ($randomIdSQL) ORDER BY `id` LIMIT 1";
        $thumbnail = mysqli_fetch_array(mysqli_query($db, $thumbnailSQL), MYSQLI_ASSOC);
        
        $thumbnailId = $thumbnail['id']-1;
        $thumbnailName = rawurlencode($thumbnail['thumbnail']);
        $thumbnailURL = "//ucdtramp.com/photos/$eventFolder/thumbnails/$thumbnailName";
        $galleryLink =  "gallery/$eventFolder/$thumbnailId";

        // Save the html for each image
        $photoHtml .= '
        <a class="event-thumbnail-link nobreak" href="'.$galleryLink.'">       
            <img class="event-thumbnail-image" src="'.$thumbnailURL.'" alt="Thumbnail">
            <span class="event-thumbnail-caption">'.
                $thisEvent['name'].'
            </span>
        </a>';
    }

    // Spit out HTML for each year
    echo '
    <div class="col-md-4">
        <div class="row">
            <div class="col-xs-6">
                <h4>Comps</h4>'.
                $competition.
            '</div>
            <div class="col-xs-6">
                <h4>Reports</h4>'.
                $report.
            '</div>
        </div>
        <div class="clearfix visible-xs-block"><!--Keeps rows aligned--></div>
        <div class="row">
            <div class="col-xs-6">
                <h4>Results</h4>'.
                $result.
            '</div>
            <div class="col-xs-6">
                <h4>Msc</h4>'.
                $msc.
                $committee.
            '</div>
        </div>
    </div>
    <div class="col-md-8 events-gallery">'.
            $photoHtml.
        '</div>
    </div>';
}

addFooter();
?>