// Set global variables to say if user is logged in and if the login tab is expanded
var loggedin = (readCookie('user') != null)? true: false
var loginShow = (readCookie('loginShow') == "show")? true: false

$(document).ready(function(){
	// When page loads, show whatever's appropriate
	if (loginShow){
		if (loggedin){
			$('#loginlinks').show()	// show committee section links when logged in
			$('#logintab').css('position', 'fixed') // tab is fixed to the top right when logged in
		}
		else{ // if logged out, show login form
			$('#loginform').show()
		}
	}
	
	// When the tab header is clicked, show or hide whatever's appropriate
	$('#loginheader').click(function(){
		if (loggedin){
			$('#loginlinks').slideToggle(function(){
				var showHide = $('#loginlinks').is(':visible')? "show" : "hide"
				createCookie('loginShow', showHide, 365) // save preference for a year
			})
		}
		else{ 
			$('#loginform').slideToggle(function(){
				var showHide = $('#loginform').is(':visible')? "show" : "hide"
				createCookie('loginShow', showHide, 1) // 1 day to expire quickly so not left open ages
			})
		}
	});
});

function checkLogin(){
	$.post( // shorthand jQuery $.ajax type:POST func call
		"http://www.ucdtramp.com/ajax_php/login.php", // post values from form inputs
		"user="+$('#commuser').val()+"&pass="+$('#commpass').val(),
		function(response){ // if successful, hide form, show links. else show error
			console.log(response);
			if (response == 1){
				
				$('#loginform').hide()
				$('#loginlinks').show()
				$('#loginusername').text(readCookie('user')) // show capitalise username in logintab 				
				$('#logintab').css('position', 'fixed') // fix tab to top right of screen
				loggedin = true;
			}
			else if (response == 2){
				$('#loginlabel').html('Details incorrect!')}
			else if (response == 3){
				$('#loginlabel').html('Please fill in all fields')} 
			else {
				$('#loginlabel').html(response)}
		}
	);
	return false;
};

function updateLoginbox(){
	//update time of last post in committee forum (forum 2)
	lastposttime(2);
	
	//true if page.php script. Adds last edittime from db
	if (typeof addLastEditTime == 'function')
		addLastEditTime();
};