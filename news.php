<?php
include_once('includes/functions.php');
$loggedin?$editnews=true:$editnews=false;



$title = "UCD Trampoline Club";
$description = "Welcome to the UCDTC website. Here you can find out what the club is about and what we get up to. Why not drop us a post on the Forum or say hi to Ask a Tramp?";
addheader();
?>

<div id="slidernotes" style="position:relative;"> <!--containts the slider and the table with notes-->
  <link rel="stylesheet" type="text/css" href="plugins/Slicebox/css/slicemini.css">
  <script type="text/javascript" src="plugins/Slicebox/js/modernizr.custom.46884.js"></script>
  
  <div id="slider"><!--contains the slider on the left-->
<style>
@media screen and (max-width:580px)
{
.sb-description 
{
	padding: 5px;
}
.nav-arrows a 
{
	width: 21px;
	height: 21px;
}
}
</style>
<!--*************  Pictures for the slicebox slider must be exactly 700 x 420 px  **************-->
    <ul id="sb-slider" class="sb-slider">

      <li><a href="http://www.ucdtramp.com/gallery/ssto1415" target="_blank">
      <img src="http://www.ucdtramp.com/images/front_slider/ssto1415.jpg" alt="Slider image"/></a>
        <div class="sb-description">
          <p>SSTO 2015</p>
        </div>
      </li>
      
      <li><a href="http://www.ucdtramp.com/gallery/christmasfunday1415" target="_blank">
      <img src="http://www.ucdtramp.com/images/front_slider/christmasfunday1415.jpg" alt="Slider image"/></a>
        <div class="sb-description">
          <p>Christmas Fun Day</p>
        </div>
      </li>
      
      <li><a href="http://www.ucdtramp.com/gallery/intervarsities1415" target="_blank">
      <img src="http://www.ucdtramp.com/images/front_slider/intervs1415.jpg" alt="Slider image"/></a>
        <div class="sb-description">
          <p>Intervarsities Winners for the 4th year in a row!</p>
        </div>
      </li>
      
      <li><a href="http://www.ucdtramp.com/gallery/inhouse1415" target="_blank">
      <img src="http://www.ucdtramp.com/images/front_slider/inhouse1415.jpg" alt="Slider image"/></a>
        <div class="sb-description">
          <p>In House Competition</p>
        </div>
      </li>
      
      <li><a href="http://www.ucdtramp.com/gallery/cavan1415" target="_blank">
      <img src="http://www.ucdtramp.com/images/front_slider/cavan1415.jpg" alt="Slider image"/></a>
        <div class="sb-description">
          <p>Cavan</p>
        </div>
      </li>
      
        <li><a href="http://www.ucdtramp.com/gallery/trampnight1415" target="_blank">
      <img src="http://www.ucdtramp.com/images/front_slider/trampnight1415.jpg" alt="Slider image"/></a>
        <div class="sb-description">
          <p>Tramp Night</p>
        </div>
      </li>
      
      <li><a href="http://www.ucdtramp.com/gallery/freshersnight1415" target="_blank">
      <img src="http://www.ucdtramp.com/images/front_slider/freshersnight1415.jpg" alt="Slider image"/></a>
        <div class="sb-description">
          <p>Freshers Night</p>
        </div>
      </li>
    
      <li><a href="http://www.ucdtramp.com/gallery/mindbodysoul1415" target="_blank">
      <img src="http://www.ucdtramp.com/images/front_slider/mindbodysoul1415.jpg" alt="Slider image"/></a>
        <div class="sb-description">
          <p>Mind Body + Soul 2014</p>
        </div>
      </li>
      
    </ul>
    <div id="shadow" class="shadow"></div>
    <div id="nav-arrows" class="nav-arrows"> <a href="#">Next</a> <a href="#">Previous</a> </div>
    <div id="nav-dots" class="nav-dots"> <span class="nav-dot-current"></span> <span></span> <span></span> <span></span> <span></span> <span></span> <span></span> <span></span> </div>
  </div> <!-- /slider -->
  <script type="text/javascript" src="plugins/Slicebox/js/jquery.slicebox.js"></script> 
  <script type="text/javascript">
  //I compressed the javascript for the slider taken from index 1. It was annoying me that it has to be inline on the page
  		$(function(){var e=function(){var e=$("#nav-arrows").hide(),t=$("#nav-dots").hide(),n=t.children("span"),r=$("#shadow").hide(),i=$("#sb-slider").slicebox({onReady:function(){e.show();t.show();r.show()},onBeforeChange:function(e){n.removeClass("nav-dot-current");n.eq(e).addClass("nav-dot-current")}}),s=function(){o()},o=function(){e.children(":first").on("click",function(){i.next();return false});e.children(":last").on("click",function(){i.previous();return false});n.each(function(e){$(this).on("click",function(t){var r=$(this);if(!i.isActive()){n.removeClass("nav-dot-current");r.addClass("nav-dot-current")}i.jump(e+1);return false})})};return{init:s}}();e.init()})
   </script> 
   
   <!--	<script src="http://www.ucdtramp.com/public_html/js/snowstorm.js"></script>
  		<script>
			snowStorm.snowColor = '#99ccff';   // blue-ish snow!?
			snowStorm.flakesMaxActive = 50;    // show more snow on screen at once
			snowStorm.useTwinkleEffect = true; // let the snow flicker in and out of view
		</script>-->
  
  <div class="whitebox " id="notes"><br>
    <div><b>Upcoming events</b><br><br>
         		  
         
          <br><strong>May</strong><br><br>
          Coming soon ...
          <br><br>
                 
          Any questions or suggestions,<br>
          contact Adam<br></div>
    <hr style="margin:.3em;">
    
    <div><b>Training Times</b><br>
		               
          <br>Out of Term: <br>
          Wednesday 8pm - 10pm<br>
          <br><br>
          <b>Gymnastics:</b><br>
          To be confirmed ... <br>
          Trampette and Airtrack madness !<br><br>
          <b>Training Area</b><br>
          UCD Sport Centre<br>
     </div><br>
	
  </div><!--/notes-->
</div><!--/slider and notes-->
<hr>


<div id="greeting" class="whitebox">
  <h2 style="margin:0 0 .5em 0;text-align:center;">Greetings</h2>
  <p>A big welcome to all, both new and experienced tramps! Congratulations on successfully joining the best sports club ever. You’ve done the hard work, paying your €15, so now let the fun begin! <br>
We have 2 exciting training sessions per week and at these our fun and experienced coaches will take you from your very first bounce to your first somersault to infinity and beyond.<br>
So whether you want to compete in the Olympics, or want to get some exercise, or just want to try something new and exciting, then you’ve joined the right club! And of course we also have a super active social life with absolutely no shortage of nights out and trips away. </p><br>
  
  <p>We take a lot of pride in our website so have a browse through it. You’ll find the profiles of all our committee members, photos from our trips and nights out and then be sure to drop by and say hey on our awesome forum. Our webmaster was very active last year and greatly improved the site. Lets hope our webmaster this year can continue to follow in Pauls footsteps!<br><br>
    <i></i></p>
  
  <br><h3>Note from the Webmaster</h3><p>
  WELCOME! I hope you like the site. If you don't, Paul will be very upset ...
 <br><br>
  
  <span title="Old and/or stupid people">Unfortunately for the best experience you're going to have to use <strong>Google Chrome</strong> as your browser but come on, who doesn't these days eh ! I definitely dont use Firefox I swear ...</span><br><br>

    I explain things about the site, give a few tips, answer bugbox submissions and fix things that are broken on this <a href="http://www.ucdtramp.com/page/log">log</a> page.<br>    
    If you have any problems, check it out.<br><br>
    
    If you have any feedback, suggestion, secrets, adoration or ideas, click that little gray box in the bottom right to <strong>let me know</strong>.<br><br>
    
    <strong>Disclaimer:</strong> All images used for the halloween theme are not my own, they were found on google and will be removed at the request of the owner if requested to do so.<br><br>

  Yours electronically,<br>
  <i>Conor Spain</i>
  </p>
    
  <br><h3>History of the club</h3>
  <p>The UCD Trampoline Club was set up by Andrew Cahill in the 1980's; the era of the A-Team, Gummi Bears, Air Wolf, Jem, Teddy Ruxpin, Brave Star and the Samurai Pizza Cats. The club is equally as cool as any of these things.</p>
  <br>
  
</div><!--/greeting-->


<div id="newslist">
<h2 class="whitebox" style="margin:0 0 .5em 0;text-align:center;">Club News</h2>
  <?php
  
//get all the news
$news_query = mysqli_query($db, "SELECT *,DATE_FORMAT(datetime,'%a %D %b') as formatted_date FROM news_items WHERE inuse = '1' ORDER BY id DESC LIMIT 10");

// loop that prints news
while($news=mysqli_fetch_array($news_query,MYSQL_ASSOC))
{?>
  <div class="whitebox" id="news_<?php echo($news['id']); ?>"> <span style="font-size:1.1em;font-weight:bold;"><?php echo(smilify($news["title"],NULL)); ?></span> <span style="font-size:.8em;float:right;">
    <?php 
        echo($news["username"]." on ".$news["formatted_date"]);
        if($editnews)
        {
            echo(" <a style='color:black;' href=\"http://www.ucdtramp.com/manage_news.php?action=Edit&amp;newsId=".$news["id"]."\" title='Edit' target=\"_top\"><i class='fa fa-pencil'></i></a>");
        } ?>
    </span>
    <hr style="clear:both">
    <p><?php echo(smilify($news["text"],NULL)); ?></p>
  </div><!--/news item-->
  
  <?php } ?>
</div>

<!--/newslist-->

<?php addfooter(); ?>
