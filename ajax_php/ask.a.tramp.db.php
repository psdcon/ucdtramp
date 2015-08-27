<?php
require_once ('../includes/db.php');

/* Escape (put in backslashes) entered values to prevent sql nastyness */
if(isset($_REQUEST['action'])){	
	$action=$_REQUEST['action'];
	
	if($action=='Ask'){
		$subject = mysqli_real_escape_string($db, $_POST['subject']);
		$question = mysqli_real_escape_string($db, $_POST['question']);
		
		if(empty($subject) && empty($question)){
			echo '2';
		} else{
			$to = "ask.a.tramp@gmail.com";
			$headers  = 'MIME-Version: 1.0' . "\r\n";
			$headers .= 'Content-type: text/html; charset=iso-8859-1' . "\r\n";
			$headers .= 'From: anon@ucdtramp.com' . "\r\n" .'X-Mailer: PHP/' . phpversion();		
			mail($to, $subject, $question, $headers); 
			
			echo "1";
		}
	}
	if($action=='Answer'){
		$user = mysqli_real_escape_string($db, $_POST['subject']);
		$answer = mysqli_real_escape_string($db, $_POST['question']);
		
		if(empty($answer)){
			echo '2';
		} else{	
			// Try connect
			$query = mysqli_query($db, "INSERT INTO aat_answers (time,user,answer,likes,show) VALUES ('".time()."','".$user."','".$answer."',0,'1')");
			
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
	if($action=='Edit'){
		$user = mysqli_real_escape_string($db, $_POST['subject']);
		$answer = mysqli_real_escape_string($db, $_POST['question']);
		$edited_id = mysqli_real_escape_string($db, $_POST['id']);
		
		if(empty($answer)){
			echo '2';
		} else{	
			// Try connect
			$query = mysqli_query($db, "INSERT INTO aat_answers (time,user,answer,likes,show) VALUES ('".time()."','".$user."','".$answer."',0,'1')");
			mysqli_query($db, "UPDATE aat_answers SET show=0 WHERE id='".$edited_id."'");
			
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
	if($action=='like'){
		$answer_id = mysqli_real_escape_string($db, $_POST['id']);
		
		if(empty($answer_id)){
			echo '2';
		} else{	
			// Try connect
			$query = mysqli_query($db, "UPDATE aat_answers SET like=like+1 WHERE id='".$answer_id."'");
			
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
		if($action=='publish'){
		$answer_id = mysqli_real_escape_string($db, $_POST['ansid']);
		$question_id = mysqli_real_escape_string($db, $_POST['qid']);
		
		if(empty($answer_id)){
			echo '2';
		} else{	
			// Try connect
			$ans_query = mysqli_query($db, "UPDATE aat_answers SET show=0 WHERE id='".$answer_id."'");
			$ques_query = mysqli_query($db, "UPDATE aat_questions SET unanswered=0 WHERE id='".$question_id."'");
			
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
}
	
?>