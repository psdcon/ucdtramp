<?php
include_once('includes/functions.php');
$title='\'s Hub';
$description="Ask a tramp, Sample routines and drill sheets, Tarrif calculator and Games";
addheader();
?>
<style>
h2, h3 {
	text-align:center;
}
.half{
	width:49%;
	display:inline-block;
	padding:.5em;
	margin:.5em;
	vertical-align:top;
	text-align:center;
}
</style>

<div style="display:flex;align-items:stretch;">
    <div class="whitebox half">
        <a href="http://www.ucdtramp.com/tariff"za style="color:black;text-decoration:none;" target="_blank">
            <h2>The Tariff Calculator of Awesome!</h2><hr>
            <img style="width:100%;" src="http://www.ucdtramp.com/images/pages/tariff_calc.png">
            It's more of a routines builder now I suppose.</a><br>
            Find the old one <a href="http://www.ucdtramp.com/page/tariff">here</a>.
    </div>
    <div class="whitebox half">
        <a href="http://www.ucdtramp.com/page/askatramp" style="color:black;text-decoration:none;">
        <h2>Ask-A-Tramp</h2><hr>
        <img src="http://www.ucdtramp.com/images/pages/Ask-A-Tramp.png" alt="Ask a Tramp" style="width:100%">
        The master of all tramp wisdom! Ask a question and you will recieve nothing but truths...</a>
    </div>
</div>

<div id="sheets" class="whitebox" style="margin-top:.5em;">
<h2>Sport</h2><hr>
	This is the part of the site that will help with your trampolining. <span style="font-size:.6em;">It's itty bitty</span><br><br>

<a href="http://www.ucdtramp.com/page/moves">Moves Glossary</a> - Ever wonder how to do a Glasgow? This page will tell you, along with explaining all of the other trampolining jargon.<br><br>

If you're Dwayne or just a really ambitious tramp, have a look at these <strong title="Down there">Drill Sheets</strong> and <strong>Sample Routines</strong>
    <ul id="stuff">
        <li><h3>Drills by Level</h3>
        <a href="http://www.ucdtramp.com/files/fitness/Novice%20Drills.pdf">Novice Drills</a><br>
        <a href="http://www.ucdtramp.com/files/fitness/Intermediate%20Drills.pdf">Intermediate Drills</a><br>
        <a href="http://www.ucdtramp.com/files/fitness/Advanced%20Drills.pdf">Advanced Drills</a><br>
        <a href="http://www.ucdtramp.com/files/fitness/Elite%20Drills.pdf">Elite Drills</a><br><br></li>

        <li><h3> Competition Routines </h3>
        <a href="http://www.ucdtramp.com/files/fitness/Regionals.pdf">Regionals</a><br>
        <a href="http://www.ucdtramp.com/files/fitness/Regionals%20Synchro.pdf">Regionals Synchro</a><br>
        <a href="http://www.ucdtramp.com/files/fitness/Scotland%2008-09.pdf">Scotland 08-09</a><br>
        <a href="http://www.ucdtramp.com/files/fitness/ISTO%2008-09.pdf">ISTO 2008-09</a><br></li>
        
        <li><h3>Worksheets and Routine Development</h3>
        <a href="http://www.ucdtramp.com/files/fitness/Group%20Worksheets.pdf">Group Worksheets</a><br>
        <a href="http://www.ucdtramp.com/files/fitness/Routine%20Development.pdf">Routine Development</a><br><br></li>
        
        <li><h3>Lovett Nutrition</h3>
        <a href="http://www.ucdtramp.com/files/nutrition/Nutrition1.pdf">Nutrition Annual Plan</a><br>
        <a href="http://www.ucdtramp.com/files/nutrition/Nutrition2.pdf">Nutrition Plan Information</a><br><br></li>
    </ul>
</div>

<div id="games" class="whitebox">
    <h2>Games</h2><hr>
    <a href="http://www.ucdtramp.com/page/lovetojump" title="Love to Jump"><img src="files/games/lovetojump.jpg" alt="Game"/></a>
    <a href="http://www.ucdtramp.com/page/elitegame" tite="Trampoline"><img src="files/games/trampoline.jpg" alt="Game"/></a>
    <a href="http://www.ucdtramp.com/page/novicegame" title="Trampoline Trickz"><img src="files/games/trickz2.jpg" alt="Game"/></a>
</div>


<div class="whitebox" style="padding:.5em;"> 
	<span id="polls">
        <div class="odd" style="text-align:center;font-weight:bold;">
        	<a href="http://www.ucdtramp.com/manage_polls.php#cont" title="Click to see all polls">Recent Polls</a>
        </div>
        <?php $recentpolls_query=mysqli_query($db, "SELECT * FROM polls ORDER BY id DESC LIMIT 5");
        while($poll = mysqli_fetch_array($recentpolls_query)){
            $row=($row=='odd'?'even':'odd');		// Alternate even and odd rows
            echo (
            "<div class='".$row."'> 
                <div>
                    <a href='http://www.ucdtramp.com/manage_polls.php?poll=".$poll['id']."&results=show' title='View Results'><i class='fa fa-bar-chart-o'></i></a>
                    <a href='http://www.ucdtramp.com/manage_polls.php?poll=".$poll['id']."' title='Vote'><i class='fa fa-crosshairs'></i></a>
                    <a style='text-decoration:none;' href='http://www.ucdtramp.com/manage_polls.php?poll=".$poll['id']."' title='View Results'>".smilify($poll['question'],0)."</a>
                </div>
            </div>"); 
        } ?>
	</span>
    <span style="margin-left:1em;width: 47%;float: left;">
    	<h3>Other stuff</h3>
        <hr>

        <a href="http://www.ucdtramp.com/page/log#cont" style="color:blue">Website Changelog</a>
        - You can see most site updates and fixes that I make on this page<br><br>
    </span>
</div>

<!-- <a href="http://www.ucdtramp.com/page/quotes">Trampy Quotes</a><br> -->
<!-- <a href="http://www.ucdtramp.com/page/ryanbjface">Hahaha!</a><br> -->

<?php
addfooter();
?>

