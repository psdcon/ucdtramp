<?php
include_once('includes/functions.php');

if(!$loggedin){header("Location: http://www.ucdtramp.com/page/404"); };
$title="Members and what not";

(date("n") >= 9)? $thisyear=date("y").(date("y")+1) : $thisyear=(date("y")-1).date("y");

// Default to the current year unless otherwise specified
isset($_REQUEST['year'])? $year = $_REQUEST['year']: $year = $thisyear;

// Default to showing all members as oppose to update, edit, add, create, delete. Makes url nicer.
isset($_REQUEST['action'])?$action=$_REQUEST['action']:$action='show';
	
// Update a member
switch($action){
	case 'Insert':
	$membership_number = mysqli_real_escape_string($db, $_REQUEST['membership_number']);
	$firstname = mysqli_real_escape_string($db, $_REQUEST['firstname']);
	$lastname = mysqli_real_escape_string($db, $_REQUEST['lastname']);
	$dob = mktime(0, 0, 0, mysqli_real_escape_string($db, $_REQUEST['dobmonth']), mysqli_real_escape_string($db, $_REQUEST['dobday']), mysqli_real_escape_string($db, $_REQUEST['dobyear']));
	$student_number = mysqli_real_escape_string($db, $_REQUEST['student_number']);
	$term_address = mysqli_real_escape_string($db, $_REQUEST['term_address']);
	$mobile = mysqli_real_escape_string($db, $_REQUEST['mobile_number']);
	$home_address = mysqli_real_escape_string($db, $_REQUEST['home_address']);
	$home_phone = mysqli_real_escape_string($db, $_REQUEST['home_number']);
	$email = mysqli_real_escape_string($db, $_REQUEST['email']);
	isset($_REQUEST['mailinglist'])?$mailinglist=1:$mailinglist=0;
	$experience = mysqli_real_escape_string($db, $_REQUEST['experience']);
	isset($_REQUEST['coach'])?$coach=1:$coach=0;
	isset($_REQUEST['judge'])?$judge=1:$judge=0;
	isset($_REQUEST['comm'])?$comm=1:$comm=0;
	$injuries = mysqli_real_escape_string($db, $_REQUEST['injuries']);
	$faculty = mysqli_real_escape_string($db, $_REQUEST['faculty']);
	$stage = mysqli_real_escape_string($db, $_REQUEST['stage']);
	if(mysqli_query($db, "INSERT INTO members_db (membership_number,firstname,lastname,dob,student_number,term_address,mobile,home_address,home_phone,email,mailinglist,experience,coach,judge,injuries,faculty,stage,comm) VALUES('".$membership_number."','".$firstname."','".$lastname."','".$dob."','".$student_number."','".$term_address."','".$mobile."','".$home_address."','".$home_phone."','".$email."','".$mailinglist."','".$experience."','".$coach."','".$judge."','".$injuries."','".$faculty."','".$stage."','".$comm."')"))
		header("Location:http://www.ucdtramp.com/manage_members.php?action=show&show=all&field=membership_number&order=DESC&success=".$firstname." ".$lastname." was added");
	else 
		echo "Something went wrong: ".mysqli_error($db);
	break;
	
case 'Update':
	$membership_number = mysqli_real_escape_string($db, $_REQUEST['membership_number']);
	$firstname = mysqli_real_escape_string($db, $_REQUEST['firstname']);
	$lastname = mysqli_real_escape_string($db, $_REQUEST['lastname']);
	$dob = mktime(0, 0, 0, mysqli_real_escape_string($db, $_REQUEST['dobmonth']), mysqli_real_escape_string($db, $_REQUEST['dobday']), mysqli_real_escape_string($db, $_REQUEST['dobyear']));
	$student_number = mysqli_real_escape_string($db, $_REQUEST['student_number']);
	$term_address = mysqli_real_escape_string($db, $_REQUEST['term_address']);
	$mobile = mysqli_real_escape_string($db, $_REQUEST['mobile_number']);
	$home_address = mysqli_real_escape_string($db, $_REQUEST['home_address']);
	$home_phone = mysqli_real_escape_string($db, $_REQUEST['home_number']);
	$email = mysqli_real_escape_string($db, $_REQUEST['email']);
	isset($_REQUEST['mailinglist'])?$mailinglist=1:$mailinglist=0;
	$experience = mysqli_real_escape_string($db, $_REQUEST['experience']);
	isset($_REQUEST['coach'])?$coach=1:$coach=0;
	isset($_REQUEST['judge'])?$judge=1:$judge=0;
	isset($_REQUEST['comm'])?$comm=1:$comm=0;
	$injuries = mysqli_real_escape_string($db, $_REQUEST['injuries']);
	$faculty = mysqli_real_escape_string($db, $_REQUEST['faculty']);
	$stage = mysqli_real_escape_string($db, $_REQUEST['stage']);
	if(mysqli_query($db, "UPDATE members_db SET membership_number='".$membership_number."',firstname='".$firstname."',lastname='".$lastname."',dob='".$dob."',student_number='".$student_number."',term_address='".$term_address."',mobile='".$mobile."',home_address='".$home_address."',home_phone='".$home_phone."',email='".$email."',mailinglist='".$mailinglist."',experience='".$experience."',coach='".$coach."',judge='".$judge."',comm='".$comm."',injuries='".$injuries."',faculty='".$faculty."',stage='".$stage."' WHERE `club_year`='".$year."' AND id='".mysqli_real_escape_string($db, $_REQUEST['id'])."'"))
		header("Location:http://www.ucdtramp.com/manage_members.php?action=show&show=all&field=membership_number&order=DESC&success=".$firstname." ".$lastname." was edited");
	else 
		echo "Something went wrong: ".mysqli_error($db);
	break;

case 'Delete':
	if(isset($_REQUEST['id'])){
		// Save name for notice
		$members_query = mysqli_query($db, "SELECT * FROM members_db WHERE `club_year`='".$year."' AND id='".mysqli_real_escape_string($db, $_REQUEST['id'])."' LIMIT 1");
		$current_member = mysqli_fetch_array($members_query); $fullname = $current_member['firstname'].' '.$current_member['lastname'];

		// Set show to 0 so member is not displayed on front end
		if(mysqli_query($db, "UPDATE `members_db` SET `show`='0' WHERE `club_year`='".$year."' AND `id`='".mysqli_real_escape_string($db, $_REQUEST['id'])."'"))
			header("Location:http://www.ucdtramp.com/manage_members.php?success=".$fullname." was removed");
		else
			echo "Something went wrong: ".mysqli_error($db);
	}
	break;

case 'Copy':
	if(isset($_REQUEST['id'])){
		// Save name for notice
		$members_query = mysqli_query($db, "SELECT * FROM members_db WHERE `club_year`='".$year."' AND id='".mysqli_real_escape_string($db, $_REQUEST['id'])."' LIMIT 1");
		$current_member = mysqli_fetch_array($members_query); $fullname = $current_member['firstname'].' '.$current_member['lastname'];
		
		$copy_query = mysqli_query($db,"
			CREATE TEMPORARY TABLE tmptable_1 SELECT * FROM members_db WHERE id = ".mysqli_real_escape_string($db, $_REQUEST['id']).";
			UPDATE tmptable_1 SET `id` = NULL, `membership_number` = 'xx', `club_year` = ".$thisyear.";
			INSERT INTO table SELECT * FROM tmptable_1;
			DROP TEMPORARY TABLE IF EXISTS tmptable_1; ");
echo "
			CREATE TEMPORARY TABLE tmptable_1 SELECT * FROM members_db WHERE id = ".mysqli_real_escape_string($db, $_REQUEST['id']).";
			UPDATE tmptable_1 SET `id` = NULL, `membership_number` = '0', `club_year` = ".$thisyear.";
			INSERT INTO members_db SELECT * FROM tmptable_1;
			DROP TEMPORARY TABLE IF EXISTS tmptable_1; ";
		if ($copy_query)
			header("Location:http://www.ucdtramp.com/manage_members.php?manage_members.php?year=".$year."&success=".$fullname." was copied");
		else
			echo "Something went wrong: ".mysqli_error($db);
	}
	break;

case 'Add':
	addheader();
	$membership_numbers_query = mysqli_query($db, "SELECT membership_number FROM members_db ORDER BY membership_number DESC");
	$membership_number_user = mysqli_fetch_array($membership_numbers_query);
	$membership_number = $membership_number_user['membership_number']+1;
	?>
    <!--Javascript code for data and form validation in general.js-->
    <a href="http://www.ucdtramp.com/manage_members.php?action=show&show=all&field=membership_number&order=DESC"><h1>Manage Membership database</h1></a>
    <div class="whitebox" style="max-width:430px;padding:.2em 1em 1em 1em;">
    <h3>Add Member</h3>
    <style>
        td{padding:0px 0px 5px 0px;}
    </style>
    
    <form action='http://www.ucdtramp.com/manage_members.php' ="return validateFormOnSubmit(this);" name='memberform' method='POST'>
    <table>
        <tr>
            <td>Membership No. <input type='text' maxlength='255' size='3' name='membership_number' value="<?php echo $membership_number; ?>" /></td>
        </tr><tr>
            <td><input type='text' style="width:48%;" maxlength='255' name='firstname' placeholder="First name" autofocus>
                <input type='text' style="width:48%" maxlength='255' name='lastname' placeholder="Last name"></td>
        </tr><tr>
            <td>
            <label>D.O.B
                <select name='dobday' onChange='validDate(document.memberform.dobday,document.memberform.dobmonth,document.memberform.dobyear)'>
                  <?php for($j=1;$j<=31;$j++){
                      echo("<option value='".$j."'>".$j."</option>"); 
                  } ?>
                </select>
        
                <select name='dobmonth' onChange='validDate(document.memberform.dobday,document.memberform.dobmonth,document.memberform.dobyear)'>
                    <option value='01'>January</option>
                    <option value='02'>February</option>
                    <option value='03'>March</option>
                    <option value='04'>April</option>
                    <option value='05'>May</option>
                    <option value='06'>June</option>
                    <option value='07'>July</option>
                    <option value='08'>August</option>
                    <option value='09'>September</option>
                    <option value='10'>October</option>
                    <option value='11'>November</option>
                    <option value='12'>December</option>
                </select>
                
                <select name='dobyear' onChange='validDate(document.memberform.dobday,document.memberform.dobmonth,document.memberform.dobyear)'>
                    <?php for($j=(date("Y")-16);$j>=1970;$j--){
                        echo("<option value='".$j."'>".$j."</option>");
                    } ?>
                </select>
            </label>
            </td>
        </tr><tr>
            <td><input type='text' maxlength='8'  style="width:31%" name='student_number' placeholder='Student number'>
                <input type='text' maxlength='20' style="width:31%" name='mobile_number' placeholder='Mobile number'>
                <input type='text' maxlength='20' style="width:31%" name='home_number' placeholder='Home number'></td>
             </td>
        </tr><tr>
            <td><input type='text' style="width:48%" maxlength='255' name='email' placeholder='Email'>
                Mailing List:  <input type='checkbox' name='mailinglist' checked>
            </td>
        </tr><tr>
            <td><textarea rows='6' name='term_address' placeholder='Term Address'></textarea></td>
        </tr><tr>
            <td><textarea rows='6' name='home_address' placeholder='Home Address'></textarea></td>
        </tr><tr>
            <td><textarea rows='3' name='experience' placeholder='Experience'></textarea></td>
        </tr><tr>
            <td><textarea rows='3' name='injuries' placeholder='Injuries'></textarea></td>
        </tr><tr>
            <td><input type='text' maxlength='255' style="width:100%" name='faculty' placeholder="Faculty"></td>
        </tr><tr>
            <td>Stage: 1st <input type="radio" name="stage" value="1">
                    2nd <input type="radio" name="stage" value="2">
                    3rd <input type="radio" name="stage" value="3">
                    4th <input type="radio" name="stage" value="4">
                    Other <input type="radio" name="stage" value="X">
            </td>
        </tr><tr>
            <td>Coach <input type='checkbox' name='coach'>
                Judge <input type='checkbox' name='judge'>
                Committee <input type='checkbox' name='comm'>
                <button type='submit' name='action' value='Insert'>Add Member</button>
            </td>
        </tr>
    </table>
    </form>
    </div>
    <?php
	break;
	
case 'Edit':
	addheader();
	$members_query = mysqli_query($db, "SELECT * FROM members_db WHERE `club_year`='".$year."' AND id='".mysqli_real_escape_string($db, $_REQUEST['id'])."' LIMIT 1");
	$current_member = mysqli_fetch_array($members_query);
	$dob_day = date('j',$current_member['dob']);
	$dob_month = date('n',$current_member['dob']);
	$dob_stage = date('Y',$current_member['dob']);
	?>
    <!--Javascript code for data and form validation in general.js-->
    <a href="http://www.ucdtramp.com/manage_members.php?action=show&show=all&field=membership_number&order=DESC"><h1>Manage Membership database</h1></a>
    <div class="whitebox" style="max-width:420px;padding: .2em 1em 1em 1em;">
    <h3>Edit Member</h3>
    <style>td{padding:0px 0px 5px 0px;}</style>
    
    <form action='http://www.ucdtramp.com/manage_members.php' ="return validateFormOnSubmit(this);" name='memberform' method='POST'>
    <table>
        <tr>
            <td>Membership No. <input type='text' maxlength='255' size='3' name='membership_number' value="<?=$current_member['membership_number']?>"></td>
        </tr><tr>
            <td><input type='text' style="width:48%;" maxlength='255' name='firstname' placeholder="First name" value="<?=$current_member['firstname']?>" autofocus>
                <input type='text' style="width:48%" maxlength='255' name='lastname' placeholder="Last name" value="<?=$current_member['lastname']?>"></td>
        </tr><tr>
            <td><label>D.O.B
                <select name='dobday' onChange='validDate(document.memberform.dobday,document.memberform.dobmonth,document.memberform.dobyear)'>
                    <?php for($j=1;$j<=31;$j++){
                        echo("<option value='".$j."'".(($j==$dob_day)?' selected':'').">".$j."</option>");
                    } ?>
                </select> 
                <select name='dobmonth' onChange='validDate(document.memberform.dobday,document.memberform.dobmonth,document.memberform.dobyear)'>
                    <option value='01' <?=((1==$dob_month)?' selected':'')?>>January</option>
                    <option value='02' <?=((2==$dob_month)?' selected':'')?>>February</option>
                    <option value='03' <?=((3==$dob_month)?' selected':'')?>>March</option>
                    <option value='04' <?=((4==$dob_month)?' selected':'')?>>April</option>
                    <option value='05' <?=((5==$dob_month)?' selected':'')?>>May</option>
                    <option value='06' <?=((6==$dob_month)?' selected':'')?>>June</option>
                    <option value='07' <?=((7==$dob_month)?' selected':'')?>>July</option>
                    <option value='08' <?=((8==$dob_month)?' selected':'')?>>August</option>
                    <option value='09' <?=((9==$dob_month)?' selected':'')?>>September</option>
                    <option value='10' <?=((10==$dob_month)?' selected':'')?>>October</option>
                    <option value='11' <?=((11==$dob_month)?' selected':'')?>>November</option>
                    <option value='12' <?=((12==$dob_month)?' selected':'')?>>December</option>
                </select>
                <select name='dobyear' onChange='validDate(document.memberform.dobday,document.memberform.dobmonth,document.memberform.dobyear)'>
                    <?php for($j=(date("Y")-16);$j>=1970;$j--){
                        echo("<option value='".$j."'".(($j==$dob_stage)?' selected':'').">".$j."</option>");
                    } ?>
                </select>
            </label></td>
        </tr><tr>
            <td><input type='text' maxlength='8'  style="width:31%" name='student_number' placeholder='Student number' value="<?=$current_member['student_number']?>">
                <input type='text' maxlength='20' style="width:31%" name='mobile_number' placeholder='Mobile number' value="<?=$current_member['mobile']?>">
                <input type='text' maxlength='20' style="width:31%" name='home_number' placeholder='Home number' value="<?=$current_member['home_phone']?>"></td>
             </td>
        </tr><tr>
            <td><input type='text' style="width:48%" maxlength='255' name='email' placeholder='Email' value="<?=$current_member['email']?>">
                Mailing List:  <input type='checkbox' name='mailinglist' <?=($current_member['mailinglist']==1?'checked':'')?>>
            </td>
        </tr><tr>
            <td><textarea rows='6' name='term_address' placeholder='Term Address'><?=$current_member['term_address']?></textarea></td>
        </tr><tr>
            <td><textarea rows='6' name='home_address' placeholder='Home Address'><?=$current_member['home_address']?></textarea></td>
        </tr><tr>
            <td><textarea rows='3' name='experience' placeholder='Experience'><?=$current_member['experience']?></textarea></td>
        </tr><tr>
            <td><textarea rows='3' name='injuries' placeholder='Injuries'><?=$current_member['injuries']?></textarea></td>
        </tr><tr>
            <td><input type='text' maxlength='255' style="width:100%" name='faculty' placeholder="Faculty" value="<?=$current_member['faculty']?>"></td>
        </tr><tr>
            <td>Stage: 1st <input type="radio" name="stage" value="1" <?=($current_member['stage']==1?'checked':'')?>>
                    2nd <input type="radio" name="stage" value="2" <?=($current_member['stage']==2?'checked':'')?>>
                    3rd <input type="radio" name="stage" value="3" <?=($current_member['stage']==3?'checked':'')?>>
                    4th <input type="radio" name="stage" value="4" <?=($current_member['stage']==4?'checked':'')?>>
                    Other <input type="radio" name="stage" value="X" <?=($current_member['stage']=='X'?'checked':'')?>></td>
        </tr><tr>
            <td>Coach <input type='checkbox' name='coach' <?=($current_member['coach']==1?'checked':'')?>>
                Judge <input type='checkbox' name='judge' <?=($current_member['judge']==1?'checked':'')?>>
                Committee <input type='checkbox' name='comm' <?=($current_member['comm']==1?'checked':'')?>>
                <input type='hidden' name='id' value="<?=$current_member['id']?>">
                <input type='submit' name='action' value='Update'></td>
        </tr>
    </table>
    </form>
    </div>
    <?php
	break;
	
case 'show': // Which members to show
	addheader();
	isset($_REQUEST['show'])?$show=$_REQUEST['show']:$show='all';
	
	// Fields and order (for sorting)
	isset($_GET['field'])?$field=$_GET['field']:$field='membership_number';
	isset($_GET['order'])?$order=$_GET['order']:$order='ASC';
	?>
	<a href="http://www.ucdtramp.com/manage_members.php?action=show&show=all&field=membership_number&order=DESC"><h1>Manage Membership database</h1></a>
	
	<?php
		if( isset($_GET['success']) ){ // If an action has been successful, a msg box will be displayed
			echo("<h3 style='color:green'>".$_GET['success']." successfully!</h3>");
		} ?>
		
	<style>
		body{overflow-x:scroll;}
		table.admin th {font-size:1em;}
		div a{
			margin:0 5px 0 5px;
			color:black;
		}
		<?php if ($year != $thisyear) { echo "table.admin tr.odd td {background: #FFD3D3;}table.admin th {background:red;}"; } ?>
	</style>
	
	<div><strong>Year:</strong>
		<a href="http://www.ucdtramp.com/manage_members.php?year=1415&action=show&field=<?=$field?>&order=<?=$order?>">1415</a>
		<a href="http://www.ucdtramp.com/manage_members.php?year=1314&action=show&field=<?=$field?>&order=<?=$order?>">1314</a>
<!--<a href="http://www.ucdtramp.com/manage_members.php?year=1213&action=show&field=<?=$field?>&order=<?=$order?>">1213</a>
-->	</div><br>
    
	<!--// down here lloll -->
    
	<div>    
		<a href="http://www.ucdtramp.com/manage_members.php?year=<?=$year?>&action=Add">+ Add member</a>
		<a href="http://www.ucdtramp.com/manage_members.php?year=<?=$year?>&action=show&show=all">All</a>
		<a href="http://www.ucdtramp.com/manage_members.php?year=<?=$year?>&action=show&show=committee">Committee</a>
		<a href="http://www.ucdtramp.com/manage_members.php?year=<?=$year?>&action=show&show=coach">Coaches</a>
		<a href="http://www.ucdtramp.com/manage_members.php?year=<?=$year?>&action=show&show=judge">Judges</a>
		<a href="http://www.ucdtramp.com/manage_members.php?year=<?=$year?>&action=show&show=under18now">Under 18 (now)</a>
		Stage: <a href="http://www.ucdtramp.com/manage_members.php?year=<?=$year?>&action=show&show=stage1">1</a>
		<a href="http://www.ucdtramp.com/manage_members.php?year=<?=$year?>&action=show&show=stage2">2</a>
		<a href="http://www.ucdtramp.com/manage_members.php?year=<?=$year?>&action=show&show=stage3">3</a>
		<a href="http://www.ucdtramp.com/manage_members.php?year=<?=$year?>&action=show&show=stage4">4</a>
		<a href="http://www.ucdtramp.com/manage_members.php?year=<?=$year?>&action=show&show=nostage">Other</a>
	</div>
	
	<br>
	
	<table class='admin'><tr>
	<th title="Edit/Delete" style="font-size:1em;"><i class="fa fa-cog"></i></th>
	<th>No. <br><a href="http://www.ucdtramp.com/manage_members.php?action=show&show=<?php echo $show; ?>&field=membership_number&order=ASC"><i <?php echo ($field=='membership_number'&&$order=='ASC')?'style="color:red"':''; ?> class="fa fa-angle-up"></i></a><a href="http://www.ucdtramp.com/manage_members.php?action=show&show=<?php echo $show; ?>&field=membership_number&order=DESC"><i <?php echo ($field=='membership_number'&&$order=='DESC')?'style="color:red"':''; ?> class="fa fa-angle-down"></i></a></th>
	<th>Name <br><a href="http://www.ucdtramp.com/manage_members.php?action=show&show=<?php echo $show; ?>&field=firstname&order=ASC"><i <?php echo ($field=='firstname'&&$order=='ASC')?'style="color:red"':''; ?> class="fa fa-angle-up"></i></a><a href="http://www.ucdtramp.com/manage_members.php?action=show&show=<?php echo $show; ?>&field=firstname&order=DESC"><i <?php echo ($field=='firstname'&&$order=='DESC')?'style="color:red"':''; ?> class="fa fa-angle-down"></i></a></th>
	<th>D.O.B. <br><a href="http://www.ucdtramp.com/manage_members.php?action=show&show=<?php echo $show; ?>&field=dob&order=ASC"><i <?php echo ($field=='dob'&&$order=='ASC')?'style="color:red"':''; ?> class="fa fa-angle-up"></i></a><a href="http://www.ucdtramp.com/manage_members.php?action=show&show=<?php echo $show; ?>&field=dob&order=DESC"><i <?php echo ($field=='dob'&&$order=='DESC')?'style="color:red"':''; ?> class="fa fa-angle-down"></i></a></th>
	<th>Student Number <br><a href="http://www.ucdtramp.com/manage_members.php?action=show&show=<?php echo $show; ?>&field=student_number&order=ASC"><i <?php echo ($field=='student_number'&&$order=='ASC')?'style="color:red"':''; ?> class="fa fa-angle-up"></i></a><a href="http://www.ucdtramp.com/manage_members.php?action=show&show=<?php echo $show; ?>&field=student_number&order=DESC"><i <?php echo ($field=='student_number'&&$order=='DESC')?'style="color:red"':''; ?> class="fa fa-angle-down"></i></a></th>
	<th>Term Address <br><a href="http://www.ucdtramp.com/manage_members.php?action=show&show=<?php echo $show; ?>&field=term_address&order=ASC"><i <?php echo ($field=='term_address'&&$order=='ASC')?'style="color:red"':''; ?> class="fa fa-angle-up"></i></a><a href="http://www.ucdtramp.com/manage_members.php?action=show&show=<?php echo $show; ?>&field=term_address&order=DESC"><i <?php echo ($field=='term_address'&&$order=='DESC')?'style="color:red"':''; ?> class="fa fa-angle-down"></i></a></th>
	<th>Mobile <br><a href="http://www.ucdtramp.com/manage_members.php?action=show&show=<?php echo $show; ?>&field=mobile&order=ASC"><i <?php echo ($field=='mobile'&&$order=='ASC')?'style="color:red"':''; ?> class="fa fa-angle-up"></i></a><a href="http://www.ucdtramp.com/manage_members.php?action=show&show=<?php echo $show; ?>&field=mobile&order=DESC"><i <?php echo ($field=='mobile'&&$order=='DESC')?'style="color:red"':''; ?> class="fa fa-angle-down"></i></a></th>
	<th>Home Address <br><a href="http://www.ucdtramp.com/manage_members.php?action=show&show=<?php echo $show; ?>&field=home_address&order=ASC"><i <?php echo ($field=='home_address'&&$order=='ASC')?'style="color:red"':''; ?> class="fa fa-angle-up"></i></a><a href="http://www.ucdtramp.com/manage_members.php?action=show&show=<?php echo $show; ?>&field=home_address&order=DESC"><i <?php echo ($field=='home_address'&&$order=='DESC')?'style="color:red"':''; ?> class="fa fa-angle-down"></i></a></th>
	<th>Home Phone <br><a href="http://www.ucdtramp.com/manage_members.php?action=show&show=<?php echo $show; ?>&field=home_phone&order=ASC"><i <?php echo ($field=='home_phone'&&$order=='ASC')?'style="color:red"':''; ?> class="fa fa-angle-up"></i></a><a href="http://www.ucdtramp.com/manage_members.php?action=show&show=<?php echo $show; ?>&field=home_phone&order=DESC"><i <?php echo ($field=='home_phone'&&$order=='DESC')?'style="color:red"':''; ?> class="fa fa-angle-down"></i></a></th>
	<th>Email <br><a href="http://www.ucdtramp.com/manage_members.php?action=show&show=<?php echo $show; ?>&field=email&order=ASC"><i <?php echo ($field=='email'&&$order=='ASC')?'style="color:red"':''; ?> class="fa fa-angle-up"></i></a><a href="http://www.ucdtramp.com/manage_members.php?action=show&show=<?php echo $show; ?>&field=email&order=DESC"><i <?php echo ($field=='email'&&$order=='DESC')?'style="color:red"':''; ?> class="fa fa-angle-down"></i></a></th>
	<th>Mailing List <br><a href="http://www.ucdtramp.com/manage_members.php?action=show&show=<?php echo $show; ?>&field=mailinglist&order=ASC"><i <?php echo ($field=='mailinglist'&&$order=='ASC')?'style="color:red"':''; ?> class="fa fa-angle-up"></i></a><a href="http://www.ucdtramp.com/manage_members.php?action=show&show=<?php echo $show; ?>&field=mailinglist&order=DESC"><i <?php echo ($field=='mailinglist'&&$order=='DESC')?'style="color:red"':''; ?> class="fa fa-angle-down"></i></a></th>
	<th>Experience <br><a href="http://www.ucdtramp.com/manage_members.php?action=show&show=<?php echo $show; ?>&field=experience&order=ASC"><i <?php echo ($field=='experience'&&$order=='ASC')?'style="color:red"':''; ?> class="fa fa-angle-up"></i></a><a href="http://www.ucdtramp.com/manage_members.php?action=show&show=<?php echo $show; ?>&field=experience&order=DESC"><i <?php echo ($field=='experience'&&$order=='DESC')?'style="color:red"':''; ?> class="fa fa-angle-down"></i></a></th>
	
	<th>Coach <br><a href="http://www.ucdtramp.com/manage_members.php?action=show&show=<?php echo $show; ?>&field=coach&order=ASC"><i <?php echo ($field=='coach'&&$order=='ASC')?'style="color:red"':''; ?> class="fa fa-angle-up"></i></a><a href="http://www.ucdtramp.com/manage_members.php?action=show&show=<?php echo $show; ?>&field=coach&order=DESC"><i <?php echo ($field=='coach'&&$order=='DESC')?'style="color:red"':''; ?> class="fa fa-angle-down"></i></a></th>
	<th>Judge <br><a href="http://www.ucdtramp.com/manage_members.php?action=show&show=<?php echo $show; ?>&field=coach&order=ASC"><i <?php echo ($field=='coach'&&$order=='ASC')?'style="color:red"':''; ?> class="fa fa-angle-up"></i></a><a href="http://www.ucdtramp.com/manage_members.php?action=show&show=<?php echo $show; ?>&field=judge&order=DESC"><i <?php echo ($field=='judge'&&$order=='DESC')?'style="color:red"':''; ?> class="fa fa-angle-down"></i></a></th>
	
	<th>Injuries <br><a href="http://www.ucdtramp.com/manage_members.php?action=show&show=<?php echo $show; ?>&field=injuries&order=ASC"><i <?php echo ($field=='injuries'&&$order=='ASC')?'style="color:red"':''; ?> class="fa fa-angle-up"></i></a><a href="http://www.ucdtramp.com/manage_members.php?action=show&show=<?php echo $show; ?>&field=injuries&order=DESC"><i <?php echo ($field=='injuries'&&$order=='DESC')?'style="color:red"':''; ?> class="fa fa-angle-down"></i></a></th>
	<th>Faculty <br><a href="http://www.ucdtramp.com/manage_members.php?action=show&show=<?php echo $show; ?>&field=faculty&order=ASC"><i <?php echo ($field=='faculty'&&$order=='ASC')?'style="color:red"':''; ?> class="fa fa-angle-up"></i></a><a href="http://www.ucdtramp.com/manage_members.php?action=show&show=<?php echo $show; ?>&field=faculty&order=DESC"><i <?php echo ($field=='faculty'&&$order=='DESC')?'style="color:red"':''; ?> class="fa fa-angle-down"></i></a></th>
	
	<th style="max-width:20px;">Stage <br><a href="http://www.ucdtramp.com/manage_members.php?action=show&show=<?php echo $show; ?>&field=stage&order=ASC"><i <?php echo ($field=='stage'&&$order=='ASC')?'style="color:red"':''; ?> class="fa fa-angle-up"></i></a><a href="http://www.ucdtramp.com/manage_members.php?action=show&show=<?php echo $show; ?>&field=stage&order=DESC"><i <?php echo ($field=='stage'&&$order=='DESC')?'style="color:red"':''; ?> class="fa fa-angle-down"></i></a></th>
	</tr>
	
		<?php
		// List all members with edit/delete buttons. Not sure why the backticks are necessary but php got upset without them...
		echo("<h3>");
		switch($show){
		case 'all':
			$members = mysqli_query($db, "SELECT * FROM `members_db` WHERE `club_year`='".$year."' AND `show`='1' ORDER BY `".$field."` ".$order);
			echo("All Members");
			break;
		case 'committee':
			$members = mysqli_query($db, "SELECT * FROM `members_db` WHERE `club_year`='".$year."' AND `comm`='1' AND `show`='1' ORDER BY `".$field."` ".$order);
			echo("Committee Members");
			echo mysqli_error($db);
			break;
		case 'coach':
			$members = mysqli_query($db, "SELECT * FROM `members_db` WHERE `club_year`='".$year."' AND `coach`='1' AND `show`='1' ORDER BY `".$field."` ".$order);
			echo("Coaches");
			break;
		case 'judge':
			$members = mysqli_query($db, "SELECT * FROM `members_db` WHERE `club_year`='".$year."' AND `judge`='1' AND `show`='1' ORDER BY `".$field."` ".$order);
			echo("Judges");
			break;
		case 'under18now':
			echo("Under 18");
			$members = mysqli_query($db, "SELECT * FROM `members_db` WHERE `club_year`='".$year."' AND `dob`>'".(time())."' AND `show`='1' ORDER BY `".$field."` ".$order);
			break;
		case 'stage1':
			echo("Stage 1");
			$members = mysqli_query($db, "SELECT * FROM `members_db` WHERE `club_year`='".$year."' AND `stage`='1' AND `show`='1' ORDER BY `".$field."` ".$order);
			break;
		case 'stage2':
			echo("Stage 2");
			$members = mysqli_query($db, "SELECT * FROM `members_db` WHERE `club_year`='".$year."' AND `stage`='2' AND `show`='1' ORDER BY `".$field."` ".$order);
			break;
		case 'stage3':
			echo("Stage 3");
			$members = mysqli_query($db, "SELECT * FROM `members_db` WHERE `club_year`='".$year."' AND `stage`='3' AND `show`='1' ORDER BY `".$field."` ".$order);
			break;
		case 'stage4':
			echo("Stage 4");
			$members = mysqli_query($db, "SELECT * FROM `members_db` WHERE `club_year`='".$year."' AND `stage`='4' AND `show`='1' ORDER BY `".$field."` ".$order);
			break;
		case 'nostage':
			echo("Other Stages");
			$members = mysqli_query($db, "SELECT * FROM `members_db` WHERE `club_year`='".$year."' AND `stage`!='1' AND `stage`!='2' AND `stage`!='3' AND `stage`!='4' AND `show`='1' ORDER BY `".$field."` ".$order);
			break;
		default:
			$members = mysqli_query($db, "SELECT * FROM `members_db` WHERE `club_year`='".$year."' AND `show`='1' ORDER BY `".$field."` ".$order);
			echo("All members");
			break;
		}
		$num_members = mysqli_num_rows($members);
		echo(" (".$num_members." members)</h3>");
	
		$row='odd';
		while($current_member=mysqli_fetch_array($members)){
			$current_member['formatted_dob'] = date("j/m/y",$current_member['dob']);
			$row=($row=='odd'?'even':'odd');		// Alternate even and odd rows
			echo"<tr class='".$row."'>";
			
			if ($year != $thisyear) //should not be able to delete or edit
				echo "<td><a href=\"http://www.ucdtramp.com/manage_members.php?year=".$year."&id=".$current_member['id']."&action=Copy\" title=\"Copy to this year\"><i class='fa fa-files-o'></i></a>";
			else
				echo "<td><a href=\"http://www.ucdtramp.com/manage_members.php?id=".$current_member['id']."&action=Edit\" title=\"Edit\"><i class='fa fa-pencil'></i></a>
					<a href=\"http://www.ucdtramp.com/manage_members.php?id=".$current_member['id']."&action=Delete\" onClick=\"return areYouSure();\"title=\"Delete\" class=\"delete\"><i class='fa fa-trash-o'></i></a></td>";
			
			echo "<td>".$current_member['membership_number']."</td><td>".$current_member['firstname']." ".$current_member['lastname']."</td>
			<td>".$current_member['formatted_dob']."</td><td>".sprintf("%08d",$current_member['student_number'])."</td>
			<td>".$current_member['term_address']."</td><td>".sprintf("%0".(strlen($current_member['mobile'])+1)."s",$current_member['mobile'])."</td>
			<td>".$current_member['home_address']."</td><td>".sprintf("%0".(strlen($current_member['home_phone'])+1)."s",$current_member['home_phone'])."</td>
			<td>".$current_member['email']."</td><td>".($current_member['mailinglist']==1?'Yes':'No')."</td><td>".$current_member['experience']."</td>
			<td>".($current_member['coach']==1?'Yes':'No')."</td><td>".($current_member['judge']==1?'Yes':'No')."</td><td>".$current_member['injuries']."</td>
			<td>".$current_member['faculty']."</td><td>".$current_member['stage']."</td></tr>
			";
		}
		?>
		</table>
	<?php
	break;
	
case 'Email':
	$title="Mailing List";
	addheader();
	$who=mysqli_real_escape_string($db, $_REQUEST['recipients']);
	switch($who){
	case 'committee':
		$committee=mysqli_query($db, "SELECT * FROM `members_db` WHERE `club_year`='".$year."' AND `comm`='1' AND `show`='1'");
		$recipients = 'trampoline@ucd.ie';
		while($member = mysqli_fetch_array($committee)){
			$recipients .= ", ".$member['email'];
		}
		break;
	case 'everyone':
		$members=mysqli_query($db, "SELECT * FROM members_db WHERE `club_year`='".$year."' AND mailinglist='1' AND email!='' AND `show`='1'");
		$recipients = 'trampoline@ucd.ie';
		while($member = mysqli_fetch_array($members)){
			$recipients .= ", ".$member['email'];
		}
		break;
	case 'coaches':
		$members=mysqli_query($db, "SELECT * FROM members_db WHERE `club_year`='".$year."' AND coach='1' AND email!='' AND `show`='1'");
		$recipients = 'trampoline@ucd.ie';
		while($member = mysqli_fetch_array($members)){
			$recipients .= ", ".$member['email'];
		}
		break;
	case 'judges':
		$members=mysqli_query($db, "SELECT * FROM members_db WHERE `club_year`='".$year."' AND judge='1' AND email!='' AND `show`='1'");
		$recipients = 'trampoline@ucd.ie';
		while($member = mysqli_fetch_array($members)){
			$recipients .= ", ".$member['email'];
		}
		break;
	default:
		$recipients='trampoline@ucd.ie';
		$members = mysqli_query($db, "SELECT * FROM members_db AND `show`='1'");
		while($member=mysqli_fetch_array($members)){
			if(get_http_var($member['membership_number'])==true&&$member['email']!=''){
				$recipients .= ', '.$member['email'];
			}
		}
		break;
	}
	echo mysqli_error($db);
?>
	<h2>Email <?=$who?></h2>
	<!-- Script used to handle writing and sending an email but this is now done soley through the Gmail account so this only prints people on the mailing list. The original code is commented out below-->
    There are <?php echo count(explode(",",$recipients));?> people on this mailing list<br>
    <textarea style="width:100%;height:20em;"><?=$recipients?></textarea>
    
	
	<?php
	break;

default:
	echo 'No action found';
	 
}
addfooter();
?>

<script>
    // Code for date and form validation used only in manage_members.php
    function validDate(day,month,stage){
        selectedDay=day.value;
        if(month.value=='02'){
            // February
            if(((stage.value%4==0)&&(stage.value%100!=0))||(stage.value%400==0)){
                // Leap stage
                day.options[30]=null;
                day.options[29]=null;
                day.options[28]=new Option('29');
            }else{
                // Non Leap stage
                day.options[30]=null;
                day.options[29]=null;
                day.options[28]=null;
            }
        }else if(month.value=='04'||month.value=='06'||month.value=='09'||month.value=='11'){
                // Months with 30 days
                day.options[30]=null;
                day.options[28]=new Option('29');
                day.options[29]=new Option('30');
        }else{
            // Months with 31 days
            day.options[28]=new Option('29');
            day.options[29]=new Option('30');
            day.options[30]=new Option('31');
        }
        
        if((day.length)<selectedDay){
            day.value=day.length;
        }else{
            day.value=selectedDay;
        }
    }

    function validateFormOnSubmit(theForm) {
       var reason = "";

      reason += validateStuNum(theForm.student_number);
      reason += validateEmail(theForm.email);
      reason += validatePhone(theForm.mobile_number);
      reason += validateStage(theForm.stage);
          
      if (reason != "") {
        alert("Some fields need correction:\n" + reason);
        return false;
      }
      
      return true;
    }
    function validateStuNum(fld) {
        var error = "";
     
        if (fld.value.length != 8) {
            fld.style.background = 'Yellow'; 
            error = "Student number must be 8 numbers long.\n";
        } 
        return error;
    }
    function trim(s){
      return s.replace(/^\s+|\s+$/, '');
    }
    function validateEmail(fld) {
        var error="";
        var tfld = trim(fld.value);  // value of field with whitespace trimmed off
        var emailFilter = /^[^@]+@[^@.]+\.[^@]*\w\w$/ ;
        var illegalChars= /[\(\)\<\>\,\;\:\\\"\[\]]/ ;
       
        if (fld.value == "") {
            fld.style.background = 'Yellow';
            error = "You didn't enter an email address.\n";
        } else if (!emailFilter.test(tfld)) { //test email for illegal characters
            fld.style.background = 'Yellow';
            error = "Please enter a valid email address.\n";
        } else if (fld.value.match(illegalChars)) {
            fld.style.background = 'Yellow';
            error = "The email address contains illegal characters.\n";
        } else {
            fld.style.background = 'White';
        }
        return error;
    }
    function validatePhone(fld) {
        var error = "";
        var stripped = fld.value.replace(/[\(\)\.\-\ ]/g, '');    

        if (isNaN(parseInt(stripped))) {
            error = "Phone numbers must only contain numbers.\n";
            fld.style.background = 'Yellow';
        }
        return error;
    }
    function validateStage(fld) {
        var error = "";
        
        for (i = 0; i < fld.length; ++ i)
        {
            if (fld [i].checked) return error;
        } 
        error = "Must select a stage.\n";
        return error;
    }
</script>