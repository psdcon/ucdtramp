//this function takes edited content from textarea and sends it to the editpage.php script.
function updatePage(container){
	$.ajax({
		type:'POST',
		url:'http://www.ucdtramp.com/ajax_php/editpage.php',  
		data:'action=pageUpdate&new_content='+ encodeURIComponent(document.getElementById('new_content').value)+'&pageurl='+(document.getElementById('pageurl').value),
		success:function(response){ //when successful
			if(response==1){
				makeNormal();
			   }
			else {$('#pagecontent').html('This message hasnt been saved to the server: <br><br> Error: '+response+'<br><br>');}			
		} 
	});
};
//edit page link calls this function
//given the content inside database entry which was loaded as html and is given the name content here
//give the page name which is the url and is sent by php 
function makeEditable(container,pagename){
	var content = container.innerHTML;
	
	while(container.hasChildNodes()){
		container.removeChild(container.firstChild);
	}
	$boxHeight=window.innerHeight*.8 +'px'
	
//create a form
	content_form = document.createElement('form');
	content_form.action='';//document.location;
	content_form.method='POST';
	content_form.id='content_form';
	content_form.onsubmit=function () { updatePage();return false; }
//making submit button for form
	content_save = document.createElement('input');
	content_save.type='submit';
	content_save.value='Save Changes';
	content_save.onclick=function () { updatePage();return false; } 
//after container(page content has been updated and save changes clicked update page function is called.

//making textarea inside form
	content_textarea = document.createElement('textarea');
	content_textarea.value=content;
	content_textarea.style.width='100%';
	content_textarea.style.height=$boxHeight;
	content_textarea.id='new_content';
//hidden input in form to hold pageurl
	content_pageurl = document.createElement('input');
	content_pageurl.type='hidden';
	content_pageurl.value=pagename;
	content_pageurl.id='pageurl';
//close all
	content_form.appendChild(content_textarea);
	content_form.appendChild(content_save);
	content_form.appendChild(content_pageurl);
	container.appendChild(content_form);
}

function makeNormal() {
	container = document.getElementById('pagecontent');
	content = document.getElementById('new_content').value;
	while(container.hasChildNodes()){
		container.removeChild(container.firstChild);
	}

	container.innerHTML = content;
}