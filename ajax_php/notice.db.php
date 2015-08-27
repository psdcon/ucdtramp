<?php
require_once ('../includes/functions.php');

if($action=='forumUpdate'){//Set Forum notice
	$notice = mysqli_real_escape_string($db, $_POST['notice']);
	if($notice!='empty'){ //If the notice isnt saved as blank
		//$notice=smilify($notice,NULL);
	}
	mysqli_query($db, "UPDATE pages SET pagecontent='".$notice."' WHERE pageurl='forumnotice'");
	
	if(mysqli_error($db)!=NULL){
		echo mysqli_error($db);
	}
	else {
		echo $_POST['notice'];
	}
}
if($action=='newsUpdate'){//Set news notice
	$notice = mysqli_real_escape_string($db, $_POST['notice']);
	if($notice!='empty'){ //If the notice isnt saved as blank
		//$notice=smilify($notice,NULL);
	}
	mysqli_query($db, "UPDATE pages SET pagecontent='".$notice."' WHERE pageurl='newsnotice'");
	
	if(mysqli_error($db)!=NULL){
		echo mysqli_error($db);
	}
	else {
		echo $_POST['notice'];
	}
}