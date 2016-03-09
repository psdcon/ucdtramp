<?php
// Included in forum.php and forum.ajax.php\

function mysql2AssocArray($mysqlPostRow) {
    global $db, $userPosition, $forumId, $usersForumId;

    $postId = $mysqlPostRow['id'];
    // Times
    $htmlDatetime = date('c', $mysqlPostRow['post_time']);
    $readableTime = date('D, d M Y H:i:s', $mysqlPostRow['post_time']);
    $niceTime = nicetime($mysqlPostRow['post_time']);
    // User and message
    $forumUser = html_entity_decode($mysqlPostRow['sender']);
    $forumUser = smilify($forumUser, $forumUser);
    $forumMessage = URL2link(smilify(nl2br(html_entity_decode($mysqlPostRow['message'])), $forumUser));
    // ip address, delete, edit button
    $headerActions = ($userPosition == 'Webmaster')? 
        decode_ip($mysqlPostRow['ipaddress']).' 
        <a class="forum-post-delete" style="color:black;" title="Delete post" href="forum/delete/'.$postId.'">
            <i class="fa fa-trash-o"></i> <span class="sr-only">Delete</span>
        </a>' : '';
    if ($mysqlPostRow['users_forum_id'] == $usersForumId || $userPosition == 'Webmaster')
        $headerActions .= '
            <a class="forum-post-edit" style="color:black;" title="Edit post" href="forum/edit/'.$postId.'">
                <i class="fa fa-pencil"></i> <span class="sr-only">Edit</span>
            </a>';
    // Likes
    $likeCount = mysqli_query($db, "SELECT count(1) c FROM forum_plusone WHERE message = $postId LIMIT 1");
    $likeCount = mysqli_fetch_array($likeCount)['c'];
    if (mysqli_num_rows(mysqli_query($db, "SELECT 1 FROM forum_plusone WHERE message = $postId AND cookie = '$usersForumId' LIMIT 1"))){
        $likedClass = 'liked';
        $likeTitle = 'Approved';
    }
    else {
        $likedClass = 'not-liked';
        $likeTitle = 'Approve Post';
    }

    return array(
        'id' => $mysqlPostRow['id'],
        'parentPostId' => $mysqlPostRow['parent_id'],
        'htmlDatetime' => $htmlDatetime,
        'readableTime' => $readableTime,
        'niceTime' => $niceTime,
        'forumUser' => $forumUser,
        'forumMessage' => $forumMessage,
        'headerActions' => $headerActions,
        'likeCount' => $likeCount,
        'likedClass' => $likedClass,
        'likeTitle' => $likeTitle
    );
}

function post2HTML($postAssocArray){
    return '
        <div class="post-header" id="'. $postAssocArray['id'] .'"> <!--top bar with name, time and other details. Has bottom border-->
            <strong class="post-header-name">'. $postAssocArray['forumUser'] .'</strong>
            <small class="post-header-time">
                <time datetime="'. $postAssocArray['htmlDatetime'] .'" title="'. $postAssocArray['readableTime'] .'">
                    '. $postAssocArray['niceTime'] .'
                </time>
            </small>

            <span class="post-header-actions"> 
                '. $postAssocArray['headerActions'] .'
            </span>
        </div>
        <div class="post-message clearfix">
            '. $postAssocArray['forumMessage'] .'

            <!-- Like button -->
            <button type="button" class="btn post-like-btn" title="'. $postAssocArray['likeTitle'] .'" data-action="likeButton" data-postid="'. $postAssocArray['id'] .'">
                <img class="post-like-img '. $postAssocArray['likedClass'] .'" src="images/pages/forum/like.svg" alt="Like">
                <span class="post-like-count">
                    '. $postAssocArray['likeCount'] .'
                </span>
            </button>
        </div>';
}

function parentPost2HTML($postId, $postHtml, $repliesHtml){
    return '
    <div class="col-xs-12 forum-post">'.
        $postHtml .'
        <div class="post-replies">'.
            $repliesHtml .'
        </div>
        <div class="post-footer">
            <!-- For reply box -->
            <button type="button" class="btn-link btn-reply" data-click="reply">reply</button>
            <form action="" class="form-horizontal reply-form" data-parentid="'. $postId .'"></form>
        </div>
    </div> <!-- forum-post -->';
}

function posts2send($mysqlPostsResult){
    $postsArray = [];
    while ($mysqlPostRow = mysqli_fetch_array($mysqlPostsResult)) {
        $postAssocArray = mysql2AssocArray($mysqlPostRow);
        if ($postAssocArray['parentPostId'] == 0){
            $postsArray[] = array(
                'id' => $postAssocArray['id'], 
                'parentPostId' => $postAssocArray['parentPostId'],
                'postHTML' => parentPost2HTML($postAssocArray['id'], post2HTML($postAssocArray), "")
            );
        }
        else {
            $postsArray[] = array(
                'id' => $postAssocArray['id'], 
                'parentPostId' => $postAssocArray['parentPostId'],
                'postHTML' => post2HTML($postAssocArray)
            );
        }
    }
    return $postsArray;
}