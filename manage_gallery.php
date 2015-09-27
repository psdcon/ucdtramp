<?php
include_once('includes/functions.php');

ini_set('display_errors',1); 
error_reporting(E_ALL);

if(!$loggedIn){
    die('YOU MUST BE LOGGED IN');
}

if(!isset($_POST['filename'])){// If post data has not been received, show import form
    addHeader();
    ?>

    <h4>Please enter the details of the event you would like to import:</h4>

    <form class="form-horizontal" role="form" action="" method="POST">
      <div class="form-group">
        <label class="control-label col-sm-2" for="name">Event:</label>
        <div class="col-sm-10">
          <input type="text" class="form-control" name="name" placeholder="Event Name" autofocus>
        </div>
      </div>
      <div class="form-group">
        <label class="control-label col-sm-2" for="folder">Folder:</label>
        <div class="col-sm-10">
          <input type="text" class="form-control" name="filename" placeholder="Folder Name">
        </div>
      </div>
      <div class="form-group">
        <label class="control-label col-sm-2" for="folder">Description:</label>
        <div class="col-sm-10">
          <textarea class="form-control" name="description" placeholder="Description like location, date and a youtube embed"></textarea>
        </div>
      </div>
      <div class="form-group">
        <label class="control-label col-sm-2" for="folder">Year:</label>
        <div class="col-sm-3">
            <select class="form-control" name='year'>  
                <?php
                $categories=mysqli_query($db, "SELECT * FROM photo_years ORDER BY `photo_years`.`id` DESC");

                // Display Section names (with link to section), and descriptions
                while($current_year = mysqli_fetch_array($categories,MYSQL_ASSOC)){
                    echo("<option value='".$current_year['id']."'>".$current_year['description']."</option>");
                } ?> 
            </select>
        </div>
      </div>
      <div class="form-group"> 
        <div class="col-sm-offset-2 col-sm-2">
          <button type="submit" value="Import" class="btn btn-primary form-control">Submit</button>
        </div>
      </div>
    </form>

    <?php
    addFooter();

} elseif ($handle = opendir("photos/".mysqli_real_escape_string($db, $_POST['filename']))) {
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
?>