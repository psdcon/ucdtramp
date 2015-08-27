<?php
include_once('includes/functions.php');

if(isset($_GET['name'])){
	$pageURL = $_GET['name'];
	$page_query = mysqli_query($db, 'SELECT * FROM pages WHERE pageurl="'.$_GET['name'].'" ');
	$page = mysqli_fetch_array($page_query);
	
	if(!$page){header("Location: http://www.ucdtramp.com/page/404");};
	if($page['readperm']>0 && !$loggedin){header("Location: http://www.ucdtramp.com/page/404");}
	
	else if ($loggedin){	
		$lasteditu = $page['lasteditu']; //user to last edit page
		$lasteditt = $page['lasteditt']; //time it was last edited		
		
		$editperm = true;
		if($page['editperm'] > 2 && $userpos != 'webmaster'){
			$editperm = false;
		}
	}
	
	$title = $page['pagetitle']; //Used in header.php to set title	
	addheader();
	
	//echo '<a class="plain" href="http://www.ucdtramp.com/page.php#cont">Year: '.$page['year'].' Type: '.ucfirst($page['type']).'</a>';
	?>
<script> //Javascript for old committee pages
    $(document).ready(function(){
	   $.localScroll({
			duration:1500,
			hash:true
		});    });
</script>
		<!--keep on one line so that when edited, whitespace is not included--> 
	<div id='pagecontent' class="whitebox"><?=$page['pagecontent']; ?></div>
	
	<script>
	// Adds last edit time to the div in the committee tab which is updated every 60seconds in general.js
	function addLastEditTime(){
		$.post(
			'http://www.ucdtramp.com/ajax_php/editpage.php',  
			'action=updateTime&pageurl=<?=stripcslashes($pageURL)?>',
			function(response){ 
				$('#lastedit').html(response)
			}
		);
	}
	</script>
	<?php 
	addfooter(); 
}

else{ //for debugin
	addheader();
	$sql = "SELECT * FROM pages WHERE readperm <2 AND pagecontent LIKE '%\%%' ORDER BY TYPE ASC , YEAR ASC ";
	$sql = "SELECT * FROM pages ORDER BY type ASC";
	$result = mysqli_query($db, $sql);
	echo '<p><strong>This is a test page and you shouldnt really be here</strong>... Currently showing '.mysqli_num_rows($result).' pages</p><br>';
	echo'<table >';
	while($row = mysqli_fetch_array($result))
	  {	
		  echo '<tr><td>'.$row['id'].'.</td>
				<td>'.$row['type'].'</td>
				<td>'.$row['year'].'</td>
				<td>'.$row['pageurl'].'</td>
				<td ><a href="page/'.$row['pageurl'].'#cont" target="_blank">'.$row['pagetitle'].'</a></td></tr>';
	  }
	echo'</table>';
	
	addfooter(); 	
}

?>