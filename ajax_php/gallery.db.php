<?php
require_once('../includes/functions.php');

if ($_POST['action'] == 'Comment') {
    $client_ip = $_SERVER['REMOTE_ADDR'];
    $user_ip = encode_ip($client_ip);
    
    $message = mysqli_real_escape_string($db, $_POST['comment']);
    $sender = mysqli_real_escape_string($db, $_POST['sender']);
    $photoid = mysqli_real_escape_string($db, $_POST['photoid']);
    $event = mysqli_real_escape_string($db, $_POST['eventname']);
    $email = NULL;
    $confirmed = 1;
    $auth = ''; //intialize to be blank, not used
    
    $insert_post = mysqli_query($db, "INSERT INTO photo_comments
          (photoid,event,sender,email,post_time,message,ipaddress,confirmed,auth) 
    VALUES('" . $photoid . "','" . $event . "','" . $sender . "','" . $email . "','" . time() . "','" . $message . "','" . $user_ip . "','" . $confirmed . "','" . $auth . "')");
    echo mysqli_error($db);
?>
    
    <div>
        <strong><?= $sender ?></strong> 
        <span style="float:right;">Just now...</span></div>
    <?php
    if ($userpos == 'webmaster') {
        echo "<div>" . $client_ip . "</div>";
    }
?>         
    <div style="padding-top:.4em;"><?= nl2br(smilify($message, NULL)) ?></div>
    <hr>
<?php
    
} else if ($_POST['action'] == 'getComments') {
    $photoid = mysqli_real_escape_string($db, $_POST['photoid']);
    $event = mysqli_real_escape_string($db, $_POST['eventname']);
    
    $photo_comments = mysqli_query($db, "SELECT * FROM photo_comments WHERE photoid='" . $photoid . "' AND event='" . $event . "' ");
    if (mysqli_num_rows($photo_comments) > 0) {
        while ($comment = mysqli_fetch_array($photo_comments, MYSQL_ASSOC)) {
            $datetime = date('G:i j/M/y', $comment['post_time']);
            $nicetime = nicetime($comment['post_time']);
?>
        
        <div>
            <strong><?= html_entity_decode($comment['sender']); ?></strong> 
            <span style="float:right;"><?= $nicetime; ?></span></div>
        <?php
            if ($userpos == 'webmaster') {
                echo "<div>" . decode_ip($comment['ipaddress']) . "</div>";
            }
?>         
        <div style="padding-top:.4em;"><?= nl2br(smilify(html_entity_decode($comment['message']), NULL)); ?></div>
        <hr>                    
<?php
        }
    } else {
        echo "<div> No Comments </div> <hr>";
    }
}