<?php
include_once('includes/functions.php');

if(!$loggedin)
{header("Location: http://www.ucdtramp.com/page/404"); };

$title='Manage News';

// Fields and order for sorting. I removed the options to sort by title and news below because why would anyone need that?
isset($_GET['field'])?$field=$_GET['field']:$field='id';
isset($_GET['order'])?$order=$_GET['order']:$order='DESC';

// If the action add, create, edit, update or hide is specified as a GET, then do that. If not, display all news items
if(isset($_GET['action'])){
	// Can't have html before these or header('location:') wont work 
	if($_GET['action']=="Create" || $_GET['action']=="Hide" || $_GET['action']=="Update"){	
		if($_GET['action']=="Create"){
			// Means text can have 's or "s. Without it, news comes in blank
			$title = mysqli_real_escape_string($db, $_GET['title']);
			$text = mysqli_real_escape_string($db, $_GET['text']);
			$post_user = $_COOKIE['user'];
			mysqli_query($db, "INSERT INTO news_items (username,title,text,datetime,inuse) VALUES('".$post_user."','".$title."','".$text."',NOW(),'1')");
			header('Location:http://www.ucdtramp.com/manage_news.php?success=added#cont');
			
		}elseif($_REQUEST['action']=="Hide"){
			// Hide news
			if(isset($_REQUEST['newsId'])){
				mysqli_query($db, "UPDATE news_items SET inuse='0' WHERE id='".$_REQUEST['newsId']."'");
				header('Location:http://www.ucdtramp.com/manage_news.php?success=hidden#cont');
			}			
		}
		elseif ($_GET['action']=="Update") {
			if(isset($_GET['newsId'])){
				$title = mysqli_real_escape_string($db, $_GET['title']);
				$text = mysqli_real_escape_string($db, $_GET['text']);
				$post_user = $_COOKIE['user'];			
				
				mysqli_query($db, "UPDATE news_items SET username='".$post_user."', datetime=NOW(), title='".$title."', text='".$text."' WHERE id='".$_GET['newsId']."'");
				header('Location:http://www.ucdtramp.com/manage_news.php?success=edited#cont');
			}		
		}
	}
	
	else{	
		addheader();
		?>
		
		<h1 style="display:inline"><a style="color:black;text-decoration:none" href="http://www.ucdtramp.com/manage_news.php#cont">Manage Site news</a></h1>
		<span style="float:right;margin-top:.7em">News must be formatted using HTML. Instructions can be found <a href="http://www.ucdtramp.com/files/usefuldocs/Committee_Page_Instructions.doc" target="_blank">here</a>.<br> WebM - do iframe's for youtube embed in phpmyadmin</span>
		
		<?php		
		if($_GET['action']=="add"){ ?>
		
			<h3>Add News item</h3>
			<form action="http://www.ucdtramp.com/manage_news.php" method="GET"><table><tr><td>
                <input type="text" name="title" placeholder="Title" tabindex="1" autofocus>
                <button type="reset">Reset</button>
                <button type="submit" name='action' value='Create' tabindex="3">Post News</button>
                </td></tr><tr><td>
			<textarea placeholder="HTML formatted news in here" style="height:25em;" name="text" tabindex="2"></textarea>
                </td></tr>
                </table></form>
			
			<?php
			
		}elseif ($_GET['action']=="Edit") {
			// Get news item to edit
			if(isset($_GET['newsId'])){
				$news_query = mysqli_query($db, "SELECT * FROM news_items WHERE id='".$_GET['newsId']."'");
				$news = mysqli_fetch_array($news_query,MYSQL_ASSOC);
		
			//Table for editing of news items
		?>	
			<h2>Edit news item:</h2>
			<form action="http://www.ucdtramp.com/manage_news.php" method="GET">        
				<table>
					<tr><td>
						   <input type="text" name="title" placeholder="Title" autofocus tabindex="1" value="<?php echo($news['title']);?>">
						   <button class="hide" onClick="return areYouSure();" type='submit' name='action' value='Hide'><i class="fa fa-eye-slash"></i> Hide</button>
						   <button type="submit" name='action' value='Update' tabindex="3">Update News</button>
						   <input type='hidden' name='newsId' value='<?php echo $news['id'] ?>'>
						  </td>
					</tr><tr><td>
							<textarea placeholder="HTML formatted news in here" style="height:25em;" name="text" tabindex="2"><?php echo($news['text']); ?></textarea>
					</td></tr>
				</table>
			</form>
		<?php
			}else{
				echo("<b>Error:</b> No news item selected for editing");
			}
			
		}  
	}
}

else {	
		addheader(); ?>
		
		<h1 style="display:inline"><a style="color:black;text-decoration:none" href="http://www.ucdtramp.com/manage_news.php#cont">Manage Site news</a></h1>
		<span style="float:right;margin-top:.7em">News must be formatted using HTML. Instructions can be found <a href="http://www.ucdtramp.com/files/usefuldocs/Committee_Page_Instructions.doc" target="_blank">here</a></span>
		
	<?php		
		if( isset($_GET['success']) ){ // If an action has been successful, a msg box will be displayed
        	echo("<h3 style='color:green'>News item ".$_GET['success']." successfully!</h3>");
		}			
        echo '<h2><a style="color:black" href="http://www.ucdtramp.com/manage_news.php?action=add#cont">+ Add News</a></h2>';
        ?>        
        
        <table class='admin'><tr style="font-size:1.5em" class='header'>
		  <th style="font-size:1em;"><i class="fa fa-cog"></i></th>
		  <th>Date <a href="http://www.ucdtramp.com/manage_news.php?field=datetime&order=ASC"><i <?php echo ($field=='datetime'&&$order=='ASC')?'style="color:red"':''; ?> class="fa fa-angle-up"></i></a>
          			<a href="http://www.ucdtramp.com/manage_news.php?field=datetime&order=DESC"><i <?php echo ($field=='datetime'&&$order=='DESC')?'style="color:red"':''; ?> class="fa fa-angle-down"></i></a></a></th>
			<th>Title</th>
            <th>News</th></tr>

		<?php

	// List all news with edit/hide buttons
        $news_query = mysqli_query($db, "SELECT *,DATE_FORMAT(datetime,'%a %e/%m/%y %H:%i') as formatted_date FROM news_items ORDER BY ".$field." ".$order." ");
        $row='odd';
		while($news=mysqli_fetch_array($news_query,MYSQL_ASSOC))
		{
			$row=($row=='odd'?'even':'odd');		// Alternate even and odd rows
			$newstext=$news['text'];
			if (strlen($newstext) > 300)
				$newstext = substr($news['text'], 0, 300) . '...';
				
			echo "<tr class='".$row."'> 
					<td style='text-align:center'>";
					if( $news['inuse']==1 ){
						echo "<a href='http://www.ucdtramp.com/manage_news.php?action=Edit&newsId=".$news['id']."#cont' title='Edit'><i class='fa fa-pencil'></i></a>
							  <a href='http://www.ucdtramp.com/manage_news.php?action=Hide&newsId=".$news['id']."' title='Hide' onClick='return areYouSure();'><i class='fa fa-eye-slash'></i></a></td>";}
					else
						echo "<a href='#cont' style='text-decoration:none;cursor:default;' title='Item is hidden'>X</a>";
					echo ("
					<td style='min-width:140px'>".$news['formatted_date']."</td>
					<td>".$news['title']."</td>
					<td>".smilify($newstext,0)."</td></tr>");
		}
		echo "</table>";
}
addfooter();

?>