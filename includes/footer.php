<div style="clear:both;"></div> <!--clearfix-->

</div><!--/padding-->
</div><!--/cont-->
</div><!--/background-->

<div id="footer">
<div id="links">
    <!--Inside containing divs so that the link description text can be added via css and hence can change per device-->
        <a href="https://www.facebook.com/UCDTC" target="_blank" title="Our Facebook page">
            <img src="http://www.ucdtramp.com/images/msc/facebook.png" alt="Facebook Icon"><br><div id="fb"></div></a>
        <a href="http://www.youtube.com/user/ucdtramp" target="_blank" title="Our YouTube Channel">
            <img src="http://www.ucdtramp.com/images/msc/youtube.png" alt="Youtube Icon"><br><div id="youtube"></div></a>
        <a href="http://www.ucdtramp.com/page/constitution" target="_blank" title="View the image full size">
            <img src="http://www.ucdtramp.com/images/msc/constitution.png" alt="Club constitution"><br><div id="const"></div></a>
    </div>
    
    <img src="http://www.ucdtramp.com/images/bkgrnd/footer.jpg" style="width:100%;" alt="Bottom of trampoline">
</div><!--footer-->
<div id="bighead">Site designed and built by Paul Connolly</div>

<script src="http://www.ucdtramp.com/js/konami.js"></script>
<script src="http://www.ucdtramp.com/js/general.js"></script>
<script src="http://www.ucdtramp.com/js/committeebox.js"></script>

<script src="http://www.ucdtramp.com/plugins/scrolling/jquery.scrollTo-1.4.3.1-min.js"></script>
<script src="http://www.ucdtramp.com/plugins/scrolling/jquery.localscroll-1.2.7-min.js"></script>

<script>
$(document).ready(function() {
	//if cookie is set make it a down arrow.
	if(readCookie('navJump')=='true'){
		window.scrollTo(0, 510.688140556369/1600*window.innerWidth);
		$('#jumptoggle').css('color','white');
		$('#jumptoggle').attr('title','Toggle On');
	}
	
	$('#jumptoggle').click(function(){
		//if cookie is set, remove it and change icon.
		console.log('hi');
		if(readCookie('navJump')=='true'){
			$('#jumptoggle').css('color','rgba(255,255,255,0.5)');
			$('#jumptoggle').attr('title','Toggle Off');
			deleteCookie('navJump');
		}
		else{
			//if cookie not set, set it and change icon.
			$('#jumptoggle').css('color','white');
			$('#jumptoggle').attr('title','Toggle On');
			createCookie('navJump','true','360000');
		}
	});	
	
	//Move 3 bars over if user is logged in sso that nav isnt hidden behind the tab
	if( $('#tools').css('display')=='block' && $('#logincont').css('display')=='block' ){
		$('#icon-bars').css({'position':'relative','right':'90px'});
	}					
	$('#icon-bars').click(function(){	
		$('nav ul').slideToggle();	//most have defined height or will be jumpy
	});	
});
</script>

<script>
  (function(i,s,o,g,r,a,m){i['GoogleAnalyticsObject']=r;i[r]=i[r]||function(){
  (i[r].q=i[r].q||[]).push(arguments)},i[r].l=1*new Date();a=s.createElement(o),
  m=s.getElementsByTagName(o)[0];a.async=1;a.src=g;m.parentNode.insertBefore(a,m)
  })(window,document,'script','//www.google-analytics.com/analytics.js','ga');

  ga('create', 'UA-41915009-3', 'ucdtramp.com');
  ga('send', 'pageview');

</script>

</body>
</html>

<?php
require_once('db.php');
mysqli_close($db);
?>