<?php
require_once ('db.php');

if($_POST['action']=='Add'){
	$client_ip = $_SERVER['REMOTE_ADDR'];
	$name = mysqli_real_escape_string($db, $_REQUEST['name']);
	$level = mysqli_real_escape_string($db, strtolower($_REQUEST['level']));
	$fig = mysqli_real_escape_string($db, $_REQUEST['fig']);
	$tariff = mysqli_real_escape_string($db, $_REQUEST['tariff']);
	$shape_bonus = 0;
	$short_desc = mysqli_real_escape_string($db, $_REQUEST['short_desc']);
	$long_desc = mysqli_real_escape_string($db, $_REQUEST['long_desc']);
	$coaching_points = mysqli_real_escape_string($db, $_REQUEST['coaching_points']);
	$prereq = mysqli_real_escape_string($db, $_REQUEST['prereq']);
	$lateral_prog = mysqli_real_escape_string($db, $_REQUEST['lateral_prog']);
	$linear_prog = mysqli_real_escape_string($db, $_REQUEST['linear_prog']);
	$vid = mysqli_real_escape_string($db, $_REQUEST['vids']);
	
	$add_skill = mysqli_query($db, "INSERT INTO `bouncebook`.`skills` 
		(`name`, `level`, `fig_notation`, `tariff`, `shape_bonus`, `short_desc`, `long_desc`, `coaching_points`, `prereq`, `lateral_prog`, `linear_prog`, `vid`) 
		VALUES ('$name', '$level', '$fig', '$tariff', '$shape_bonus', '$short_desc', '$long_desc', '$coaching_points', '$prereq', '$lateral_prog', '$linear_prog', '$vid');");
	
	if (mysqli_connect_errno()){ /* Couldnt connect to the database*/
		echo "Failed to connect to MySQL: " . mysqli_connect_error($db);
	}
	else if($add_skill){
		echo "1$name was sucessfully added to the database :)";
	}
	else{ //Unsuccessfuly entry
		echo 'Database error: '.mysqli_error($db);
	}
}

elseif($_POST['action']=='Update'){
	$client_ip = $_SERVER['REMOTE_ADDR'];
	$id = mysqli_real_escape_string($db, $_REQUEST['id']);

	$name = mysqli_real_escape_string($db, $_REQUEST['name']);
	$level = mysqli_real_escape_string($db, strtolower($_REQUEST['level']));
	$fig_notation = mysqli_real_escape_string($db, $_REQUEST['fig_notation']);
	$tariff = mysqli_real_escape_string($db, $_REQUEST['tariff']);
	$shape_bonus = mysqli_real_escape_string($db, $_REQUEST['shape_bonus']);
	$short_desc = mysqli_real_escape_string($db, $_REQUEST['short_desc']);
	$long_desc = mysqli_real_escape_string($db, $_REQUEST['long_desc']);
	$coaching_points = mysqli_real_escape_string($db, $_REQUEST['coaching_points']);
	$prereq = mysqli_real_escape_string($db, $_REQUEST['prereq']);
	$lateral_prog = mysqli_real_escape_string($db, $_REQUEST['lateral_prog']);
	$linear_prog = mysqli_real_escape_string($db, $_REQUEST['linear_prog']);
	$vid = mysqli_real_escape_string($db, $_REQUEST['vids']);
	
	// $copy_old_skill = mysqli_query($db, "INSERT INTO old_skills 
	// (edit_time,name,level,short_desc,long_desc,coaching_points,prereq,lateral_prog,linear_prog,tariff,vid) 
	// SELECT '".time()."',name,level,short_desc,long_desc,coaching_points,prereq,lateral_prog,linear_prog,tariff,vid
	// FROM skills
	// WHERE id='".$old_id."'");
	$query = "UPDATE skills SET
		name='$name', level='$level', fig_notation='$fig_notation', tariff='$tariff', shape_bonus='$shape_bonus', short_desc='$short_desc', long_desc='$long_desc', 
		coaching_points='$coaching_points', prereq='$prereq', lateral_prog='$lateral_prog', linear_prog='$linear_prog',  vid='$vid' 
		WHERE id='$id'";
	$add_skill = mysqli_query($db, $query);

	//$delete_old_skill = mysqli_query($db, "DELETE FROM skills WHERE id='".$old_id."'");

	if (mysqli_connect_errno()){ /* Couldnt connect to the database*/
		echo "Failed to connect to MySQL: " . mysqli_connect_error($db);
	}
	else if($add_skill){
		echo "1$name was sucessfully updated :)";
	}
	else{ //Unsuccessfuly entry
		echo 'Database error: '.mysqli_error($db).' ... Query: '.$query;
	}
}

elseif($_POST['action']=='Delete'){	
	$copy_skill = mysqli_query($db, "INSERT INTO old_skills 
	(edit_time,name,level,short_desc,long_desc,coaching_points,prereq,lateral_prog,linear_prog,tariff,vid) 
	SELECT '".time()."',name,level,short_desc,long_desc,coaching_points,prereq,lateral_prog,linear_prog,tariff,vid
	FROM skills
	WHERE id='".$delete_id."'");
	
	$delete_skill = mysqli_query($db, "DELETE FROM skills WHERE id='".$delete_id."'");

	if (mysqli_connect_errno()){ /* Couldnt connect to the database*/
		echo "Failed to connect to MySQL: " . mysqli_connect_error($db);
	}
	else if($copy_skill && $delete_skill){
		echo "1$name was sucessfully updated :)";
	}
	else{ //Unsuccessfuly entry
		echo 'Database error: '.mysqli_error($db);
	}	
}