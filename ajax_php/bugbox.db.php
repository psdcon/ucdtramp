<?php 
require_once ('../includes/functions.php');

if($action=='send'){
	$client_ip = $_SERVER['REMOTE_ADDR'];
	$user_ip = encode_ip($client_ip);
	isset($_COOKIE['Milk'])?$milk=$_COOKIE['Milk']:$milk='';
	$msg = mysqli_real_escape_string($db, $_REQUEST['message']);
	$pgtitle = mysqli_real_escape_string($db, $_REQUEST['pgtitle']);
	echo $msg;
	
	if(empty($msg)){
		echo '2';
	} else{	
		
		$to      = 'psdcon@gmail.com';
		$subject = 'Bug/Typo Message';
		($milk!='')?$milk='Anon':$milk=$milk;
		$message='From: '.$milk.'. They say: '.$msg;
		$headers = 'From: BugBox@ucdtramp.com' . "\r\n" .
			'X-Mailer: PHP/' . phpversion();
		
		mail($to, $subject, $message, $headers);

		// Try connect
		$query = mysqli_query($db, "INSERT INTO webmaster_reports (time,name,message,pgtitle,ip) 
						VALUES ('".time()."','".$milk."','".$msg."','".$pgtitle."','".$user_ip."')");
		
		$insert_posts = mysqli_query($db, "INSERT INTO forum_posts (forum,sender,post_time,message,ipaddress) 
									VALUES('404','".$milk."','".time()."','".$msg."','".$user_ip."')");
		
		if (mysqli_connect_errno()){ /* Couldnt connect to the database*/
			echo "Failed to connect to MySQL: " . mysqli_connect_error($db);
		}	
		else{		
			if($query==1){			
				echo '1'; /* Sucessful database entry. */
			}
			else{ //Unsuccessfuly entry
				echo 'Invalid query: ' . mysqli_error($db);
			}
		}
	}
}