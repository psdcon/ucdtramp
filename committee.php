<?php
include_once('includes/functions.php');
if (!$loggedIn) {
    header("Location: page/404#cont");
}

// calculate how many committee posts are unseen by comparing last forum/2 access against last post time
$user = $_COOKIE["user"];
$query = mysqli_query($db, "SELECT * FROM committee_users WHERE user = '$user' ");
while ($row = mysqli_fetch_assoc($query)) {
    $lastlogin = $row['thislogin'];
    $lastcommforumview = $row['commforum'];
    $numlogins = $row['numlogins'];
    $previousPosition = $row['previous_position'];
}

$postsSince = mysqli_fetch_array(mysqli_query($db, "SELECT count(1) AS c FROM forum_posts WHERE forum=2 AND post_time>$lastcommforumview ORDER BY id DESC"))['c'];
$postsSince = ($postsSince > 0)? '<span style="font-size:3em;">'.$postsSince."</span>": 0;
$lastlogin = nicetime($lastlogin);
if ($lastlogin == 'Bad date') $lastlogin = 'never';

$title = 'Committee Area';
addHeader();
?>
<div>
    <h2>Committee Area</h3>
    <h4><i class="fa fa-user-secret" aria-hidden="true"></i> <?= $user ?>, Your Stats</h4>
    <ul>
        <li>
            Num logins: <?= $numlogins ?>
        </li>
        <li>
            Last login: <?= $lastlogin ?>
        </li>
        <li><a href="forum/2">Committee Forum</a> posts you haven't seen: <?= $postsSince ?></li>
    </ul>
</div>
<style>
    .committee-actions a {
        display: block;
    }
</style>

    <h4><i class="fa fa-paint-brush" aria-hidden="true"></i> Create</h4>
    <ul>
        <li><a href="manage_news.php">Manage News</a> </li>
        <li><a href="manage_polls.php">Manage Polls</a> </li>
        <li><a href="https://mail.google.com/mail/u/?authuser=ask.a.tramp@ucdtramp.com">Ask.a.Tramp</a></li>
    </ul>

    <h4><i class="fa fa-clipboard" aria-hidden="true"></i> Committee Stuff</h4>
    <ul>
        <li><a href="forum/2"><font color="orange">Committee Forum</font></a></li>
        <li><a href="manage_members.php?action=show&show=committee">Committee details</a> </li>
        <li><a href="/page/numbers">Info - Minutes &amp; more </a></li>
    </ul>

    <div>
        <h4><i class="fa fa-users" aria-hidden="true"></i> <a href="manage_members.php?action=show&show=all">Members Database</a></h4>
        <ul style="display:inline-block">
            <li><strong>Database</strong></li>
            <li><a href="manage_members.php?action=show&show=all">All Members</a></li>
            <li><a href="manage_members.php?action=show&show=committee">Committee</a></li>
            <li><a href="manage_members.php?action=show&show=coach">Coaches</a></li>
            <li><a href="manage_members.php?action=show&show=judge">Judges</a></li>
        </ul>

        <ul style="display:inline-block">
            <li><strong>Email</strong></li>
            <li><a href="manage_members.php?action=Email&recipients=everyone">All Members</a></li>
            <li><a href="manage_members.php?action=Email&recipients=committee">Committee</a></li>
            <li><a href="manage_members.php?action=Email&recipients=coaches">Coaches</a></li>
            <li><a href="manage_members.php?action=Email&recipients=judges">Judges</a></li>
        </ul>
    </div>

    <h4><i class="fa fa-lightbulb-o" aria-hidden="true"></i> Msc</h4>
    <ul>
        <li><a href="forum_stats.php">Forum Stats - Public</a></li>
        <li><a href="forum_stats.php?forum=2">Forum Stats - Committee</a></li>
        <li><a href="page/getingearnewera">Get In Gear & New Era</a></li>
        <!-- <li><a href="page/log" style="color:#6F0;">Ch-ch-ch-ch-Changes</a> </li> -->
    </ul>

    <h4><i class="fa fa-book" aria-hidden="true"></i> Position Diary</h4>
    <ul>
        <li><a href="files/usefuldocs/Committee_Page_Instructions.doc" style="color:#FF8080;">Instructions</a></li>
        <?php
        if ($userPosition == 'Webmaster'){
            echo '<li><a href="page/captain">Captain Page</a></li>';
            echo '<li><a href="page/secretary">Secretary Page</a></li>';
            echo '<li><a href="page/treasurer">Treasurer Page</a></li>';
            echo '<li><a href="page/comps">Comps Page</a></li>';
            echo '<li><a href="page/pro">PRO Page</a></li>';
            echo '<li><a href="page/ents">ENTS Page</a></li>';
            echo '<li><a href="page/headcoach">Head Coach Page</a></li>';
            echo '<li><a href="page/ass headcoach">Assistant Head Coach Page</a></li>';
            echo '<li><a href="page/webmaster">Webmaster Page</a></li>';
        }
        else{
            echo '<li><a href="page/'.strtolower($userPosition).'">'.$userPosition.' Page</a></li>';
            if ($previousPosition)
                echo '<li><a href="page/'.strtolower($previousPosition).'">'.$previousPosition.' Page</a></li>';
        }
        ?>
    </ul>

    <style>
        .notice-textarea{
            display:none;
            margin-bottom: 1em;
        }
    </style>
    <div id="forum-notice-me-please">
        <?php
            $noticeContent = mysqli_fetch_array(mysqli_query($db, "SELECT pagecontent FROM  pages WHERE pageurl='forumnotice' ORDER BY  id DESC LIMIT 1"))['pagecontent'];
            if ($noticeContent == '') $noticeContent = '<strong>Notice is not set. Hit edit</strong>';
        ?>
        <h4><i class="fa fa-thumb-tack" aria-hidden="true"></i> Forum Notice</h4>
        <div>Click the button to edit the forum notice. To turn it off, save the message as blank.</div>

        <div class="notice-preview alert forum-notice" data-pageid="forumnotice"><?= $noticeContent ?></div>
        <textarea class="notice-textarea form-control"></textarea>

        <div class="btn-edit-notice">
            <button class="btn btn-default js-edit-notice-start">Edit</button>
            <div class="btn-group js-edit-notice-running" style="display:none">
                <button class="btn btn-default js-btn-cancel">Cancel</button>
                <button class="btn btn-primary js-btn-save" style="margin-left: -1px;">Publish</button>
            </div>
            <a href="https://ucdtramp.com/forum" class="btn btn-default">Go to Forum</a>
        </div>
    </div>


<?php

// This stuff is broken
//---------------------=----------------------------
if ($userPosition == "Webmaster this should not be used cause it's weird...") {
    //Spying box showing peoples last login time
    echo '
    <li>
        <h4>Spying</h4>';

    $query = mysqli_query($db, "SELECT * FROM committee_users ORDER BY `committee_users`.`thislogin` DESC");
    while ($row = mysqli_fetch_assoc($query)) {
        if ($row['cookie'] != 0) {
            echo "<span style='color:#65a830'>&#9679;</span> " . $row['user'] . " logged in " . nicetime($row['thislogin']) . ".<br>";
        } else {
            ($row['thislogin'] != 0) ? ($msg = nicetime($row['thislogin'])) : $msg = 'never';
            echo "<span style='color:#d92929'>&#9679;</span> " . $row['user'] . " last logged in " . $msg . ".<br>";
        }
    }
    echo '</li>';
}
?>
</ul>

<?php
addFooter();
?>

<script>
var Notice = {
    editor : '',
    preview : '',
    originalHTML: '',
    init : function() {
        // Save original HTML for cancel
        this.editor = $('.notice-textarea');
        this.preview = $('.notice-preview');
        this.originalHTML = this.preview.html();
        // Set up the buttons, etc
        this.bindUIActions();
    },
    bindUIActions: function(){
        $('.js-edit-notice-start').on('click', Notice.startHandler);
        $('.js-btn-cancel').on('click', Notice.cancelHandler);
        $('.js-btn-save').on('click', Notice.savePage);
    },

    startHandler: function(){
        // Start the editor and set it's content
        Notice.editor.show();
        Notice.editor.html(Notice.originalHTML);
        Notice.startLiveUpdate();

        // http://www.jacklmoore.com/autosize/
        autosize.update(Notice.editor);

        // Change to action buttons
        Notice.toggleButtons();

        // Register
        window.onbeforeunload = Notice.confirmOnPageExit;
    },
    cancelHandler: function(){
        // Restore the original HTML
        Notice.preview.html(Notice.originalHTML);
        Notice.resetNotice();
    },
    confirmOnPageExit: function (e) {
        // If we haven't been passed the event get the window.event
        e = e || window.event;

        var message = 'Wait! The forum notice editor is still open...';

        // For IE6-8 and Firefox prior to version 4
        if (e)
        {
            e.returnValue = message;
        }

        // For Chrome, Safari, IE8+ and Opera 12+
        return message;
    },
    resetNotice: function(){
        // Remove editor from page and end sessions
        Notice.editor.hide();
        Notice.editor.html('');
        // Change to edit page button
        Notice.toggleButtons();
        // Remove
        window.onbeforeunload = null;
    },
    toggleButtons: function(){
        $('.js-edit-notice-start').toggle();
        $('.js-edit-notice-running').toggle();
    },
    startLiveUpdate: function(){
        // Updates the page when typing has stopped for 500ms
        var editorTimer = null;
        Notice.editor.on('input', function(){
            if (editorTimer) {
                clearTimeout(editorTimer);   // clear previous pending timer
            }
            editorTimer = setTimeout(function(){
                Notice.preview.html(Notice.editor.val());
                if(Notice.editor.val() === ''){
                    $('.js-btn-save').text('Turn off notice');
                }
                else {
                    $('.js-btn-save').text('Publish');
                }
            }, 500);
        });
    },
    savePage: function(){
        Notice.preview.html(Notice.editor.val());
        $.ajax({
            type: 'POST',
            url : 'page.php',
            data:'action=pageUpdate'+
                 '&new_content='+ encodeURIComponent(Notice.editor.val())+
                 '&pageurl='+Notice.preview.data('pageid'),
            dataType: 'text', // server return type
            success: function(response){
                if (response.trim() === ''){
                    Notice.resetNotice();
                }
                else{
                    // show the error dramatically
                    alert('The notice could not be updated: '+response);
                }
            }
        });
    }
};
$(document).ready(function () {
    Notice.init();
    <?php
    if (isset($_GET['edit_forum_notice'])){
        echo 'Notice.startHandler()';
    }?>
});
</script>
