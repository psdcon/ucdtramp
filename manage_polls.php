<?php
include_once('includes/functions.php');
$title = 'Polls';

// Create poll
if (isset($_GET['action']) && $loggedIn) {
    $action = $_GET['action'];

    if ($action == 'add') { // Show form to create new poll
        addHeader();
        echo '
        <h2>
            Creating Poll
            <small><small><a href="polls" title="Back to all Polls">Poll menu</a></small></small>
        </h2>
        <style>
            tr td:nth-of-type(1) {
                text-align: right;
            }
            /*Desktop*/
            @media (min-width: 768px) {
                tr td:nth-of-type(1) {
                    width: 150px;
                }
            }
        </style>
        <p>
            Note: You don\'t need to fill in all the options, any that are left blank won\'t appear in the poll.
        </p>
        <form action="manage_polls.php" method="GET">
            <table class="table table-hover">
                <thead>
                    <tr>
                        <td><strong>Poll Question: </strong></td>
                        <td>
                            <input class="form-control" type="text" name="question" />
                        </td>
                    </tr>
                </thead>
                <tbody>';

        for ($i = 1; $i <= 12; $i++) {
            echo '
                    <tr>
                        <td>Option '.$i.':</td>
                        <td>
                            <input class="form-control" type="text" name="option'.$i.'" />
                        </td>
                    </tr>';
        }
        echo '
                </tbody>
                <tfoot>
                    <tr>
                        <td>&nbsp;</td>
                        <td style="text-align:left">
                            <input class="btn btn-default" type="submit" name="action" value="Create Poll" />
                            <label class="checkbox-inline"><input type="checkbox" name="showOnForum" checked>Show on forum for next 3 days</label>
                        </td>
                    </tr>
                </tfoot>
            </table>
        </form>';

    // When the above form is submitted, this runs and the info is added to the database
    } else if ($action == 'Create Poll') {
        $showOnForum = (isset($_GET['showOnForum']))? 1: 0; // When set, the checkbox is checked to show the poll on the forum
        mysqli_query($db, "INSERT INTO polls (question, created, show_on_forum) VALUES('".mysqli_real_escape_string($db, ($_GET['question']))."',".time().", $showOnForum)");
        $lastId = mysqli_insert_id($db);

        for ($i = 1; strlen($_GET['option'.$i]) != 0; $i++) {
            mysqli_query($db, "UPDATE polls
                SET option".$i." = '".(mysqli_real_escape_string($db, ($_GET['option'.$i])))."',
                    numofoptions=".$i."
                WHERE id=".$lastId);
        }
        header("Location:polls/".$lastId);
    }

// Show/vote on Poll
} else {
    if (isset($_REQUEST['poll'])) {

        // User is voting
        $poll = $_REQUEST['poll'];
        if ($poll == 'latest') {
            $currentpoll_query = mysqli_query($db, "SELECT * FROM polls ORDER BY id DESC LIMIT 1");
        } else {
            $currentpoll_query = mysqli_query($db, "SELECT * FROM polls WHERE id=$poll LIMIT 1");
        }
        $currentpoll = mysqli_fetch_assoc($currentpoll_query);


        // Add vote in database to selected poll
        if (isset($_REQUEST['vote'])) {
            // Make sure people only vote once
            $user_ip = encode_ip($_SERVER['REMOTE_ADDR']); // encode users ip for database check
            $existing_voters = mysqli_query($db, "SELECT * FROM poll_voters WHERE poll=$poll AND ip='$user_ip' LIMIT 1");
            if (mysqli_num_rows($existing_voters) > 0){
                // If they already voted, redirect to results with a warning message
                header("Location:polls/results/$poll&naughty=person");
            } else {
                // Increase count
                $option = mysqli_real_escape_string($db, $_REQUEST['option']);
                $new_numofvotes = $currentpoll['numofvotes'] + 1;
                $new_optionvotes = $currentpoll['vote'.$option] + 1;
                // Add increased counts to database
                mysqli_query($db, "UPDATE polls SET vote$option = '$new_optionvotes', numofvotes = $new_numofvotes WHERE id = $poll");

                // Add voter's ipaddress to db so they connot vote on this poll again
                mysqli_query($db, "INSERT INTO poll_voters (poll, option_picked, ip) VALUES ($poll, $option, '$user_ip') ");

                // Print error if there's any database problems. This will hault the header() function
                echo mysqli_error($db);
                // Otherwise move onto the show results page
                header("Location:polls/results/$poll");
            }
        }

        // Display the results of a poll
        else if (isset($_REQUEST['results'])) {
            addHeader();
            echo '
            <h2>
                Results
                <small><small><a href="polls" title="Back to all Polls">Poll menu</a></small></small>
            </h2>';

            // Check to see if the user has already voted on this poll
            $user_ip = encode_ip($_SERVER['REMOTE_ADDR']); // encode users ip for database check
            $existing_voters = mysqli_query($db, "SELECT * FROM poll_voters WHERE poll=$poll AND ip='$user_ip' LIMIT 1");
            $tableFooter = '';
            if (mysqli_num_rows($existing_voters) == 0){
                // Not voted yet
                $tableFooter = '<a class="btn btn-primary" href="polls/'.$currentpoll['id'].'">Cast Vote</a>';
            }
            else {
                // Aleady voted
                $tableFooter = '<a class="btn btn-default" href="manage_polls">Polls Menu</a>';
            }

            if (isset($_REQUEST['naughty'])) {
                echo '
                <h4 class="alert alert-danger">
                    You can only vote once!
                </h4>';
            }

            echo '
            <table class="table" style="text-align:left;">
                <thead>
                    <tr>
                        <th>
                            <big>'.$currentpoll['question'].'</big>
                            <small style="float:right;">'.$currentpoll['numofvotes'].' votes</small>
                        </th>
                    </tr>
                </thead>
                <tbody>';
            for ($i = 1; $i <= $currentpoll['numofoptions']; $i++) {
                $percentage = ($currentpoll['numofvotes'] == 0)? 0:
                    round((100 * $currentpoll['vote'.$i] / $currentpoll['numofvotes']), 0);

                echo '
                    <tr>
                        <td>
                            <div class="clearfix">'.
                                $currentpoll['option'.$i].'
                                <small style="float:right;">'.(($currentpoll['vote'.$i] == 0) ? '0' : $currentpoll['vote'.$i]).' votes ('.$percentage.'%)</small>
                            </div>
                            <div class="progress" style="margin-bottom:0;">
                                <div class="progress-bar" style="width:'.$percentage.'%;"></div>
                            </div>
                        </td>
                    </tr>';
            }
            echo '
                </tbody>
                <tfoot>
                    <tr>
                        <td>'.
                            $tableFooter.'
                        </td>
                    </tr>
                </tfoot>
            </table>';
        }

        // Voting form
        else {
            addHeader();
            // Check to see if the user has already voted on this poll
            $user_ip = encode_ip($_SERVER['REMOTE_ADDR']); // encode users ip for database check
            $existing_voters = mysqli_query($db, "SELECT * FROM poll_voters WHERE poll=$poll AND ip='$user_ip' LIMIT 1");
            $tableFooter = '';
            if (mysqli_num_rows($existing_voters) == 0){
                // Not voted yet
                $tableFooter = '<input class="btn btn-primary" type="submit" name="vote" title="You only get one" value="Cast Vote" />';
            }
            else {
                // Aleady voted
                $tableFooter = '
                    <input disabled class="btn btn-primary disabled" type="submit" name="vote" title="You\'ve already voted" value="Cast Vote" />
                    <a class="btn btn-default" href="polls/results/'.$currentpoll['id'].'">View Results</a>';
            }

            echo '
            <h2>
                Vote
                <small><small><a href="polls" title="Back to all Polls">Poll menu</a></small></small>
            </h2>
            <form action="manage_polls.php" method="GET">
                <table class="table table-hover" style="text-align:left;">
                    <thead>
                        <tr>
                            <th>
                                <big>'.$currentpoll['question'].'</big>
                                <input type="hidden" name="poll" value="'.$currentpoll['id'].'"/>
                            </th>
                        </tr>
                    </thead>
                    <tbody>';
                for ($i = 1; $i <= $currentpoll['numofoptions']; $i++) {
                    echo '
                        <tr>
                            <td>
                                <input type="radio" name="option" value="'.$i.'" id="option'.$i.'"/>
                                <label style="font-weight:normal;" for="option'.$i.'">'.$currentpoll['option'.$i].'</label>
                            </td>
                        </tr>';
                }
                echo '
                    </tbody>
                    <tfoot>
                        <tr>
                            <td>'.
                                $tableFooter.'
                            </td>
                        </tr>
                    </tfoot>
                </table>
            </form>';
        }

    // List all polls
    } else {
        addHeader();
        $polls = mysqli_query($db, "SELECT * FROM polls ORDER BY id DESC");

        echo '
        <h2>
            Polls
        </h2>';

        if ($loggedIn) {
            echo '
            <h4>
                <a href="manage_polls.php?action=add" title="Committee Only">
                    + Create New Poll
                </a>
            </h4>';
        }

        echo '
        <style>
            th {
                text-align: center;
            }
            td:nth-child(1) {
                width: 10%;
            }
            td:nth-child(2) {
                width: 10%;
            }
            td:nth-child(3){
                width: 12%;
            }
            td:nth-child(4){
                width: 10%;
            }
            td:nth-child(5){
                text-align:left;
                width: 58%;
            }
            /*Mobile*/
            @media (max-width: 768px) {
                table  { display: block; padding: 0;}
                table  td, table  th { display: inline-block; }
                td:nth-child(1) {
                    width: 20%;
                }
                td:nth-child(2) {
                    width: 40%;
                }
                td:nth-child(3){
                    width: 20%;
                }
                td:nth-child(4){
                    width: 20%;
                }
                td:nth-child(5){
                    text-align:left;
                    width: 100%;
                }
            }
        </style>
        <table class="table table-striped">
            <tr>
                <th>ID</th>
                <th>Action</th>
                <th>Num Choices</th>
                <th>Num Votes</th>
                <th style="text-align:left;">Question</th>
            </tr>
            <tbody>';

        // List all polls with view results/vote buttons
        while ($poll = mysqli_fetch_assoc($polls)) {
            echo'
                <tr>
                    <td>'.$poll['id'].'</td>
                    <td>
                        <a href="polls/results/'.$poll['id'].'" title="View Results"><i class="fa fa-bar-chart-o"></i> Results</a>
                        <br>
                        <a href="polls/'.$poll['id'].'" title="Vote"><i class="fa fa-crosshairs"></i> Vote</a>
                    </td>
                    <td>'.$poll['numofoptions'].'</td>
                    <td>'.$poll['numofvotes'].'</td>
                    <td>'.
                        smilify(($poll['question']), 0).'
                    </td>
                </tr>';
        }
        echo '
            </tbody>
        </table>';
    }
}
addFooter();
?>