<?php
require_once('includes/functions.php');

if (isset($_REQUEST['action'])) {
    $action = $_REQUEST['action'];
    
    if ($action == 'Ask') {
        $subject = mysqli_real_escape_string($db, $_POST['subject']);
        $question = mysqli_real_escape_string($db, $_POST['question']);
        
        if (empty($subject) && empty($question)) {
            echo 'Please fill in all the stuffs';
        } else {
            $to = "ask.a.tramp@gmail.com";
            $headers = 'MIME-Version: 1.0' . "\r\n";
            $headers .= 'Content-type: text/html; charset=iso-8859-1' . "\r\n";
            $headers .= 'From: anon@ucdtramp.com' . "\r\n" . 'X-Mailer: PHP/' . phpversion();
            mail($to, $subject, $question, $headers);
            die();
        }
    }
}
else {
    addHeader();
}
?>

<div class="row">
    <h2 style="text-align:center;">Ask a Tramp</h2>
    <div class="col-md-6" style="text-align:center">
        <img class="ask-a-tramp__image" src="images/pages/askatramp/Ask-A-Tramp.png" onmouseover="this.src='images/pages/askatramp/Cooler-Ask-A-Tramp.png'" onmouseleave="this.src='//ucdtramp.com/images/pages/Ask-A-Tramp.png'" alt="Ask a Tramp">
    </div>
    <div class="col-md-6">
        <p>The wisdom of Ask a Tramp is a free service available to all curious tramps pondering the world. No question is too long, short, young, old or stupid. Submit your queries right here!</p>
        <!--Exams, it may look bad now but when they're over... it's Christmas! <br> 
            <img style="display:inline;" height="300" src="http://i.imgur.com/1oQnoSM.gif">-->
        <!--falling stickmen - http://i992.photobucket.com/albums/af45/somethinglikethebest/stickman.gif-->

        <form onsubmit="return askQuestion();" class="form-horizontal aat-forum" role="form">
          <div class="form-group">
            <label class="control-label col-md-3">To:</label>
            <div class="col-md-9">
              <input type="text" class="form-control aat-forum__to" value="Ask a Tramp" readonly>
            </div>
          </div>
          <div class="form-group">
            <label class="control-label col-md-3" for="subject">Subject:</label>
            <div class="col-md-9"> 
              <input type="text" class="form-control aat-forum__subject" id="subject" placeholder="Subject">
            </div>
          </div>
          <div class="form-group"> 
            <label class="control-label col-md-3" for="subject">Message:</label>
            <div class="col-md-9">
              <textarea class="form-control aat-forum__message" rows="3" placeholder="What's on your mind, honeysuckle?"></textarea>
            </div>
          </div>
          <div class="form-group"> 
            <div class="col-md-offset-3 col-md-9">
              <button type="submit" class="btn btn-danger btn-aat">Submit Question</button>
              <div class="aat-forum__alert"></div>
            </div>
          </div>
        </form>
    </div>
</div>

<script>
    // When the Send Question button on the form is clicked, this function sends the message to the server which sends an email
    // The response reports back to a message box reporting either delievered successfully or error
    function askQuestion() {
        // Don't send empty input to server
        var subjectVal = $('.aat-forum__subject').val();
        var messageVal = $('.aat-forum__message').val();
        var $formAlert = $('.aat-forum__alert');

        if (subjectVal === "" || messageVal === "") {
            $formAlert.css({'color': 'red'})
                      .show()
                      .text('Please fill in all fields!');
        } else {
            $.ajax({
                type: 'POST',
                url: 'askatramp.php',
                // Get subject and question value from inputs and POST them
                data: "action=Ask&subject=" + subjectVal + "&question=" + messageVal,
                success: function (response) {
                    if (response === '') {
                        $('.aat-forum')[0].reset();
                        $formAlert.css({'color': 'green'})
                            .show()
                            .text("Your message was sent successfully! How good am I ;)?!");
                    } else {
                        $formAlert.css({'color': 'red'})
                            .show()
                            .html(response);
                    }
                }
            });
        }
        return false;
    }

    $(document).ready(function () {
        // Set up inital states
        $('.tabs li:first-child').addClass('current');
        $('.tab-content:first-child').addClass('fadeInDownSmall');

        // Add list click events
        $('ul.tabs li').click(function(){
            var tab_id = $(this).attr('data-tab');

            $('ul.tabs li').removeClass('current');
            $('.tab-content').removeClass('fadeInDownSmall');

            $(this).addClass('current');
            $("#"+tab_id).addClass('fadeInDownSmall');
        });
    });
</script>

<?php
    $aatGroupsQuery = "SELECT id, DATE_FORMAT(title,'%D %b \'%y') as title FROM `askatramp_groups` ORDER BY `askatramp_groups`.`id` DESC";
    $aatGroups = mysqli_query($db, $aatGroupsQuery); $tabLinks = ''; $tabContent = '';
    while ($group = mysqli_fetch_array($aatGroups)) {
        $tabLinks .= '<li class="tab-link" data-tab="tab-'.$group['id'].'">'.$group['title'];

        $aatAnswersQuery = "SELECT * FROM `askatramp_answers` WHERE `grouping_id`=".$group['id'];
        $aatAnswers = mysqli_query($db, $aatAnswersQuery); $answerIndex = 0;
        $tabContent .= '<div class="tab-content tab--aat animated" id="tab-'.$group['id'].'">';
        while ($answer = mysqli_fetch_array($aatAnswers)) {
            $answerIndex++;
            $tabContent .= '<p><span class="red">'.$answerIndex.'. </span><strong>'.$answer['subject'].' - '.$answer['question'].'</strong>
                            <br>'.$answer['answer'].'</p>';
        }
        $tabContent .= '</div>';
    }
?>

<div>
    <hr>
    <h3>Ask a Tramp Answers:</h3>
    <ul class="tabs tabs--aat">
        <?= $tabLinks ?>
    </ul>

    <div class="answers">
        <?= $tabContent ?>
    </div>
</div>
<?php
addFooter();
?>