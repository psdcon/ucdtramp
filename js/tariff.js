// Trampolining tariff calculator
//  2004 Barry Wardell
// If you want to use this calculator on your own site, you are free to
// do so, but must include this copyright notice. 
// Please also email me first at: barry dot wardell at gmail dot com

// Total number of skills
var num_skills = 136;

// Skill Descriptions as shown in dropdown box
var skill_desc = new Array(num_skills-1);

// 2004 tariffs for each skill
var skill_tariff = new Array(num_skills-1);	

// Load up array skill_desc
skill_desc[0] = "1/2 In 1/2 Out Tiffus (Pike)"
skill_desc[1] = "1/2 In 1/2 Out Tiffus (Tuck)"
skill_desc[2] = "1/2 In Rudi Out Tiffus (Pike)"
skill_desc[3] = "1/2 In Rudi Out Tiffus (Tuck)"
skill_desc[4] = "1/2 Out Tiffus (Pike)"
skill_desc[5] = "1/2 Out Tiffus (Tuck)"
skill_desc[6] = "1/2 In - 1/2 Out (Pike)"
skill_desc[7] = "1/2 In - 1/2 Out (Straight)"
skill_desc[8] = "1/2 In - 1/2 Out (Tuck)"
skill_desc[9] = "1/2 In - Back Out (Pike)"
skill_desc[10] = "1/2 In - Back Out (Straight)"
skill_desc[11] = "1/2 In - Back Out (Tuck)"
skill_desc[12] = "1/2 In - Randy Out (Pike)"
skill_desc[13] = "1/2 In - Randy Out (Straight)"
skill_desc[14] = "1/2 In - Randy Out (Tuck)"
skill_desc[15] = "1/2 In - Rudi Out (Pike)"
skill_desc[16] = "1/2 In - Rudi Out (Straight)"
skill_desc[17] = "1/2 In - Rudi Out (Tuck)"
skill_desc[18] = "1/2 Out (Pike)"
skill_desc[19] = "1/2 Out (Straight)"
skill_desc[20] = "1/2 Out (Tuck)"
skill_desc[21] = "1/2 Out Quad (Tuck)"
skill_desc[22] = "1/2 Twist Jump"
skill_desc[23] = "1/2 Twist to Back Drop"
skill_desc[24] = "1/2 Twist to Crash Dive"
skill_desc[25] = "1/2 Twist to Feet (from front)"
skill_desc[26] = "1/2 Twist to Feet (from back)"
skill_desc[27] = "1/2 Twist to Feet (from seat)"
skill_desc[28] = "1/2 Twist to Front Drop"
skill_desc[29] = "1/2 Twist to Seat Drop"
skill_desc[30] = "1/2 Turnover"
skill_desc[31] = "1 Twist Jump"
skill_desc[32] = "1 3/4 Front S/S (Pike)"
skill_desc[33] = "1 3/4 Front S/S (Straight)"
skill_desc[34] = "1 3/4 Front S/S (Tuck)"
skill_desc[35] = "2 3/4 Front S/S (Pike)"
skill_desc[36] = "2 3/4 Front S/S (Straight)"
skill_desc[37] = "2 3/4 Front S/S (Tuck)"
skill_desc[38] = "Adolph"
skill_desc[39] = "Baby Fliffus"
skill_desc[40] = "Back Drop"
skill_desc[41] = "Back In - Full Out (Pike)"
skill_desc[42] = "Back In - Full Out (Straight)"
skill_desc[43] = "Back In - Full Out (Tuck)"
skill_desc[44] = "Back Pullover to Feet"
skill_desc[45] = "Back S/S (Pike)"
skill_desc[46] = "Back S/S (Straight)"
skill_desc[47] = "Back S/S (Tuck)"
skill_desc[48] = "Back S/S to Seat (Pike)"
skill_desc[49] = "Back S/S to Seat (Straight)"
skill_desc[50] = "Back S/S to Seat (Tuck)"
skill_desc[51] = "Back to Feet"
skill_desc[52] = "Ball Out"
skill_desc[53] = "Ball Out - 1/2 Out (Pike)"
skill_desc[54] = "Ball Out - 1/2 Out (Straight)"
skill_desc[55] = "Ball Out - 1/2 Out (Tuck)"
skill_desc[56] = "Ball Out - Adolf"
skill_desc[57] = "Ball Out - Barani"
skill_desc[58] = "Ball Out - Randy"
skill_desc[59] = "Ball Out - Rudy"
skill_desc[60] = "Barani (Pike)"
skill_desc[61] = "Barani (Straight)"
skill_desc[62] = "Barani (Tuck)"
skill_desc[63] = "Barani In - Back Out"
skill_desc[64] = "Barani Out"
skill_desc[65] = "Barrel Roll"
skill_desc[66] = "Bounce-Roll (Pike)"
skill_desc[67] = "Bounce-Roll (Straight)"
skill_desc[68] = "Bounce-Roll (Tuck)"
skill_desc[69] = "Cat Twist"
skill_desc[70] = "Cody (Pike)"
skill_desc[71] = "Cody (Straight)"
skill_desc[72] = "Cody (Tuck)"
skill_desc[73] = "Corkscrew"
skill_desc[74] = "Cradle"
skill_desc[75] = "Crash Dive"
skill_desc[76] = "Double Back (Pike)"
skill_desc[77] = "Double Back (Straight)"
skill_desc[78] = "Double Back (Tuck)"
skill_desc[79] = "Double Bounce-Roll (Pike)"
skill_desc[80] = "Double Bounce-Roll (Straight)"
skill_desc[81] = "Double Bounce-Roll (Tuck)"
skill_desc[82] = "Double Full"
skill_desc[83] = "Front Drop"
skill_desc[84] = "Front S/S (Pike)"
skill_desc[85] = "Front S/S (Straight)"
skill_desc[86] = "Front S/S (Tuck)"
skill_desc[87] = "Front to Feet"
skill_desc[88] = "Full"
skill_desc[89] = "Full In - 1/2 Out (Pike)"
skill_desc[90] = "Full In - 1/2 Out (Straight)"
skill_desc[91] = "Full In - 1/2 Out (Tuck)"
skill_desc[92] = "Full In - Back Out (Pike)"
skill_desc[93] = "Full In - Back Out (Straight)"
skill_desc[94] = "Full In - Back Out (Tuck)"
skill_desc[95] = "Full In - Double Full Out (Pike)"
skill_desc[96] = "Full In - Double Full Out (Straight)"
skill_desc[97] = "Full In - Double Full Out (Tuck)"
skill_desc[98] = "Full In - Full Out (Pike)"
skill_desc[99] = "Full In - Full Out (Straight)"
skill_desc[100] = "Full In - Full Out (Tuck)"
skill_desc[101] = "Full In - Rudi Out (Pike)"
skill_desc[102] = "Full In - Rudi Out (Straight)"
skill_desc[103] = "Full In - Rudi Out (Tuck)"
skill_desc[104] = "Full Out (Pike)"
skill_desc[105] = "Full Out (Straight)"
skill_desc[106] = "Full Out (Tuck)"
skill_desc[107] = "Full Twist Jump"
skill_desc[108] = "Full Twist to Feet (from seat)"
skill_desc[109] = "Full Twist to Feet (from back/front)"
skill_desc[110] = "Lazy Back"
skill_desc[111] = "Log Roll"
skill_desc[112] = "Miller (Pike)"
skill_desc[113] = "Miller (Straight)"
skill_desc[114] = "Miller (Tuck)"
skill_desc[115] = "Piked Jump"
skill_desc[116] = "Poliarush (Pike)"
skill_desc[117] = "Poliarush (Straight)"
skill_desc[118] = "Poliarush (Tuck)"
skill_desc[119] = "Randolph/ Randy"
skill_desc[120] = "Randy Out (Pike)"
skill_desc[121] = "Randy Out (Tuck)"
skill_desc[122] = "Roller"
skill_desc[123] = "Rudi Out (Pike)"
skill_desc[124] = "Rudi Out (Straight)"
skill_desc[125] = "Rudi Out (Tuck)"
skill_desc[126] = "Rudi Out Triffus (Pike)"
skill_desc[127] = "Rudi Out Triffus (Tuck)"
skill_desc[128] = "Rudolph/Rudi"
skill_desc[129] = "Seat 1/2 Twist to Seat Drop"
skill_desc[130] = "Seat Drop"
skill_desc[131] = "Seat to Feet"
skill_desc[132] = "Straddle Jump"
skill_desc[133] = "Straight Jump"
skill_desc[134] = "Triffus (Pike)"
skill_desc[135] = "Tuck Jump"

skill_tariff[0] = 1.9
skill_tariff[1] = 1.7
skill_tariff[2] = 2.1
skill_tariff[3] = 1.9
skill_tariff[4] = 1.8
skill_tariff[5] = 1.6
skill_tariff[6] = 1.4
skill_tariff[7] = 1.4
skill_tariff[8] = 1.2
skill_tariff[9] = 1.3
skill_tariff[10] = 1.3
skill_tariff[11] = 1.1
skill_tariff[12] = 1.8
skill_tariff[13] = 1.8
skill_tariff[14] = 1.6
skill_tariff[15] = 1.6
skill_tariff[16] = 1.6
skill_tariff[17] = 1.4
skill_tariff[18] = 1.3
skill_tariff[19] = 1.3
skill_tariff[20] = 1.1
skill_tariff[21] = 2.1
skill_tariff[22] = 0.1
skill_tariff[23] = 0.2
skill_tariff[24] = 0.4
skill_tariff[25] = 0.2
skill_tariff[26] = 0.2
skill_tariff[27] = 0.1
skill_tariff[28] = 0.2
skill_tariff[29] = 0.1
skill_tariff[30] = 0.3
skill_tariff[31] = 0.3
skill_tariff[32] = 0.9
skill_tariff[33] = 0.9
skill_tariff[34] = 0.8
skill_tariff[35] = 1.5
skill_tariff[36] = 1.5
skill_tariff[37] = 1.3
skill_tariff[38] = 1.2
skill_tariff[39] = 0.7
skill_tariff[40] = 0.1
skill_tariff[41] = 1.4
skill_tariff[42] = 1.4
skill_tariff[43] = 1.2
skill_tariff[44] = 0.3
skill_tariff[45] = 0.6
skill_tariff[46] = 0.6
skill_tariff[47] = 0.5
skill_tariff[48] = 0.6
skill_tariff[49] = 0.6
skill_tariff[50] = 0.5
skill_tariff[51] = 0.1
skill_tariff[52] = 0.6
skill_tariff[53] = 1.4
skill_tariff[54] = 1.4
skill_tariff[55] = 1.2
skill_tariff[56] = 1.3
skill_tariff[57] = 0.7
skill_tariff[58] = 1.1
skill_tariff[59] = 0.9
skill_tariff[60] = 0.6
skill_tariff[61] = 0.6
skill_tariff[62] = 0.6
skill_tariff[63] = 1.1
skill_tariff[64] = 1.1
skill_tariff[65] = 0.2
skill_tariff[66] = 0.6
skill_tariff[67] = 0.6
skill_tariff[68] = 0.5
skill_tariff[69] = 0.2
skill_tariff[70] = 0.7
skill_tariff[71] = 0.7
skill_tariff[72] = 0.6
skill_tariff[73] = 0.5
skill_tariff[74] = 0.3
skill_tariff[75] = 0.3
skill_tariff[76] = 1.2
skill_tariff[77] = 1.2
skill_tariff[78] = 1.0
skill_tariff[89] = 1.2
skill_tariff[80] = 1.2
skill_tariff[81] = 1.0
skill_tariff[82] = 0.9
skill_tariff[83] = 0.1
skill_tariff[84] = 0.6
skill_tariff[85] = 0.6
skill_tariff[86] = 0.5
skill_tariff[87] = 0.1
skill_tariff[88] = 0.7
skill_tariff[89] = 1.5
skill_tariff[90] = 1.5
skill_tariff[91] = 1.3
skill_tariff[92] = 1.4
skill_tariff[93] = 1.4
skill_tariff[94] = 1.2
skill_tariff[95] = 1.8
skill_tariff[96] = 1.8
skill_tariff[97] = 1.6
skill_tariff[98] = 1.6
skill_tariff[99] = 1.6
skill_tariff[100] = 1.4
skill_tariff[101] = 1.7
skill_tariff[102] = 1.7
skill_tariff[103] = 1.5
skill_tariff[104] = 1.4
skill_tariff[105] = 1.4
skill_tariff[106] = 1.2
skill_tariff[107] = 0.2
skill_tariff[108] = 0.2
skill_tariff[109] = 0.3
skill_tariff[110] = 0.3
skill_tariff[111] = 0.2
skill_tariff[112] = 1.8
skill_tariff[113] = 1.8
skill_tariff[114] = 1.6
skill_tariff[115] = 0.0
skill_tariff[116] = 2.0
skill_tariff[117] = 2.0
skill_tariff[118] = 1.8
skill_tariff[119] = 1.0
skill_tariff[120] = 1.7
skill_tariff[121] = 1.5
skill_tariff[122] = 0.2
skill_tariff[123] = 1.5
skill_tariff[124] = 1.5
skill_tariff[125] = 1.3
skill_tariff[126] = 2.0
skill_tariff[127] = 1.8
skill_tariff[128] = 0.8
skill_tariff[129] = 0.1
skill_tariff[130] = 0.0
skill_tariff[131] = 0.0
skill_tariff[132] = 0.0
skill_tariff[133] = 0.0
skill_tariff[134] = 1.8
skill_tariff[135] = 0.0


// This is executed each time a new move is selected. It updates the tariffs and the total
function change_move()
{
	// Calculate individual move tariffs
	document.tariffForm.tariff1.value = document.tariffForm.skill1.value;
	document.tariffForm.tariff2.value = (document.tariffForm.skill2.selectedIndex==document.tariffForm.skill1.selectedIndex)
	                                    ?'repeat':document.tariffForm.skill2.value;
	document.tariffForm.tariff3.value = (document.tariffForm.skill3.selectedIndex==document.tariffForm.skill1.selectedIndex)||
	                                    (document.tariffForm.skill3.selectedIndex==document.tariffForm.skill2.selectedIndex)
	                                    ?'repeat':document.tariffForm.skill3.value;
	document.tariffForm.tariff4.value = (document.tariffForm.skill4.selectedIndex==document.tariffForm.skill1.selectedIndex)||
	                                    (document.tariffForm.skill4.selectedIndex==document.tariffForm.skill2.selectedIndex)||
	                                    (document.tariffForm.skill4.selectedIndex==document.tariffForm.skill3.selectedIndex)
	                                    ?'repeat':document.tariffForm.skill4.value;
	document.tariffForm.tariff5.value = (document.tariffForm.skill5.selectedIndex==document.tariffForm.skill1.selectedIndex)||
	                                    (document.tariffForm.skill5.selectedIndex==document.tariffForm.skill2.selectedIndex)||
	                                    (document.tariffForm.skill5.selectedIndex==document.tariffForm.skill3.selectedIndex)||
	                                    (document.tariffForm.skill5.selectedIndex==document.tariffForm.skill4.selectedIndex)
	                                    ?'repeat':document.tariffForm.skill5.value;
	document.tariffForm.tariff6.value = (document.tariffForm.skill6.selectedIndex==document.tariffForm.skill1.selectedIndex)||
	                                    (document.tariffForm.skill6.selectedIndex==document.tariffForm.skill2.selectedIndex)||
	                                    (document.tariffForm.skill6.selectedIndex==document.tariffForm.skill3.selectedIndex)||
	                                    (document.tariffForm.skill6.selectedIndex==document.tariffForm.skill4.selectedIndex)||
	                                    (document.tariffForm.skill6.selectedIndex==document.tariffForm.skill5.selectedIndex)
	                                    ?'repeat':document.tariffForm.skill6.value;
	document.tariffForm.tariff7.value = (document.tariffForm.skill7.selectedIndex==document.tariffForm.skill1.selectedIndex)||
	                                    (document.tariffForm.skill7.selectedIndex==document.tariffForm.skill2.selectedIndex)||
	                                    (document.tariffForm.skill7.selectedIndex==document.tariffForm.skill3.selectedIndex)||
	                                    (document.tariffForm.skill7.selectedIndex==document.tariffForm.skill4.selectedIndex)||
	                                    (document.tariffForm.skill7.selectedIndex==document.tariffForm.skill5.selectedIndex)||
	                                    (document.tariffForm.skill7.selectedIndex==document.tariffForm.skill6.selectedIndex)
	                                    ?'repeat':document.tariffForm.skill7.value;
	document.tariffForm.tariff8.value = (document.tariffForm.skill8.selectedIndex==document.tariffForm.skill1.selectedIndex)||
	                                    (document.tariffForm.skill8.selectedIndex==document.tariffForm.skill2.selectedIndex)||
	                                    (document.tariffForm.skill8.selectedIndex==document.tariffForm.skill3.selectedIndex)||
	                                    (document.tariffForm.skill8.selectedIndex==document.tariffForm.skill4.selectedIndex)||
	                                    (document.tariffForm.skill8.selectedIndex==document.tariffForm.skill5.selectedIndex)||
	                                    (document.tariffForm.skill8.selectedIndex==document.tariffForm.skill6.selectedIndex)||
	                                    (document.tariffForm.skill8.selectedIndex==document.tariffForm.skill7.selectedIndex)
	                                    ?'repeat':document.tariffForm.skill8.value;
	document.tariffForm.tariff9.value = (document.tariffForm.skill9.selectedIndex==document.tariffForm.skill1.selectedIndex)||
	                                    (document.tariffForm.skill9.selectedIndex==document.tariffForm.skill2.selectedIndex)||
	                                    (document.tariffForm.skill9.selectedIndex==document.tariffForm.skill3.selectedIndex)||
	                                    (document.tariffForm.skill9.selectedIndex==document.tariffForm.skill4.selectedIndex)||
	                                    (document.tariffForm.skill9.selectedIndex==document.tariffForm.skill5.selectedIndex)||
	                                    (document.tariffForm.skill9.selectedIndex==document.tariffForm.skill6.selectedIndex)||
	                                    (document.tariffForm.skill9.selectedIndex==document.tariffForm.skill7.selectedIndex)||
	                                    (document.tariffForm.skill9.selectedIndex==document.tariffForm.skill8.selectedIndex)
	                                    ?'repeat':document.tariffForm.skill9.value;
	document.tariffForm.tariff10.value =(document.tariffForm.skill10.selectedIndex==document.tariffForm.skill1.selectedIndex)||
	                                    (document.tariffForm.skill10.selectedIndex==document.tariffForm.skill2.selectedIndex)||
	                                    (document.tariffForm.skill10.selectedIndex==document.tariffForm.skill3.selectedIndex)||
	                                    (document.tariffForm.skill10.selectedIndex==document.tariffForm.skill4.selectedIndex)||
	                                    (document.tariffForm.skill10.selectedIndex==document.tariffForm.skill5.selectedIndex)||
	                                    (document.tariffForm.skill10.selectedIndex==document.tariffForm.skill6.selectedIndex)||
	                                    (document.tariffForm.skill10.selectedIndex==document.tariffForm.skill7.selectedIndex)||
	                                    (document.tariffForm.skill10.selectedIndex==document.tariffForm.skill8.selectedIndex)||
	                                    (document.tariffForm.skill10.selectedIndex==document.tariffForm.skill9.selectedIndex)
	                                    ?'repeat':document.tariffForm.skill10.value;
	
	// Calculate and update total tariff
	document.tariffForm.total.value = skill_tariff[document.tariffForm.skill1.selectedIndex] +
										
										((document.tariffForm.skill2.selectedIndex==document.tariffForm.skill1.selectedIndex)
	                                    ?0.0:skill_tariff[document.tariffForm.skill2.selectedIndex]) +
										
										((document.tariffForm.skill3.selectedIndex==document.tariffForm.skill1.selectedIndex)||
	                                    (document.tariffForm.skill3.selectedIndex==document.tariffForm.skill2.selectedIndex)
	                                    ?0.0:skill_tariff[document.tariffForm.skill3.selectedIndex]) +
												
										((document.tariffForm.skill4.selectedIndex==document.tariffForm.skill1.selectedIndex)||
	                                    (document.tariffForm.skill4.selectedIndex==document.tariffForm.skill2.selectedIndex)||
	                                    (document.tariffForm.skill4.selectedIndex==document.tariffForm.skill3.selectedIndex)
	                                    ?0.0:skill_tariff[document.tariffForm.skill4.selectedIndex]) +
												
										((document.tariffForm.skill5.selectedIndex==document.tariffForm.skill1.selectedIndex)||
	                                    (document.tariffForm.skill5.selectedIndex==document.tariffForm.skill2.selectedIndex)||
	                                    (document.tariffForm.skill5.selectedIndex==document.tariffForm.skill3.selectedIndex)||
	                                    (document.tariffForm.skill5.selectedIndex==document.tariffForm.skill4.selectedIndex)
	                                    ?0.0:skill_tariff[document.tariffForm.skill5.selectedIndex]) +
										
										((document.tariffForm.skill6.selectedIndex==document.tariffForm.skill1.selectedIndex)||
	                                    (document.tariffForm.skill6.selectedIndex==document.tariffForm.skill2.selectedIndex)||
	                                    (document.tariffForm.skill6.selectedIndex==document.tariffForm.skill3.selectedIndex)||
	                                    (document.tariffForm.skill6.selectedIndex==document.tariffForm.skill4.selectedIndex)||
	                                    (document.tariffForm.skill6.selectedIndex==document.tariffForm.skill5.selectedIndex)
	                                    ?0.0:skill_tariff[document.tariffForm.skill6.selectedIndex]) +
												
										((document.tariffForm.skill7.selectedIndex==document.tariffForm.skill1.selectedIndex)||
	                                    (document.tariffForm.skill7.selectedIndex==document.tariffForm.skill2.selectedIndex)||
	                                    (document.tariffForm.skill7.selectedIndex==document.tariffForm.skill3.selectedIndex)||
	                                    (document.tariffForm.skill7.selectedIndex==document.tariffForm.skill4.selectedIndex)||
	                                    (document.tariffForm.skill7.selectedIndex==document.tariffForm.skill5.selectedIndex)||
	                                    (document.tariffForm.skill7.selectedIndex==document.tariffForm.skill6.selectedIndex)
	                                    ?0.0:skill_tariff[document.tariffForm.skill7.selectedIndex]) +
										
										((document.tariffForm.skill8.selectedIndex==document.tariffForm.skill1.selectedIndex)||
	                                    (document.tariffForm.skill8.selectedIndex==document.tariffForm.skill2.selectedIndex)||
	                                    (document.tariffForm.skill8.selectedIndex==document.tariffForm.skill3.selectedIndex)||
	                                    (document.tariffForm.skill8.selectedIndex==document.tariffForm.skill4.selectedIndex)||
	                                    (document.tariffForm.skill8.selectedIndex==document.tariffForm.skill5.selectedIndex)||
	                                    (document.tariffForm.skill8.selectedIndex==document.tariffForm.skill6.selectedIndex)||
	                                    (document.tariffForm.skill8.selectedIndex==document.tariffForm.skill7.selectedIndex)
	                                    ?0.0:skill_tariff[document.tariffForm.skill8.selectedIndex]) +
										
										((document.tariffForm.skill9.selectedIndex==document.tariffForm.skill1.selectedIndex)||
	                                    (document.tariffForm.skill9.selectedIndex==document.tariffForm.skill2.selectedIndex)||
	                                    (document.tariffForm.skill9.selectedIndex==document.tariffForm.skill3.selectedIndex)||
	                                    (document.tariffForm.skill9.selectedIndex==document.tariffForm.skill4.selectedIndex)||
	                                    (document.tariffForm.skill9.selectedIndex==document.tariffForm.skill5.selectedIndex)||
	                                    (document.tariffForm.skill9.selectedIndex==document.tariffForm.skill6.selectedIndex)||
	                                    (document.tariffForm.skill9.selectedIndex==document.tariffForm.skill7.selectedIndex)||
	                                    (document.tariffForm.skill9.selectedIndex==document.tariffForm.skill8.selectedIndex)
	                                    ?0.0:skill_tariff[document.tariffForm.skill9.selectedIndex]) +
										
										((document.tariffForm.skill10.selectedIndex==document.tariffForm.skill1.selectedIndex)||
	                                    (document.tariffForm.skill10.selectedIndex==document.tariffForm.skill2.selectedIndex)||
	                                    (document.tariffForm.skill10.selectedIndex==document.tariffForm.skill3.selectedIndex)||
	                                    (document.tariffForm.skill10.selectedIndex==document.tariffForm.skill4.selectedIndex)||
	                                    (document.tariffForm.skill10.selectedIndex==document.tariffForm.skill5.selectedIndex)||
	                                    (document.tariffForm.skill10.selectedIndex==document.tariffForm.skill6.selectedIndex)||
	                                    (document.tariffForm.skill10.selectedIndex==document.tariffForm.skill7.selectedIndex)||
	                                    (document.tariffForm.skill10.selectedIndex==document.tariffForm.skill8.selectedIndex)||
	                                    (document.tariffForm.skill10.selectedIndex==document.tariffForm.skill9.selectedIndex)
	                                    ?0.0:skill_tariff[document.tariffForm.skill10.selectedIndex]);
	                                    
	document.tariffForm.total.value = Math.round(document.tariffForm.total.value*10.0)/10.0;
												
	// Set Routine to custom
	document.tariffForm.routine.value = 20;
}

// This ir executed when a new routine is selected.
// It updates all the skills and tariffs for the current routine.
function change_routine()
{
	// Currently selected routine
	routine = document.tariffForm.routine.value;
	
	// Update skills for selected routine
	switch(document.tariffForm.routine.value)
	{
	case "1":
		// Novice Routine
		document.tariffForm.skill1.selectedIndex = 83;
		document.tariffForm.skill2.selectedIndex = 87;
		document.tariffForm.skill3.selectedIndex = 132;
		document.tariffForm.skill4.selectedIndex = 22;
		document.tariffForm.skill5.selectedIndex = 130;
		document.tariffForm.skill6.selectedIndex = 27;
		document.tariffForm.skill7.selectedIndex = 135;
		document.tariffForm.skill8.selectedIndex = 115;
		document.tariffForm.skill9.selectedIndex = 40;
		document.tariffForm.skill10.selectedIndex = 26;
		break;
	case "2":
		// Intermediate Routine
		document.tariffForm.skill1.selectedIndex = 47;
		document.tariffForm.skill2.selectedIndex = 132;
		document.tariffForm.skill3.selectedIndex = 130;
		document.tariffForm.skill4.selectedIndex = 129;
		document.tariffForm.skill5.selectedIndex = 27;
		document.tariffForm.skill6.selectedIndex = 115;
		document.tariffForm.skill7.selectedIndex = 40;
		document.tariffForm.skill8.selectedIndex = 26;
		document.tariffForm.skill9.selectedIndex = 135;
		document.tariffForm.skill10.selectedIndex = 86;
		break;
	case "3":
		// Advanced Routine
		document.tariffForm.skill1.selectedIndex = 46;
		document.tariffForm.skill2.selectedIndex = 132;
		document.tariffForm.skill3.selectedIndex = 47;
		document.tariffForm.skill4.selectedIndex = 130;
		document.tariffForm.skill5.selectedIndex = 27;
		document.tariffForm.skill6.selectedIndex = 115;
		document.tariffForm.skill7.selectedIndex = 62;
		document.tariffForm.skill8.selectedIndex = 22;
		document.tariffForm.skill9.selectedIndex = 135;
		document.tariffForm.skill10.selectedIndex = 86;
		break;
	case "4":
		// Intervarsity Novice Routine 1
		document.tariffForm.skill1.selectedIndex = 83;
		document.tariffForm.skill2.selectedIndex = 87;
		document.tariffForm.skill3.selectedIndex = 132;
		document.tariffForm.skill4.selectedIndex = 22;
		document.tariffForm.skill5.selectedIndex = 130;
		document.tariffForm.skill6.selectedIndex = 27;
		document.tariffForm.skill7.selectedIndex = 135;
		document.tariffForm.skill8.selectedIndex = 115;
		document.tariffForm.skill9.selectedIndex = 130;
		document.tariffForm.skill10.selectedIndex = 27;
		break;
	case "5":
		// Intervarsity Novice Routine 2
		document.tariffForm.skill1.selectedIndex = 40;
		document.tariffForm.skill2.selectedIndex = 87;
		document.tariffForm.skill3.selectedIndex = 132;
		document.tariffForm.skill4.selectedIndex = 22;
		document.tariffForm.skill5.selectedIndex = 130;
		document.tariffForm.skill6.selectedIndex = 27;
		document.tariffForm.skill7.selectedIndex = 135;
		document.tariffForm.skill8.selectedIndex = 115;
		document.tariffForm.skill9.selectedIndex = 130;
		document.tariffForm.skill10.selectedIndex = 27;
		break;
	case "6":
		// Intervarsity Intermediate Routine 1
		document.tariffForm.skill1.selectedIndex = 107;
		document.tariffForm.skill2.selectedIndex = 132;
		document.tariffForm.skill3.selectedIndex = 130;
		document.tariffForm.skill4.selectedIndex = 129;
		document.tariffForm.skill5.selectedIndex = 27;
		document.tariffForm.skill6.selectedIndex = 115;
		document.tariffForm.skill7.selectedIndex = 40;
		document.tariffForm.skill8.selectedIndex = 26;
		document.tariffForm.skill9.selectedIndex = 135;
		document.tariffForm.skill10.selectedIndex = 86;
		break;
	case "7":
		// Intervarsity Intermediate Routine 2
		document.tariffForm.skill1.selectedIndex = 47;
		document.tariffForm.skill2.selectedIndex = 132;
		document.tariffForm.skill3.selectedIndex = 130;
		document.tariffForm.skill4.selectedIndex = 129;
		document.tariffForm.skill5.selectedIndex = 27;
		document.tariffForm.skill6.selectedIndex = 115;
		document.tariffForm.skill7.selectedIndex = 40;
		document.tariffForm.skill8.selectedIndex = 26;
		document.tariffForm.skill9.selectedIndex = 135;
		document.tariffForm.skill10.selectedIndex = 107;
		break;
	case "8":
		// Intervarsity Advanced Routine
		document.tariffForm.skill1.selectedIndex = 46;
		document.tariffForm.skill2.selectedIndex = 132;
		document.tariffForm.skill3.selectedIndex = 47;
		document.tariffForm.skill4.selectedIndex = 130;
		document.tariffForm.skill5.selectedIndex = 27;
		document.tariffForm.skill6.selectedIndex = 115;
		document.tariffForm.skill7.selectedIndex = 62;
		document.tariffForm.skill8.selectedIndex = 22;
		document.tariffForm.skill9.selectedIndex = 135;
		document.tariffForm.skill10.selectedIndex = 84;
		break;
	case "9":
		// Colours Novice Routine 1
		document.tariffForm.skill1.selectedIndex = 40;
		document.tariffForm.skill2.selectedIndex = 51;
		document.tariffForm.skill3.selectedIndex = 135;
		document.tariffForm.skill4.selectedIndex = 130;
		document.tariffForm.skill5.selectedIndex = 27;
		document.tariffForm.skill6.selectedIndex = 22;
		document.tariffForm.skill7.selectedIndex = 132;
		document.tariffForm.skill8.selectedIndex = 83;
		document.tariffForm.skill9.selectedIndex = 87;
		document.tariffForm.skill10.selectedIndex = 107;
		break;
	case "10":
		// Colours Novice Routine 2
		document.tariffForm.skill1.selectedIndex = 83;
		document.tariffForm.skill2.selectedIndex = 87;
		document.tariffForm.skill3.selectedIndex = 135;
		document.tariffForm.skill4.selectedIndex = 107;
		document.tariffForm.skill5.selectedIndex = 132;
		document.tariffForm.skill6.selectedIndex = 130;
		document.tariffForm.skill7.selectedIndex = 27;
		document.tariffForm.skill8.selectedIndex = 115;
		document.tariffForm.skill9.selectedIndex = 40;
		document.tariffForm.skill10.selectedIndex = 51;
		break;
	case "11":
		// Colours Intermediate Routine 1
		document.tariffForm.skill1.selectedIndex = 107;
		document.tariffForm.skill2.selectedIndex = 132;
		document.tariffForm.skill3.selectedIndex = 130;
		document.tariffForm.skill4.selectedIndex = 129;
		document.tariffForm.skill5.selectedIndex = 27;
		document.tariffForm.skill6.selectedIndex = 115;
		document.tariffForm.skill7.selectedIndex = 40;
		document.tariffForm.skill8.selectedIndex = 26;
		document.tariffForm.skill9.selectedIndex = 135;
		document.tariffForm.skill10.selectedIndex = 86;
		break;
	case "12":
		// Colours Intermediate Routine 2
		document.tariffForm.skill1.selectedIndex = 47;
		document.tariffForm.skill2.selectedIndex = 132;
		document.tariffForm.skill3.selectedIndex = 130;
		document.tariffForm.skill4.selectedIndex = 129;
		document.tariffForm.skill5.selectedIndex = 27;
		document.tariffForm.skill6.selectedIndex = 115;
		document.tariffForm.skill7.selectedIndex = 40;
		document.tariffForm.skill8.selectedIndex = 26;
		document.tariffForm.skill9.selectedIndex = 135;
		document.tariffForm.skill10.selectedIndex = 107;
		break;
	case "13":
		// Colours Advanced Routine
		document.tariffForm.skill1.selectedIndex = 46;
		document.tariffForm.skill2.selectedIndex = 132;
		document.tariffForm.skill3.selectedIndex = 47;
		document.tariffForm.skill4.selectedIndex = 130;
		document.tariffForm.skill5.selectedIndex = 27;
		document.tariffForm.skill6.selectedIndex = 115;
		document.tariffForm.skill7.selectedIndex = 62;
		document.tariffForm.skill8.selectedIndex = 22;
		document.tariffForm.skill9.selectedIndex = 135;
		document.tariffForm.skill10.selectedIndex = 86;
		break;
	case "14":
		// Scotland Novice Routine 1
		document.tariffForm.skill1.selectedIndex = 40;
		document.tariffForm.skill2.selectedIndex = 51;
		document.tariffForm.skill3.selectedIndex = 135;
		document.tariffForm.skill4.selectedIndex = 130;
		document.tariffForm.skill5.selectedIndex = 27;
		document.tariffForm.skill6.selectedIndex = 22;
		document.tariffForm.skill7.selectedIndex = 132;
		document.tariffForm.skill8.selectedIndex = 83;
		document.tariffForm.skill9.selectedIndex = 87;
		document.tariffForm.skill10.selectedIndex = 107;
		break;
	case "15":
		// Scotland Novice Routine 2
		document.tariffForm.skill1.selectedIndex = 83;
		document.tariffForm.skill2.selectedIndex = 87;
		document.tariffForm.skill3.selectedIndex = 135;
		document.tariffForm.skill4.selectedIndex = 107;
		document.tariffForm.skill5.selectedIndex = 132;
		document.tariffForm.skill6.selectedIndex = 130;
		document.tariffForm.skill7.selectedIndex = 27;
		document.tariffForm.skill8.selectedIndex = 115;
		document.tariffForm.skill9.selectedIndex = 40;
		document.tariffForm.skill10.selectedIndex = 51;
		break;
	case "16":
		// Scotland Intermediate Routine 1
		document.tariffForm.skill1.selectedIndex = 107;
		document.tariffForm.skill2.selectedIndex = 132;
		document.tariffForm.skill3.selectedIndex = 130;
		document.tariffForm.skill4.selectedIndex = 129;
		document.tariffForm.skill5.selectedIndex = 27;
		document.tariffForm.skill6.selectedIndex = 115;
		document.tariffForm.skill7.selectedIndex = 40;
		document.tariffForm.skill8.selectedIndex = 26;
		document.tariffForm.skill9.selectedIndex = 135;
		document.tariffForm.skill10.selectedIndex = 86;
		break;
	case "17":
		// Scotland Intermediate Routine 2
		document.tariffForm.skill1.selectedIndex = 47;
		document.tariffForm.skill2.selectedIndex = 132;
		document.tariffForm.skill3.selectedIndex = 130;
		document.tariffForm.skill4.selectedIndex = 129;
		document.tariffForm.skill5.selectedIndex = 27;
		document.tariffForm.skill6.selectedIndex = 115;
		document.tariffForm.skill7.selectedIndex = 40;
		document.tariffForm.skill8.selectedIndex = 26;
		document.tariffForm.skill9.selectedIndex = 135;
		document.tariffForm.skill10.selectedIndex = 107;
		break;
	case "18":
		// Scotland Advanced Routine
		document.tariffForm.skill1.selectedIndex = 46;
		document.tariffForm.skill2.selectedIndex = 132;
		document.tariffForm.skill3.selectedIndex = 45;
		document.tariffForm.skill4.selectedIndex = 130;
		document.tariffForm.skill5.selectedIndex = 27;
		document.tariffForm.skill6.selectedIndex = 115;
		document.tariffForm.skill7.selectedIndex = 62;
		document.tariffForm.skill8.selectedIndex = 22;
		document.tariffForm.skill9.selectedIndex = 135;
		document.tariffForm.skill10.selectedIndex = 86;
		break;
	case "19":
		// National Routine
		document.tariffForm.skill1.selectedIndex = 46;
		document.tariffForm.skill2.selectedIndex = 61;
		document.tariffForm.skill3.selectedIndex = 47;
		document.tariffForm.skill4.selectedIndex = 132;
		document.tariffForm.skill5.selectedIndex = 60;
		document.tariffForm.skill6.selectedIndex = 22;
		document.tariffForm.skill7.selectedIndex = 135;
		document.tariffForm.skill8.selectedIndex = 45;
		document.tariffForm.skill9.selectedIndex = 75;
		document.tariffForm.skill10.selectedIndex = 57;
		break;
	case "20":
		// Custom Routine
		break;
	}
	
	// Update tariffs for new routine
	change_move();
	
	// Don't want change_move() to change the routine to custom
	document.tariffForm.routine.value = routine;
}

// This loads the select boxes with all the skills and sets the tariffs
// It is run when the page is loaded
function load_skills ( skills, tariff ) {
	// Load all skills
	for(j=0;j<skill_desc.length;j++){
		document.tariffForm.skill1.options[j] = new Option(skills[j], tariff[j]);
		document.tariffForm.skill2.options[j] = new Option(skills[j], tariff[j]);
		document.tariffForm.skill3.options[j] = new Option(skills[j], tariff[j]);
		document.tariffForm.skill4.options[j] = new Option(skills[j], tariff[j]);
		document.tariffForm.skill5.options[j] = new Option(skills[j], tariff[j]);
		document.tariffForm.skill6.options[j] = new Option(skills[j], tariff[j]);
		document.tariffForm.skill7.options[j] = new Option(skills[j], tariff[j]);
		document.tariffForm.skill8.options[j] = new Option(skills[j], tariff[j]);
		document.tariffForm.skill9.options[j] = new Option(skills[j], tariff[j]);
		document.tariffForm.skill10.options[j] = new Option(skills[j], tariff[j]);
	}

	// Set routine to novice
	document.tariffForm.routine.value = 1;
	change_routine();
}

// Load skills into form
load_skills(skill_desc, skill_tariff)
