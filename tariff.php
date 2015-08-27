<?php
require_once ('includes/functions.php');
if(isset($_GET['level'])){

	$level = mysqli_real_escape_string($db,$_GET['level']);
    $comp = mysqli_real_escape_string($db,$_GET['comp']);
    $name = mysqli_real_escape_string($db,$_GET['name']);
    $routine = mysqli_real_escape_string($db,$_GET['routine']);
	$routine = explode(',',$routine);
	
	$insert_post = mysqli_query($db,"INSERT INTO tariff_routines
		  (level,comp,name,skill1,skill2,skill3,skill4,skill5,skill6,skill7,skill8,skill9,skill10) 
	VALUES('".$level."','".$comp."','".$name."','".$routine[0]."','".$routine[1]."','".$routine[2]."','".$routine[3]."','".$routine[4]."','".$routine[5]."','".$routine[6]."','".$routine[7]."','".$routine[8]."','".$routine[9]."')"); 
	if ( mysqli_error($db) )
		exit( mysqli_error($db) ); 
	else 
		exit("Routine saved. It will appear once the webmaster approves it");
}
$title="Tariff Calculator";
addheader();
?>
    <style>        
        #tariffCalc{
			margin:0;
			background: #FFF;
			padding: 10px 20px 10px 20px;
			font: 1em "Helvetica Neue", Helvetica, Arial, sans-serif;
			text-shadow: 1px 1px 1px #FFF;
			border:1px solid black;
			border-radius: 5px;
			-webkit-border-radius: 5px;
			-moz-border-radius: 5px;
			position:relative;
			
			box-shadow: 3px 3px 0px 0px rgba(50, 50, 50, 0.75);
        }
		#calcHeader{
			padding-left: 20px;
			border-bottom: 1px solid #DADADA;
			margin: -10px -20px 26px -20px;
		}
		/*Input styles*/
		.calcBox, #tariffCalc select{
			vertical-align: bottom;
			height: 1.4em;
			border: 1px solid #CCC;
			border-radius: 4px;			
			-webkit-border-radius: 4px;
			-moz-border-radius: 4px;
			-webkit-box-shadow: inset 0 1px 1px rgba(0, 0, 0, 0.075);
			box-shadow: inset 0 1px 1px rgba(0, 0, 0, 0.075);
			-moz-box-shadow: inset 0 1px 1px rgba(0, 0, 0, 0.075);
		}
		#tariffCalc select {
			height: 25px;
		}
		label{
			cursor:pointer;
		}
		#levelSelect label{
			display:none;
		}
		.skillSelect{
			min-width:237px;	
		}		
		#tariffCalc input[type="radio"]{
			margin: 0 12px 0 12px;
		}
		.calcBox{
			display: inline-block;
		}
		.tariff, #total{
			text-align:center;
			width:3em;
			margin-left:2em;
        }
		/*Fig Notation boxes*/
		.FIG{margin-left:2em;}	
		.FIG span{
        	display:inline-block;
			vertical-align:text-bottom;
			height: 100%;
		}
		#FIGHeader, .FIG{
			display:none; /*start hidden cause checkbox is unchecked*/
		}
		.twists{
			border-right: 1px solid #CCC;
			border-left: 1px solid #CCC;
			margin:0 5px 0 5px;
		}
		/*Error Boxes*/
		#repeats, #invalidLinks{
			padding:.4em;
			margin-bottom:.5em;
			display:none;
			border-radius: 4px;			
			-webkit-border-radius: 4px;
			-moz-border-radius: 4px;
		}
		#repeats{border:1px solid red;}
		#invalidLinks{border:1px solid orange;}
		
		#showFIG{
			position:absolute;
			top:0;
			right:0;
			font-size:.8em;
			color:#888;
		}
		.cf:before,
		.cf:after {
			content: " "; /* 1 */
			display: table; /* 2 */
		}
		.cf:after {
			clear: both;
		}
		

		#tariffCalc button {
			background: #428bca;
			border: 1px solid #357ebd;
			padding: 6px 15px 6px 15px;
			color: white;
			border-radius: 4px;
			box-shadow: none;
		}
		#tariffCalc button:hover {
			color:black;
			background-color: #EBEBEB;
			border-color: #ADADAD;
		}
		
		#description{
			clear: both;
			border-top: 1px solid #DADADA;
			margin: -10px -20px 20px -20px;
			padding: 20px 0 0 30px;	
		}
    </style>
</head>

<body>
    <div id="tariffCalc" class="cf">
    	<div id="calcHeader"><h1 style="margin-bottom:0;text-align:center;">New Tariff Calculator</h1> 
        	<div style="font-size:.8em;text-align:center;">Press tab to move through skills. Use arrow keys to change shape.</div><br>
            
            Selecting a competition will show all saved routines for that competition. Selecting a difficulty level will show only routines for that level.
            Orange means the link between the two skills is impossible. Red means skill is repeated which is not allowed in a set routine. 
            For voluntary routines, this is allowed but no tariff is given for the repeated skill. Novice skills are ordered by shape skills followed by seat, front and back skills.<br><br>
            <label id="showFIG">Show FIG? <input type="checkbox" onChange="showFIG();"></label>
            
            Select a competition:
            <select id="compSelect" onChange="compChange()" autofocus>
            	<option value="Free">Free Builder</option>
            	<option value="In-House">In-House</option>
                <option value="Intervarsities">Intervarsities</option>
                <option value="SSTO">SSTO</option>
                <option value="ISTO">ISTO</option>
                <option value="Dublin Open">Dublin Open</option>                
                <option value="Regionals">Regionals</option>                
            </select>
            &nbsp;&nbsp;&nbsp;&nbsp;
            <form id="levelSelect" style="display:inline-block;">
                <label>Select difficulty:</label>
                <label><input type="radio" name="level" title="Novice" value="Novice">Novice</label>
                <label><input type="radio" name="level" title="Intermediate" value="Intermediate">Intermediate</label>
                <label><input type="radio" name="level" title="Intervanced" value="Intervanced">Intervanced</label>
                <label><input type="radio" name="level" title="Advanced" value="Advanced">Advanced</label>
                <label><input type="radio" name="level" title="Elite" value="Elite">Elite</label></form><br><br>
        </div>
        
        <ol style="display:inline-block;margin-bottom:25px"> <!--Calculator buttons-->
        	<div style="position:relative;top:-12px;left:25px;height:25px;font-weight:bold;">
            	<span id="routineSelectPlaceholder">Select Routine Skills</span>
                <select style="width:200px;display:none;" id="routineSelect" onchange="routineChange()">
                        <option value="0">Sample Routines...</option></select>
                <span style="position:absolute;left:236px;">Tuck &nbsp;Pike&nbsp; Straight</span>
                <span style="position:absolute;left:403px;">Tariff</span>
                <span id="FIGHeader" style="position:absolute;left:486px;">FIG Notation</span>
            </div>
        	<?php for ($i=0;$i<10;$i++) {echo '
        	<li style="margin-top:5px;"> <!--skill row-->
            	<select class="skillSelect" onClick="shapeRemove('.$i.');" onChange="skillChange($(this).val(),'.$i.')">
					<option value="0" selected></option></select>
				<form class="shapeForm" style="display:inline;">
					<input type="radio" name="shape" title="Tuck" onClick="shapeChange(\'Tuck\','.$i.')" disabled>
					<input type="radio" name="shape" title="Pike" onClick="shapeChange(\'Pike\','.$i.')" disabled>
					<input type="radio" name="shape" title="Straight" onClick="shapeChange(\'Straight\','.$i.')" disabled>
				</form>
                <span class="tariff calcBox"></span>
                <span class="FIG calcBox">
					<span class="saltos" style="width:18px;text-align:right;" title="Number of quater somersault rotations"></span> 
					<span class="twists" style="width:42px;text-align:center;" title="Number of half twists in each somerfault"></span> 
					<span class="FIGShape" style="width:1em;" title="Shape of skill"></span>
				</span>
            </li>
			'; }?>
            
            <div style="margin-top:0.8em;position:relative;left:25px;">
            	<button onClick="saveRoutine()">Save Routine</button>
                <strong style="position:relative;left:195px;">Total:</strong> 
                <span id="total" style="position:relative;left:198px;" class="calcBox"></span>
            </div>
        </ol>
        <!--Error box-->
        <div id="error" style="display:inline-block;vertical-align:top;position:relative;top:-12px;margin-left:2.5em;width: 48%;">
            <strong>Repeated Skills and Clashing Links</strong><br><br>
            <div id="repeats"></div>
            <div id="invalidLinks"></div>
            <div id="easter" style="display:none;">
                Yeeeessss!! The easter eggs are yours. Take them, take them all! Cruising is the awesome, try it sometime :P <br><br>
                Special thanks to Colm who tested and suggested, Jordan who did most of the FIG notation and Glasgow especially who was there every time we drew a blank. <br><br>
                I wish the club all the best when I'm 9000 km away next year. Sincerely yours, the second best webmaster ever cause props to Barry,<br><br> Paul.<br>
                <img src="http://explorestlouis.com/wp-content/uploads/2014/03/Easter-Eggs1.jpg">
            </div>
        </div>
        
        <div id="description">
        	Select a skill for an explanation of it: 
        	<select onChange="$('#exp').html($(this).val());">
            	<option></option><optgroup style="display:none;">
        	<?php 
				$skills_query = mysqli_query($db, "SELECT * FROM tariff_skills ORDER BY `level`, `order`");
				$lastLevel="";
				while($skill=mysqli_fetch_array($skills_query)){
					if ($skill['description'] != ''){
						if($skill['level'] != $lastLevel){
							echo '</optgroup><optgroup label="'.$skill['level'].'">';
							$lastLevel=$skill['level'];
						}
						echo '<option value="<br><strong>'.$skill['skill'].'</strong> <span style=\'margin-left:1.2em;font-size:.9em;\' title=\'FIG Notation\'>'.$skill['fig_notation'].'</span> 
							<br>'.$skill['description'].'">'.$skill['skill'].'</option>';
					}
				} ?>
                </optgroup><option value="<br><strong>Plum</strong><br> A move that starts off well but then goes badly and lets everybody down">Plum</option>
			</select>
            <div id="exp"></div>
        </div>
    </div>
    
    <script>
<?php
	$skills_query = mysqli_query($db, "SELECT * FROM tariff_skills ORDER BY `level`, `order`");
	// open javascript 2D array. first element all blanks so when blank option is selected all boxes are cleared
	echo "var skills = [ 
			[\"\",\"\",\"\",\"\",\"\",\"\",\"\",[\"\",\"\",\"\"]],"; 
	while($skill=mysqli_fetch_array($skills_query)){
		//split FIG into array of saltos, 
		/*preg_match('#(\d\d?) ((?:[-\d]\s*)+)\s?(o)?#i',$skill['fig_notation'],$matches);
		if (strlen($matches[2]) > 3) // TODO wasnt able to not capture last whitespace of 1/2 rotations
			$matches[2] = substr($matches[2], 0 ,-1);
		$FIGArray = '["'.$matches[1].'","'.$matches[2].'","'.$matches[3].'"]';*/
		
		preg_match('#(\d\d?) #i',$skill['fig_notation'],$matches);
		$saltos = $matches[1];
		preg_match('#(o|<|/)#i',$skill['fig_notation'],$matches);
		$shape = $matches[1];
		$twists = substr($skill['fig_notation'],strlen($saltos));
		$twists = str_replace($shape,"",$twists);
		$FIGArray = '["'.$saltos.'","'.$twists.'","'.$shape.'"]';
		
		echo '["'.$skill['level'].'","'.$skill['skill'].'","'.$skill['shaped'].'","'.$skill['shape_bonus'].'","'.lcfirst($skill['start_position']).'","'.lcfirst($skill['end_position']).'","'.$skill['tariff'].'",'.$FIGArray.'],
		';
	}
	echo '["empty_skill"] ]'; // Last element of js array must not end with a ,
	
	// Correctly orders level by difficulty vs alphabetic
	$routineSpecialSQL = "SELECT * FROM tariff_routines
		WHERE level IN ('Novice','Intermediate','Intervanced','Advanced','Elite')
		ORDER BY CASE level 
			WHEN 'Novice' THEN 1
			WHEN 'Intermediate' THEN 2
			WHEN 'Intervanced' THEN 3
			WHEN 'Advanced' THEN 4
			WHEN 'Elite' THEN 5
       END";
	$routines_query = mysqli_query($db, $routineSpecialSQL);
	// Open Javascript 2D array. First element all blanks so when option 0 is selected all boxes are cleared
	echo "\n\n\t\t var routines = [
			[\"\",\"\",\"\",\"\",\"\",\"\",\"\",[\"\",\"\",\"\"]],"; 
	while($rout=mysqli_fetch_array($routines_query)){
		if ($rout['show'] == '1'){	
			echo '["'.$rout['level'].'","'.$rout['comp'].'","'.$rout['name'].'",';
			for ($i=1;$i<10;$i++)
				echo '"'.$rout['skill'.$i].'",';
			echo '"'.$rout['skill10'].'"],
			';
		}
	}
	echo '["empty_routine"] ]'; // Last element of js array must not end with a ,
?>
    </script>
    <script src="js/new_tariff.js"></script>

<?php
addfooter();
?>