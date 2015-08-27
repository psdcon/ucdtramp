<?php

require_once('../includes/db.php');

if(!isset($_GET['id'])){
	exit;
}

if($_GET['action']=='getCount'){
	$result = mysqli_query($db, "SELECT COUNT(message) count FROM forum_plusone WHERE message='".mysqli_real_escape_string($db, $_GET['id'])."'");

	$row = mysqli_fetch_array($result);
	
	echo $row['count'];
}

if($_GET['action']=='updateCount'){
	if(isset($_COOKIE['plusone'])){
		$cookieval= $_COOKIE['plusone'];
	} else {
		$cookieval = md5(genRandomString());
		setcookie("plusone", $cookieval, time() + '31556926',"/"); //seconds in year
	}
	$oldresult = mysqli_fetch_array(mysqli_query($db, "SELECT COUNT(message) count FROM forum_plusone WHERE message='".mysqli_real_escape_string($db, $_GET['id'])."'"));
	$oldcount=$oldresult['count'];
	
	//Add one
	mysqli_query($db, "INSERT INTO forum_plusone (`message`,`cookie`) VALUES ('".mysqli_real_escape_string($db, $_GET['id'])."','$cookieval')");

	$newresult = mysqli_fetch_array(mysqli_query($db, "SELECT COUNT(message) count FROM forum_plusone WHERE message='".mysqli_real_escape_string($db, $_GET['id'])."'"));
	$newcount = $newresult['count'];
	
	if($oldcount==$newcount)
		echo 'same';
	else	
		echo $newcount;
}


function genRandomString() {
    $length = 8;
    $characters = '0123456789abcdefghijklmnopqrstuvwxyz';
    $string = "";    
    for ($p = 0; $p < $length; $p++) {
        $string .= $characters[mt_rand(0, strlen($characters-1))];
    }
    return $string;
}
?>