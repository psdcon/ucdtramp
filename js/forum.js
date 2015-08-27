$(document).ready(function(){ //show post box
    var postClicked=false;
    $("#forum_header #postbutton").click(function(){
        $("#secrets").hide('slide');
        $("#writepost").slideToggle();
        postClicked = (postClicked===false)? true : false;
        $("#postbutton").html(function(i, v){
            return postClicked === true ? 'Hide' : '<i class="fa fa-comment-o"></i> Post';
        });
    });

     //show secrets box
    var secretsClicked=false;
    $("#forum_header #secretsbutton").click(function(){
        $("#secrets").slideToggle();
        secretsClicked = (secretsClicked===false)? true: false;
        $("#secretsbutton").text(function(i, v){
            return secretsClicked === true ? 'Hide' : 'Secrets';
        });
    });
});

function getCount(id){
    $.ajax({
        type: "GET",
        url: "http://www.ucdtramp.com/ajax_php/plusone.php",
        data: "action=getCount&id="+id,
        dataType: "text",
        success: function(data){
                $('#message_count_text_id'+id).text(data);
            }
    });
}

function updateCount(id,thislike){
    if (!readCookie(id)){
        var likeimg=$(thislike).find('.likeimg');
        likeimg.attr('id', 'active');/*When the like img is clicked the containing anchor is given an id of active if the likecount doesnt change. #active in the stylesheet has transform properties which rotate the like 360. I cant make the rotation not wobble the white box which wilts me*/ 
                        
        var oldcookie = readCookie('ForumLikes');
        if (oldcookie!==null && oldcookie.length > 3000){
            oldcookie = oldcookie.substring(0,3000);
        }
        createCookie('ForumLikes', id+','+oldcookie, 365);
        
        $.ajax({
            type: "GET",
            url: "http://www.ucdtramp.com/ajax_php/plusone.php",
            data: "action=updateCount&id="+id,
            dataType: "text",
            success: function(data){
                    if(data!='same'){
                        $('#message_count_text_id'+id).text(data);
                    }   
                    likeimg.attr('src', 'http://www.ucdtramp.com/images/msc/like_used.png');
                }
        });
    }
}

//general
function mustFill(theForm) {
    if (theForm.eggs.value.length === 0 || theForm.bacon.value.length === 0) {
        alert ("Fill all fields you lazy farmer");
        return false;
    } 
    return true;    
}

var clicked=false;
function ajaxPost(thisform){
    if(mustFill(thisform) && clicked === false){
        $('#postsubmit').text('Working...');
        clicked=true;
        $.ajax({
            type: "POST",
            url: "http://www.ucdtramp.com/ajax_php/forum.db.php",
            data: "action=Post&forumid="+$('#forumid').val()+"&eggs="+$('#eggs').val()+"&bacon="+$('#bacon').val(),
            dataType: "html",
            complete: function(){clicked=false;},
            success: function(data){
            if(isNaN(data)){
                $('#bacon').val('');
                $('#writepost').hide('slow');
                $("#postbutton").html('<i class="fa fa-comment-o"></i> Post');
                
                var newpost=$(document.createElement('div'));
                newpost.attr( "class", "whitebox" );
                newpost.html(data);
                newpost.css( 'display','none');
                newpost.insertAfter( $('#placeholder') );
                newpost.slideDown();
                lastposttime (1); //Update post time in nav bar
                $('#postsubmit').text('Submit');

                var audio = new Audio('../files/applause.wav');
                audio.play();
            }
            else {console.log(data);}
        }
        });
    }
    return false;
}

function postReply(thatForm){ //make sure name and message arent empty
    if($(thatForm).find('#replyname').val() === "" || $(thatForm).find('#replymessage').val() === ""){
        alert ("Put something in the box first...");
        return false;
    }
    else{
        var parid = $(thatForm).find('#parentid').val(); //put the parentid in a variable for below
        $.ajax({
            type: "POST",
            url: "http://www.ucdtramp.com/ajax_php/forum.db.php",
            data: "action=Reply&forumid="+$('#forumid').val()+
                "&parentid="+$(thatForm).find('#parentid').val()+
                "&replyname="+$(thatForm).find('#replyname').val()+
                "&replymessage="+$(thatForm).find('#replymessage').val()+
                "&sausage="+$(thatForm).find('#sausage').val(),             
            dataType: "html",
            success: function(data){
                if(isNaN(data)){
                    $(thatForm).find('#replymessage').val(''); //Empty the form.
                    $('#replybox_'+parid).hide('slow');
                    
                    //insert the reply post which is formatted on the server end
                    var reply=$(document.createElement('div'));
                    reply.attr( "class", "reply" );
                    reply.html(data);
                    reply.css( 'display','none');
                    reply.insertBefore( $('#replybox_'+parid) );
                    reply.slideDown();
                    lastposttime (1); //Update post time in nav bar

                    var audio = new Audio('../files/fake_applause.wav');
                    audio.play();
                }
                else {console.log(data);}
            }
        });
    }
    return false;
}