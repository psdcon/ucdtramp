<?php
include_once 'includes/functions.php';

if (!isset($_POST['action']))
    die("No POST action");

$ipBlacklist = array('09809','66.36.229.205','84.139.95.31','220.225.172.229','217.159.200.187','66.199.247.42','216.195.49.179','66.79.163.226','206.83.210.59','24.46.72.158','5.39.219.26');

if ($_POST['action'] == 'checkForNewPost'){
    // Every request must send forumId
    $forumId = $_POST['forumId'];
    $newestPostsId = $_POST['newestPostsId'];

    $newPostCheckSQL = "SELECT * FROM forum_posts WHERE id > $newestPostsId AND forum = $forumId";
    if (!$newPostResult = mysqli_query($db, $newPostCheckSQL))
        die(json_encode(array('error' => mysqli_error($db))));
    
    if (mysqli_num_rows($newPostResult) > 0){
        // Send post json obj down
        $postsArray = posts2AssocArray($newPostResult);
        die(json_encode(array('posts' => $postsArray)));
    }
    else {
        die(); // no post
    }
}
else if ($_POST['action'] == 'newPost'){
    $clientIp = $_SERVER['REMOTE_ADDR']=='::1'?'00000000':encode_ip($_SERVER['REMOTE_ADDR']);
    $usersForumId = mysqli_real_escape_string($db, $_COOKIE['usersForumId']);
    $forumId = mysqli_real_escape_string($db, $_POST['forumId']);
    $parentPostId = mysqli_real_escape_string($db, $_POST['parentPostId']);
    $forumUser = mysqli_real_escape_string($db, htmlentities($_POST['forumUser']));
    $forumMessage = mysqli_real_escape_string($db, htmlentities($_POST['forumMessage']));
    $forumMessage = $emojione->toShort($forumMessage);
    $postTime = time();

    // Check for spam
    checkSpam($clientIp, $forumUser, $forumMessage);

    // Add post
    $addPostSQL = "INSERT INTO forum_posts (forum, users_forum_id, sender, parent_id, post_time, message, ipaddress) VALUES ($forumId, '$usersForumId', '$forumUser', $parentPostId, $postTime, '$forumMessage', '$clientIp')";
    if (!mysqli_query($db, $addPostSQL))
        die(json_encode(array('error' => mysqli_error($db))));

    // Saves users name for next time. Lasts for a year
    setcookie("forumUser", $forumUser, (time()+31556926), '/');
    if ($forumId == 1) 
        notificationEveryone();
    
    // Return with the id of the post in the db
    $newPostId = mysqli_insert_id($db);
    $newPostCheckSQL = "SELECT * FROM forum_posts WHERE id = $newPostId LIMIT 1";
    if (!$newPostResult = mysqli_query($db, $newPostCheckSQL))
        die(json_encode(array('error' => mysqli_error($db))));

    // Get post just submitted
    $postsArray = posts2AssocArray($newPostResult);
    // die(var_dump($postsArray[0]));
    // Email any committee posts
    $emailId = ($postsArray[0]['parentPostId'] == 0)? $postsArray[0]['id'] : $postsArray[0]['parentPostId'];
    emailPost($emailId, $postsArray[0]['forumUser'], $postsArray[0]['forumMessage']);

    // Send formatted post to client
    die(json_encode(array('posts' => $postsArray)));
}
else if ($_POST['action'] == 'editPost'){
    $clientIp = $_SERVER['REMOTE_ADDR']=='::1'?'00000000':encode_ip($_SERVER['REMOTE_ADDR']);
    $forumId = mysqli_real_escape_string($db, $_POST['forumId']);
    $postId = mysqli_real_escape_string($db, $_POST['postId']);
    $usersForumId = mysqli_real_escape_string($db, $_COOKIE['usersForumId']);
    $forumUser = mysqli_real_escape_string($db, $_POST['forumUser']);
    $forumMessage = mysqli_real_escape_string($db, htmlentities($_POST['forumMessage']));
    $forumMessage = $emojione->toShort($forumMessage);
    $postTime = time();

    // Check for spam
    checkSpam($clientIp, $forumUser, $forumMessage);

    // Copy previous post to deleted forum
    $backupSQL = "INSERT INTO `forum_posts`(`parent_id`, `forum`, `users_forum_id`, `sender`, `post_time`, `message`, `ipaddress`, `length1`, `length2`)
                  SELECT `parent_id`, 0, `users_forum_id`, `sender`, `post_time`, `message`, `ipaddress`, `length1`, `length2` FROM `forum_posts` WHERE `id` = $postId";
    $updateSQL = "UPDATE `forum_posts` SET `message` = '$forumMessage', `ipaddress` = '$clientIp' WHERE `id` = $postId";
    if (!mysqli_query($db, $backupSQL) || !mysqli_query($db, $updateSQL))
        die(json_encode(array('error' => mysqli_error($db))));
    
    header("Location: forum/".$forumId."#".$postId);
}
/*
    Likes
*/
else if($_POST['action'] == 'updateLikeCount'){
    $postId = mysqli_real_escape_string($db, $_POST['postId']);
    $usersForumId = mysqli_real_escape_string($db, $_COOKIE['usersForumId']);
    mysqli_query($db, "INSERT INTO forum_plusone (`message`,`cookie`) VALUES ($postId,'$usersForumId')");
}

/*
    Notifications
*/
else if ($_POST['action'] == 'sendNotifications'){
    notificationEveryone();
}
else if ($_POST['action'] == 'saveSubscription'){
    $user = mysqli_real_escape_string($db, $_POST['forumUser']);
    $subscriptionId = mysqli_real_escape_string($db, $_POST['subscriptionId']);

    // Do nothing if the id is already in the db
    $existsQuery = "SELECT EXISTS(SELECT 1 FROM `forum_subscriptions` WHERE `subscriptionId` = '$subscriptionId')";
    if (mysqli_fetch_row(mysqli_query($db, $existsQuery))[0])
        die();
    // Add new subscription
    $addSubscriptionSQL = "INSERT INTO `forum_subscriptions` (`subscriptionId`, `user`, `timestamp`) VALUES ('$subscriptionId', '$user', NOW())";
    if (!mysqli_query($db, $addSubscriptionSQL))
        die(json_encode(array('error' => mysqli_error($db))));
}
else if ($_POST['action'] == 'deleteSubscription'){
    $subscriptionId = mysqli_real_escape_string($db, $_POST['subscriptionId']);
    $deleteSubscriptionSQL = "DELETE FROM `forum_subscriptions` WHERE `subscriptionId` = '$subscriptionId'";
    if (!mysqli_query($db, $deleteSubscriptionSQL))
        die(json_encode(array('error' => mysqli_error($db))));
}
else if ($_POST['action'] == 'testSubscription'){
    sendNotifications(array($_POST['subscriptionId']));
}

/*
    Functions
*/
function notificationEveryone(){
    global $db;

    $subscriptionsQuery = "SELECT `subscriptionId` FROM `forum_subscriptions`";
    $subscriptionsResult =  mysqli_query($db, $subscriptionsQuery);
    if (mysqli_num_rows($subscriptionsResult) == 0) // Do nothing if no one's subscribed
        return;
    $subscriptionIds = array();
    while ($row = $subscriptionsResult->fetch_assoc()){
        $subscriptionIds[] = $row['subscriptionId'];
    }
    sendNotifications($subscriptionIds);
}
function sendNotifications($subscriptionIds){
    $data = json_encode(array(
        'registration_ids' => $subscriptionIds,
    ));

    $ch = curl_init();
    $curlConfig = array(
        CURLOPT_URL => "https://android.googleapis.com/gcm/send ",
        CURLOPT_POST => true,
        CURLOPT_POSTFIELDS => $data,
        CURLOPT_RETURNTRANSFER => true,
        CURLOPT_SSL_VERIFYPEER => false,
        CURLOPT_HTTPHEADER => array(
            'Authorization: key=AIzaSyB4__3jfqqrcViXDrpcmSfr5JkX_ObL6bY',
            'Content-Type: application/json'
        )
    );
    curl_setopt_array($ch, $curlConfig);
    $response = json_decode(curl_exec($ch), true);
    if ($response == NULL){
        echo "Couldn't build curl for Push Notifications";
        var_dump(curl_exec($ch));
        die();
    }
    else if ($response['success'])
        return;
    else // some kind of error
        die(json_encode($response['results'][0]));
}
function checkSpam($clientIp, $forumUser, $forumMessage){
    global $ipBlacklist; // Give this function access to array above

    if ($forumUser == '' || $forumMessage == '')
        die(json_encode(array('spam' => 'User and/or message field empty')));
    foreach ($ipBlacklist as $ip) {
        if ($clientIp == $ip)
            die(json_encode(array('spam' => 'Bad ip')));
    }
    if (preg_match('%</a>%i', $forumUser) || preg_match('%</a>%i', $forumMessage) || preg_match('%/url%i', $forumUser) || preg_match('%/url%i', $forumMessage)){
        die(json_encode(array('spam' => 'Spam attempt')));
    }
}
function emailPost($emailId, $forumUser, $forumMessage){
    global $forumId;

    if ($forumId != 2)
        return;

    switch ($forumId) {
        case 0:
            //$to      = 'psdcon@gmail.com';
            $subject = 'UCDTC Deleted Forum Post';  
            break;
        case 1:
            //$to      = 'psdcon@gmail.com';
            $subject = 'UCDTC Public Forum Post';
            break;
        case 2:
            $to      = 'psdcon@gmail.com, colmgalligan@gmail.com, roseanne.b.loco@gmail.com, orlacole@hotmail.com, mheslin8@gmail.com, mquirkebolt@yahoo.ie, emilyrose.farrell94@gmail.com, keith.fay@ucdconnect.ie, nicoletianihad@gmail.com, glasgowtc@gmail.com';
            $subject = 'UCDTC Committee Forum Post #'.$emailId;

        default:
            # code...
            break;
    }

    $mailmessage = '
        <html>
            <head><title>Forum Post</title></head>
            <body>
                <p>
                    <strong>'. $forumUser .'</strong>: 
                    '. $forumMessage .'
                </p>
                <br>
                <a href="forum/2">Click here to go to the committee forum</a>
            </body>
        </html>';
    $headers  = 'MIME-Version: 1.0' . "\r\n";
    $headers .= 'Content-type: text/html; charset=iso-8859-1' . "\r\n";
    $headers .= 'From: Committee Forum <committee.forum@ucdtramp.com>' . "\r\n" .'X-Mailer: PHP/' . phpversion();

    mail($to, $subject, $mailmessage, $headers);
}