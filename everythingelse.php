<?php
include_once('includes/functions.php');
$title='\'s Hub';
$description="Ask a tramp, Sample routines and drill sheets, Tarrif calculator and Games";
addHeader();
?>
<div class="row">
    <div class="col-xs-12 col-md-6 ee-thumb-link">
        <a class="no-ul" href="//ucdtramp.com/tariff">
            <h3>Tariff Calculator</h3>
            <img src="images/pages/everythingelse/tariff_calc.png" alt="Tariff calc">
            <div>It's more of a routines builder now I suppose.</div>
        </a>
        <p>Find the old one <a href="page/tariff">here</a>.</p>
    </div>
    <div class="col-xs-12 col-md-6 ee-thumb-link">
        <a class="no-ul" href="page/squad">
            <h3>Squad Page</h3>
            <img style="border-radius: 10px;" src="images/pages/squad/squadx800.jpg" alt="Love Squad">
            <p>Level up those tramp skillz</p>
        </a>
    </div>
</div>
<div class="row">
    <div class="col-xs-12 col-md-6 ee-thumb-link">
        <a class="no-ul" href="youtubevids">
            <h3>Youtube Routines</h3>
            <img style="padding:10%" src="https://www.youtube.com/yt/brand/media/image/YouTube-icon-full_color.png" alt="Youtube icon">
            <p>Videos on our youtube channel, group by person</p>
        </a><br>
    </div>
    <div class="col-xs-12 col-md-6 ee-thumb-link">
        <a class="no-ul" href="askatramp">
            <h3>Ask-A-Tramp</h3>
            <img src="images/pages/askatramp/Ask-A-Tramp.png" alt="Ask a Tramp">
            <p>The master of all tramp wisdom! Ask a question and you will recieve nothing but truths...</p>
        </a>
    </div>
</div>

<div class="row" id="sheets">
    <div class="col-xs-12">
        <h2>Sport</h2>
    	This is the part of the site that will help with your trampolining. <span style="font-size:.6em;">It's itty bitty</span><br><br>

        <a href="page/moves">Moves Glossary</a> - Ever wonder how to do a Glasgow? This page will tell you, along with explaining all of the other trampolining jargon.<br><br>

        If you're Dwayne or some other a really ambitious tramp (Colm), have a look at these <strong title="Down there">Drill Sheets</strong> and <strong>Sample Routines</strong>
        
        <h3>Drills by Level</h3>
        <ul class="ee-sports-links">
            <li><a href="//ucdtramp.com/files/fitness/Novice%20Drills.pdf">Novice Drills</a></li>
            <li><a href="//ucdtramp.com/files/fitness/Intermediate%20Drills.pdf">Intermediate Drills</a></li>
            <li><a href="//ucdtramp.com/files/fitness/Advanced%20Drills.pdf">Advanced Drills</a></li>
            <li><a href="//ucdtramp.com/files/fitness/Elite%20Drills.pdf">Elite Drills</a></li>
        </ul>
        <h3> Competition Routines </h3>
        <ul class="ee-sports-links">
            <li><a href="//ucdtramp.com/files/fitness/Regionals.pdf">Regionals</a></li>
            <li><a href="//ucdtramp.com/files/fitness/Regionals%20Synchro.pdf">Regionals Synchro</a></li>
            <li><a href="//ucdtramp.com/files/fitness/Scotland%2008-09.pdf">Scotland 08-09</a></li>
            <li><a href="//ucdtramp.com/files/fitness/ISTO%2008-09.pdf">ISTO 2008-09</a></li>
        </ul>        
        <h3>Worksheets and Routine Development</h3>
        <ul class="ee-sports-links">
            <li><a href="//ucdtramp.com/files/fitness/Group%20Worksheets.pdf">Group Worksheets</a></li>
            <li><a href="//ucdtramp.com/files/fitness/Routine%20Development.pdf">Routine Development</a></li>
        </ul>        
        <h3>Lovett Nutrition</h3>
        <ul class="ee-sports-links">
            <li><a href="//ucdtramp.com/files/nutrition/Nutrition1.pdf">Nutrition Annual Plan</a></li>
            <li><a href="//ucdtramp.com/files/nutrition/Nutrition2.pdf">Nutrition Plan Information</a></li>
        </ul>
    </div>
</div>

<div class="row" id="games">
    <div class="col-xs-12">
        <h2>Games</h2>
        <a href="//ucdtramp.com/page/lovetojump" title="Love to Jump">
            <img src="//ucdtramp.com/files/games/lovetojump.jpg" alt="Game"/>
        </a>
        <a href="//ucdtramp.com/page/elitegame" tite="Trampoline">
            <img src="//ucdtramp.com/files/games/trampoline.jpg" alt="Game"/>
        </a>
        <a href="//ucdtramp.com/page/novicegame" title="Trampoline Trickz">
            <img src="//ucdtramp.com/files/games/trickz2.jpg" alt="Game"/>
        </a>
    </div>
</div>

<div class="row" style="margin-top:1em;">
    <div class="col-xs-12 col-md-6" id="polls">
        <table class="table table-striped">
            <thead>
                <tr>
                    <th>
                        <a href="polls" title="Click to see all polls">Recent Polls</a>                        
                    </th>
                </tr>
            </thead>
            <tbody>
<?php 
                $recentPolls=mysqli_query($db, "SELECT * FROM polls ORDER BY id DESC LIMIT 5");
                while($poll = mysqli_fetch_array($recentPolls)){
                    echo 
                    '<tr>
                        <td> 
                            <a href="polls/'.$poll['id'].'&results=show" title="View Results">
                                <i class="fa fa-bar-chart-o"></i>
                            </a>
                            <a href="polls/'.$poll['id'].'" title="Vote">
                                <i class="fa fa-crosshairs"></i>
                            </a>'.
                            smilify($poll['question'],NULL).'
                        </td>
                    </tr>'; 
                } 
?>
            </tbody>
        </table>
        
    </div>
    <div class="col-xs-12 col-md-6" id="other">
            <h3>Other stuff</h3>
    
            <a href="page/quotes">Trampy Quotes</a><br>
            <a href="page/ryanbjface">Hahaha!</a><br>
            <!-- <a href="page/log">Website Changelog</a>
            - Info about updates to the website are half documented here -->
    </div>
</div>

<?php
addFooter();
?>

