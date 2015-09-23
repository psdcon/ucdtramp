<?php
include_once('includes/functions.php');

if (!$loggedIn) {
    header("Location: page/404");
}
$title = "Members and what not";

// The year rolls over in September
$thisyear = (date("n") >= 9) ? date("y").(date("y") + 1) : (date("y") - 1).date("y");

// Default to the current year unless otherwise specified
isset($_REQUEST['year']) ? $year = $_REQUEST['year'] : $year = $thisyear;

// Default to showing all members as oppose to update, edit, add, create, delete. Makes url nicer.
isset($_REQUEST['action']) ? $action = $_REQUEST['action'] : $action = 'show';

// Update a member
switch ($action) {
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
        isset($_REQUEST['mailinglist']) ? $mailinglist = 1 : $mailinglist = 0;
        $experience = mysqli_real_escape_string($db, $_REQUEST['experience']);
        isset($_REQUEST['coach']) ? $coach = 1 : $coach = 0;
        isset($_REQUEST['judge']) ? $judge = 1 : $judge = 0;
        isset($_REQUEST['comm']) ? $comm = 1 : $comm = 0;
        $injuries = mysqli_real_escape_string($db, $_REQUEST['injuries']);
        $faculty = mysqli_real_escape_string($db, $_REQUEST['faculty']);
        $stage = mysqli_real_escape_string($db, $_REQUEST['stage']);
        if (mysqli_query($db, "INSERT INTO members_db (club_year,membership_number,firstname,lastname,dob,student_number,term_address,mobile,home_address,home_phone,email,mailinglist,experience,coach,judge,injuries,faculty,stage,comm) 
            VALUES('".$thisyear."','".$membership_number."','".$firstname."','".$lastname."','".$dob."','".$student_number."','".$term_address."','".$mobile."','".$home_address."','".$home_phone."','".$email."','".$mailinglist."','".$experience."','".$coach."','".$judge."','".$injuries."','".$faculty."','".$stage."','".$comm."')"))
            header("Location:manage_members.php?action=show&show=all&field=membership_number&order=DESC&success=".$firstname." ".$lastname." was added");
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
        isset($_REQUEST['mailinglist']) ? $mailinglist = 1 : $mailinglist = 0;
        $experience = mysqli_real_escape_string($db, $_REQUEST['experience']);
        isset($_REQUEST['coach']) ? $coach = 1 : $coach = 0;
        isset($_REQUEST['judge']) ? $judge = 1 : $judge = 0;
        isset($_REQUEST['comm']) ? $comm = 1 : $comm = 0;
        $injuries = mysqli_real_escape_string($db, $_REQUEST['injuries']);
        $faculty = mysqli_real_escape_string($db, $_REQUEST['faculty']);
        $stage = mysqli_real_escape_string($db, $_REQUEST['stage']);
        if (mysqli_query($db, "UPDATE members_db SET membership_number='".$membership_number."',firstname='".$firstname."',lastname='".$lastname."',dob='".$dob."',student_number='".$student_number."',term_address='".$term_address."',mobile='".$mobile."',home_address='".$home_address."',home_phone='".$home_phone."',email='".$email."',mailinglist='".$mailinglist."',experience='".$experience."',coach='".$coach."',judge='".$judge."',comm='".$comm."',injuries='".$injuries."',faculty='".$faculty."',stage='".$stage."' WHERE `club_year`='".$year."' AND id='".mysqli_real_escape_string($db, $_REQUEST['id'])."'"))
            header("Location:manage_members.php?action=show&show=all&field=membership_number&order=DESC&success=".$firstname." ".$lastname." was edited");
        else
            echo "Something went wrong: ".mysqli_error($db);
        break;
    
    case 'Delete':
        if (isset($_REQUEST['id'])) {
            // Save name for notice
            $memberSQL = mysqli_query($db, "SELECT * FROM members_db WHERE `club_year`='".$year."' AND id='".mysqli_real_escape_string($db, $_REQUEST['id'])."' LIMIT 1");
            $current_member = mysqli_fetch_array($memberSQL);
            $fullname = $current_member['firstname'].' '.$current_member['lastname'];
            
            // Set show to 0 so member is not displayed on front end
            if (mysqli_query($db, "UPDATE `members_db` SET `show`='0' WHERE `club_year`='".$year."' AND `id`='".mysqli_real_escape_string($db, $_REQUEST['id'])."'"))
                header("Location:manage_members.php?success=".$fullname." was removed");
            else
                echo "Something went wrong: ".mysqli_error($db);
        }
        break;
    
    case 'Copy':
        if (isset($_REQUEST['id'])) {
            $memId = mysqli_real_escape_string($db, $_REQUEST['id']);

            // Get members name for use in notice
            $member = mysqli_fetch_array(mysqli_query($db, "SELECT firstname,lastname FROM members_db WHERE `id` = $memId LIMIT 1"));
            $name = $member['firstname'].' '.$member['lastname'];
            
            $newMembershipNo = mysqli_fetch_array(mysqli_query($db, "SELECT MAX(membership_number) as num FROM members_db WHERE `club_year`='$thisyear' LIMIT 1"));
            $newMembershipNo = $newMembershipNo['num'] + 1;
            
            $insertFields = "`id`, `club_year`, `membership_number`, `firstname`, `lastname`, `dob`, `student_number`, `term_address`, `mobile`, `home_address`, `home_phone`, `email`, `mailinglist`, `experience`, `coach`, `judge`, `injuries`, `faculty`, `stage`, `comm`, `show`";
            $selectFields = "NULL, '$thisyear', '$newMembershipNo', `firstname`, `lastname`, `dob`, `student_number`, `term_address`, `mobile`, `home_address`, `home_phone`, `email`, `mailinglist`, `experience`, `coach`, `judge`, `injuries`, `faculty`, `stage`, `comm`, `show`";
            $copyResult = mysqli_query($db, "INSERT INTO `members_db` ($insertFields) SELECT $selectFields FROM `members_db` WHERE `id` = $memId");
            var_dump($copyResult);
            if ($copyResult)
                header("Location:manage_members.php?manage_members.php?year=".$thisyear."&success=".$name." was copied");
            else
                echo "Something went wrong: ".mysqli_error($db);
        }
        break;
    
    case 'Add':
        addHeader();
        $membership_number = mysqli_fetch_array(mysqli_query($db, "SELECT MAX(membership_number) AS num FROM members_db WHERE `club_year` = '$thisyear' LIMIT 1"));
        $membership_number = $membership_number['num'] + 1;
        ?>
        <!--Javascript code for data and form validation is below-->
        <h2>
            Manage Members
            <small><small><a href="manage_members.php?action=show&show=all&field=membership_number&order=ASC">Main Menu</a></small></small>
        </h2>
        <h3>Add New Member</h3>
        
        <form class="form-horizontal" action="manage_members.php" onsubmit="return validateFormOnSubmit(this);" name="memberform" method="POST" role="form">
            <div class="form-group">
                <label class="col-md-2" for="membership_number">Membership No.</label>
                <div class="col-md-10">
                    <input class="form-control" type="text"name="membership_number" value="<?= $membership_number ?>" />
                </div>
            </div>
            <div class="form-group">
                <div class="col-xs-6">
                    <input class="form-control" type="text"name="firstname" placeholder="First name" autofocus>
                </div>
                <div class="col-xs-6">
                    <input class="form-control" type="text" name="lastname" placeholder="Last name">
                </div>
            </div>
            <div class="form-group">
                <label class="col-xs-12 col-md-1">D.O.B</label>
                <div class="col-xs-4 col-md-3">
                    <select class="form-control" name="dobday" onChange="validDate(document.memberform.dobday,document.memberform.dobmonth,document.memberform.dobyear)">
                    <?php
                        for ($j = 1; $j <= 31; $j++) 
                            echo '<option value="'.$j.'">'.$j.'</option>';
                    ?>
                    </select>
                </div>
        
                <div class="col-xs-4 col-md-4">
                    <select class="form-control" name="dobmonth" onChange="validDate(document.memberform.dobday,document.memberform.dobmonth,document.memberform.dobyear)">
                        <option value="01">January</option>
                        <option value="02">February</option>
                        <option value="03">March</option>
                        <option value="04">April</option>
                        <option value="05">May</option>
                        <option value="06">June</option>
                        <option value="07">July</option>
                        <option value="08">August</option>
                        <option value="09">September</option>
                        <option value="10">October</option>
                        <option value="11">November</option>
                        <option value="12">December</option>
                    </select>
                </div>
                
                <div class="col-xs-4 col-md-4">
                    <select class="form-control" name="dobyear" onChange="validDate(document.memberform.dobday,document.memberform.dobmonth,document.memberform.dobyear)">
                    <?php
                        for ($j = (date("Y") - 16); $j >= 1970; $j--)
                            echo '<option value="'.$j.'">'.$j.'</option>';
                    ?>
                    </select>
                </div>
            </div>
            <div class="form-group">
                <div class="col-xs-4">
                    <input class="form-control" type="text" name="student_number" placeholder="Student number" maxlength="8">
                </div>
                <div class="col-xs-4">
                    <input class="form-control" type="text" name="mobile_number" placeholder="Mobile number">
                </div>
                <div class="col-xs-4">
                    <input class="form-control" type="text" name="home_number" placeholder="Home number">
                </div>
            </div>
            <div class="form-group">
                <div class="col-md-9">
                    <input class="form-control" type="text" name="email" placeholder="Email">
                </div>
                <div class="col-md-3">
                    <label>
                        <input type="checkbox" name="mailinglist" checked>
                        Mailing List
                    </label>
                </div>
            </div>
            <div class="form-group">
                <div class="col-xs-6">
                    <textarea class="form-control" rows="6" name="term_address" placeholder="Term Address"></textarea>
                </div>
                <div class="col-xs-6">
                    <textarea class="form-control" rows="6" name="home_address" placeholder="Home Address"></textarea>
                </div>
            </div>
            <div class="form-group">
                <div class="col-xs-6 col-md-4" style="padding-bottom:1em;">
                    <textarea class="form-control" rows="3" name="experience" placeholder="Experience"></textarea>
                </div>
                <div class="col-xs-6 col-md-4">
                    <textarea class="form-control" rows="3" name="injuries" placeholder="Injuries"></textarea>
                </div>
                <div class="col-md-4">
                    <input class="form-control" type="text" name="faculty" placeholder="Faculty">
                </div>
            </div>
            <div class="form-group">
                <div class="col-xs-12">
                    <label>Stage:</label>
                    <label class="radio-inline"><input type="radio" name="stage" value="1"> 1st</label>
                    <label class="radio-inline"><input type="radio" name="stage" value="2"> 2nd</label>
                    <label class="radio-inline"><input type="radio" name="stage" value="3"> 3rd</label>
                    <label class="radio-inline"><input type="radio" name="stage" value="4"> 4th</label>
                    <label class="radio-inline"><input type="radio" name="stage" value="X"> Other</label>
                </div>
            </div>
            <div class="form-group">
                <div class="col-xs-12">
                    <label class="checkbox-inline"><input type="checkbox" name="coach"> Coach</label>
                    <label class="checkbox-inline"><input type="checkbox" name="judge"> Judge</label>
                    <label class="checkbox-inline"><input type="checkbox" name="comm"> Committee</label>
                </div>
            </div>
            <button class="btn btn-primary" type="submit" name="action" value="Insert">Add Member</button>
        </form>
        <?php
        break;
    
    case 'Edit':
        addHeader();
        $members_query = mysqli_query($db, "SELECT * FROM members_db WHERE `club_year`='".$year."' AND id='".mysqli_real_escape_string($db, $_REQUEST['id'])."' LIMIT 1");
        $current_member = mysqli_fetch_array($members_query);
        $dob_day = date('j', $current_member['dob']);
        $dob_month = date('n', $current_member['dob']);
        $dob_stage = date('Y', $current_member['dob']);
        ?>
        <h2>
            Manage Members
            <small><small><a href="manage_members.php?action=show&show=all&field=membership_number&order=ASC">Main Menu</a></small></small>
        </h2>
        <h3>Edit Member</h3>
        
        <form class="form-horizontal" action="manage_members.php" onsubmit="return validateFormOnSubmit(this);" name="memberform" method="POST" role="form">
            <div class="form-group">
                <label class="col-md-2" for="membership_number">Membership No.</label>
                <div class="col-md-10">
                    <input class="form-control" type="text"name="membership_number" value="<?= $current_member['membership_number'] ?>" />
                </div>
            </div>
            <div class="form-group">
                <div class="col-xs-6">
                    <input class="form-control" type="text"name="firstname" placeholder="First name" value="<?= $current_member['firstname'] ?>" autofocus>
                </div>
                <div class="col-xs-6">
                    <input class="form-control" type="text" name="lastname" placeholder="Last name" value="<?= $current_member['lastname'] ?>">
                </div>
            </div>
            <div class="form-group">
                <label class="col-xs-12 col-md-1">D.O.B</label>
                <div class="col-xs-4 col-md-3">
                    <select class="form-control" name="dobday" onChange="validDate(document.memberform.dobday,document.memberform.dobmonth,document.memberform.dobyear)">
                    <?php
                        for ($j = 1; $j <= 31; $j++) 
                            echo "<option value='".$j."'".(($j == $dob_day) ? ' selected' : '').">".$j."</option>";
                    ?>
                    </select>
                </div>
            
                <div class="col-xs-4">
                    <select class="form-control" name="dobmonth" onChange="validDate(document.memberform.dobday,document.memberform.dobmonth,document.memberform.dobyear)">
                        <option value='01' <?= ((1 == $dob_month) ? ' selected' : '') ?>>January</option>
                        <option value='02' <?= ((2 == $dob_month) ? ' selected' : '') ?>>February</option>
                        <option value='03' <?= ((3 == $dob_month) ? ' selected' : '') ?>>March</option>
                        <option value='04' <?= ((4 == $dob_month) ? ' selected' : '') ?>>April</option>
                        <option value='05' <?= ((5 == $dob_month) ? ' selected' : '') ?>>May</option>
                        <option value='06' <?= ((6 == $dob_month) ? ' selected' : '') ?>>June</option>
                        <option value='07' <?= ((7 == $dob_month) ? ' selected' : '') ?>>July</option>
                        <option value='08' <?= ((8 == $dob_month) ? ' selected' : '') ?>>August</option>
                        <option value='09' <?= ((9 == $dob_month) ? ' selected' : '') ?>>September</option>
                        <option value='10' <?= ((10 == $dob_month) ? ' selected' : '') ?>>October</option>
                        <option value='11' <?= ((11 == $dob_month) ? ' selected' : '') ?>>November</option>
                        <option value='12' <?= ((12 == $dob_month) ? ' selected' : '') ?>>December</option>
                    </select>
                </div>
                
                <div class="col-xs-4">
                    <select class="form-control" name="dobyear" onChange="validDate(document.memberform.dobday,document.memberform.dobmonth,document.memberform.dobyear)">
                    <?php
                        for ($j = (date("Y") - 16); $j >= 1970; $j--)
                            echo "<option value='".$j."'".(($j == $dob_stage) ? ' selected' : '').">".$j."</option>";
                    ?>
                    </select>
                </div>
            </div>

            <div class="form-group">
                <div class="col-xs-4">
                    <input class="form-control" type="text" name="student_number" placeholder="Student number" value="<?= $current_member['student_number'] ?>">
                </div>
                <div class="col-xs-4">
                    <input class="form-control" type="text" name="mobile_number" placeholder="Mobile number" value="<?= $current_member['mobile'] ?>">
                </div>
                <div class="col-xs-4">
                    <input class="form-control" type="text" name="home_number" placeholder="Home number" value="<?= $current_member['home_phone'] ?>">
                </div>
            </div>
            <div class="form-group">
                <div class="col-xs-12">
                    <input class="form-control" type="text" name="email" placeholder="Email" value="<?= $current_member['email'] ?>">
                    <label>
                        <input type="checkbox" name="mailinglist" <?= ($current_member['mailinglist'] == 1)? 'checked' : '' ?>>
                        Mailing List
                    </label>
                </div>
            </div>
            <div class="form-group">
                <div class="col-xs-6">
                    <textarea class="form-control" rows="6" name="term_address" placeholder="Term Address"><?= $current_member['term_address'] ?></textarea>
                </div>
                <div class="col-xs-6">
                    <textarea class="form-control" rows="6" name="home_address" placeholder="Home Address"><?= $current_member['home_address'] ?></textarea>
                </div>
            </div>
            <div class="form-group">
                <div class="col-xs-6 col-md-4" style="padding-bottom:1em;">
                    <textarea class="form-control" rows="3" name="experience" placeholder="Experience"><?= $current_member['experience'] ?></textarea>
                </div>
                <div class="col-xs-6 col-md-4">
                    <textarea class="form-control" rows="3" name="injuries" placeholder="Injuries"><?= $current_member['injuries'] ?></textarea>
                </div>
                <div class="col-xs-12 col-md-4">
                    <input class="form-control" type="text" name="faculty" placeholder="Faculty" value="<?= $current_member['faculty'] ?>">
                </div>
            </div>
            <div class="form-group">
                <div class="col-xs-12">
                    <label>Stage:</label>
                    <label class="radio-inline"><input type="radio" name="stage" value="1" <?= ($current_member['stage'] == 1 ? 'checked' : '') ?>> 1st</label>
                    <label class="radio-inline"><input type="radio" name="stage" value="2" <?= ($current_member['stage'] == 2 ? 'checked' : '') ?>> 2nd</label>
                    <label class="radio-inline"><input type="radio" name="stage" value="3" <?= ($current_member['stage'] == 3 ? 'checked' : '') ?>> 3rd</label>
                    <label class="radio-inline"><input type="radio" name="stage" value="4" <?= ($current_member['stage'] == 4 ? 'checked' : '') ?>> 4th</label>
                    <label class="radio-inline"><input type="radio" name="stage" value="X" <?= ($current_member['stage'] == 'X' ? 'checked' : '') ?>> Other</label>
                </div>
            </div>
            <div class="form-group">
                <div class="col-xs-12">
                    <label class="checkbox-inline"><input type="checkbox" name="coach" <?= ($current_member['coach'] == 1 ? 'checked' : '') ?>> Coach</label>
                    <label class="checkbox-inline"><input type="checkbox" name="judge" <?= ($current_member['judge'] == 1 ? 'checked' : '') ?>> Judge</label>
                    <label class="checkbox-inline"><input type="checkbox" name="comm" <?= ($current_member['comm'] == 1 ? 'checked' : '') ?>> Committee</label>
                </div>
            </div>
            <input type='hidden' name='id' value="<?= $current_member['id'] ?>">
            <button class="btn btn-primary" type="submit" name="action" value="Update">Update Member</button>
        </form>
        <?php
        break;
    
    case 'show': // Which members to show
        addHeader();
        isset($_REQUEST['show']) ? $show = $_REQUEST['show'] : $show = 'all';
        
        // Fields and order (for sorting)
        isset($_GET['field']) ? $field = $_GET['field'] : $field = 'membership_number';
        isset($_GET['order']) ? $order = $_GET['order'] : $order = 'ASC';
        ?>
        <h2>
            Manage Members
        </h2>
    
        <?php
        if (isset($_GET['success'])) { // If an action has been successful, a msg box will be displayed
            echo ("<h3 style='color:green'>".$_GET['success']."!</h3>");
        }

        // List all members with edit/delete buttons. Not sure why the backticks are necessary but php got upset without them... 
        switch ($show) {
            case 'all':
                $query = "SELECT * FROM `members_db` WHERE `show`=1 AND `club_year`='".$year."' ORDER BY `".$field."` ".$order;
                $pageTitle = "All Members";
                break;
            case 'committee':
                $query = "SELECT * FROM `members_db` WHERE `show`=1 AND `club_year`='".$year."' AND `comm`='1' ORDER BY `".$field."` ".$order;
                $pageTitle = "Committee Members";
                break;
            case 'coach':
                $query = "SELECT * FROM `members_db` WHERE `show`=1 AND `club_year`='".$year."' AND `coach`='1' ORDER BY `".$field."` ".$order;
                $pageTitle = "Coaches";
                break;
            case 'judge':
                $query = "SELECT * FROM `members_db` WHERE `show`=1 AND `club_year`='".$year."' AND `judge`='1' ORDER BY `".$field."` ".$order;
                $pageTitle = "Judges";
                break;
            case 'under18now':
                $query = "SELECT * FROM `members_db` WHERE `show`=1 AND `club_year`='".$year."' AND `dob`>'".(time())."' ORDER BY `".$field."` ".$order;
                $pageTitle = "Under 18";
                break;
            case 'stage1':
                $query = "SELECT * FROM `members_db` WHERE `show`=1 AND `club_year`='".$year."' AND `stage`='1' ORDER BY `".$field."` ".$order;
                $pageTitle = "Stage 1";
                break;
            case 'stage2':
                $query = "SELECT * FROM `members_db` WHERE `show`=1 AND `club_year`='".$year."' AND `stage`='2' ORDER BY `".$field."` ".$order;
                $pageTitle = "Stage 2";
                break;
            case 'stage3':
                $query = "SELECT * FROM `members_db` WHERE `show`=1 AND `club_year`='".$year."' AND `stage`='3' ORDER BY `".$field."` ".$order;
                $pageTitle = "Stage 3";
                break;
            case 'stage4':
                $query = "SELECT * FROM `members_db` WHERE `show`=1 AND `club_year`='".$year."' AND `stage`='4' ORDER BY `".$field."` ".$order;
                $pageTitle = "Stage 4";
                break;
            case 'nostage':
                $query = "SELECT * FROM `members_db` WHERE `show`=1 AND `club_year`='".$year."' AND `stage`!='1' AND `stage`!='2' AND `stage`!='3' AND `stage`!='4' ORDER BY `".$field."` ".$order;
                $pageTitle = "Other Stages";
                break;
            default:
                $query = "SELECT * FROM `members_db` WHERE `show`=1 AND `club_year`='".$year."' ORDER BY `".$field."` ".$order;
                $pageTitle = "All members";
                break;
        }
        $members = mysqli_query($db, $query);
        echo mysqli_error($db);
        ?>
    
        <a class="btn btn-primary" href="manage_members.php?year=<?= $year ?>&action=Add">+ Add member</a>
        <p>
            <div class="btn-group">
                <div class="btn btn-default"><strong>Year:</strong></div>
                <a class="btn btn-default <?= $year == '1516'? 'active': '' ?>" href="manage_members.php?year=1516&action=show&show=<?= $show ?>&field=<?= $field ?>&order=<?= $order ?>">1516</a>
                <a class="btn btn-default <?= $year == '1415'? 'active': '' ?>" href="manage_members.php?year=1415&action=show&show=<?= $show ?>&field=<?= $field ?>&order=<?= $order ?>">1415</a>
                <a class="btn btn-default <?= $year == '1314'? 'active': '' ?>" href="manage_members.php?year=1314&action=show&show=<?= $show ?>&field=<?= $field ?>&order=<?= $order ?>">1314</a>
                <!-- <a class="btn btn-default" href="manage_members.php?year=1213&action=show&field=<?= $field ?>&order=<?= $order ?>">1213</a> -->
            </div>
        </p>
        
        <p>
            <div class="btn-group">
                <div class="btn btn-default"><strong>Show: </strong></div>
                <a class="btn btn-default <?= $show == 'all'? 'active': '' ?>" href="manage_members.php?year=<?= $year ?>&action=show&show=all&field=<?= $field ?>&order=<?= $order ?>">All</a>
                <a class="btn btn-default <?= $show == 'committee'? 'active': '' ?>" href="manage_members.php?year=<?= $year ?>&action=show&show=committee&field=<?= $field ?>&order=<?= $order ?>">Committee</a>
                <a class="btn btn-default <?= $show == 'coach'? 'active': '' ?>" href="manage_members.php?year=<?= $year ?>&action=show&show=coach&field=<?= $field ?>&order=<?= $order ?>">Coaches</a>
                <a class="btn btn-default <?= $show == 'judge'? 'active': '' ?>" href="manage_members.php?year=<?= $year ?>&action=show&show=judge&field=<?= $field ?>&order=<?= $order ?>">Judges</a>
                <a class="btn btn-default <?= $show == 'under18now'? 'active': '' ?>" href="manage_members.php?year=<?= $year ?>&action=show&show=under18now&field=<?= $field ?>&order=<?= $order ?>">Under 18 (now)</a>
            </div>
        </p>
        <p>
            <div class="btn-group">
                <div class="btn btn-default"><strong>Stage: </strong></div>
                <a class="btn btn-default <?= $show == 'stage1'? 'active': '' ?>" href="manage_members.php?year=<?= $year ?>&action=show&show=stage1&field=<?= $field ?>&order=<?= $order ?>">1</a>
                <a class="btn btn-default <?= $show == 'stage2'? 'active': '' ?>" href="manage_members.php?year=<?= $year ?>&action=show&show=stage2&field=<?= $field ?>&order=<?= $order ?>">2</a>
                <a class="btn btn-default <?= $show == 'stage3'? 'active': '' ?>" href="manage_members.php?year=<?= $year ?>&action=show&show=stage3&field=<?= $field ?>&order=<?= $order ?>">3</a>
                <a class="btn btn-default <?= $show == 'stage4'? 'active': '' ?>" href="manage_members.php?year=<?= $year ?>&action=show&show=stage4&field=<?= $field ?>&order=<?= $order ?>">4</a>
                <a class="btn btn-default <?= $show == 'nostage'? 'active': '' ?>" href="manage_members.php?year=<?= $year ?>&action=show&show=nostage&field=<?= $field ?>&order=<?= $order ?>">Other</a>
            </div>
        </p>

        <h3><?= $pageTitle ?> <?= mysqli_num_rows($members) ?> members)</h3>

        <style>
            .table {
                margin-bottom: 0;
            }
            thead {font-weight: bold;}
            td {white-space: nowrap;}
            .table-responsive {
                width: 100%;
                margin-bottom: 15px;
                overflow-y: hidden;
                -ms-overflow-style: -ms-autohiding-scrollbar;
                border: 1px solid #ddd;
                background-color: #fff;
            }
        </style>
        
        <div class="table-responsive">
            <table class="table table-striped">
                <thead style="position: -webkit-sticky; position: -moz-sticky; position: -ms-sticky; position: -o-sticky; position: sticky; top: 0;">
                    <tr>
                        <td title="Edit/Delete" style="font-size:1em;">
                            <i class="fa fa-cog"></i>
                        </td>
                        <td>
                            No. <br>
                            <a href="manage_members.php?action=show&show=<?= $show; ?>&field=membership_number&order=ASC&year=<?= $year ?>"><i <?= ($field == 'membership_number' && $order == 'ASC') ? 'style="color:red"' : ''; ?> class="fa fa-angle-up"></i></a>
                            <a href="manage_members.php?action=show&show=<?= $show; ?>&field=membership_number&order=DESC&year=<?= $year ?>"><i <?= ($field == 'membership_number' && $order == 'DESC') ? 'style="color:red"' : ''; ?> class="fa fa-angle-down"></i></a>
                        </td>
                        <td>
                            Name <br>
                            <a href="manage_members.php?action=show&show=<?= $show; ?>&field=firstname&order=ASC&year=<?= $year ?>"><i <?= ($field == 'firstname' && $order == 'ASC') ? 'style="color:red"' : ''; ?> class="fa fa-angle-up"></i></a>
                            <a href="manage_members.php?action=show&show=<?= $show; ?>&field=firstname&order=DESC&year=<?= $year ?>"><i <?= ($field == 'firstname' && $order == 'DESC') ? 'style="color:red"' : ''; ?> class="fa fa-angle-down"></i></a>
                        </td>
                        <td>
                            D.O.B. <br>
                            <a href="manage_members.php?action=show&show=<?= $show; ?>&field=dob&order=ASC&year=<?= $year ?>"><i <?= ($field == 'dob' && $order == 'ASC') ? 'style="color:red"' : ''; ?> class="fa fa-angle-up"></i></a>
                            <a href="manage_members.php?action=show&show=<?= $show; ?>&field=dob&order=DESC&year=<?= $year ?>"><i <?= ($field == 'dob' && $order == 'DESC') ? 'style="color:red"' : ''; ?> class="fa fa-angle-down"></i></a>
                        </td>
                        <td>
                            Student Number <br>
                            <a href="manage_members.php?action=show&show=<?= $show; ?>&field=student_number&order=ASC&year=<?= $year ?>"><i <?= ($field == 'student_number' && $order == 'ASC') ? 'style="color:red"' : ''; ?> class="fa fa-angle-up"></i></a>
                            <a href="manage_members.php?action=show&show=<?= $show; ?>&field=student_number&order=DESC&year=<?= $year ?>"><i <?= ($field == 'student_number' && $order == 'DESC') ? 'style="color:red"' : ''; ?> class="fa fa-angle-down"></i></a>
                        </td>
                        <td>
                            Home Address <br>
                            <a href="manage_members.php?action=show&show=<?= $show; ?>&field=home_address&order=ASC&year=<?= $year ?>"><i <?= ($field == 'home_address' && $order == 'ASC') ? 'style="color:red"' : ''; ?> class="fa fa-angle-up"></i></a>
                            <a href="manage_members.php?action=show&show=<?= $show; ?>&field=home_address&order=DESC&year=<?= $year ?>"><i <?= ($field == 'home_address' && $order == 'DESC') ? 'style="color:red"' : ''; ?> class="fa fa-angle-down"></i></a>
                        </td>
                        <td>
                            Term Address <br>
                            <a href="manage_members.php?action=show&show=<?= $show; ?>&field=term_address&order=ASC&year=<?= $year ?>"><i <?= ($field == 'term_address' && $order == 'ASC') ? 'style="color:red"' : ''; ?> class="fa fa-angle-up"></i></a>
                            <a href="manage_members.php?action=show&show=<?= $show; ?>&field=term_address&order=DESC&year=<?= $year ?>"><i <?= ($field == 'term_address' && $order == 'DESC') ? 'style="color:red"' : ''; ?> class="fa fa-angle-down"></i></a>
                        </td>
                        <td>
                            Experience <br>
                            <a href="manage_members.php?action=show&show=<?= $show; ?>&field=experience&order=ASC&year=<?= $year ?>"><i <?= ($field == 'experience' && $order == 'ASC') ? 'style="color:red"' : ''; ?> class="fa fa-angle-up"></i></a>
                            <a href="manage_members.php?action=show&show=<?= $show; ?>&field=experience&order=DESC&year=<?= $year ?>"><i <?= ($field == 'experience' && $order == 'DESC') ? 'style="color:red"' : ''; ?> class="fa fa-angle-down"></i></a>
                        </td>
                        <td>
                            Injuries <br>
                            <a href="manage_members.php?action=show&show=<?= $show; ?>&field=injuries&order=ASC&year=<?= $year ?>"><i <?= ($field == 'injuries' && $order == 'ASC') ? 'style="color:red"' : ''; ?> class="fa fa-angle-up"></i></a>
                            <a href="manage_members.php?action=show&show=<?= $show; ?>&field=injuries&order=DESC&year=<?= $year ?>"><i <?= ($field == 'injuries' && $order == 'DESC') ? 'style="color:red"' : ''; ?> class="fa fa-angle-down"></i></a>
                        </td>                    
                        <td>
                            Mobile Phone <br>
                            <a href="manage_members.php?action=show&show=<?= $show; ?>&field=mobile&order=ASC&year=<?= $year ?>"><i <?= ($field == 'mobile' && $order == 'ASC') ? 'style="color:red"' : ''; ?> class="fa fa-angle-up"></i></a>
                            <a href="manage_members.php?action=show&show=<?= $show; ?>&field=mobile&order=DESC&year=<?= $year ?>"><i <?= ($field == 'mobile' && $order == 'DESC') ? 'style="color:red"' : ''; ?> class="fa fa-angle-down"></i></a>
                        </td>
                        <td>
                            Home Phone <br>
                            <a href="manage_members.php?action=show&show=<?= $show; ?>&field=home_phone&order=ASC&year=<?= $year ?>"><i <?= ($field == 'home_phone' && $order == 'ASC') ? 'style="color:red"' : ''; ?> class="fa fa-angle-up"></i></a>
                            <a href="manage_members.php?action=show&show=<?= $show; ?>&field=home_phone&order=DESC&year=<?= $year ?>"><i <?= ($field == 'home_phone' && $order == 'DESC') ? 'style="color:red"' : ''; ?> class="fa fa-angle-down"></i></a>
                        </td>
                        <td>
                            Email <br>
                            <a href="manage_members.php?action=show&show=<?= $show; ?>&field=email&order=ASC&year=<?= $year ?>"><i <?= ($field == 'email' && $order == 'ASC') ? 'style="color:red"' : ''; ?> class="fa fa-angle-up"></i></a>
                            <a href="manage_members.php?action=show&show=<?= $show; ?>&field=email&order=DESC&year=<?= $year ?>"><i <?= ($field == 'email' && $order == 'DESC') ? 'style="color:red"' : ''; ?> class="fa fa-angle-down"></i></a>
                        </td>
                        <td>
                            Coach <br>
                            <a href="manage_members.php?action=show&show=<?= $show; ?>&field=coach&order=ASC&year=<?= $year ?>"><i <?= ($field == 'coach' && $order == 'ASC') ? 'style="color:red"' : ''; ?> class="fa fa-angle-up"></i></a>
                            <a href="manage_members.php?action=show&show=<?= $show; ?>&field=coach&order=DESC&year=<?= $year ?>"><i <?= ($field == 'coach' && $order == 'DESC') ? 'style="color:red"' : ''; ?> class="fa fa-angle-down"></i></a>
                        </td>
                        <td>
                            Judge <br>
                            <a href="manage_members.php?action=show&show=<?= $show; ?>&field=coach&order=ASC&year=<?= $year ?>"><i <?= ($field == 'coach' && $order == 'ASC') ? 'style="color:red"' : ''; ?> class="fa fa-angle-up"></i></a>
                            <a href="manage_members.php?action=show&show=<?= $show; ?>&field=judge&order=DESC&year=<?= $year ?>"><i <?= ($field == 'judge' && $order == 'DESC') ? 'style="color:red"' : ''; ?> class="fa fa-angle-down"></i></a>
                        </td>
                        <td>
                            Mailing <br>List
                            <a href="manage_members.php?action=show&show=<?= $show; ?>&field=mailinglist&order=ASC&year=<?= $year ?>"><i <?= ($field == 'mailinglist' && $order == 'ASC') ? 'style="color:red"' : ''; ?> class="fa fa-angle-up"></i></a>
                            <a href="manage_members.php?action=show&show=<?= $show; ?>&field=mailinglist&order=DESC&year=<?= $year ?>"><i <?= ($field == 'mailinglist' && $order == 'DESC') ? 'style="color:red"' : ''; ?> class="fa fa-angle-down"></i></a>
                        </td>
                        <td>
                            Faculty <br>
                            <a href="manage_members.php?action=show&show=<?= $show; ?>&field=faculty&order=ASC&year=<?= $year ?>"><i <?= ($field == 'faculty' && $order == 'ASC') ? 'style="color:red"' : ''; ?> class="fa fa-angle-up"></i></a>
                            <a href="manage_members.php?action=show&show=<?= $show; ?>&field=faculty&order=DESC&year=<?= $year ?>"><i <?= ($field == 'faculty' && $order == 'DESC') ? 'style="color:red"' : ''; ?> class="fa fa-angle-down"></i></a>
                        </td>  
                        <td>
                            Stage <br>
                            <a href="manage_members.php?action=show&show=<?= $show; ?>&field=stage&order=ASC&year=<?= $year ?>"><i <?= ($field == 'stage' && $order == 'ASC') ? 'style="color:red"' : ''; ?> class="fa fa-angle-up"></i></a>
                            <a href="manage_members.php?action=show&show=<?= $show; ?>&field=stage&order=DESC&year=<?= $year ?>"><i <?= ($field == 'stage' && $order == 'DESC') ? 'style="color:red"' : ''; ?> class="fa fa-angle-down"></i></a>
                        </td>
                    </tr>
                </thead>
                <tbody>
                <?php
                while ($current_member = mysqli_fetch_array($members)) {
                    $current_member['formatted_dob'] = date("j/m/y", $current_member['dob']);
                    echo "
                    <tr>";
                    
                    if ($year != $thisyear) //should not be able to delete or edit
                        echo "
                        <td>
                            <a href=\"manage_members.php?action=Copy&year=".$year."&id=".$current_member['id']."\" title=\"Copy to this year\"><i class='fa fa-files-o'></i></a>";
                    else
                        echo "
                        <td><a href=\"manage_members.php?id=".$current_member['id']."&action=Edit\" title=\"Edit\"><i class='fa fa-pencil'></i></a>
                            <a href=\"manage_members.php?id=".$current_member['id']."&action=Delete\" onClick=\"return areYouSure();\"title=\"Delete\" class=\"delete\"><i class='fa fa-trash-o'></i></a></td>";
                    
                    echo "
                        <td>".$current_member['membership_number']."</td>
                        <td>".$current_member['firstname']." ".$current_member['lastname']."</td>
                        <td>".$current_member['formatted_dob']."</td>
                        <td>".sprintf("%08d", $current_member['student_number'])."</td>
                        <td>".$current_member['home_address']."</td>
                        <td>".$current_member['term_address']."</td>
                        <td>".$current_member['experience']."</td>
                        <td>".$current_member['injuries']."</td>
                        <td>".sprintf("%0".(strlen($current_member['mobile']) + 1)."s", $current_member['mobile'])."</td>
                        <td>".sprintf("%0".(strlen($current_member['home_phone']) + 1)."s", $current_member['home_phone'])."</td>
                        <td>".$current_member['email']."</td>
                        <td>".($current_member['mailinglist'] == 1 ? 'Yes' : 'No')."</td>
                        <td>".($current_member['coach'] == 1 ? 'Yes' : 'No')."</td>
                        <td>".($current_member['judge'] == 1 ? 'Yes' : 'No')."</td>
                        <td>".$current_member['faculty']."</td>
                        <td>".$current_member['stage']."</td>
                    </tr>";
                }
                ?>
                </tbody>
            </table>
        </div>
        <?php
        break;
    
    case 'Email':
        $title = "Mailing List";
        addHeader();
        $who = mysqli_real_escape_string($db, $_REQUEST['recipients']);
        switch ($who) {
            case 'committee':
                $committee = mysqli_query($db, "SELECT * FROM `members_db` WHERE `show`=1 AND `club_year`='".$year."' AND `comm`='1' AND `show`='1'");
                $recipients = 'trampoline@ucd.ie';
                while ($member = mysqli_fetch_array($committee)) {
                    $recipients .= ", ".$member['email'];
                }
                break;
            case 'everyone':
                $members = mysqli_query($db, "SELECT * FROM members_db WHERE `club_year`='".$year."' AND mailinglist='1' AND email!='' AND `show`='1'");
                $recipients = 'trampoline@ucd.ie';
                while ($member = mysqli_fetch_array($members)) {
                    $recipients .= ", ".$member['email'];
                }
                break;
            case 'coaches':
                $members = mysqli_query($db, "SELECT * FROM members_db WHERE `club_year`='".$year."' AND coach='1' AND email!='' AND `show`='1'");
                $recipients = 'trampoline@ucd.ie';
                while ($member = mysqli_fetch_array($members)) {
                    $recipients .= ", ".$member['email'];
                }
                break;
            case 'judges':
                $members = mysqli_query($db, "SELECT * FROM members_db WHERE `club_year`='".$year."' AND judge='1' AND email!='' AND `show`='1'");
                $recipients = 'trampoline@ucd.ie';
                while ($member = mysqli_fetch_array($members)) {
                    $recipients .= ", ".$member['email'];
                }
                break;
            default:
                $recipients = 'trampoline@ucd.ie';
                $members = mysqli_query($db, "SELECT * FROM members_db AND `show`='1'");
                while ($member = mysqli_fetch_array($members)) {
                    if (get_http_var($member['membership_number']) == true && $member['email'] != '') {
                        $recipients .= ', '.$member['email'];
                    }
                }
                break;
        }
        echo mysqli_error($db);
        ?>
        <h2>Email <?= $who ?></h2>        
        There are <?= count(explode(",", $recipients)) ?> people on this mailing list<br>
        <textarea style="width:100%;height:20em;"><?= $recipients ?></textarea>
        <?php
        break;
}
addFooter();
?>

<script>
    // Code for date and form validation used only in manage_members.php
    function validDate(day,month,stage){
        selectedDay=day.value;
        if(month.value=='02'){
            // February
            if(((stage.value%4===0)&&(stage.value%100!==0))||(stage.value%400===0)){
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
          
      if (reason !== "") {
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
        var illegalChars = /[\(\)\<\>\,\;\:\\\"\[\]]/ ;
       
        if (fld.value === "") {
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