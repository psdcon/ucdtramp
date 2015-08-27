<?php
require_once ('db.php');

if(isset($_GET['showremaining'])){
	$unused_photos = mysqli_query($db, "SELECT * FROM  `photo_punishment` WHERE  `used` <2");
	$selected_photo=mt_rand(1,mysqli_num_rows($unused_photos)-1);
	
	$punish_photo=mysqli_query($db, "SELECT * FROM `photo_punishment` WHERE `used` <2 ");
	echo "Here are the punishment photos still available on the site<br><br>";
	while($punish=mysqli_fetch_array($punish_photo)){
		echo "<img style='display:inline;float:left;height:200px;margin:5px;' src='http://www.ucdtramp.com/images/punishment_photos/".$punish['img']."'>";
	}
}
else if(isset($_GET['addnew'])){
	$i=1;
	if ($handle = opendir('../images/punishment_photos')) {
		/* This is the correct way to loop over the directory. */
		while (false !== ($entry = readdir($handle))) {
			if ($entry != "." && $entry != ".." && $entry != "used" && $entry != "_notes") {
				$insert_punishment = mysqli_query($db, "INSERT INTO photo_punishment (img,used) VALUES('".$entry."',0)");
				if(!$insert_punishment){
					exit ( mysqli_error($db) );
				}
				$i++;
			}		
		}
		if($insert_punishment)
				echo $i.' photos successfully added';
		closedir($handle);
	}
}
else{
	echo "To view the images still left, use the url http://www.ucdtramp.com/includes/punish_photos.php?showremaining=foo<br>
		  To add new photos to the db from the images/punishnment_photo folder, use http://www.ucdtramp.com/includes/punish_photos.php?addnew=foo";
}