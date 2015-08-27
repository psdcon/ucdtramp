<?php
include_once('../includes/functions.php');

switch($_REQUEST['action'])
{
	case 'pageUpdate':
		if (mysqli_query($db, 'UPDATE pages SET pagecontent="'.mysqli_real_escape_string($db,$_POST['new_content']).'", 
				lasteditu="'.$_COOKIE['user'].'", lasteditt="'.time().'" WHERE pageurl="'.$_POST['pageurl'].'"'))
			echo '1';
		else
			echo mysqli_error($db);
		break; 
	
	case 'updateTime':
		$lastedittime = mysqli_query($db, "SELECT * FROM pages WHERE pageurl='".$_REQUEST['pageurl']."'");
		while ($row = mysqli_fetch_array($lastedittime)){
			$nicetime = nicetime($row['lasteditt']);
			if ($nicetime == "Bad date")
				echo "Never";
			else
				echo '<p>'.nicetime($row['lasteditt']).' by '.$row['lasteditu'].'</p>';
		}
	
	default:	
	break;
}
?>