<?php
include_once('includes/functions.php');

/* 
-This page uses an external plugin called fancybox which is awesome
*/


//Show images of event

// Update the 'sitename' variable which is used as a page's title.
$title='Gallery';
$description="See photos from all our major events. Watch out for that smoulder though";
addheader();

// Show images in specifified event
if(!isset($_REQUEST['eventname'])){ //Part 3 ----  If a event is not specified, act as Index Page listing ALL event per year?>
    <p style="display:inline;"><strong>Welcome to the Photo Gallery</strong> (Videos can be found on our <a href="http://www.youtube.com/user/ucdtramp" target="_blank">Youtube Channel</a>)</p>
    <p style="display:inline;float:right;"><a  href="http://www.ucdtramp.com/page/zippedphotos">Download Albums</a></p>
    <script>	
	function fadeIn(obj) {
		$(obj).parent().fadeIn(1000);
	}
	</script>
<style type="text/css">
    ul.polaroids li {
	  display:inline;
	  float:left;
	  width:22%;
    }
  	ul.polaroids a { background: #fff; display: inline; float: left; margin-bottom:27px; width: auto; padding: 5px 5px 7px; text-align: center; text-decoration: none; color: #333; -webkit-box-shadow: 0 3px 6px rgba(0,0,0,.25); -moz-box-shadow: 0 3px 6px rgba(0,0,0,.25); -webkit-transform: rotate(-2deg); -webkit-transition: -webkit-transform .15s linear; -moz-transform: rotate(-2deg); }

	ul.polaroids img { display:block; width:100%; margin-bottom: 7px; }
	
	ul.polaroids li:nth-child(even) a { -webkit-transform: rotate(2deg);  -moz-transform: rotate(2deg); }
	ul.polaroids li:nth-child(3n) a { -webkit-transform: none; position: relative; top: -5px;  -moz-transform: none; }
	ul.polaroids li:nth-child(5n) a { -webkit-transform: rotate(5deg); position: relative; right: 5px;  -moz-transform: rotate(5deg); }
	ul.polaroids li:nth-child(8n) a { position: relative; right: 5px; top: 8px; }
	ul.polaroids li:nth-child(11n) a { position: relative; left: -5px; top: 3px; }

	ul.polaroids li a:hover { -webkit-transform: scale(1.25); -moz-transform: scale(1.25); -webkit-box-shadow: 0 3px 6px rgba(0,0,0,.5); -moz-box-shadow: 0 3px 6px rgba(0,0,0,.5); position: relative; z-index: 5; }
</style>
    
    <div style="text-align:center;">
	<?php // Load Year
        $years=mysqli_query($db, "SELECT * FROM photo_years ORDER BY id DESC");
            
        // Display event title and thumbnail (with link to event)
        while($current_category = mysqli_fetch_array($years,MYSQL_ASSOC)){ ?>
        <hr>
        <div id="titleyear"><h3><?=htmlentities($current_category["description"]); ?></h3></div> 
        <hr>
        
        <ul class="polaroids">
	<?php
		// Load event			
		$event=mysqli_query($db, "SELECT * FROM photo_events where category='".$current_category['id']."' ORDER BY id DESC");
		while($current_event = mysqli_fetch_array($event,MYSQL_ASSOC)){ 		
			$album_comments = mysqli_query($db, "SELECT * FROM photo_comments WHERE event='".$current_event['filename']."'"); 
			$numcomments = mysqli_num_rows($album_comments); ?>
			
            <li>
			<a style="display:none;" href="gallery/<?=$current_event['filename']; ?>" title="<?=$current_event['name']?>">
				<img onload="fadeIn(this)" src="http://www.ucdtramp.com/photos/<?=htmlentities($current_event['filename']); ?>/preview/<?php echo rawurlencode($current_event['filename']); ?>x200.jpg" >
                <div style="width:100%;text-align:left;"><?=$current_event['name']?>
                	<span style="float:right;font-weight:bold;"><?=$numcomments?> <i class="fa fa-comments-o"></i></span>
                </div>
			</a>
            </li>			  
	<?php
		}
	}
echo '</ul></div>';

} // Grid of photos for Event
else {
	$event_query = mysqli_query($db, "SELECT * FROM photo_events WHERE filename='".$_REQUEST['eventname']."'");
	$current_event = mysqli_fetch_array($event_query,MYSQL_ASSOC);
	$event_id = $current_event['id'];
	$numofimgs = mysqli_num_rows(mysqli_query($db, "SELECT * FROM photos WHERE event='$event_id'"));?>
	
    <!--Album Heading with date and num of images-->
    <h1 style='display:inline;'><?=htmlentities($current_event["name"])?></h1>	
	<p style="display:inline;float:right;text-align:right;">
        Created on: <?=date('F Y',$current_event['created']);?><br>
        Number of images: <?=$numofimgs?></p>
    <p><br><?=htmlentities($current_event["description"])?></p>    
    <hr>
    
    <!--Comment pannel is hidden until a photo is loaded by fancybox--> 
    <div id="commentPannel"> 
        <form onSubmit="return commentPost();" class="photopost">
            <input id="sender" style="width:98%" tabindex="1" type="text" value="<?php if(isset($_COOKIE['user'])){echo $_COOKIE['user'];} else if(isset($_COOKIE['Milk'])){echo $_COOKIE['Milk'];}?>" placeholder="Name">           
            <textarea id="comment" style="width:98%;height:3em;" tabindex="2" placeholder="Comment..."></textarea>
            <input type="hidden"  id="eventname" value="<?=$_REQUEST['eventname']?>">
            <button tabindex="3" type="submit">Submit</button></form>
            <button id="showHide">Comment</button>
       	<div style="text-align:center;margin-top:.3em;">Image <span id="currentImg">...</span> of <?=$numofimgs?></div>
        <hr>
        <div id="comments"></div>
        <div id="spinner" style="width:100%;text-align:center;">Loading...</div>
    </div>
    
    <!--Show all images in a flexible contrainer so spacing between photos is even-->
	<div class="flex-container">
<?php
	$photos=mysqli_query($db, "SELECT * FROM photos WHERE event='$event_id'");
	while($photo=mysqli_fetch_array($photos, MYSQL_ASSOC)){
		$photo_comments = mysqli_query($db,"SELECT * FROM photo_comments WHERE photoid='".$photo['id']."' AND event='".$_REQUEST['eventname']."'");
		$numComments = mysqli_num_rows($photo_comments); //Count comments in photo for thumbnail caption ?>

    	<a rel="fancy" style="text-decoration:none;" href="http://www.ucdtramp.com/photos/<?=rawurlencode($current_event['filename']); ?>/<?=htmlentities($photo['filename']);?>">
        <!--Used to use centered background image with width 125% but backgrounds are last to load and made the gallery seem slower-->
            <div class="event_thumbnail">
                <span class="caption"><?=$numComments;?></span>
                <img style="width:120%" src="http://www.ucdtramp.com/photos/<?=htmlentities($current_event['filename']); ?>/thumbnails/<?=rawurlencode($photo['thumbnail']);?>">                
            </div>
        </a>    
    <?php
	} ?>
</div> <!--flex-->

<script>
	var imageIds = [<?php 
		$photos=mysqli_query($db, "SELECT * FROM photos WHERE event='$event_id'");
			while($photo=mysqli_fetch_array($photos, MYSQL_ASSOC)){
				echo $photo['id'].',';	
			}
	?>-1];
</script>
            
	<script src="http://www.ucdtramp.com/plugins/jquery.fancybox-v3beta/jquery.fancybox.js"></script>
    <link rel="stylesheet" type="text/css" href="http://www.ucdtramp.com/plugins/jquery.fancybox-v3beta/jquery.fancybox.css">
	
    <script type="text/javascript">	
		$(document).ready(function() {
			if(window.innerWidth > 480){ //larger screen, comments aligned to the right
				$("[rel='fancy']").fancybox({
						leftRatio	: 0.35,
						maxWidth	: window.innerWidth*0.65
				});
			}else{
				$("[rel='fancy']").fancybox({
						topRatio	: 0, //small screen, comment aligned to bottom
						maxHeight	: window.innerHeight*0.65
				});
			}
			$("[rel='fancy']").click(function(){
				commentsShowHide(); //show comments when photo is clicked
			})
			
			$('#showHide').click(function(){
				if($('#showHide').text() != 'Hide'){
					$(this).css('width','26%');
					$(this).text('Hide');
					$('.photopost').css('display','inline');
				}else{
					$(this).css('width','100%');
					$(this).text('Comment');
					$('.photopost').hide();
				}
			});
		});
		
		var currentImg=-1;//global var
		
		function magic(photoNum){ //Changes url when photo is opened/changed
			currentImg=photoNum;
			$('#currentImg').text(currentImg+1); //Update pannel with current image number
			commentLoad(); //load the comments relative to that photo
			
			var photoEvent='<?=$_REQUEST['eventname']?>';
			history.replaceState(null, null, '/gallery/'+photoEvent+'/'+photoNum+'');
		}
		
		function getScrollbarWidth() {
		  var div, width = getScrollbarWidth.width;
		  if (width === undefined) {
			div = document.createElement('div');
			div.innerHTML = '<div style="width:50px;height:50px;position:absolute;left:-50px;top:-50px;overflow:auto;"><div style="width:1px;height:100px;"></div></div>';
			div = div.firstChild;
			document.body.appendChild(div);
			width = getScrollbarWidth.width = div.offsetWidth - div.clientWidth;
			document.body.removeChild(div);
		  }
		  return width;
		};
		var sbWidth = getScrollbarWidth(); //used in showpannel
		
		function commentsShowHide(){
			var $panPos = $('#commentPannel');
			var panOffset = parseInt(($panPos.outerWidth()+sbWidth),10)
					
			$panPos.animate({
			  right: parseInt($panPos.css('right'),10) == 0-sbWidth ? //if pannel is offscreen...
				-panOffset+'px' : //moveleft of scoll bar
				-sbWidth //move to the right of scroll bar
			});		
			$('#comments').html('');
		}
		
		function commentLoad(){
			//ajax that returns the comments scructured correctly for append
			$('#comments').fadeOut(1); //hide comments from previous photo
			$('#spinner').show(); //show loading.. text
			$.ajax({
				type: "POST",
				url: "http://www.ucdtramp.com/ajax_php/gallery.db.php",
				data: "action=getComments&photoid="+(imageIds[currentImg])+"&eventname="+$('#eventname').val(),
				dataType: "html",
				success: function(data){
					$('#comments').html(data).fadeIn();
					$('#spinner').hide();
				}
			});			
		}
		
		function commentPost(){
			console.log('commentPost Called');
			if($('#sender').val()=='' || $('#comment').val()==''){
				alert("Fill the text boxes");
				return false;		 
			}
		 	$.ajax({
				type: "POST",
				url: "http://www.ucdtramp.com/ajax_php/gallery.db.php",
				data: "action=Comment&sender="+$('#sender').val()+"&comment="+$('#comment').val()+"&photoid="+(imageIds[currentImg])+"&eventname="+$('#eventname').val(),
				dataType: "html",
				success: function(data){
					$('#comment').val(''); //empty textarea
					$('#comments').append(data).fadeIn();
				}
			});
			return false;		
		}
	</script>
                
	<?php			
    if(isset($_GET['image'])){	//If GET image is set, that image is loaded.		
        echo "<script>$( document ).ready(function() {
				$('[rel=\"fancy\"]').eq(".($_GET['image']).").trigger('click');
			});
	  		</script>";
    }
}
addfooter(); 
?>