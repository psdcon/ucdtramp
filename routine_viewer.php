<?php
require_once ('includes/functions.php');
$title="Routine Viewer";
addheader();
?>
<style>
  tbody tr {
    cursor: pointer;
  }
  .table-striped>tbody>tr:hover {
    background-color: #ccc;
  }
</style>
<div class="row">
    <div class="col-md-push-5 col-md-7">
        <div class="embed-responsive">
            <!-- 1. The <iframe> (and video player) will replace this <div> tag. -->
            <!-- Specify the video id in the javascript below -->
            <div class="embed-responsive-item" id="player"></div>
        </div>
    </div>

    <div class="col-md-pull-7 col-md-5">
        <table class="table table-striped">
            <thead>
                <tr>
                    <th>Skill</th>
                    <th>Tariff</th>
                    <th>Deduction</th>
                    <th>Fault</th>
                </tr>
            </thead>

            <tbody>
                <tr data-timestamp="12.6">
                    <td>Rudi</td>
                    <td>0.8</td>
                    <td>0.1+0.1</td>
                    <td>A,L</td>
                </tr>
                <tr data-timestamp="14.5">
                    <td>Straight Back</td>
                    <td>0.6</td>
                    <td>0.1</td>
                    <td>S</td>
                </tr>
                <tr data-timestamp="16.3">
                    <td>Straight Barani</td>
                    <td>0.6</td>
                    <td>0.1</td>
                    <td>S</td>
                </tr>
                <tr data-timestamp="18">
                    <td>Pike Back</td>
                    <td>0.6</td>
                    <td>0.2</td>
                    <td>S</td>
                </tr>
                <tr data-timestamp="19.6">
                    <td>Pike Barani</td>
                    <td>0.6</td>
                    <td>0.1+0.1+0.1</td>
                    <td>A,L,S</td>
                </tr>
                <tr data-timestamp="21.3">
                    <td>Tuck Back</td>
                    <td>0.5</td>
                    <td>0.1+0.1</td>
                    <td>S,A</td>
                </tr>
                <tr data-timestamp="23.1">
                    <td>Tuck Barani</td>
                    <td>0.6</td>
                    <td>0.2</td>
                    <td>S</td>
                </tr>
                <tr data-timestamp="24.8">
                    <td>Half Twist</td>
                    <td>0.1</td>
                    <td>0.1</td>
                    <td>A</td>
                </tr>
                <tr data-timestamp="26.5">
                    <td>Tuck Jump</td>
                    <td>0.0</td>
                    <td>0.1</td>
                    <td>S</td>
                </tr>
                <tr data-timestamp="28.2">
                    <td>Full</td>
                    <td>0.7</td>
                    <td>0.1+0.1+0.2</td>
                    <td>A,S,TI</td>
                </tr>
            </tbody>

            <tfoot>
                <tr>
                    <th>Avg. Score</th>
                    <th>Total Tariff</th>
                    <th>Judge Score</th>
                    <th>Total Score</th>
                </tr>
                <tr>
                    <td>8.0</td>
                    <td>5.1</td>
                    <td>24.0</td>
                    <td>29.1</td>
                </tr>
            </tfoot>
        </table>
    </div>
</div>

<div class="col-md-12">
        <a href="https://ucdtramp.com/page/mynamespaulandimalegend" style="color: #333"><strong><u>Legend:</u></strong></a>

        <p> All explanations are from the current code of points. </p>

        <p><b><u>A</u></b><br>
        Deduction for arms being in the incorrect position. <br>
        <i>"14.5 The arms should be straight and/or held close to the body whenever possible. <br>
            Moving arms away from the body is acceptable to stop a twisting rotation.  The maximum of the angle
            between the trunk and the arms should be: <br>
            <ul>
                <li>Barani, Full, multiple somersaults with ½ out movements     45°</li>
                <li>More than full twist and all other multiple twisting somersaults     90°"</li></i>  </ul>
        </p>

        <p><b><u>L</u></b><br>
        Deduction for legs being in the incorrect position. <br>
        <i>"14.1 In all positions, the feet and legs should be kept together (except straddle jumps), and the feet and
            toes pointed." <br>  </i>
        </p>

        <p><b><u>S</u></b><br>
        Deduction for shape being incorrect. <br>
        <i>"14.2 Depending on the requirements of the movement, the body should be either, tucked, piked or
                 straight. <br>  
            14.3 In the tucked and piked positions the thighs should be close to the upper body except in the
                 twisting phase of multiple somersaults (see §14.7). <br>
            14.4 In the tucked position the hands should touch the legs below the knees except in the twisting phase
                 of multiple somersaults (see §14.7). <br>
            14.6 The following defines the minimum requirements for a particular body shape:<br>
            <ol>
                <li>Straight position: The angle between the upper body and thighs must be greater than
                    135°.</li>
                <li>Pike position: The angle between the upper body and thighs must be equal to or less than
                    135° and the angle between the thighs and the lower legs must be greater than 135°.     </li></i>
                <li>Tuck position: The angle between the upper body and thighs must be equal to or less
                    than 135° and the angle between the thighs and the lower legs must be equal to or less
                    than 135°.</li>
            </ol>
            14.7 In multiple somersaults with twists, the tuck and pike position may be modified during the twisting
                 phase (puck and pike twisting positions)."</i>
        </p>

        <p><b><u>TI</u></b><br>
        Deduction for tucking in before a certain position during the skill. Deduction is relative to where tucking occurs, eg. 1 o'clock.<br>
        </p>
</div>

<script>
    // 2. This code loads the IFrame Player API code asynchronously.
    var tag = document.createElement('script');

    tag.src = "https://www.youtube.com/iframe_api";
    var firstScriptTag = document.getElementsByTagName('script')[0];
    firstScriptTag.parentNode.insertBefore(tag, firstScriptTag);

    // 3. This function creates an <iframe> (and YouTube player)
    //    after the API code downloads.
    var player;
    function onYouTubeIframeAPIReady() {
        player = new YT.Player('player', {
            width: '',
            height: '',
            videoId: '-ZwzSEZe-WE',
            playerVars: {rel: 0}, // Don't show related videos when finished
            events: {
                'onReady': onPlayerReady,
                'onStateChange': onPlayerStateChange
            }
        });
    }

      // 4. The API will call this function when the video player is ready.
      // This starts the video automatically on desktop devices.
      // Mobile devices will ignore this because of potential data charges
    function onPlayerReady(event) {
        event.target.playVideo();
    }

    // 5. The API calls this function when the player's state changes.
    //    The function indicates that when playing a video (state=1),
    //    the player should play for six seconds and then stop.
    var highlighInterval = null;
    function onPlayerStateChange(event) {
        if (event.data == YT.PlayerState.PLAYING) {
            // While the video is playing, check every 25 ms if we should highlight a skill
            // Save interval instance so it can be turned off later
            highlighInterval = setInterval(highlightSkill, 25);
        }
        else {
            // Turn off skill highlight checker if video is not playing
            clearInterval(highlighInterval);
        }
    }

    //
    // Code for highlighting skill
    //

    // Get an array of all the row elements that have a timestamp
    // Then make an array of the timestamp numbers
    var $rows = $('*[data-timestamp]');
    var timestamps = [];
    $rows.each(function(i, value){
        timestamps[i] = $(this).data('timestamp');

        // Clicking the line changes the player position
        $(this).click(function(){
            player.seekTo(timestamps[i], true);
        });
    });


    // Function called every 25 ms if video is playing
    function highlightSkill() {
        // Get the current time the video is at in seconds
        ct = player.getCurrentTime();

        // If video is before the first move, remove any highlights
        if (ct < timestamps[0]){
            $('.highlightSkill').removeClass('highlightSkill');
            return;
        }
        // If the video is 3 seconds after the last skill, remove highlighting (It's probably finished).
        if (ct > timestamps[timestamps.length-1] + 3){
            $('.highlightSkill').removeClass('highlightSkill');
            return;
        }

        // Otherwise, loop through skill timestamps to find out what skill to highlight

        var currentSkill = 0;
        var previousSkill = 1;

        for (var i = 0; i < timestamps.length; i++) {
            thisTs = timestamps[i];
            nextTs = (i == timestamps.length-1)? 99999 : timestamps[i+1]; // At the end of the array, there's no nextTs so make it some big number

            // Update the current move to the one being shown. Happens every 25 ms.
            if (ct >= thisTs && ct < nextTs){
                currentSkill = i;
            }
            // Remove highlightSkill from any old rows and add it to the current row element. Old happens when current skill changes.
            if (currentSkill != previousSkill){
                $('.highlightSkill').removeClass('highlightSkill');
                $rows.eq(i).addClass('highlightSkill');
                previousSkill = currentSkill;
            }
        }
    }
</script>

<style>
    .highlightSkill,
    .table-striped>tbody>tr:nth-of-type(odd).highlightSkill {
        color: #3c763d;
        background-color: #dff0d8;
        border-color: #d6e9c6;
    }
</style>

<?php
addfooter();
?>
