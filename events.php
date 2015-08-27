<?php
include_once('includes/functions.php');

$title = 'Upcoming and Past Events';
$description = "Find infomation about our upcoming events and see the Reports from past competitions and trips";
addheader();
?>
<script>
    $(document).ready(function()
    {   $.localScroll({
			duration:1500,
			hash:true
		});    });
</script>	

<div class="whitebox" id="timeline" style="text-align:center;padding:.5em;-ms-word-break:keep-all;word-break:keep-all;">

<?php
if (date("n") > 9) //On september 1st, tramp year is this year
    $yearstart = date("y");
else // tramp year is year stage started
    $yearstart = date("y") - 1;

for ($year = $yearstart; $year > 4; $year = $year - 2) {
    $nextyear = $year + 1;
    ($year < 10) ? $year = ('0' . $year) : $year;
    ($nextyear < 10) ? $nextyear = ('0' . $nextyear) : $nextyear;
    
    echo '<a class="rotateanti" href="#20' . $year . '" title="20' . $year . ' - 20' . $nextyear . '">20' . $year . '</a><span class="space">&nbsp;</span>';
}
echo '<hr>';
for ($year = $yearstart - 1; $year > 4; $year = $year - 2) {
    $nextyear = $year + 1;
    ($year < 10) ? $year = ('0' . $year) : $year;
    ($nextyear < 10) ? $nextyear = ('0' . $nextyear) : $nextyear;
    
    echo '<a class="rotateclock" href="#20' . $year . '" title="20' . $year . ' - 20' . $nextyear . '">20' . $year . '</a><span class="space" >&nbsp;</span>';
}
?>
</div>

<style>
a{
	color:black;
}
h2{
	text-align:center;	
}
</style>
<!--OLD CALENDER FROM TRINITY <iframe src="https://www.google.com/calendar/b/0/embed?title=Trampoline%20Website%20Calendar&amp;height=600&amp;wkst=2&amp;hl=en_GB&amp;bgcolor=%23ffffff&amp;src=0kv265itm8tkvj0ch1gpomliq4%40group.calendar.google.com&amp;color=%23528800&amp;src=nodsnoucqh8q1qsgvfpom30ics%40group.calendar.google.com&amp;color=%235229A3&amp;src=t0lopth0tit756vbhh2ehklhno%40group.calendar.google.com&amp;color=%23125A12&amp;src=dutrampoline%40gmail.com&amp;color=%23BE6D00&amp;src=8ic3rmo4m5ipblpsim3kmeidbk%40group.calendar.google.com&amp;color=%23711616&amp;ctz=Europe%2FDublin" style="border-width:0;" width="100%" height="600" frameborder="0" scrolling="no"></iframe>-->

<?php //Lowest year is 0405, 0304 gets set to year x
for ($year = $yearstart; $year > 3; $year--) {
    $nextyear = $year + 1;
    ($year < 10) ? $year = ('0' . $year) : $year;
    ($nextyear < 10) ? $nextyear = ('0' . $nextyear) : $nextyear;
    
    $pageyear = $year . $nextyear;
    echo '<div class="year" id="20' . $year . '"><hr>';
    echo '<h2>20' . $year . ' - 20' . $nextyear . '</h2>';
    echo '<hr></div>';
    
    /* This loop sets up a whitebox table for each year. In the first row are two td's. The first contains comp pages
    The 2nd contatins 2 divs which get stacked on mobiles via css and are inline on desktops. On each loop itteration, 
    the year is set for the mysql checks and the pages with types are echoed as links. After all the pages are done the 
    row is closed and another one spanning the first two cols is made. The gallery thimbnails are printed on this row. 
    They are inside a container called events_gallery which has its overflow-y set to scroll. The images inside this are 
    each in a div which is told to display inline block and have a width of 12%. The thumbnail images are set to be 100% 
    of this width. */
    echo '<div class="whitebox"><div class="compsnresults">';
    
    for ($i = 0; $i < 5; $i++) {
        if ($i == 0) {
            $type = 'competition';
            echo '<h3>Competitions & Results</h3>';
        } else if ($i == 1) {
            $type = 'result';
        } else if ($i == 2) {
            $type = 'report';
            echo '</div><div class="reportsnmsc"><div class="reports"><h3>Reports</h3>';
        } else if ($i == 3) {
            $type = 'msc';
            echo '</div><div class="msc"><h3>Msc</h3>';
        } else if ($i == 4) {
            $type = 'committee';
        }
        
        $result = mysqli_query($db, 'SELECT * FROM pages WHERE readperm=0 AND year=\'' . $pageyear . '\' AND type=\'' . $type . '\' ORDER BY type ASC, year ASC');
        while ($row = mysqli_fetch_array($result)) {
            echo '<a href="/page/' . $row['pageurl'] . '">' . $row['eventname'] . '</a><br>';
        }
    }
    echo '</div></div><div>';
    
    $photoyear = mysqli_query($db, "SELECT * FROM photo_years WHERE name='" . $pageyear . "' ");
    // Display event thumbanil (with link to event)
    while ($current_category = mysqli_fetch_array($photoyear, MYSQL_ASSOC)) {
        echo '<h3 style="text-align:center">Photo Gallery</h3><hr></div><div>';
        
        echo '<div class="events_gallery">';
        // Load event			
        $event = mysqli_query($db, "SELECT * FROM photo_events where category='" . $current_category['id'] . "' ORDER BY id DESC");
        while ($current_event = mysqli_fetch_array($event, MYSQL_ASSOC)) {
?>
					
		<div class="events_gallery_thumbnail">
		<a href="http://www.ucdtramp.com/gallery/<?= $current_event['filename']; ?>">		
			<img style="width:100%" alt="Thumbnail" src="http://www.ucdtramp.com/photos/<?= htmlentities($current_event['filename']); ?>/preview/<?= htmlentities($current_event['filename']); ?>x800.jpg">
            <div class="events_gallery_thumbnail_caption"><span><?= $current_event['name']; ?></span></div>   			                 
		</a></div>
<?php
        }
        echo '</div></div></div>';
    }
}
addfooter();
?>