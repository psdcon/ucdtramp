<?php

if(!isset($_GET['forum'])){
	exit;
}

require_once('../includes/db.php');

$forum = mysqli_query($db, "SELECT post_time FROM forum_posts WHERE forum=".$_GET['forum']." ORDER BY id DESC LIMIT 1");
	while($row = mysqli_fetch_array($forum)){
		echo nicetimeshort($row['post_time']);
	  }

function nicetimeshort($date)
{    
    $periods         = array("s", "m", "h", "d", "w", "mth", "yr", "decade");
    $lengths         = array("60","60","24","7","4.35","12","10");
    
    $now             = time();
    $unix_date       = ($date);
    
       // check validity of date
    if(empty($unix_date)) {    
        return "Bad date";
    }
   
    $difference     = $now - $unix_date;
    $tense         = "ago";
   
    for($j = 0; $difference >= $lengths[$j] && $j < count($lengths)-1; $j++) {
        $difference /= $lengths[$j];
    }
    
    $difference = round($difference);

    if($periods[$j]=='s'){return "-1m";}
	
    return " -$difference$periods[$j]";
}

?>