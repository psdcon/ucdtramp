<?php
require_once('/includes/db.php');

$move_query = mysqli_query($db, "SELECT * FROM moves ORDER BY id ASC");
echo '["';
while($move = mysqli_fetch_array($move_query)){ 
	echo $move['id'].'. '.$move['name'].'","';
}
echo']';