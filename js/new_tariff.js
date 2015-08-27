
/* Global Var's for easy access */
var shapeArray = new Array("","","","","","","","","","");
var selectedSkillsArray = new Array("","","","","","","","","","");	

/* Function start calls */
populateSelects();
/*showFIG(true);*/

/* Function definitions */
/* Add's all entries in skills array as options to each of the 10 selects */
function populateSelects(){ 
	var lastLevel, selectOptions = "";
	
	//add all entries in skills array as options in option groups to the first select, then duplicate the other 9
	for (var i=1;i<skills.length-1;i++){			
		if (skills[i][0] != lastLevel && i !== 0){
			selectOptions += ('<optgroup label="'+skills[i][0]+'">');
			lastLevel = skills[i][0]; //save last level
		}
		selectOptions += ('<option value="'+i+'" title="Description...">'+skills[i][1]+'</option>'); 
		//$('.skillSelect optgroup:last-child').append('<option value="'+i+'" title="Description...">'+skills[i][1]+'</option>'); 
	}
	$('.skillSelect').append(selectOptions);
}

/* When a select is clicked and the move has a shape, reset the option's value to not contian the shape
   When both events, onClick and onChange fire when users clicks a skill, 
   	skillChange goes first, add's the move to the option text. 
	onClick goes next, removes move from option. 
   These fire within 20 milisconds of each other. Longer than that is OK
*/
var lastSkillChange= new Date();
function shapeRemove(selectNum){
	if ( shapeArray[selectNum] !== "" && (lastSkillChange - new Date())<-20 ){
		var allSelectOptions = $( ".skillSelect").eq(selectNum).children('optgroup').children();
		var skillIndex = selectedSkillsArray[selectNum];
		console.log('Removing shape from '+allSelectOptions.eq(skillIndex).html() );
		
		allSelectOptions.eq(skillIndex-1).html(skills[skillIndex][1]);
	}
}

/* When a skill select we add the tariff score and fig notation.
   Then check if the move has a shape
	if yes, activate tuck shape by default and call shapeChange() to add to global array and change text of option etc
	else deactive radio buttons which might have been active from previous move
 activate the update  the tariff score, fi */
function skillChange(skillIndex,selectNum){
	shapeRemove(selectNum);
	lastSkillChange=new Date();		
	
	if(skillIndex == "0")
		selectedSkillsArray[selectNum] = "";
	else
		selectedSkillsArray[selectNum] =  skillIndex;

	$('.tariff').eq(selectNum).text(skills[skillIndex][6]);
	
	$('.saltos').eq(selectNum).text(skills[skillIndex][7][0]);
	$('.twists').eq(selectNum).text(skills[skillIndex][7][1]);
	$('.FIGShape').eq(selectNum).text(skills[skillIndex][7][2]);
	
	if (skills[skillIndex][2] == '1'){ 
		$('.shapeForm').eq(selectNum).children().removeAttr( "disabled" );
		$('.shapeForm').eq(selectNum).children().eq(0).attr('checked',true);
		shapeChange('Tuck',selectNum); return; // dont call checkOK() and total() twice
	}
	else{ //disable and uncheck all 3 shape buttons for that move
		$('.shapeForm').eq(selectNum).children().attr('disabled',true);
		$('.shapeForm').eq(selectNum).children().attr('checked',false);
		shapeArray[selectNum] = "";
	}
	checkOK(); // check for repeated moves and incorrect links	
	total(); // add up total of tariff scores
}

/* Called when radio button is changed of move with shape is selected
	Adds (shape) to the name of the skill selected
	If either piked or straight, add tariff bonus and change fig shape
*/
function shapeChange(shape,selectNum){
	// get skill index from global array
	var skillIndex = selectedSkillsArray[selectNum];		
	shapeArray[selectNum]=shape; // save shape to global array for easy reference
	
	$('.skillSelect option:selected').eq(selectNum).html( skills[skillIndex][1]+' ('+shape+')' );
	// if shape is not tuck, it's piked or staight.
	if (shape != 'Tuck'){
		// update tariff value by coverting string to float, adding then rounding to nearest decimal 
		bonusTariff = parseFloat(skills[skillIndex][6]) + parseFloat(skills[skillIndex][3]);
		$('.tariff').eq(selectNum).text( Math.round(bonusTariff*10.0)/10.0 );
		// change fig shape by replacing o (tuck is our default) by pike < or straight /
		if(shape == 'Pike')
			$('.FIGShape').eq(selectNum).text('<');
		else if(shape == 'Straight')
			$('.FIGShape').eq(selectNum).text('/');
	}
	else{
		// remove any bonus from tariff
		$('.tariff').eq(selectNum).text(skills[skillIndex][6]);
		$('.FIGShape').eq(selectNum).text('o');
	}
	
	checkOK(); // same move could be repeated in different shape
	total(); // retaly total in case of added tariff bonus for pike or straight
}

/* Has 3 parts, reset, repeats and links (includes ending in feet)*/
var repeatsOK=true, linksOK=true; // used to check that routine is okay before saving
function checkOK(){
	// Part 1: Resets
	//empty error msg boxes and reset global vars. (routine is innocent until proven guilty)
	$('#repeats').text("").hide(); $('#invalidLinks').text("").hide();
	repeatsOK = true; linksOK = true;
	// reset all select borders 
	$('.skillSelect').each(function(i) {
		// Grey if no option selected
		if(selectedSkillsArray[i] === "")
			$(this).css('border-color','#CCC');
		else{
			$(this).css('border-color','green');
			// reset the tariff value from 'Rpt'
			if(shapeArray[i] === "" || shapeArray[i] == "Tuck"){
				$('.tariff').eq(i).text(skills[ selectedSkillsArray[i] ][6]);
			}
			else{
				bonusTariff = parseFloat(skills[ selectedSkillsArray[i] ][6]) + parseFloat(skills[ selectedSkillsArray[i] ][3]);
				$('.tariff').eq(i).text( Math.round(bonusTariff*10.0)/10.0 );
			}
		} // reset all tariff borders which fo red for repeat too
		$('.tariff').eq(i).css('border-color','#CCC');
	});
	
	// Part 2: Check for reapeated skills, make select and tariff border red
	$('.skillSelect').each(function(i) {
		for (var j=i+1; j<10 && selectedSkillsArray[i] !== ""; j++){
			if (selectedSkillsArray[i] == selectedSkillsArray[j] ){
				// Get existing error text
				var rptTxt = $('#repeats').html();
				// Skill is the same but might have different shapes
				if (shapeArray[i] !== "" && shapeArray[i] == shapeArray[j]){
					$('.skillSelect').eq(i).css('border-color','red');
					$('.skillSelect').eq(j).css('border-color','red');
					$('.tariff').eq(j).css('border-color','red').text('Rpt'); 
					// total() called after which ignores 'Rpt'
					
					// Error text to be added
					rptTxt += "The shape for skill "+(i+1)+" and "+(j+1)+" is the same<br>"; repeatsOK = false;				
				}						
				else if (shapeArray[i] === "") {
					$('.skillSelect').eq(i).css('border-color','red');
					$('.skillSelect').eq(j).css('border-color','red');
					$('.tariff').eq(j).css('border-color','red').text('Rpt');
					// total() called after which ignores 'Rpt'
					
					// Error text to be added
					rptTxt += "Skills "+(i+1)+" and "+(j+1)+" are the same<br>"; repeatsOK = false;					
				}
				$('#repeats').html(rptTxt); 
				break; // stop after finding one clashing skill
			}
			
			// Easteregg code
			if (skills[selectedSkillsArray[i]][1] == "Front Drop" && selectedSkillsArray[j] !== "")
				if (skills[selectedSkillsArray[j]][1] == "&frac12; Twist to Front Drop")
					$('#easter').show('slow');
		}
	});
	if (!repeatsOK){
		var rptTxt = $('#repeats').html();
		rptTxt += "<br><span style='font-size:0.8em;'>Repeats only allowed in <strong>Voluntary</strong> routines.<br> No tariff is awarded for repeated skill</span>";
		$('#repeats').html(rptTxt).show();
	}
	
	// Part 3: Check that previous end and next start position match. Make border orange if not
	$('.skillSelect').each(function(i) {
		if (i<9 && selectedSkillsArray[i] !== "" && selectedSkillsArray[i+1] !== ""){				
			if (skills[ selectedSkillsArray[i] ][5] != skills[ selectedSkillsArray[i+1] ][4]){
				$('.skillSelect').eq(i).css('border-color','orange');
				$('.skillSelect').eq(i+1).css('border-color','orange');
				var linkTxt = $('#invalidLinks').html();
				 linkTxt += "Skill "+(i+1)+" ends in "+skills[ selectedSkillsArray[i] ][5]+
				 		" but skill "+(i+2)+" starts from "+skills[ selectedSkillsArray[i+1] ][4]+"<br>";
				$('#invalidLinks').html(linkTxt).show();
				linksOK = false;
			}
		}
	});
	// make sure the last moves ends on your feet
	if (selectedSkillsArray[9] !== "" && skills[ selectedSkillsArray[9] ][5] != 'feet'){
		$('.skillSelect').eq(9).css('border-color','orange');	
		var str = $('#invalidLinks').html();
		 str += "The last skill must end in feet, not "+skills[ selectedSkillsArray[9] ][5]+"<br>";
		$('#invalidLinks').html(str).show();
		linksOK = false;
	}
}
/* Adds moves from skills array to the skill seleccts based on names. If the name of a skill is changed in the database, it must also be changed to if it appears in a routine. This means there is no messing trying to get skill id's or indexs working, using names is easier.
	The routine skill is checked for a shape
		if found this is stripped and the new str is the skill base
		else skill base is the routine skill name
	The skill base is then compared against the skills array until a match is found
	

	The routine move is comapred to each move in the skills array until a match is found
	When yes, the skills array is checked to see if the move can be shaped
	If yes, the shape is found using a regular expression. See regexr.com */
function routineChange(){
	var routineIndex = $('#routineSelect').val(); // index of selected routine in routines array
	var shapeEx = new RegExp("(Tuck|Pike|Straight)");
	var	skillFound = false; // flag to exit loop once routine skill is matched
	
	// If blank option was chosen, reset all skills selects to blank
	if (routineIndex == '0'){
		$('.skillSelect').each(function(i){
			$(this).val(0);
			skillChange(0,i);	
		});
	}
	else{
		var skillRoot="", isShaped;
		
		// loop through the skills array comparing skill name to name of the routine's skill
		for (var i=0;i<10;i++, skillFound=false){				
			// find shape if any and strip it. Search returns index of shape in sting
			// i+3 because 4th array pos is 1st skill (0=level 1=comp 2=name)
			isShaped = routines[routineIndex][i+3].search(/ \((Tuck|Pike|Straight)/gi);
			if (isShaped>0) skillRoot = routines[routineIndex][i+3].substring(0,(isShaped));
			else skillRoot = routines[routineIndex][i+3];
			
			for (var j=1;j<skills.length-1;j++){					
				// Try find a match
				if (skillRoot == skills[j][1]){
					//console.log('Skill',i,routines[routineIndex][i+3]+" matched "+skills[j][1])
					$('.skillSelect').eq(i).val(j);// set select value based on current skill index
					skillChange(j,i);
					
					// Check if that skill is shaped. exec returns shape 
					// No need for Tuck if statement because skill Change will default the radio buttons to tuck
					if (skills[j][2] == '1'){
						var shape = shapeEx.exec(routines[routineIndex][i+3]);
						//console.log("Skill "+routines[routineIndex][i+3]+" is shaped. Found "+shape);
						if (shape[1] == 'Pike')
							$('.shapeForm').eq(i).children().eq(1).attr('checked',true);
						else if (shape[1] == 'Straight')
							$('.shapeForm').eq(i).children().eq(2).attr('checked',true);
						shapeChange(shape[1],i);
					}
					skillFound = true;
					break;
				}					
			}
			if (!skillFound) 
				alert("Skill "+(i+1)+" '"+routines[routineIndex][i+3]+"' was NOT found");
		}
	}
}

/* Converts the text in the tariff box to a float, add all these ignoring blanks and 'Rpt's 
   Round this number to the nearest decimal place*/
function total(){
	var count=0.0;
	$('.tariff').each(function(){
		var tariffText = $(this).text();
		if (tariffText !== "" && tariffText != 'Rpt')
			count += parseFloat(tariffText);
	});
	// correct to 1 decimal place
	count = Math.round(count*10.0)/10.0;
	if (count > 0) $('#total').text(count);
	else $('#total').text("");
}

//Show FIG check box
function showFIG() {
	var checkbox = document.getElementById("showFIG").children[0];
	if (checkbox.checked === true){
		$('.FIG').show('slow').css("display", "inline-block");
		$('#FIGHeader').show('slow').css('top','0');
		$('#error').css('width','34%');
	}
	else{
		$('.FIG').hide('slow');
		$('#FIGHeader').hide('slow');
		$('#error').css('width','48%');
	}
}

function compChange(){
	var comp=$('#compSelect').val();
	var levelLabels=$('#levelSelect label');
	
	if(comp != "Free"){
		// If a comp is selected, hide the placeholder(Select Rotuine SKills) and fade in routineSelect
		$('#routineSelectPlaceholder').fadeOut('fast',function(){
			$('#routineSelect').fadeIn();
		});			
		populateRoutineSelect(false); // Add all routines to box at first
		levelLabels.each(function(){ // Show all difficulty level labels 
			if ($(this).text() == 'Intervanced' && (comp == 'Regionals' || comp =='In-House'))
				$(this).hide('fast');
			else
				$(this).fadeIn();
		});
	}
	else{
		// no competition, hide radios and replace routineSelect box with placeholder again
		levelLabels.fadeOut();
		$('#routineSelect').fadeOut(function(){
			$('#routineSelectPlaceholder').fadeIn();
		});
	}
	// Uncheck all radios every routine change
	$('#levelSelect label').children(":radio").attr("checked", false);		
}

/* Filters routines showing in the routineSelect
	if level, filters out all routines except that level
	else if no level, shows all rotuines for that comp */
function populateRoutineSelect(level){	
	var comp = $('#compSelect').val();
	var i,count=0, // show's how many currently saved
		routineOptions = '"<option value="0"></option>'; // text added to this option below
	
	if (level && comp){
		// add routines of same level from the comp to select 
		for (i=1, l=routines.length-1;i<l ;i++){
			if (routines[i][0] == level && routines[i][1] == comp){
				routineOptions += ('<option value="'+i+'">'+routines[i][0]+' - '+routines[i][2]+'</option>');
				count ++;
			}
		}
	}
	else if (!level){
		// add all (any level) routines from this comp to select
		var lastLevel="";
		for (i=1;i<routines.length-1;i++){
			if (routines[i][1] == comp){
				if (lastLevel != routines[i][0]){ // optgroup's arent closed but chrome didnt mind so fukid
					routineOptions +='<optgroup label="'+routines[i][0]+'">';
					lastLevel = routines[i][0];
				}
				routineOptions += ('<option value="'+i+'">'+routines[i][2]+'</option>');
				count++;
			}
		}
	}
	// add options to the select
	$('#routineSelect').html(routineOptions);
	
	// add text to first option
	 var optText = (count === 0)? "No Routines yet...": "Sample Routines..." +' ('+count+')';
	$('#routineSelect').children().eq(0).text(optText);		
}

/* When level radio button is changed, call the above function */
$("#levelSelect input").click(function(){
	populateRoutineSelect(this.value);
});

/* Save a routine to the database */
function saveRoutine(){
	errTxt = "Your routine can't be saved until:";
	if (linksOK === false)
		errTxt += "\n- All links are valid";
		
	// Make sure none of the skill's are empty
	var allSelected=true;
	for (var i=0;i<10;i++){
		if (selectedSkillsArray[i] === "")
			allSelected=false;
	}		
	if (allSelected === false)
		errTxt += "\n- All 10 skills are selected";		
	
	// Competition 
	var comp = $('#compSelect option:selected');
	var compName;
	if (comp.val() !== "")
		compName = comp.text();
	else
		errTxt += "\n- A competition is selected";
	
	//Level
	var level = $('#levelSelect input[type="radio"]:checked');
	var levelVal;
	if(level.length > 0)
		levelVal = level.val();
	else
		errTxt += "\n- A difficult level is selected";
	
	// Alright?
	if (errTxt != "Your routine can't be saved until:")
		alert(errTxt);
	else{		
		var name = prompt("Please enter a name for this routine. "+
			"\nFor example: 20YY - Front/Back");
		if (name === null) return false;
		
		// This bit of code is really compact. I like it but it's hard to read... Sucks for you :P
		for (i=0;i<10;i++, str += skills[ selectedSkillsArray[i] ][1]){
			str += (shapeArray[i] !== "")? ' ('+shapeArray[i]+'),': ',';
		} var newRoutine = encodeURIComponent(str);
		
		$.ajax({
			type: "GET",
			url: "/newer_tariff.php",
			data: "name="+name+"&comp="+compName+"&level="+levelVal+"&routine="+newRoutine,
			dataType: "text",
			success: function(data){
				alert(data);
			}
		});
	}
}