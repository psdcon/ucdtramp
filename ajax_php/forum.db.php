<?php
require_once ('../includes/functions.php');

isset($_REQUEST['action'])?$action=$_REQUEST['action']:$action='';

function mailPost($parent_id, $sender, $message){
	//Forum Mailing List
	if($_REQUEST['forumid']==0){
		//$to      = 'psdcon@gmail.com';
		$subject = 'Deleted Forum Post';		
	}
	else if($_REQUEST['forumid']==1){
		//$to      = 'psdcon@gmail.com';
		$subject = 'Public Forum Post';
	} 
	else if($_REQUEST['forumid']==2){
		// $to      = 'psdcon@gmail.com';
		$to      = 'psdcon@gmail.com, colmgalligan@gmail.com, roseanne.b.loco@gmail.com, orlacole@hotmail.com, mheslin8@gmail.com, mquirkebolt@yahoo.ie, emilyrose.farrell94@gmail.com, keith.fay@ucdconnect.ie, nicoletianihad@gmail.com, glasgowtc@gmail.com';
		$subject = 'Committee Forum Post #'.$parent_id;		
	}
	$mailmessage = '
		<html>
			<head><title>Forum Post</title></head>
			<body>
				<p>
					<strong>'.smilify(html_entity_decode($sender),html_entity_decode($sender)).'</strong>: 
					'.smilify(URL_to_link(nl2br(html_entity_decode($message))), (html_entity_decode($sender))).'
				</p>
				<br>
				<a href="http://www.ucdtramp.com/forum/2">Click here to go to the committee forum</a>
			</body>
		</html>';
	$headers  = 'MIME-Version: 1.0' . "\r\n";
	$headers .= 'Content-type: text/html; charset=iso-8859-1' . "\r\n";
	$headers .= 'From: Committee Forum <committee.forum@ucdtramp.com>' . "\r\n" .'X-Mailer: PHP/' . phpversion();
	
	mail($to, $subject, $mailmessage, $headers);
	//End of mailing
}

if($action=='Post'){
	$client_ip = $_SERVER['REMOTE_ADDR'];
	$message = mysqli_real_escape_string($db, $_REQUEST['bacon']);
	$sender = mysqli_real_escape_string($db, $_REQUEST['eggs']);	
	
	//Spam Checks
	$spam=0;
	if($_REQUEST['sausage']!='')
		$spam=1;
	else if($client_ip == '09809' || $client_ip == '66.36.229.205' || $client_ip == '84.139.95.31' || $client_ip == '220.225.172.229' || $client_ip == '217.159.200.187'|| $client_ip == '66.199.247.42'|| $client_ip == '216.195.49.179' || $client_ip == '66.79.163.226' || $client_ip == '206.83.210.59'|| $client_ip == '24.46.72.158'|| $client_ip == '5.39.219.26')
		$spam=1;
	else if(preg_match('%</a>%i', $_POST['bacon']) || 
			preg_match('%</a>%i', $message) || 
			preg_match('%/url%i', $_POST['bacon']) || 
			preg_match('%/url%i', $message))
		$spam=1;
	
	if($spam==0){
		$user_ip = encode_ip($client_ip);
		$insert_posts = mysqli_query($db, "INSERT INTO forum_posts (forum,sender,post_time,message,ipaddress) 
									VALUES('".$_REQUEST['forumid']."','".htmlentities($sender)."','".time()."','".htmlentities($message)."','".$user_ip."')");	
		
		if (mysqli_connect_errno()){ /* Couldnt connect to the database*/
				echo "Failed to connect to MySQL: " . mysqli_connect_error($db);
			}	
		else if($insert_posts){	// if it worked, return the element to be insterted for ajax call.
				
				setcookie("Milk", $sender, (time()+31556926), '/'); //Saves users name for next time. Lasts for a year		
				//to return the post to the ajax on the other side we need to find the last id and i found its easier just to read the entire post again from the db.
				$id_query = mysqli_query($db, "SELECT * FROM  forum_posts WHERE forum=".$_REQUEST['forumid']." ORDER BY id DESC LIMIT 1");
				echo mysqli_error($db);
				while($new=mysqli_fetch_array($id_query)){
					$nicetime = nicetime(time());
					echo '
	<div class="details">
		<span class="name">'.smilify(html_entity_decode($new['sender']),html_entity_decode($new['sender'])).'</span>
		<span class="time">
			<span class="nicetime">'.$nicetime.'</span>
		</span>
	<span class="ip"><button onClick="$(\'#replybox_'.$new['id'].'\').slideToggle();">Reply</button>';
	if($userpos=='webmaster'){
	echo '<span class="ip">'.$client_ip.'
	<a title="Delete" style="color:black;" href="http://www.ucdtramp.com/forum/'.$new['forum'].'/'.$new['id'].'">
	<i class="fa fa-trash-o"></i></a></span>';} 
	
	echo '</div><div class="msg">'.smilify(URL_to_link(nl2br(html_entity_decode($new['message']))), (html_entity_decode($new['sender'])) ).'<a class="like"  title="Approve Post" onclick="updateCount(\''.$new['id'].'\',this);"><img class="likeimg" src="http://www.ucdtramp.com/images/msc/like.png" alt="Like"><span id="message_count_text_id'.$new['id'].'"> 0</span></a></div></div>';
	
					mailPost($new['id'],$new['sender'],$new['message']);
				}

			}
			
		else{ //Unsuccessfuly entry
			echo 'Database error: '.mysqli_error($db);
		}
				
	} // Spam
	else{
		echo 'Error: Post not submitted<br><br>';
	}
} 

else if($action=='Reply'){
	$client_ip = $_SERVER['REMOTE_ADDR'];			
	$parent_id = mysqli_real_escape_string($db, $_REQUEST['parentid']);
	$sender = mysqli_real_escape_string($db, $_REQUEST['replyname']);
	$message = mysqli_real_escape_string($db, $_REQUEST['replymessage']);
	
	//Spam Checks
	$spam=0;
	if($_REQUEST['sausage']!='')
		$spam=1;
	else if($client_ip == '09809' || $client_ip == '66.36.229.205' || $client_ip == '84.139.95.31' || $client_ip == '220.225.172.229' || $client_ip == '217.159.200.187'|| $client_ip == '66.199.247.42'|| $client_ip == '216.195.49.179' || $client_ip == '66.79.163.226' || $client_ip == '206.83.210.59'|| $client_ip == '24.46.72.158'|| $client_ip == '5.39.219.26')
		$spam=1;
	else if(preg_match('%</a>%i', $message) || preg_match('%</a>%i', $message) || preg_match('%/url%i', $message) || preg_match('%/url%i', $message))
		$spam=1;
	
	if($spam==0){
		$user_ip = encode_ip($client_ip);
		$insert_posts = mysqli_query($db, "INSERT INTO forum_posts (parent_id,forum,sender,post_time,message,ipaddress) 
									VALUES('".$parent_id."','".$_REQUEST['forumid']."','".htmlentities($sender)."','".time()."','".htmlentities($message)."','".$user_ip."')");	
		
		if (mysqli_connect_errno()){ /* Couldnt connect to the database*/
				echo "Failed to connect to MySQL: " . mysqli_connect_error($db);
			}	
		else if($insert_posts==1){	// if it worked, return the element to be insterted for ajax call.
				
				setcookie("Milk", $sender, (time()+31556926), '/'); //Saves users name for next time. Lasts for a year		
				//to return the post to the ajax on the other side we need to find the last id and i found its easier just to read the entire post again from the db.
				$id_query = mysqli_query($db, "SELECT * FROM  forum_posts WHERE forum=".$_REQUEST['forumid']." ORDER BY id DESC LIMIT 1");
				echo mysqli_error($db);
				while($new=mysqli_fetch_array($id_query)){
					$nicetime = nicetime(time());
					echo '
		<span onMouseOver="$(this).next().slideToggle();">'.smilify(html_entity_decode($new['sender']),html_entity_decode($new['sender'])).'</span>
		<span style="display:none;color:#666;font-size:.8em;"> - '.$nicetime.'</span>
	  <span class="msg"> - '.smilify(URL_to_link(nl2br(html_entity_decode($new['message']))), (html_entity_decode($new['sender'])) ).'
	    <a class="like" title="Approve Post" onclick="updateCount(\''.$new['id'].'\',this);">
	    <i class="fa fa-check likeimg" style="color:limegreen;font-size:1.4em"></i>
		<span id="message_count_text_id'.$new['id'].'"> 0</span></a>
	  </span>';
				
				mailPost($new['parent_id'],$new['sender'],$new['message']);

				}
			}
			
		else{ //Unsuccessfuly entry
			echo 'Database error: '.mysqli_error($db);
		}
				
	} // Spam
	else{
		echo 'Error: Post not submitted<br><br>';
	}
}