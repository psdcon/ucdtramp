function elementInViewport(t){var e=t[0].getBoundingClientRect();return e.top>0&&e.left>0&&e.bottom<(window.innerHeight||document.documentElement.clientHeight)&&e.right<(window.innerWidth||document.documentElement.clientWidth)}function attemptUserLogin(){var t=$("#login-user").val().trim(),e=$("#login-pass").val().trim(),o=$("#login-error");return o.hide(),""===t||""===e?(o.text("Please fill both fields"),o.show(),!1):($.post("includes/process_login.php","action=login&user="+t+"&pass="+e,function(t){1==t?window.location.reload():2==t?(o.text("Username or password is incorrect!"),o.show()):3==t?(o.text("Please fill both fields"),o.show()):(o.html(t),o.show())}),!1)}function createCookie(t,e,o){var n;if(o){var i=new Date;i.setTime(i.getTime()+24*o*60*60*1e3),n="; expires="+i.toGMTString()}else n="";document.cookie=t+"="+e+n+"; path=/"}function readCookie(t){for(var e=t+"=",o=document.cookie.split(";"),n=0;n<o.length;n++){for(var i=o[n];" "==i.charAt(0);)i=i.substring(1,i.length);if(0===i.indexOf(e))return i.substring(e.length,i.length)}return null}function deleteCookie(t){createCookie(t,"",-1)}$(document).ready(function(){function t(){var t=$(".content").offset().top;e(t)}function e(t){$(window).scrollTop()>t?$(".navbar-yellow").addClass("navbar-fixed"):$(".navbar-yellow").removeClass("navbar-fixed")}if(/MSIE (\d+\.\d+);/.test(navigator.userAgent)){var o=Number(RegExp.$1);o>=9?alert("You're using IE"+o+". Don't do that. I can't guarantee the site will work"):o>=8?alert("You're using IE"+o+". Don't do that. Very little is going to work."):7>=o&&alert("You're using IE"+o+". Don't do that. Nothing is going to work for you."),document.querySelectorAll(".background-sides")[0].style.zIndex=0,document.querySelectorAll("nav")[0].style.filter=""}if(desktop=window.innerWidth>768?!0:!1,desktop){var n;t(),$(window).bind("load",t),$(window).bind("resize",t),$(window).bind("orientationchange",t),$(window).scroll(function(){e(n)})}$(".scrollToNav").click(function(){$("body").animate({scrollTop:$(".content").offset().top},1e3)}),$('*[data-toggle="showhide"]').click(function(){var t=$(this).data("target");t=$(t),t.hasClass("collapse")?t.removeClass("collapse"):t.addClass("collapse")}),$(function(){$(".soi-snapchat").tooltip({html:!0})}),$('*[data-click="bounceMe"]').mouseover(function(){var t=$(this).data("numclicks");t++,$(this).data("numclicks",t),t%5===0?($(this).addClass("animated wobble"),$(this).one("webkitAnimationEnd mozAnimationEnd MSAnimationEnd oanimationend animationend",function(){$(this).removeClass("animated wobble")})):($(this).addClass("animated shake"),$(this).one("webkitAnimationEnd mozAnimationEnd MSAnimationEnd oanimationend animationend",function(){$(this).removeClass("animated shake")}))})});var Forum={newestPostsId:-1,forumId:-1,submittingPost:!1,unseenPosts:[],checkNewForumPostIntervalId:0,init:function(){this.bindUIActions(),this.checkNewForumPostIntervalId=window.setInterval(this.checkNewForumPost,2e3),this.newestPostsId=$(".js-newestPostsId").val(),this.forumId=$(".js-forumId").val()},bindUIActions:function(){$(".btn-post").prop("disabled",!0),$(document).on("input",".forum-user",this.handlers.enablePostButton),$(document).on("input",".forum-message",this.handlers.enablePostButton),$(".post-form").on("submit",this.handlers.submitPost),$(document).on("submit",".reply-form",this.handlers.submitPost),$(document).on("click",'*[data-click="reply"]',this.handlers.addReplyForm),$(document).on("click",'*[data-action="likeButton"]',this.handlers.likeButtonClick),$(document).on("mouseover",'*[data-action="likeButton"]',this.handlers.likeButtonMouseover),$("time").timeago()},handlers:{enablePostButton:function(){$postForm=$(this).parent().parent().parent();var t=$postForm.find(".btn-post"),e=$postForm.find(".forum-user").val().trim().length,o=$postForm.find(".forum-message").val().trim().length;0!==e&&0!==o?t.prop("disabled",!1):t.prop("disabled",!0)},submitPost:function(t){Forum.submittingPost=!0;var e=$(this),o=e.find(".btn-post"),n=e.find(".forum-user"),i=e.find(".forum-message");0===e.data("parentid")?o.html('<img src="images/pages/forum/spinner-blue.gif" alt="Loading..." />').prop("disabled",!0):o.html('<img src="images/pages/forum/spinner-yellow.gif" alt="Loading..." />').prop("disabled",!0);var s=(new Date,{});return s.forumUser=n.val().trim(),s.forumMessage=i.val().trim(),s.parentPostId=e.data("parentid"),$.ajax({type:"POST",url:"forum.ajax.php",data:"action=newPost&forumId="+Forum.forumId+"&parentPostId="+s.parentPostId+"&forumUser="+encodeURIComponent(s.forumUser)+"&forumMessage="+encodeURIComponent(s.forumMessage),dataType:"json",success:function(t){Forum.submittingPost=!1,o.text("Post").prop("disabled",!1),console.log(t),t.posts?(newPost=t.posts[0],Forum.newestPostsId=newPost.id,$newPostEl=Forum.addPost(newPost),0===s.parentPostId?(e[0].reset(),$(".btn-post:first-child").prop("disabled",!0)):(e.html(""),e.parent().find("button").text("reply"))):Forum.error(t.error)},error:function(t,e,o){Forum.error(t.responseText)}}),t.preventDefault(),!1},addReplyForm:function(){var t=$(this).parent().find("form");if("reply"==$(this).text()){var e=$(".post-form").children().clone(),o=e.find(".btn-post");o.removeClass("btn-primary").addClass("btn-warning"),e.prop("disabled",!0),e.find(".js-buttons").empty(),t.html(e),$(this).text("cancel")}else t.html(""),$(this).text("reply"),Emoji.$textarea=$(".post-form").find("textarea")},likeButtonMouseover:function(){if(1!=$(this).find(".not-liked").length){var t=$(this).children(".post-like-count");t.addClass("animated bounce"),t.one("webkitAnimationEnd mozAnimationEnd MSAnimationEnd oanimationend animationend",function(){t.removeClass("animated bounce")})}},likeButtonClick:function(){if(1!=$(this).find(".liked").length){var t=$(this).children("img"),e=$(this).children(".post-like-count"),o=$(this).data("postid"),n=parseInt(e.text());t.addClass("animated swing liked"),t.one("webkitAnimationEnd mozAnimationEnd MSAnimationEnd oanimationend animationend",function(){t.removeClass("animated swing not-liked")}),e.text(n+1),e.addClass("animated bounce"),e.one("webkitAnimationEnd mozAnimationEnd MSAnimationEnd oanimationend animationend",function(){e.removeClass("animated bounce")}),console.log("Liking post "+o),$.ajax({type:"POST",url:"forum.ajax.php",data:"action=updateLikeCount&postId="+o,dataType:"text",success:function(t){""!==t&&console.log(t)}})}},postScrollHandler:function(t,e){"use strict";var o=null,n=300;return function(){o&&clearTimeout(o),o=setTimeout(function(){elementInViewport(t)&&(t.children().first().addClass("fade-highlight"),Forum.unseenPosts.remove(t),0===Forum.unseenPosts.length?($(".new-post-notification").removeClass("fadeInUp").addClass("fadeOutDown"),$(".new-post-notification").one("webkitAnimationEnd mozAnimationEnd MSAnimationEnd oanimationend animationend",function(){e.remove(),$(".new-post-notification").html("")})):e.remove(),$(window).off("scroll",this))},n)}},scrollToClickHandler:function(t){"use strict";return function(){var e=t.offset().top-window.innerHeight/2;$("body").animate({scrollTop:e},1e3)}}},addPost:function(t){var e,o='<div class="col-xs-12 forum-post">',n='<div class="for-hightling"><div class="post-contents"><div class="post-header" id="'+t.id+'"><strong class="post-header-name">'+t.forumUser+'</strong><small class="post-header-time"> <time datetime="'+t.htmlDatetime+'" title="'+t.readableTime+'">'+t.niceTime+'</time></small><span class="post-header-actions">'+t.headerActions+'</span></div><div class="post-message clearfix">'+t.forumMessage+'<!-- Like button --><button type="button" class="btn post-like-btn" title="Approve" data-action="likeButton" data-postid="'+t.id+'"><img class="post-like-img not-liked" src="images/pages/forum/like.svg" alt="Like" > <span class="post-like-count"> 0</span></button></div></div></div>',i='<div class="post-replies"></div><div class="post-footer"><!-- For reply box --><button class="btn-reply btn-link" data-click="reply">reply</button><form class="form-horizontal reply-form" data-postid="'+t.id+'"></form></div></div>';return"0"==t.parentPostId?(e=$(o+n+i),$("#posts-container").prepend(e)):(e=$(n),$("#"+t.parentPostId).parent(".forum-post").children(".post-replies").append(e)),e.find("time").timeago(),e},checkNewForumPost:function(){Forum.submittingPost||$.ajax({type:"POST",url:"forum.ajax.php",data:"action=checkForNewPost&forumId="+Forum.forumId+"&newestPostsId="+Forum.newestPostsId,dataType:"json",success:function(t){if(t.posts){for(var e=0;e<t.posts.length;e++){var o=t.posts[e],n=Forum.unseenPosts.push(Forum.addPost(o));Forum.unseenPosts[n-1].children().first().addClass("highlight"),Forum.unseenPosts[n-1].alreadyHandlered=!1,o.id>Forum.newestPostsId&&(Forum.newestPostsId=o.id)}1===Forum.unseenPosts.length?$.titleAlert(t.posts[0].forumUser+" posted in the forum",{requireBlur:!0,stopOnFocus:!0,interval:1e3}):$.titleAlert(Forum.unseenPosts.length+" new posts in the forum",{requireBlur:!0,stopOnFocus:!0,interval:1e3}),Forum.soundNotification();var i=function(){if(tabVisible())for(var t=0;t<Forum.unseenPosts.length;t++){var e=Forum.unseenPosts[t];if(elementInViewport(e))e.children().first().addClass("fade-highlight"),Forum.unseenPosts.remove(e);else if(!e.alreadyHandlered){var o=e.find(".post-header-name").text(),n=$("<div>Click to scroll to "+o+"'s post</div>");n.on("click",Forum.handlers.scrollToClickHandler(e)),$(".new-post-notification").removeClass("fadeOutDown").addClass("fadeInUp"),$(".new-post-notification").append(n),$(window).on("scroll",Forum.handlers.postScrollHandler(e,n)),e.alreadyHandlered=!0}}};i(),tabVisible(i)}else Forum.error(t)},error:function(t,e,o){""!==t.responseText&&Forum.error(t.responseText),200!=t.status&&Forum.stopCheckNewForumPost()}})},soundNotification:function(){var t=document.getElementById("notification-sound");t.load(),t.play()},error:function(t){var e="<strong>Oops, something went wrong.</strong> Please show the error below to the Webmaster <br><pre>"+t+"</pre>";$("#posts-container").prepend(e)},stopped:!1,stopCheckNewForumPost:function(){Forum.stopped||($("#posts-container").prepend("<pre>Something went wrong and automatic Forum updates had to stop. Maybe you lost your internet connection :(. Please refresh the page to restart them.</pre>"),window.clearInterval(Forum.checkNewForumPostIntervalId),Forum.stopped=!0)}};Array.prototype.remove=function(t){return this.splice(this.indexOf(t),1)},$(function(){function t(){var t=e.scrollTop(),o=1.1*e.height();$(".revealOnScroll:not(.animated)").each(function(){var e=$(this),n=e.offset().top;t+o>n&&(e.data("timeout")?window.setTimeout(function(){e.addClass("animated "+e.data("animation"))},parseInt(e.data("timeout"),10)):e.addClass("animated "+e.data("animation")))})}{var e=$(window);1.1*e.height()}e.on("scroll",t),t()});var tabVisible=function(){var t,e,o={hidden:"visibilitychange",webkitHidden:"webkitvisibilitychange",mozHidden:"mozvisibilitychange",msHidden:"msvisibilitychange"};for(t in o)if(t in document){e=o[t];break}return function(o){return o&&document.addEventListener(e,o),!document[t]}}();