//Gets the last post times and assigns each to their respective spans
function lastposttime (forum) { 
	$.ajax({
		type: "GET",
		url: "http://www.ucdtramp.com/ajax_php/lastposttime.php",
		data: "forum="+forum,
		dataType: "text",
		success: function(data){
				if(forum==1){
					$('#lastposttime1h').text(data);
					$('#lastposttime1v').text(data);					
				}
				else
					$('#lastposttime2').text(data);
			}
	});
}
setInterval( "lastposttime(1)", 60000 ); //update post time every min 
setInterval( "updateLoginbox()", 60000 ); //update committe forum time every min

$(document).ready(function(){ //show the login box and last post time as soon as page loads
	lastposttime(1);
	updateLoginbox();
});

//Display and ajax call for bugbox
$(document).ready(function(){
	$('#bugspan').click(function() {
		$('#bugbox form').slideToggle('slow');
		$('#loveyou').hide();
	});
});
function toWebmaster(){
	$('#bugbutton').text("I'm hearin' ya..");
	$.ajax({
		type: "GET",
		url: "http://www.ucdtramp.com/ajax_php/bugbox.db.php",
		data: "action=send&pgtitle="+$('#pgtitle').val()+"&message="+$('#bugs').val(),
		dataType: "text",
		success: function(data){
				if(data==2){
					$('#loveyou').text('Put something in the box first you eager Eimear');		
					$('#loveyou').slideToggle('slow');
					$('#bugbutton').text("Actually tell Paul");					
				}
				else{
					$('#bugs').val('');
					$('#bugbox form').hide('slow');
					$('#loveyou').slideToggle('slow');
					$('#loveyou').html('Thank you so very much! <i class="fa fa-heart"></i>');
					$('#bugbutton').text('Tell Paul');
					console.log(data);
				}
			}
	});
};

// Activate Konami code
var easter_egg = new Konami(function() { $('#spidey').show(); });

// Confirmation box for when items of class 'delete' are clicked
function areYouSure(){
	if(confirm("Are you SURE....?")){
	   return true;
	} else {
		event.preventDefault();
		return false;
	}
};

// Cookie Code from http://www.quirksmode.org/js/cookies.html
function createCookie(name,value,days) {
	if (days) {
		var date = new Date();
		date.setTime(date.getTime()+(days*24*60*60*1000));
		var expires = "; expires="+date.toGMTString();
	}
	else var expires = "";
	document.cookie = name+"="+value+expires+"; path=/";
}

function readCookie(name) {
	var nameEQ = name + "=";
	var ca = document.cookie.split(';');
	for(var i=0;i < ca.length;i++) {
		var c = ca[i];
		while (c.charAt(0)==' ') c = c.substring(1,c.length);
		if (c.indexOf(nameEQ) == 0) return c.substring(nameEQ.length,c.length);
	}
	return null;
}

function deleteCookie(name) {
	createCookie(name,"",-1);
}