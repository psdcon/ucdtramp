<?php
include_once('includes/functions.php');

$total_different_words = 0;
$total_words = 0;
$total_users = 0;
$row = 'even';

(isset($_GET['forum'])) ? $forum = $_GET['forum'] : $forum = 1;
if ($forum == 2 && !$loggedin) {
    header("Location: 404.html");
}
$title = 'Forum Stats';
addheader();

$posts = mysqli_query($db, "SELECT * FROM forum_posts WHERE forum=$forum");
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

echo "<h1>Forum Statistics for <a class='plain' href='http://www.ucdtramp.com/forum_stats.php'>Public</a>";
if ($loggedin) {
    echo "<a class='plain' href='http://www.ucdtramp.com/forum_stats.php?forum=2'>/Committee</a>";
}
echo " Forum</h1>";
if (!isset($_GET['specific'])) {
    $specific = 'empty';
} else {
    $specific = $_GET['specific'];
}
switch ($specific) {
    case 'post':
        if (isset($_GET['id'])) {
            $specific_posts = mysqli_query($db, "SELECT * FROM forum_posts WHERE id='".$_GET['id']."' AND forum=$forum");
        } elseif (isset($_GET['person'])) {
            $specific_posts = mysqli_query($db, "SELECT * FROM forum_posts WHERE sender='".$_GET['person']."' AND forum=$forum");
            echo "<p>Showing posts submitted by <b>".$_GET['person']."</b></p><br>";
        } elseif (isset($_GET['day'])) {
            $specific_posts = mysqli_query($db, "SELECT * FROM forum_posts WHERE post_time>='".$_GET['day']."' AND post_time <'".($_GET['day'] + 86400)."' AND forum=$forum");
            echo "<p>Showing all posts from <b>".date('j M Y', $_GET['day'])."</b></p><br>";
        }
        while ($specific_post = mysqli_fetch_array($specific_posts, MYSQL_ASSOC)) {
            $datetime = date('j M Y, G:i', $specific_post['post_time']);
?>

    
<div class="whitebox">
  <div class="details">
    <table>
      <tr>
        <td class="name" style="width:40%;"><?= htmlentities($specific_post['sender']); ?></td>
        <td style="width:40%;text-align:center;color:#666;"> 
          <span class="datetime">
          <?= $datetime ?>
          </span></td>
      </tr>
    </table>
  </div>
  <div class="msg" ><?php
            echo smilify(URL_to_link(nl2br(htmlentities($specific_post['message']))), htmlentities($specific_post['sender']));
?> 
  </div>
</div>
    
<?php
        }
        echo "</table>";
        break;
    case 'words':
?>
<h2>Words</h2>
<table class="admin">
<tr><th>Place</th><th>Word</th><th>Frequency</th>
<?php
        $i = 1;
        foreach ($word_counts as $key => $val) {
            $row = ($row == 'odd' ? 'even' : 'odd'); // Alternate even and odd rows
            echo ("<tr class=".$row."><td>".$i."</td><td>".$key."</td><td>".$val."</td></tr>\n");
            $i++;
        }
?>
</table>
<?php
        break;
    
    case 'people':
?>
<h2>People</h2>
<table class="admin">
<tr><th>Place</th><th>Person</th><th>Posts</th>
<?php
        $i = 1;
        foreach ($user_counts as $key => $val) {
            $row = ($row == 'odd' ? 'even' : 'odd'); // Alternate even and odd rows
            echo ("<tr class=".$row."><td>".$i."</td><td><a href=\"http://www.ucdtramp.com/forum_stats.php?forum=".$forum."&specific=post&person=".$key."\">".$key."</a></td><td>".$val."</td></tr>\n");
            $i++;
        }
?>
</table>
<?php
        break;
    
    case 'days':
?>
<h2>Days</h2>
<table class="admin">
<tr><th>Place</th><th>Date</th><th>Posts</th>
<?php
        $i = 1;
        foreach ($date_counts as $key => $val) {
            $row = ($row == 'odd' ? 'even' : 'odd'); // Alternate even and odd rows
            echo ("<tr class=".$row."><td>".$i."</td><td>".$key."</td><td>".$val."</td></tr>\n");
            $i++;
        }
?>
</table>
<?php
        break;
    
    default:
?>
<style>
.whitebox{
	width:30%;
	float:left;
	margin:1em;
	
}
</style>
<div class="whitebox" style="width:60%;position:relative;left:18%;">
<h2 style="text-align:center;">Overall</h2>
<table cellpadding="5">
<tr><th>Total Posts:</th><td><?= $total_posts; ?></td><th>Average Post:</th><td><?= floor($total_words / $total_posts); ?> words</td></tr>
<tr><th>Total Words:</th><td><?= $total_words; ?></td><th>Shortest Post:</th><td><a href="http://www.ucdtramp.com/forum_stats.php?forum=<?= $forum; ?>&specific=post&id=<?= $shortest_post_id ?>"><?= $shortest_post; ?> words</a></td></tr>
<tr><th>Different Words:</th><td><?= $total_different_words; ?></td><th>Longest Post:</th><td><a href="http://www.ucdtramp.com/forum_stats.php?forum=<?= $forum; ?>&specific=post&id=<?= $longest_post_id ?>"><?= $longest_post; ?> words</a></td></tr>
<tr><th>People:</th><td><?= $total_users; ?></td><th>Average Posts Per User:</th><td><?= floor($total_posts / $total_users); ?></td></tr>
<tr><th>Days:</th><td><?= $number_of_days; ?></td><th>Average Posts Per Day:</th><td><?= floor($total_posts / $number_of_days); ?></td></tr>
</table>
&nbsp;
</div>

<br style="clear:both;">
<div class="whitebox">
<h2>Words (<a href="http://www.ucdtramp.com/forum_stats.php?forum=<?= $forum; ?>&specific=words">Full Listing</a>)</h2>
<table class="admin">
<tr><th>Place</th><th>Word</th><th>Frequency</th>
<?php
    for ($i = 1; $i <= 10; $i++) {
        $row = ($row == 'odd' ? 'even' : 'odd'); // Alternate even and odd rows
        list($key, $val) = each($word_counts);
        echo ("<tr class=".$row."><td>".$i."</td><td>".$key."</td><td>".$val."</td></tr>\n");
    }
?>
</table>
</div>

<div class="whitebox">
<h2>People (<a href="http://www.ucdtramp.com/forum_stats.php?forum=<?= $forum; ?>&specific=people">Full Listing</a>)</h2>
<table class="admin">
<tr><th>Place</th><th>Person</th><th>Posts</th>
<?php
    for ($i = 1; $i <= 10; $i++) {
        $row = ($row == 'odd' ? 'even' : 'odd'); // Alternate even and odd rows
        list($key, $val) = each($user_counts);
        echo ("<tr class=".$row."><td>".$i."</td><td><a href=\"http://www.ucdtramp.com/forum_stats.php?forum=".$forum."&specific=post&person=".$key."\">".$key."</a></td><td>".$val."</td></tr>\n");
    }
?>
</table>
</div>

<div class="whitebox">
<h2>Days (<a href="http://www.ucdtramp.com/forum_stats.php?forum=<?= $forum; ?>&specific=days">Full Listing</a>)</h2>
<table class="admin">
<tr><th>Place</th><th>Date</th><th>Posts</th>
<?php
    for ($i = 1; $i <= 10; $i++) {
        $row = ($row == 'odd' ? 'even' : 'odd'); // Alternate even and odd rows
        list($key, $val) = each($date_counts);
        echo ("<tr class=".$row."><td>".$i."</td><td><a href=\"http://www.ucdtramp.com/forum_stats.php?forum=".$forum."&specific=post&day=".strtotime($key)."\">".$key."</td><td>".$val."</td></tr>\n");
    }
?>
</table>
</div>
<?php
}
addfooter();
?>
