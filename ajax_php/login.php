<?php
require_once ('../includes/db.php');

/* escape entered values to prevent sql nastyness */
    $user = strtolower (mysqli_real_escape_string($db, $_POST['user']));
    $pass = mysqli_real_escape_string($db, $_POST['pass']);
	$pass = md5($pass); //encrypt incoming pass

    if(!empty($user) && !empty($pass)){    

        /* we query through our database to search for a user that has been entered */
        $query = mysqli_query($db, "SELECT * FROM committee_users WHERE user = '$user' AND pass ='$pass'");
        
        if(mysqli_num_rows($query) == 1){
            /* if there is a match with the database, we select the user and pass
            from the database corresponding to the entered user */
            while($row = mysqli_fetch_assoc($query)){
                $db_user = $row['user'];
                $db_pass = $row['pass'];
				$numlogins = $row['numlogins']; 
            }
            /* we compare the entered user and pass with the ones we
           just selected from the database */
            if($user == strtolower($db_user) && $pass == $db_pass){
				$thislogin = time();
				$expire = $thislogin + 60*60*24*365; //cookie lasts a year
				setcookie('user', ucfirst($db_user), $expire, '/'); 
				setcookie('pass', $pass, $expire, '/');
				
				$newnum = $numlogins +1;
				
				mysqli_query($db, "UPDATE committee_users 
								   SET cookie='1', thislogin='".$thislogin."', numlogins='".$newnum."'
								   WHERE user='".$user."'"
							 ) ;
				/*If the entered user and pass are correct, return 1 */
                echo '1';
            }
        } else {
            /* If the entered user or pass do not match, return 2 */
            echo '2';
        }
    } else {
        /* If both fields are empty, return 3 */
        echo '3';
    }
?>