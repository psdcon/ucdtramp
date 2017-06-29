<?php
include_once 'includes/functions.php';
$title = 'Punishment Photo Manager';
$description = "";
addHeader();

$photoSQL = "SELECT * FROM `photo_punishment` WHERE `used` < 2 AND `id` < 24";
echo '<pre>'.$photoSQL.'</pre>';
$photos = mysqli_query($db, $photoSQL);
while ($photo = mysqli_fetch_array($photos, MYSQLI_ASSOC)) {
	echo '<img style="max-width:10%;" src="https://ucdtramp.com/images/punishment_photos/'.$photo['img'].'">';
}

addFooter();
