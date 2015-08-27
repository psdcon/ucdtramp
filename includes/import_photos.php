<?php
include_once('functions.php');

//show errors in this script
ini_set('display_errors',1); 
error_reporting(E_ALL);

if(!$loggedin)
{echo 'YOU MUST BE LOGGED IN';}
else{	
	if(!isset($_POST['filename'])){// If post data has not been received, show import form
	?>
		Please enter the details of the event you would like to import:<br>
		
		<form action="" method="POST">
		<table><tr>
			<td><input style="width:100%" type="text" name="name" placeholder="Event Name" autofocus></td>
			<td><input style="width:100%" type="text" name="filename" placeholder="Folder name" ></td>
		</tr>
		<tr>
			<td colspan="2"><textarea style="width:100%" name='description' placeholder="Description or note?"></textarea></td>
		</tr>
		<tr>
			<td>Year: <select name='year'>  
		<?php
			$categories=mysqli_query($db, "SELECT * FROM photo_years ORDER BY `photo_years`.`id` DESC");
	
			// Display Section names (with link to section), and descriptions
			while($current_year = mysqli_fetch_array($categories,MYSQL_ASSOC)){
				echo("<option value='".$current_year['id']."'>".$current_year['description']."</option>");
			}
	?>  	</select></td>
		</tr>
		<tr>
			<td><input type='Submit' value='Import'></td></tr>  
		</table>
	
	<?php
	} elseif($handle = opendir("../photos/".mysqli_real_escape_string($db, $_POST['filename']))) {
		// Read all the files in the specified directory, create thumbnails if required, and put information into database
		
		// Add event entry to database if it does not already exist
		$existing_event_query=mysqli_query($db, "SELECT * FROM photo_events WHERE filename='".mysqli_real_escape_string($db, $_POST['filename'])."'");
			if(mysqli_num_rows($existing_event_query)==0){ // If the event doesn't already exist add it to the database
				mysqli_query($db, "INSERT INTO photo_events (name, filename, description, category, created) VALUES ('".mysqli_real_escape_string($db, $_POST['name'])."','".mysqli_real_escape_string($db, $_POST['filename'])."','".mysqli_real_escape_string($db, $_POST['description'])."','".mysqli_real_escape_string($db, $_POST['year'])."','".mktime()."')");
				$eventId = mysqli_insert_id($db);
				
		// Read filenames (excluding . and ..), then if the files are images (gif,jpg,png) create thumbnails and add them to database
				$number_of_images=0;
				for($i=0; false !== ($file = readdir($handle)); $i++){
					if ($file != "." && $file != ".." && $file != "thumbnails" && $file != "preview") {
						mysqli_query($db, "INSERT INTO photos (filename,event,thumbnail) VALUES('".$file."','".$eventId."','".$file."')");
							echo mysqli_error($db);
							$number_of_images++;
					}			
				}
				echo("$number_of_images Files successfully imported!");
			}else{
				echo("Event already exists!");
			}	
		closedir($handle);
	}
}
?>