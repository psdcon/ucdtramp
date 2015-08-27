<?php
include_once('includes/functions.php');
$title = 'Polls';

if (isset($_GET['action']) && $loggedin) { // Create poll
    $action = $_GET['action'];
    
    if ($action == 'add') { // Show form to create new poll
        addheader();
        echo '
        <h1>
            <a style="color:black;text-decoration:none;" href="http://www.ucdtramp.com/manage_polls.php#cont" title="Back to all Polls">
                Create Poll
            </a>
        </h1>

        <div class="whitebox">
            <p>
                Create a new poll with the form below. You don\'t need to fill in all the options, any that are left blank won\'t appear in the poll.
            </p>
            <br>
            <form action="http://www.ucdtramp.com/manage_polls.php" method="GET">
            <table>
                <tr>
                    <td align="right">Poll Question: </td>
                    <td align="left">
                        <input type="text" name="question" maxlength="255" size="70" />
                    </td>
                </tr>';

        for ($i = 1; $i <= 12; $i++) {
            echo '
                <tr>
                    <td align="right">Option '.$i.':</td>
                    <td align="left"> <input type="text" name="option'.$i.'" maxlength="255" size="70" /></td>
                </tr>';
        }

        echo '
                <tr>
                    <td align="right">&nbsp;</td>
                    <td align="left"><input type="submit" name="action" value="Create Poll" /></td>
                </tr>
            </table>
            </form>
        </div>';

    // When the above form is submitted, this runs and the info is added to the database
    } else if ($action == 'Create Poll') { 
        mysqli_query($db, "INSERT INTO polls (question,created) VALUES('".mysqli_real_escape_string($db, ($_GET['question']))."',".time().")");

        for ($i = 1; strlen($_GET['option'.$i]) != 0; $i++) {
            mysqli_query($db, "UPDATE polls 
                SET option".$i." = '".(mysqli_real_escape_string($db, ($_GET['option'.$i])))."',
                    numofoptions=".$i." 
                WHERE id=LAST_INSERT_ID()");
        }
        header("Location:http://www.ucdtramp.com/manage_polls.php?poll=latest#cont");
    }

// Show/vote on Poll
} else {
    if (isset($_REQUEST['poll'])) {
        
        // User is voting
        $poll = $_REQUEST['poll'];
        if ($poll == 'latest') {
            $currentpoll_query = mysqli_query($db, "SELECT * FROM polls ORDER BY id DESC LIMIT 1");
        } else {
            $currentpoll_query = mysqli_query($db, "SELECT * FROM polls WHERE id='$poll'");
        }
        $currentpoll = mysqli_fetch_array($currentpoll_query);
        
        // Add vote in database to selected poll
        if (isset($_REQUEST['vote'])) {
            // Make sure people only vote once
            $user_ip = encode_ip($_SERVER['REMOTE_ADDR']); // encode users ip for database check
            $existing_voters = mysqli_query($db, "SELECT * FROM poll_voters WHERE poll='$poll' AND ip='$user_ip'");
            if (mysqli_fetch_array($existing_voters)){
                // If they already voted, redirect to results with a warning message
                header("Location:http://www.ucdtramp.com/manage_polls.php?poll=$poll&results=show&naughty=person#cont");
            } else {
                // increase counts
                $option          = mysqli_real_escape_string($db, $_REQUEST['option']);
                $new_numofvotes  = $currentpoll['numofvotes'] + 1;
                $new_optionvotes = $currentpoll['vote'.$option] + 1;
                // Add increased counts to database
                mysqli_query($db, "UPDATE polls 
                    SET vote$option = '$new_optionvotes',
                    numofvotes = $new_numofvotes
                    WHERE id = $poll");

                // Add voter's ipaddress to db so they connot vote on this poll again
                mysqli_query($db, "INSERT INTO poll_voters (poll, option_picked, ip) VALUES ($poll, $option, '$user_ip') ");
                
                // Print error if there's any database problems. This will hault the header() function
                echo mysqli_error($db);
                // Otherwise move onto the show results page
                header("Location:http://www.ucdtramp.com/manage_polls.php?poll=$poll&results=show#cont");
            }
        }
        
        // Display the results of a poll
        else if (isset($_REQUEST['results'])) {
            addheader();
            echo '
                <h1>
                    <a style="color:black;text-decoration:none;" href="http://www.ucdtramp.com/manage_polls.php#cont" title="Back to all Polls">
                        Results
                    </a>
                </h1>';
            
            if (isset($_REQUEST['naughty'])) {
                echo '
                <p style="color:red;font-size:2em">
                    Nice try but you\'re only allowed to vote once!
                </p>
                <br>';
            }
?>
            <style> /* CSS for the animation of the progress-bar width */
                @-webkit-keyframes progress-bar {
                   0% { width: 0; }
                }
                @-moz-keyframes progress-bar {
                   0% { width: 0; }
                }
                .progress {
                  height: 21px;
                  margin-bottom: 21px;
                  margin-top: 10px;
                  overflow: hidden;
                  background-color: #cccccc;
                  -webkit-border-radius: 7px;
                     -moz-border-radius: 7px;
                          border-radius: 7px;
                  -webkit-box-shadow: inset 0 1px 2px rgba(0, 0, 0, 0.1);
                          box-shadow: inset 0 1px 2px rgba(0, 0, 0, 0.1);
                }

                .progress-bar {
                  float: left;
                  width: 0;
                  height: 100%;
                  font-size: 13px;
                  color: #ffffff;
                  text-align: center;
                  background-color: #007fff;
                  -webkit-box-shadow: inset 0 -1px 0 rgba(0, 0, 0, 0.15);
                          box-shadow: inset 0 -1px 0 rgba(0, 0, 0, 0.15);
                  -webkit-animation: progress-bar 2s;
                     -moz-animation: progress-bar 2s; 
                }
            </style>

<?php 
            // 12 progress bar colours
            $bgcolours = array(
                "#9954bb",
                "#007fff",
                "#3fb618",
                "#ff7518",
                "#FF0000",
                "#0CF",
                "#FC0",
                "#9954bb",
                "#007fff",
                "#3fb618",
                "#ff7518",
                "#ff0039"
            );


            echo '
            <div class="whitebox">
                <h3>'.($currentpoll['question']).' '.$currentpoll['numofvotes'].' votes</h3>';
   
            for ($i = 1; $i <= $currentpoll['numofoptions']; $i++) {
                $percentage = round((100 * $currentpoll['vote'.$i] / $currentpoll['numofvotes']), 1);
                
                echo '
                <div>
                    <strong>'.$currentpoll['option'.$i].'</strong>
                    <em> - '.(($currentpoll['vote'.$i] == 0) ? '0' : $currentpoll['vote'.$i]).' votes ('.$percentage.'%)</em>
                </div>
                <div class="progress">
                    <div class="progress-bar" style="width:'.$percentage.'%;background-color:'.$bgcolours[$i].'"></div>
                </div>';
            }
            echo '
                <br>
                <a href="http://www.ucdtramp.com/manage_polls.php?poll='.$currentpoll['id'].'#cont">Vote</a> or see
                <a href="http://www.ucdtramp.com/manage_polls.php#cont">Old Polls</a>
            </div>';
        }
        
        // Voting form
        else {
            addheader();
            echo '
            <h1>
                <a style="color:black;text-decoration:none;" href="http://www.ucdtramp.com/manage_polls.php#cont" title="Back to all Polls">
                    Vote
                </a>
            </h1>
            <form action="http://www.ucdtramp.com/manage_polls.php" method="GET">
                <div class="whitebox">
                    <strong>'.$currentpoll['question'].'</strong>
                    <br><br>
                    <input type="hidden" name="poll" value="'.$currentpoll['id'].'"/>';
                for ($i = 1; $i <= $currentpoll['numofoptions']; $i++) {
                    echo '
                    <p style="line-height:1.6">
                        <input type="radio" name="option" value="'.$i.'" id="option'.$i.'"/>
                        <label for="option'.$i.'">'.$currentpoll['option'.$i].'</label>
                    </p>';
                }
                echo '
                    <br>
                    <input type="submit" name="vote" title="You only get one" value="Cast Vote" />
                    <br><br><br>
                    <a href="http://www.ucdtramp.com/manage_polls.php?poll='.$currentpoll['id'].'&results=show#cont">View Results</a> or see
                    <a href="http://www.ucdtramp.com/manage_polls.php#cont">Old Polls</a>
                </div>
            </form>';
        }
        
    // List all polls   
    } else {
        addheader();
        echo '
        <h1>
            <a style="color:black;text-decoration:none;" href="http://www.ucdtramp.com/manage_polls.php#cont">
                Polls
            </a>
        </h1>';
        $polls = mysqli_query($db, "SELECT * FROM polls ORDER BY id DESC");
        
        if (isset($_GET['success'])) { // If an action has been successful, a msg box will be displayed
            echo '
            <h3 style="color:green">
                Poll '.$_GET['success'].' successfully!
            </h3>';
        }
        if ($loggedin) {
            echo '
            <h2>
                <a style="color:black" href="http://www.ucdtramp.com/manage_polls.php?action=add#cont" title="Committee Only">
                    + New Poll
                </a>
            </h2>';
        }

        echo '
        <table class="admin">
            <tr class="header">
                <th>ID</th>
                <th style="width:100px;">Action</th>
                <th>Num Choices</th>
                <th>Num Votes</th>
                <th style="text-align:left">Question</th>
            </tr>';
        
        // List all polls with view results/vote buttons
        $row = 'odd';
        while ($poll = mysqli_fetch_array($polls)) {
            $row = ($row == 'odd' ? 'even' : 'odd'); // Alternate even and odd rows
            echo'
            <tr class="'.$row.'" style="text-align:center"> 
                <td>'.$poll['id'].'</td>
                <td>
                    <a href="http://www.ucdtramp.com/manage_polls.php?poll='.$poll['id'].'&results=show#cont" title="View Results"><i class="fa fa-bar-chart-o"></i> View</a>
                    <br>
                    <a href="http://www.ucdtramp.com/manage_polls.php?poll='.$poll['id'].'#cont" title="Vote"><i class="fa fa-crosshairs"></i> Vote</a>
                </td>
                <td>'.$poll['numofoptions'].'</td>
                <td>'.$poll['numofvotes'].'</td>
                <td style="text-align:left">
                    <a style="text-decoration:none;" href="http://www.ucdtramp.com/manage_polls.php?poll='.$poll['id'].'&results=show#cont" title="View Results">'.
                        smilify(($poll['question']), 0).'
                    </a>
                </td>
            </tr>';
        }
        echo '
        </table>';
    }
}
addfooter();
?>