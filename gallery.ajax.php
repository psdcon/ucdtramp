<?php
require_once('includes/functions.php');

if ($_POST['action'] == 'addComment') {
    $client_ip = $_SERVER['REMOTE_ADDR'];
    $user_ip = encode_ip($client_ip);
    
    $sender = mysqli_real_escape_string($db, $_POST['sender']);
    $message = mysqli_real_escape_string($db, $_POST['message']);
    $photoid = mysqli_real_escape_string($db, $_POST['photoid']);
    $event = mysqli_real_escape_string($db, $_POST['eventname']);
    // intialize to be blank, not used
    $email = NULL;
    $confirmed = 1;
    $auth = '';
    
    $insert_post = mysqli_query($db, "INSERT INTO photo_comments (photoid, event, sender, email, post_time, message, ipaddress, confirmed, auth) VALUES('$photoid', '$event', '$sender', '$email', '".time()."', '$message', '$user_ip', '$confirmed', '$auth')");
    if (!$insert_post){
        die(json_encode(array('error' => mysqli_error($db))));
    }
    else{
        $special = ($userPosition == 'Webmaster')? $client_ip: '';
        die(json_encode(array('newComment' => array(
            'sender' => smilify(html_entity_decode($_POST['sender']), $_POST['sender']),
            'message' => URL2link(smilify(nl2br(html_entity_decode($_POST['message'])), $_POST['sender'])),
            'htmlDatetime' => date('c', time()),
            'readableTime' => date('D, d M Y H:i:s', time()),
            'niceTime' => nicetime(time()),
            'special' => $special
        ))));
    }
    
} else if ($_POST['action'] == 'getComments') {
    $photoid = mysqli_real_escape_string($db, $_POST['photoid']);
    $event = mysqli_real_escape_string($db, $_POST['eventname']);
    
    $photo_comments = mysqli_query($db, "SELECT * FROM photo_comments WHERE photoid='$photoid' AND event='$event' ");
    if (mysqli_num_rows($photo_comments) == 0) {
        die(json_encode(array('empty' => 'no comments')));
    }
    else {
        $comments = array();
        while ($comment = mysqli_fetch_array($photo_comments, MYSQL_ASSOC)) {
            $datetime = date('G:i j/M/y', $comment['post_time']);
            $nicetime = nicetime($comment['post_time']);
            $special = ($userPosition == 'Webmaster')? decode_ip($comment['ipaddress']): '';

            $comments[] = array(
                'sender' => smilify(html_entity_decode($comment['sender']), $comment['sender']),
                'message' => URL2link(smilify(nl2br(html_entity_decode($comment['message'])), $comment['sender'])),
                'htmlDatetime' => date('c', $comment['post_time']),
                'readableTime' => date('D, d M Y H:i:s', $comment['post_time']),
                'niceTime' => nicetime($comment['post_time']),
                'special' => $special
            );
        }
        die(json_encode(array('comments' => $comments)));        
    }
}