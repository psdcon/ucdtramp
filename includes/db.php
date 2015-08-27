<?php 
($_SERVER['SERVER_NAME'] == 'localhost')?
	$db = new mysqli("localhost", "root", "", "ucdtc"): // for local environment
	$db = new mysqli("127.0.0.1", "ucdtramp_user", "showmeALL", "ucdtramp_ucdtc"); // server environment

if (mysqli_connect_errno()) {
    echo "Failed to connect to MySQL: " . mysqli_connect_error();
}