<?php
include_once('includes/functions.php');
if(!$loggedin)
{header("Location: http://www.ucdtramp.com/page/404#cont"); };
// calculate how many committee posts are unseen by comparing last forum/2 access against last post time
$user = $_COOKIE["user"];
$query = mysqli_query($db, "SELECT * FROM committee_users WHERE user = '$user' ");
while($row = mysqli_fetch_assoc($query)){
	$lastlogin = $row['thislogin'];
	$lastcommforumview = $row['commforum'];
	$numlogins = $row['numlogins'];
}

$posts_since = 0;
$forum = mysqli_query($db, "SELECT post_time FROM forum_posts WHERE forum=2 ORDER BY id DESC");
	while($row = mysqli_fetch_array($forum)){
		if ($row['post_time']>$lastcommforumview){
			$posts_since += 1;}
		else{break;}
	  }
$title='Committee Section';
echo mysqli_error($db);
addheader();
?>
    <style>
    .whitebox{   
        margin:10px;
        float:left;
        display:block; 
		padding:.5em;       
    }
	@media screen and (max-width:580px) {
		.whitebox {
			width:100%;
		}
	}
    .whitebox a {
        display:block;
		color:black;
		text-decoration:none;
    }
	#forumnoticebox a{
		display:inline;
		color:blue;
		text-decoration:underline;
	}
    .heading{
		text-align:center;
		font-size:1.1em;
		margin-bottom:.5em;
		text-decoration:underline;
    }
    td{
        margin:0px 5px 0px 5px;
    }          
    </style>

    <div class="whitebox" style="width:100%">Welcome to the new Committee Section <b> <?php echo ucfirst($_COOKIE["user"]) ?></b>. 
    You've logged into the new site <?php 
	$lastlogin=nicetime($lastlogin);
	($lastlogin=='Bad date')?$lastlogin='never':$lastlogin=$lastlogin;
	echo "$numlogins times, the most recent being $lastlogin. This login will be remembered for a month at which time you'll automatically be logged out. ";
	
	$lastcommforumview=nicetime($lastcommforumview);
	($lastcommforumview=='Bad date')?$lastcommforumview='never':$lastcommforumview=$lastcommforumview;
	echo "You last looked at the Committee forum $lastcommforumview. There ";
	if($posts_since==1){
		echo "has been <b>1</b> post";
	}
	else{
		echo "have been <b>".$posts_since."</b> posts";
	}
	 ?> since then.
    <br><br>You know what to do :) ...</div>

<ul>
    <li class="whitebox">
        <div class="heading">Create</div>
        <a href="http://www.ucdtramp.com/manage_news.php">Manage News</a> 
        <a href="http://www.ucdtramp.com/manage_polls.php">Manage Polls</a> 
        <a href="https://www.google.com/calendar/b/1/render?tab=mc" target="_blank">Google Calendar</a>
		<a href="https://mail.google.com/mail/u/?authuser=ask.a.tramp@ucdtramp.com">Ask.a.Tramp</a>
    </li>
    
    <li class="whitebox">
        <div class="heading">Committee Stuff</div>
      <a href="http://www.ucdtramp.com/forum/2"><font color="orange">Committee Forum</font></a>
        <a href="http://www.ucdtramp.com/manage_members.php?action=show&show=committee">Committee details</a> 
      <a href="/page/numbers">Info - Minutes & more </a>
      
    </li>
    
    <li class="whitebox">
        <div class="heading"><a href="http://www.ucdtramp.com/manage_members.php?action=show&show=all">Members</a></div>
        
        <table style="text-align:center">
            <tr>
                <td><strong>Database</strong></td><td>&nbsp;</td><td><strong>Email</strong></td>
            </tr>
            <tr>
                <td><a href="http://www.ucdtramp.com/manage_members.php?action=show&show=all">All</a></td><td>&nbsp;</td><td><a href="http://www.ucdtramp.com/manage_members.php?action=Email&recipients=everyone">All</a></td>
            </tr>
            <tr>
                <td><a href="http://www.ucdtramp.com/manage_members.php?action=show&show=committee">Committee</a></td><td>&nbsp;</td><td><a href="http://www.ucdtramp.com/manage_members.php?action=Email&recipients=committee">Committee</a></td>
            </tr>
            <tr>
                <td><a href="http://www.ucdtramp.com/manage_members.php?action=show&show=coach">Coaches</a></td><td>&nbsp;</td><td><a href="http://www.ucdtramp.com/manage_members.php?action=Email&recipients=coaches">Coaches</a></td>
            </tr>
            <tr>
                <td><a href="http://www.ucdtramp.com/manage_members.php?action=show&show=judge">Judges</a></td><td>&nbsp;</td><td><a href="http://www.ucdtramp.com/manage_members.php?action=Email&recipients=judges">Judges</a></td>
            </tr>
        </table>
      <!--<a href="index.php?area=members">Database - All, Committee(details), Coaches, Judges</a> 
      <a style="color:#CCC" href="index.php?area=members&amp;action=Email&amp;recipients=all">Copy Emails - All, Committee, Coaches</a> 
      <a style="color:#CCC" href="index.php?area=members&amp;action=Email&amp;recipients=committee">Email the committee</a> 
      <a style="color:#CCC" href="index.php?area=members&amp;action=Email&amp;recipients=coaches">Email the coaches</a> -->
    </li>
    
  <li class="whitebox"><div class="heading">Msc</div>
    
  	  <a href="http://www.ucdtramp.com/forum_stats.php">Forum Stats - Public</a>
      <a href="http://www.ucdtramp.com/forum_stats.php?forum=2">Forum Stats - Committee</a>
      <a href="page/getingearnewera">Get In Gear & New Era</a>      
      <a href="http://www.ucdtramp.com/page/log" style="color:#6F0;">Ch-ch-ch-ch-Changes</a> 
  <!--<li><a href="index.php?area=ucd_debtors">Black List</a></li>-->
    </li>   

    
    <li class="whitebox">
      <div class="heading">Position Diary</div>
      <a href="/files/usefuldocs/Committee_Page_Instructions.doc" style="text-decoration:none;color:#FF8080;">Instructions</a><br>
      
     <?php
        if ($userpos=="captain" || $userpos=="webmaster")
        	echo"<a href='page/captain'>Captain Page</a>";

		if ($userpos=="secretary" || $userpos=="captain" || $userpos=="webmaster")
			echo"<a href='page/secretary'>Secretary Page</a>";
		
		if ($userpos=="treasurer" || $userpos=="captain" || $userpos=="webmaster")
			echo"<a href='page/treasurer'>Treasurer Page</a>";
		
		if ($userpos=="comps" || $userpos=="captain" || $userpos=="webmaster")
			echo"<a href='page/co'>CO Page</a>";
		
		if ($userpos=="pro" || $userpos=="captain" || $userpos=="webmaster")
			echo"<a href='page/pro'>PRO Page</a>";
		
		if ($userpos=="ents" || $userpos=="captain" || $userpos=="webmaster")
			echo"<a href='page/ents'>ENTS Page</a>";
		
		if ($userpos=="headcoach" || $userpos=="captain" || $userpos=="webmaster")
			echo"<a href='page/headcoach'>Head Coach Page</a>";

		if ($userpos=="aheadcoach" || $userpos=="captain" || $userpos=="webmaster")
			echo"<a href='page/ahead'>Assistant Head Coach Page</a>";
		
		if ($userpos=="webmaster")
			echo"<a href='page/webmaster'>Webmaster Page</a>";

        echo "</li>";
//---------------------Forum Notice edit box----------------------------
		echo '<li class="whitebox" id="forumnoticebox"><div class="heading">Forum Notice</div>
<div>Click below the line to edit the forum notice. To turn it off, save the message as blank.<br><hr></div> 
<span id="forumnoticecontent" style="cursor:pointer;">';
		$notice_query = mysqli_query($db, "SELECT * FROM  pages WHERE pageurl='forumnotice' ORDER BY  id DESC LIMIT 1");
		while($notice=mysqli_fetch_array($notice_query)){
			if($notice['pagecontent']!='empty')
echo $notice['pagecontent'];
			else
				echo 'Not set, Click to add';
		}
    echo '</span><div id="newforumnotice"></div></li>';

//---------------------=----------------------------
        if ($userpos=="webmaster"){
			//Spying box showing peoples last login time
            echo '<li class="whitebox"><div class="heading">Spying</div>';
          
            $query = mysqli_query($db, "SELECT * FROM committee_users");
            while($row = mysqli_fetch_assoc($query)){
                if($row['cookie']!=0){
                    echo "<span style='color:#65a830'>&#9679;</span> ".$row['user']." logged in ".nicetime($row['thislogin']).".<br>";
                }
                else{
					($row['thislogin']!=0)?($msg=nicetime($row['thislogin'])):$msg='never';
                    echo "<span style='color:#d92929'>&#9679;</span> ".$row['user']." last logged in ".$msg.".<br>";
                }
            }
			
			// Shows bug reports
			echo '<li class="whitebox"><div class="heading">Bug Box Messages</div> Who What Where When<br>';
			
			$bug=mysqli_query($db, "SELECT * FROM webmaster_reports ORDER BY id DESC");
			echo mysqli_error($db);
			while($bugs=mysqli_fetch_array($bug)){
				echo '<strong>'.$bugs['name'].'</strong> - '.$bugs['message'].'<br>';
			}
			echo '</li>';						
			
        }
      ?>        
      </li>    
</ul>

<script>

function editForumNotice(){
	var notice=$('#newforumnotice form textarea').val()
	if(notice=='')
		notice='empty';
	
	$.ajax({
		type: "POST",
		url: "http://www.ucdtramp.com/ajax_php/notice.db.php",
		data: "action=forumUpdate&notice="+notice,
		dataType: "html",
		success: function(data){
			if(data=='empty')					
				$('#forumnoticecontent').html('<strong>You have hidden the notice!</strong><br>Add to it by clicking here');
			else
				$('#forumnoticecontent').html(data);
				
			$('#newforumnotice').empty();
		}
	})
	return false;
}
$(document).ready(function(){
	$('#forumnoticecontent').click(function(){
		
		$('#newforumnotice').text('To make a new line, add <br>');
		$('#newforumnotice').append('<form onsubmit="return editForumNotice();">');
		$('#newforumnotice form').append('<textarea autofocus style="height:5em;"></textarea>');
		$('#newforumnotice form').append('<button type="submit">Save</button>');
		$('#newforumnotice form textarea').val( $('#forumnoticecontent').html() );
	
		$('#addlink').hide();
		$('#forumnoticecontent').html('');
	});

});

</script>

<?php
addfooter(); 
?>