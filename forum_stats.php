<?php
include_once('includes/functions.php');

$title = 'Forum Stats';
$description = 'See interesting info about the forum';
addHeader();

$total_different_words = 0;
$total_words = 0;
$total_users = 0;
$row = 'even';

(isset($_GET['forum'])) ? $forum = $_GET['forum'] : $forum = 1;
if ($forum == 2 && !$loggedIn) {
    header("Location: 404.html");
}

$posts = mysqli_query($db, "SELECT * FROM forum_posts WHERE forum='" . $forum . "' ");
while ($post = mysqli_fetch_array($posts, MYSQL_ASSOC)) {
    // Calculate most frequent words
    $words = preg_split('/[^a-zA-Z]+/', strtolower($post['message']));
    $number_of_words = sizeof($words);
    for ($i = 0; $i < $number_of_words; $i++) {
        if (isset($word_counts[$words[$i]])) {
            $word_counts[$words[$i]]++;
        } else {
            if ($words[$i] == '' || $words[$i] == 'amp') {
                continue;
            }
            $word_counts[$words[$i]] = 1;
            $total_different_words++;
        }
    }
    $total_words += $number_of_words;
    $post_length[$post['id']] = $number_of_words;
    
    // Calculate Users
    $message_user = $post['sender'];
    if (isset($user_counts[$message_user])) {
        $user_counts[$message_user]++;
    } else {
        $user_counts[$message_user] = 1;
        $total_users++;
    }
    
    // Calculate Dates
    $date = date('j M Y', $post['post_time']);
    if (isset($date_counts[$date])) {
        $date_counts[$date]++;
    } else {
        $date_counts[$date] = 1;
    }
    
}

arsort($word_counts);
arsort($user_counts);
arsort($date_counts);
$total_posts = mysqli_num_rows($posts);
$number_of_days = floor((time() - 1379099547) / 86400); //Time since forum wipe Sept 2013, divided by num of seconds in a day, rounded down
$longest_post = max($post_length);
$longest_post_id = array_search($longest_post, $post_length);
$shortest_post = min($post_length);
$shortest_post_id = array_search($shortest_post, $post_length);

// Heading
echo "
<h2>
    Forum Statistics
</h2>
<style>
    th {
        text-align:center;
    }
</style>
";
if ($forum == 1 && $loggedIn) {
    echo "<a href=\"forum_stats.php?forum=2\">Committee Forum Stats</a>";
}
else if ($forum == 2 && $loggedIn) {
    echo "<a href=\"forum_stats.php\">Public Forum Stats</a>";
}

if (!isset($_GET['specific'])) {
    $specific = 'empty';
} else {
    $specific = $_GET['specific'];
}
switch ($specific) {
    case 'post':
        echo "<div class=\"row\">";

        if (isset($_GET['id'])) {
            $specific_posts = mysqli_query($db, "SELECT * FROM forum_posts WHERE id='" . $_GET['id'] . "' AND forum='" . $forum . "' ORDER BY `post_time` DESC");
            echo "<h3 class=\"col-xs-12\">Showing " . $_GET['title'] . " post</h3>";
        } elseif (isset($_GET['person'])) {
            $specific_posts = mysqli_query($db, "SELECT * FROM forum_posts WHERE sender='" . $_GET['person'] . "' AND forum='" . $forum . "' ORDER BY `post_time` DESC");
            echo "<h3 class=\"col-xs-12\">Showing posts submitted by " . $_GET['person'] . "</h3>";
        } elseif (isset($_GET['day'])) {
            $specific_posts = mysqli_query($db, "SELECT * FROM forum_posts WHERE post_time>='" . $_GET['day'] . "' AND post_time <'" . ($_GET['day'] + 86400) . "' AND forum='" . $forum . "' ORDER BY `post_time` DESC");
            echo "<h3 class=\"col-xs-12\">Showing all posts from " . date('j M Y', $_GET['day']) . "</h3>";
        }
        while ($specific_post = mysqli_fetch_array($specific_posts, MYSQL_ASSOC)) {
            $htmlDatetime = date('c', $specific_post['post_time']);
            $readableTime = date('D, d M Y H:i:s', $specific_post['post_time']);
            $niceTime = nicetime($specific_post['post_time']);
            
            $forumUserEmoji = html_entity_decode($specific_post['sender']);
            $forumUser = smilify($forumUserEmoji, $forumUserEmoji);
            $forumMessage = URL2link(smilify(nl2br(html_entity_decode($specific_post['message'])), $forumUserEmoji));
?>
            <div class="col-xs-12 forum-post">
                <div class="post-header"> <!--top bar with name, time and other details. bottom border-->
                    <strong class="post-header-name"><?= $forumUser ?></strong>
                    <small class="post-header-time">
                        <time datetime="<?= $htmlDatetime ?>" title="<?= $readableTime ?>">
                            <?= $niceTime ?>
                        </time>
                    </small>
                </div>
                <div class="post-message clearfix">
                    <?= $forumMessage ?>
                </div>
            </div>
<?php
        }
        echo "</div>";
        break;
    case 'words':
?>
        <h2>Words</h2>
        <table class="table table-striped">
            <tr>
                <th>Place</th>
                <th>Word</th>
                <th>Frequency</th>
            </tr>
        <?php
                $i = 1;
                foreach ($word_counts as $key => $val) {
                    echo "
                    <tr>
                        <td>" . $i . "</td>
                        <td>" . $key . "</td>
                        <td>" . $val . "</td>
                    </tr>";
                    $i++;
                }
        ?>
        </table>
<?php
        break;
    
    case 'people':
?>
        <h2>People</h2>
        <table class="table table-striped">
            <tr>
                <th>Place</th>
                <th>Person</th>
                <th>Posts</th>
            </tr>
<?php
                $i = 1;
                foreach ($user_counts as $key => $val) {
                    echo "
                    <tr>
                        <td>" . $i . "</td>
                        <td><a href=\"forum_stats.php?forum=" . $forum . "&specific=post&person=" . $key . "\">" . $key . "</a></td>
                        <td>" . $val . "</td>
                    </tr>";
                    $i++;
                }
?>
        </table>
<?php
        break;
    
    case 'days':
?>
        <h2>Days</h2>
        <table class="table table-striped">
            <tr>
                <th>Place</th>
                <th>Date</th>
                <th>Posts</th>
            </tr>
<?php
                $i = 1;
                foreach ($date_counts as $key => $val) {
                    echo "
                    <tr>
                        <td>" . $i . "</td>
                        <td><a href=\"forum_stats.php?forum=" . $forum . "&specific=post&day=" . strtotime($key) . "\">" . $key . "</a></td>
                        <td>" . $val . "</td>
                    </tr>";
                    $i++;
                }
?>
        </table>
<?php
        break;
    
    default:
?>
        <div class="row">
            <div class="col-xs-12">
                <h3>Overall</h3>
                <div class="row">
                    <div class="col-sm-6">
                        <table class="table table-striped">
                            <tr>
                                <th>Total Posts:</th>
                                <td><?= $total_posts; ?></td>
                            </tr>
                            <tr>
                                <th>Total Words:</th>
                                <td><?= $total_words; ?></td>
                            </tr>
                            <tr>
                                <th>Different Words:</th>
                                <td><?= $total_different_words; ?></td>
                            </tr>
                            <tr>
                                <th>People:</th>
                                <td><?= $total_users; ?></td>
                            </tr>
                            <tr>
                                <th>Days:</th>
                                <td><?= $number_of_days; ?></td>
                            </tr>
                        </table>
                    </div>
                    <div class="col-sm-6">
                        <table class="table table-striped">
                            <tr>
                                <th>Average Post:</th>
                                <td><?= floor($total_words / $total_posts); ?> words</td>
                            </tr>
                            <tr>
                                <th>Shortest Post:</th>
                                <td><a href="forum_stats.php?forum=<?= $forum; ?>&specific=post&id=<?= $shortest_post_id ?>&title=shortest"><?= $shortest_post; ?> word<?= ($shortest_post>1)?'s':''; ?></a></td>
                            </tr>
                            <tr>
                                <th>Longest Post:</th>
                                <td><a href="forum_stats.php?forum=<?= $forum; ?>&specific=post&id=<?= $longest_post_id ?>&title=longest"><?= $longest_post; ?> words</a></td>
                            </tr>
                            <tr>
                                <th>Average Posts Per User:</th>
                                <td><?= floor($total_posts / $total_users); ?></td>
                            </tr>
                            <tr>
                                <th>Average Posts Per Day:</th>
                                <td><?= floor($total_posts / $number_of_days); ?></td>
                            </tr>
                        </table>
                    </div>
                </div>
            </div>
        </div>
        <div class="row">
            <div class="col-sm-4">
                <h3>Words <small>(<a href="forum_stats.php?forum=<?= $forum; ?>&specific=words">Full Listing</a>)</small></h3>
                <table class="table table-striped">
                    <tr>
                        <th>Place</th>
                        <th>Word</th>
                        <th>Frequency</th>
                    </tr>
<?php
                    for ($i = 1; $i <= 10; $i++) {
                        list($key, $val) = each($word_counts);
                        echo "
                        <tr>
                            <td>" . $i . "</td>
                            <td>" . $key . "</td>
                            <td>" . $val . "</td>
                        </tr>";
                    }
?>
                </table>
            </div>
            
            <div class="col-sm-4">
                <h3>People <small>(<a href="forum_stats.php?forum=<?= $forum; ?>&specific=people">Full Listing</a>)</small></h3>
                <table class="table table-striped">
                    <tr>
                        <th>Place</th>
                        <th>Person</th>
                        <th>Posts</th>
                    </tr>
<?php
                    for ($i = 1; $i <= 10; $i++) {
                        list($key, $val) = each($user_counts);
                        echo "
                        <tr>
                            <td>" . $i . "</td>
                            <td><a href=\"forum_stats.php?forum=" . $forum . "&specific=post&person=" . $key . "\">" . $key . "</a></td>
                            <td>" . $val . "</td>
                        </tr>";
                    }
?>
                </table>
            </div>
            
            <div class="col-sm-4">
                <h3>Days <small>(<a href="forum_stats.php?forum=<?= $forum; ?>&specific=days">Full Listing</a>)</small></h3>
                <table class="table table-striped">
                    <tr>
                        <th>Place</th>
                        <th>Date</th>
                        <th>Posts</th>
                    </tr>
<?php
                    for ($i = 1; $i <= 10; $i++) {
                        list($key, $val) = each($date_counts);
                        echo "
                        <tr>
                            <td>" . $i . "</td>
                            <td><a href=\"forum_stats.php?forum=" . $forum . "&specific=post&day=" . strtotime($key) . "\">" . $key . "</a></td>
                            <td>" . $val . "</td>
                        </tr>";
                    }
?>
                </table>
            </div>
        </div>
<?php
}
addFooter();
?>
