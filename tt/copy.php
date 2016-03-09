<?php
// Copy the names of skills from 1 db to the other

$dbapp=mysqli_connect("127.0.0.1", "root", "", "theapp") or die(mysqli_error($dbapp));
$skills = mysqli_query($dbapp, "SELECT * FROM tariff_skills ORDER BY id ASC");
if (mysqli_connect_errno()){ // Couldnt connect to the database
	echo "Failed to connect to MySQL: " . mysqli_connect_error($dbapp);
}
else if($skills){
		echo "Got skillz";
}
else{ //Unsuccessfuly 
	echo 'Database error: '.mysqli_error($dbapp);
}

$dbbb=mysqli_connect("127.0.0.1", "root", "", "bouncebook") or die(mysqli_error($dbbb));
while($skill = mysqli_fetch_array($skills)){

	$name = mysqli_real_escape_string($dbbb,$skill['skill_name']);
	$fig = mysqli_real_escape_string($dbbb,$skill['fig_notation']);
	$level = mysqli_real_escape_string($dbbb,$skill['level']);
	$tariff = mysqli_real_escape_string($dbbb,$skill['tariff']);
	$shape = mysqli_real_escape_string($dbbb,$skill['shape_bonus']);
	$description = mysqli_real_escape_string($dbbb,$skill['description']);

	$add_skill = mysqli_query($dbbb, "INSERT INTO skills 
		  (name,fig_notation,level,short_desc,long_desc,coaching_points,prereq,lateral_prog,linear_prog,shape_bonus,tariff,vid) 
	VALUES('$name','$fig','$level','$description','','','','','','$shape','$tariff','')");
	
	if($add_skill){
		echo "$name was sucessfully added to the database :)<br>";
	}
	else{
		echo 'Database error: '.mysqli_error($dbbb).'<br>';
	}
	
}
?>