<!doctype html>
<html><head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0, maximum-scale=1.0 user-scalable=0"/>
    <title><?php if($title != 'UCD Trampoline Club') echo 'UCDTC ';?><?=$title;?></title>
    <meta name="description" content="<?=$description?>"></meta>
    <link rel="icon" type="image/png" href="http://www.ucdtramp.com/styles/favicon.png">
    
    <link rel="stylesheet" type="text/css" href="http://www.ucdtramp.com/styles/reset.css">
    <link href="//netdna.bootstrapcdn.com/font-awesome/4.0.3/css/font-awesome.css" rel="stylesheet"> <!--Font awesome - icon plugin-->
    <link href="http://www.ucdtramp.com/styles/stylesheet.css" rel="stylesheet" type="text/css">
    <link href="http://www.ucdtramp.com/styles/mobile.css" rel="stylesheet" type="text/css" media="screen and (max-width: 580px)">
    
    <script src="http://code.jquery.com/jquery-1.8.3.min.js"></script>
  		
</head>


<!--LOGIN STUFF-->
<body>
<div id="logintab"><div id="loginheader" title="For committee only">Committee Login</div>
	<div id="loginform" style="display:none;margin-top:.2em;">
        <form onSubmit="return checkLogin();">
            <label style="color:white;">Try it, I dare you!</label><br>
            <input type="text" id="commuser" style="width:9em;" placeholder="Username" title="Case insensitive"><br>
            <input type="password" id="commpass" style="width:8.8em;" placeholder="Password"><br>
            <label id="loginlabel" style="font-size:12px;color:red;display:block;"></label><!--Holds Errors-->
            <button type="submit" style="width:9em;">Log In</button></form>
    </div>
    <div id="loginlinks">
        Hello <span id="loginusername"><?php if(isset($_COOKIE['user'])){echo $_COOKIE['user'];}?></span> <i class="fa fa-smile-o"></i><br>
        <a style="color:#C3C;" href="http://www.ucdtramp.com/commapps">Com Section</a><br>
        <a style="color:orange;" href="http://www.ucdtramp.com/forum/2">Com Forum <span id="lastposttime2"></span></a><br>
        <?php if ($editperm){ // true in page.php if logged in and have correct edit permission for page ?>
            <a href="javascript:makeEditable(document.getElementById('pagecontent'),'<?php echo $pageURL; ?>');" style="color:#6F0;">Edit Page</a><br>
            <script type="text/javascript" src="http://www.ucdtramp.com/js/page.js"></script> <?php }	
	        if (isset($lasteditu)){echo 'Last Edit: <div id="lastedit"><!--filled by editpage.php--></div>';} ?>
        <button style="width:9em;"><a style="text-decoration:none;color:black;" href="http://www.ucdtramp.com/ajax_php/logout.php">
            <i class="fa fa-sign-out" style="font-size:.8em;"></i> <strong>Log out</strong></a></button>
    </div> <!--/links-->
</div>

<!--UCD info in top left-->
<div id="UCD">
    <a href="http://www.ucd.ie/" target="_blank">
    	<img src="http://www.ucdtramp.com/images/bkgrnd/UCDlogo.png" alt="UCD Crest"></a>
    <div id="bragsnottobeproudof">
        UCD Event of the year 2003<br>
        UCD Club of the year 2005<br>
        UCD Club of the year 2011<br></div>
</div>
    
<div id="top"><!--Men image with the trampoline set as the bkground-->
  <img title="Nice logo, aint it" src="<?php ($title=='News')? $header_img='http://www.ucdtramp.com/images/bkgrnd/men.png': $header_img='http://www.ucdtramp.com/images/bkgrnd/men.png'; echo $header_img;?>" style="width:100%;" alt="UCDTC Evolutoion logo"></div> 
  
<div id="background"> <!--Background of sides and wood-->
	<a style="position:absolute;height:100%;width:10%;" href="#cont" title="Scroll to Navbar"></a>

    <div id="bugbox">
        <span id="bugspan" title="See any of these?">Bugs, Typos or &#xfffd;'s?</span>
        <div id="loveyou" style="display:none;color:red;"></div>
        
        <form style="display:none" onSubmit="toWebmaster();return false;">
            <textarea id="bugs" placeholder="See something that looks wrong or if something weird just happened or if you see a typo or a &#xfffd; or you have an AWESOME idea to improve the site please let me know from right here."></textarea>
            <input type="hidden" id="pgtitle" value="<?=$title?>">
            <button type="submit" id="bugbutton" style="width:68%;padding-right:2%;">Tell Conor</button>
            <a href="http://www.ucdtramp.com/forum/404"><button type="button" style="width:30%">404 Forum</button></a>
        </form>
    </div><!--/bugbox-->
    
    <!--Konami-->
    <div style="display:none;position:fixed;top:30%;width:100%;text-align:center;" id="spidey">
        <img src="http://www.ucdtramp.com/images/msc/spiderman-dancing.gif" alt="Go spidey, go!"></div>
        
    <div id="cont"> <!--contains all content incl navbar-->
    <nav>
      <!--displays on smaller screens. 3 spans become horizontal bars that, when toggled, display nav links-->
      <div id="navtitle">UCD Trampoline Club  <span id="lastposttime1v"></span>
          <span id="icon-bars" style="display:inline-block;padding-top:2px;float:right;cursor:pointer;">
              <span class="icon-bar"></span><span class="icon-bar"></span><span class="icon-bar"></span></span>  
      </div> <!--/navtitle-->
      <ul>
        <li><a href="http://www.ucdtramp.com/news" title="Home Page"><i class="fa fa-home"></i> &nbsp;News</a></li>
        <li><a href="http://www.ucdtramp.com/about" 
title="About the Club:
-Fitness
-Our Social Side
-Committee
-Coaches">About</a></li>
        <li><a href="http://www.ucdtramp.com/events" 
	title="Upcoming and Past Events:
-Events Calender
-Competitions and Results
-Reports on trips/competitions
">Events</a></li>
        <li style="position:relative;margin-right:1em;"><a href="http://www.ucdtramp.com/forum" title="Where it's at!">FORUM
            <span id="lastposttime1h"></span></a></li>
        <li><a href="http://www.ucdtramp.com/gallery" title="Photo Gallery">Gallery</a></li>
        <li><a href="http://www.ucdtramp.com/hub" 
	title="Extras !
-Tariff Calculator
-Ask A Tramp
-Moves Glossary
-Devolpment Drills
-Nutrition
-Games
-Polls
">Hub</a></li>
        <li><a href="http://www.ucdtramp.com/page/contact" title="Self explanatory really">Contact</a></li>
        <span id="jumptoggle" title="Toggle Off"><i class="fa fa-angle-down"></i></span>
      </ul>
    </nav>
    
    <div style="padding:.5em;">