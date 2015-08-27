<?php 
require_once ('../includes/db.php');
mysqli_query($db, "UPDATE committee_users SET cookie='0' WHERE user='".$_COOKIE['user']."'") ;

$hour = time() - 1800; //cookie set to past time
setcookie('user', '', $hour, '/'); 
setcookie('pass', '', $hour, '/');

header("Location: ../index.php"); 

?> 