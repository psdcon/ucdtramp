$(document).ready(function () {

    // Internet explorer check
    if (/MSIE (\d+\.\d+);/.test(navigator.userAgent)) { //test for MSIE x.x;
        var ieversion = Number(RegExp.$1); // capture x.x portion and store as a number
        if (ieversion>=9)
            alert("You're using IE"+ieversion+". Don't do that. I can't guarantee the site will work");
        else if (ieversion>=8)
            alert("You're using IE"+ieversion+". Don't do that. Very little is going to work.");
        else if (ieversion<=7)
            alert("You're using IE"+ieversion+". Don't do that. Nothing is going to work for you.");
        // Make items on the page clickable. Makes navbar go above trampoline sides.
        document.querySelectorAll('.background-sides')[0].style.zIndex = 0;
        // Makes dropdowns work. Makes navbar a weird color...
        document.querySelectorAll('nav')[0].style.filter = "";
    }

    desktop = (window.innerWidth > 768)? true: false;
    /***********************************************************
                               NAVBAR STUFF
    ***********************************************************/
    // Attaches the navbar to the top of the screen once the user scrolls past
    // Only on desktop
    function navbarOffsetChange() {
        var navbarOffset = $('.content').offset().top;
        navbarPositionChange(navbarOffset);
    }

    function navbarPositionChange(navbarOffset) {
        if ($(window).scrollTop() > navbarOffset) {
            $('.navbar-yellow').addClass('navbar-fixed');
        } else {
            $('.navbar-yellow').removeClass('navbar-fixed');
        }
    }

    if (desktop) {
        var navbarOffset;
        navbarOffsetChange();
        $(window).bind('load', navbarOffsetChange);
        $(window).bind('resize', navbarOffsetChange);
        $(window).bind('orientationchange', navbarOffsetChange);
        $(window).scroll(function () {
            navbarPositionChange(navbarOffset);
        });
    }

    // Clicking the left side of the trampoline scrolls to top of the page
    $('.scrollToNav').click(function(){
        $('body').animate({
            scrollTop: $('.content').offset().top
        }, 1000);
    });
    
    // Toggles the mobile dropdown nav bar without animating height
    $('*[data-toggle="showhide"]').click(function () {
        var nav = $(this).data('target'); // uses target attribute
        nav = $(nav); // Get jQuery element
        // If it's collapsed, show it
        if (nav.hasClass('collapse')) {
            nav.removeClass('collapse');
        }
        // If it's not collapsed, hide it
        else {
            nav.addClass('collapse');
        }
    });

    /***********************************************************
                               News
    ***********************************************************/
    $(function () {
        // renders title attribute of snapchat icon as html image
        $('.soi-snapchat').tooltip({html:true});
    });

    /***********************************************************
                               ABOUT
    ***********************************************************/

    // Shakes coach image on hover
    // When hovered more than 5 times, images shakes harder
    $('*[data-click="bounceMe"]').mouseover(function () {
        var numclicks = $(this).data('numclicks');
        numclicks++;
        $(this).data('numclicks', numclicks);

        if (numclicks % 5 === 0) {
            $(this).addClass('animated wobble');
            $(this).one('webkitAnimationEnd mozAnimationEnd MSAnimationEnd oanimationend animationend', function () {
                $(this).removeClass('animated wobble');
            });
        } else {
            $(this).addClass('animated shake');
            $(this).one('webkitAnimationEnd mozAnimationEnd MSAnimationEnd oanimationend animationend', function () {
                $(this).removeClass('animated shake');
            });
        }
    });
});
    /***********************************************************
                               FORUM
    ***********************************************************/
var Forum = {
    newestPostsId: -1,
    forumId : -1,
    submittingPost: false,
    unseenPosts: [],
    checkNewForumPostIntervalId: 0,
    checkInterval: 4000,
    init : function() {
        this.bindUIActions();
        this.checkNewForumPostIntervalId = window.setInterval(this.checkNewForumPost, this.checkInterval);
        // init vars
        this.newestPostsId = $('.js-newestPostsId').val();
        this.forumId = $('.js-forumId').val();
    },
    bindUIActions: function(){
        // Disable post button until both name and message fields are filled
        $('.btn-post').prop('disabled', true); // Disable initally
        $(document).on('input', '.forum-user', this.handlers.enablePostButton);
        $(document).on('input', '.forum-message', this.handlers.enablePostButton);
        
        // Submit post handlers
        $('.post-form').on('submit', this.handlers.submitPost);
        $(document).on('submit', '.reply-form', this.handlers.submitPost);

        // Adds reply forum
        $(document).on('click', '*[data-click="reply"]', this.handlers.addReplyForm);

        // Like button
        $(document).on('click', '*[data-action="likeButton"]', this.handlers.likeButtonClick);
        $(document).on('mouseover', '*[data-action="likeButton"]', this.handlers.likeButtonMouseover);

        // Update time stamps
        $('time').timeago();
    },
    handlers:{
        enablePostButton: function(){
            $postForm = $(this).parent() // row
                               .parent() // form group
                               .parent();// form-horizontal
            var $postBtn = $postForm.find('.btn-post');
            var userLen = $postForm.find('.forum-user').val().trim().length;
            var msgLen  = $postForm.find('.forum-message').val().trim().length;
            // When both fields have data
            if (userLen !== 0 && msgLen !== 0){
                $postBtn.prop('disabled', false);
            }
            else{
                $postBtn.prop('disabled', true);
            }
        },
        submitPost: function(event){
            Forum.submittingPost = true; // Tell checkNewForumPost to wait
            var $postForm = $(this); 
            var $postBtn = $postForm.find('.btn-post');
            var $forumUserEl = $postForm.find('.forum-user');
            var $forumMessageEl = $postForm.find('.forum-message');

            // Set the button content to spinner
            spinnerBtn = Ladda.create($postBtn[0]);
            spinnerBtn.start();

            // Post data
            var postTime = new Date();
            var post = {}; // object to hold all post data
                post.forumUser = $forumUserEl.val().trim();
                post.forumMessage = $forumMessageEl.val().trim();
                post.parentPostId = $postForm.data('parentid');

            // Add post to database
            $.ajax({
                type: 'POST',
                url : 'forum.ajax.php',
                data: 'action=newPost'+
                    '&forumId='+ Forum.forumId +
                    '&parentPostId='+ post.parentPostId +
                    '&forumUser='+ encodeURIComponent(post.forumUser) +
                    '&forumMessage='+ encodeURIComponent(post.forumMessage),
                dataType: 'json', // server return type
                success: function(response){
                    Forum.submittingPost = false;
                    spinnerBtn.stop();
                    $postBtn.text('Post')
                            .prop('disabled', false);

                    // console.log(response);

                    if(response.post && response.post.length>0){
                        thisPost = response.post[0];
                        // Update the id of the newest post
                        Forum.newestPostsId = thisPost.id;
                        // Add post to page
                        $newPostEl = Forum.addPost(thisPost);

                        // Reset forms
                        if (post.parentPostId === 0){
                            $postForm[0].reset(); // Reset forum
                            $postBtn.prop('disabled', true); // Turn off btn again
                        }
                        else {
                            // When reply, delete reply form.
                            $postForm.html(''); // Remove reply form
                            $postForm.parent().find('button').text('reply'); // reset 'cancel' buttn to 'reply'
                        }
                    }
                    else {
                        // An expected error from the server
                        Forum.error('Error submitting post: ' + JSON.stringify(response));
                    }
                },
                // An unexpected error from the server; json parse probably failed
                error: function(jqXHR, textStatus, errorThrown) { 
                    Forum.error('Unexpected error submitting post: ' + jqXHR.responseText);
                }
            });

            // Don't have form submit trigger page refresh
            event.preventDefault();
            return false;
        },

        /***** REPLY POST FORM ****/
        addReplyForm: function(){
            var $replyForm = $(this) // reply button
                            .parent() // post-footer
                            .find('form'); // empty form el
            if($(this).text() == 'reply'){
                // Clone main post form and change the color of the button
                var $postFormClone = $('.post-form').children().clone();
                var $replyFormBtn = $postFormClone.find('.btn-post');
                $replyFormBtn.removeClass('btn-primary').addClass('btn-warning'); // make it yellow
                $postFormClone.prop('disabled', true); // textarea emptied in clone

                // Remove buttons cause none of them will work
                $postFormClone.find('.js-buttons').empty();

                // Modify HTML
                $replyForm.html($postFormClone);
                $(this).text('cancel');
            }
            else{
                // Erase form
                $replyForm.html('');
                $(this).text('reply');
                Emoji.$textarea = $('.post-form').find('textarea');
            }
        },

        /***** LIKE BUTTON ****/
        likeButtonMouseover: function() {
            // Don't animate unliked post. Only post that's already liked with bouce
            if ($(this).find('.not-liked').length == 1)
                return;
            // Bounce number on hover
            var $likeCountEl = $(this).children('.post-like-count');
            $likeCountEl.addClass('animated bounce');
            $likeCountEl.one('webkitAnimationEnd mozAnimationEnd MSAnimationEnd oanimationend animationend', function () {
                $likeCountEl.removeClass('animated bounce');
            });        
        },
        likeButtonClick: function() {
            // Don't increment like for an already liked post
            if ($(this).find('.liked').length == 1)
                return;
            var $likeImg = $(this).children('img');
            var $likeCountEl = $(this).children('.post-like-count');
            var postId = $(this).data('postid');
            var likeCount = parseInt($likeCountEl.text());

            // Animate thumb image
            $likeImg.addClass('animated swing liked');
            $likeImg.one('webkitAnimationEnd mozAnimationEnd MSAnimationEnd oanimationend animationend', function () {
                $likeImg.removeClass('animated swing not-liked');
            });
            
            // Incement like count on dom on DOM
            $likeCountEl.text(likeCount+1);
            $likeCountEl.addClass('animated bounce');
            $likeCountEl.one('webkitAnimationEnd mozAnimationEnd MSAnimationEnd oanimationend animationend', function () {
                $likeCountEl.removeClass('animated bounce');
            }); 

            // Add like to database
            // console.log("Liking post "+ postId);
            $.ajax({
                type: 'POST',
                url : 'forum.ajax.php',
                data: "action=updateLikeCount&postId="+postId,
                dataType: "text",
                success: function(data){
                    if (data !== ''){
                        Forum.error('Error liking post: ' + postId + ' ' + response);
                    }
                }
            });
        },

        // New post scroll handler
        postScrollHandler: function($thisNewPost, $scrollToClick){
            "use strict";
            // Each scroll resets the timer so that the calculating fucntion isnt called till the timer is up
            // This means you can scroll past new posts without them being read
            var scrollTimer = null;
            var timeoutMs = 300;
            return function(){
                if (scrollTimer) {
                    clearTimeout(scrollTimer); // clear previous pending timer
                }
                scrollTimer = setTimeout(function(){                    
                    if (elementInViewport($thisNewPost)){
                        $thisNewPost.addClass('fade-highlight');

                        // Update notification
                        Forum.unseenPosts.remove($thisNewPost);
                        if(Forum.unseenPosts.length === 0){ // if all clicks are gone, hide notification
                            $('.new-post-notification').removeClass('fadeInUp').addClass('fadeOutDown');
                            $('.new-post-notification').one('webkitAnimationEnd mozAnimationEnd MSAnimationEnd oanimationend animationend', function () {
                                $scrollToClick.remove(); // remove post after animation has finished
                                $('.new-post-notification').html('');
                            });
                        }   
                        else{ // If not the last scroll click left, pull it out
                            $scrollToClick.remove(); // remove html of click
                        }

                        // Remove scroll handler once animated in
                        $(window).off("scroll", this);
                    }
                }, timeoutMs);   // set new timer
            };
        },
        scrollToClickHandler: function($thisNewPost){
            "use strict";
            return function(){
                // this can change if new posts come in...
                var scrollToPosition = $thisNewPost.offset().top - window.innerHeight/2;
                $('body').animate({
                    scrollTop: scrollToPosition
                }, 1000);
            };
        }
    },


    // Adds new post to the DOM
    addPost: function(post){
       
        // Add post to page
        var $newPostEl = $(post.postHTML); // keeps a jQuery reference of the new psot in the DOM after the post's been added
        if (post.parentPostId == '0'){ 
            $('#posts-container').prepend($newPostEl); // Add to top of page
        }
        else { // When reply, delete reply form.
            $('#'+post.parentPostId).parent('.forum-post').children('.post-replies').append($newPostEl); // Add as reply to parent post
        }

        // Start live time update
        $newPostEl.find('time').timeago();

        // Return object reference
        return $newPostEl;
    },
    
    /***** DYNAMIC POSTS (and likes?) ****/
    checkNewForumPost: function(){
        // Only check for new post after a new post has been submitted 
        // so that newestPostsId can be updated
        if (Forum.submittingPost)
            return;
        
        $.ajax({
            type: 'POST',
            url : 'forum.ajax.php',
            data: 'action=checkForNewPost' +
                    '&forumId=' + Forum.forumId +
                    '&newestPostsId=' + Forum.newestPostsId,
            dataType: 'json',
            success: function(response){
                if(response.posts){
                    // Add each new posts to page
                    for (var i = 0; i<response.posts.length; i++){
                        var thatPost = response.posts[i];

                        // Add post to page
                        var unseenLength = Forum.unseenPosts.push(Forum.addPost(thatPost));

                        // Use query element reference to edit post after it's added
                        Forum.unseenPosts[unseenLength-1].addClass('highlight');
                        // Used to check if post loaded out of view
                        Forum.unseenPosts[unseenLength-1].alreadyHandlered = false; 
                        
                        // Update the id to the MAX post id. if because might receive lower number
                        if (thatPost.id > Forum.newestPostsId)
                            Forum.newestPostsId = thatPost.id;
                    }

                    // Flash title
                    if (Forum.unseenPosts.length === 1) {
                        $.titleAlert("Someone posted in the forum", {requireBlur:true, stopOnFocus:true, interval:1000});
                    }
                    else {
                        $.titleAlert(Forum.unseenPosts.length + " new posts in the forum", {requireBlur:true, stopOnFocus:true, interval:1000});
                    }

                    // Play sound
                    Forum.soundNotification();

                    // The magic part...
                    // Function is called when tab is made active
                    var postInViewHandler = function(){
                        // Do nothing if this tab is out of focus and wait till it's active
                        if(!tabVisible())
                            return;

                        for(var i=0; i<Forum.unseenPosts.length; i++){
                            var $thisNewPost = Forum.unseenPosts[i];

                            // Post loaded into viewport
                            if (elementInViewport($thisNewPost)){
                                $thisNewPost.addClass('fade-highlight');
                                Forum.unseenPosts.remove($thisNewPost);
                            }
                            // Post loaded out of view. Add a scroll listener and notification link
                            else if(!$thisNewPost.alreadyHandlered){ // If no listeners already associated with the post
                                // var notificationLimit = 2;
                                // if(Forum.unseenPosts.length == notificationLimit){
                                //     var limitMessage = 'Limit reached '+Forum.unseenPosts.length - notificationLimit+' more...';
                                //     $('.new-post-notification').html('<div class="limit">'+limitMessage+'</div>');
                                // }

                                // Add post to notification popup
                                // var forumUser = $thisNewPost.find('.post-header-name').text();
                                // var $scrollToClick = $('<div>Click to scroll to '+forumUser+'\'s post</div>');
                                // $scrollToClick.on('click', Forum.handlers.scrollToClickHandler($thisNewPost));
                                // Show notification
                                // $('.new-post-notification').removeClass('fadeOutDown').addClass('fadeInUp');
                                // $('.new-post-notification').append($scrollToClick);

                                // Add scroll handler for each new post
                                // $(window).on('scroll', Forum.handlers.postScrollHandler($thisNewPost, $scrollToClick));

                                $thisNewPost.alreadyHandlered = true;
                            }
                        }
                    };
                    
                    postInViewHandler(); // Run to see if tab is in view
                    tabVisible(postInViewHandler); // Else set the tab listener and when the tab comes into view, then run
                }
                else { // Server error that came down as json
                    Forum.error('Error fetching new posts: ' + JSON.stringify(response));
                }
            },
            // An error from the server;
            error: function(jqXHR, textStatus, errorThrown) { 
                // '' means no new posts on server. Anything else would be some unexpected php error which failed the json parse
                if(jqXHR.responseText.trim() !== ''){
                    Forum.error('Unexpected error fetching new posts: ' + jqXHR.responseText);
                }
            }
        });
    },
    soundNotification: function(){
        // var notificationSound = $('#notification-sound')[0];
        var notificationSound = document.getElementById('notification-sound');
        notificationSound.load();
        notificationSound.play();
    },
    // Forum.error function
    error: function(errorMessage){
        var messageHtml = 
            '<div class="alert alert-danger" role="alert">'+
                '<strong>Oops!</strong> Something went wrong. Please show the error below to the Webmaster.<br>'+
                '<pre>' + errorMessage + '</pre>' +
            '</div>';
        $('#posts-container').prepend(messageHtml);
        
        Forum.stopCheckNewForumPost();
    },
    stopCheckNewForumPost: function(){
        // Stop polling the server and signal to the user to refresh
        window.clearInterval(Forum.checkNewForumPostIntervalId);
        $('#posts-container').prepend(
            '<div class="alert alert-success" style="cursor:pointer;" onclick="window.location.reload();">'+
                '<strong>Heads up!</strong> Click here to <span class="alert-link">refresh the page</span> to see new forum posts.' +
            '</div>');
    }
};

Array.prototype.remove = function(el){
    return this.splice(this.indexOf(el), 1);
};

/***********************************************************
                           MSC
***********************************************************/
// Animate on scroll
$(function () {

    var $window = $(window),
        win_height_padded = $window.height() * 1.1;

    // if (desktop)
        $window.on('scroll', revealOnScroll);

    function revealOnScroll() {
        var scrolled = $window.scrollTop(),
            win_height_padded = $window.height() * 1.1;

        // Showed...
        $('.revealOnScroll:not(.animated)').each(function () {
            var $this = $(this),
                offsetTop = $this.offset().top;

            if (scrolled + win_height_padded > offsetTop) {
                if ($this.data('timeout')) {
                    window.setTimeout(function () {
                        $this.addClass('animated ' + $this.data('animation'));
                    }, parseInt($this.data('timeout'), 10));
                } else {
                    $this.addClass('animated ' + $this.data('animation'));
                }
            }
        });
        // Hidden...
        // $('.revealOnScroll.animated').each(function (index) {
        //     var $this = $(this),
        //         offsetTop = $this.offset().top;
        //     if (scrolled + win_height_padded < offsetTop) {
        //         $(this).removeClass('animated');
        //     }
        // });
    }
    revealOnScroll();
});

var tabVisible = (function(){
    var stateKey, eventKey, keys = {
        hidden: "visibilitychange",
        webkitHidden: "webkitvisibilitychange",
        mozHidden: "mozvisibilitychange",
        msHidden: "msvisibilitychange"
    }; // eventKery assigned the relevent listener 
    for (stateKey in keys) {
        if (stateKey in document) {
            eventKey = keys[stateKey];
            break;
        }
    }
    return function(c) {
        if (c) document.addEventListener(eventKey, c); // if given a handler function, add listener
        return !document[stateKey]; // if no handler arg, return tab stateKey
    };
})();

function elementInViewport (el) {
    var rect = el[0].getBoundingClientRect();

    return (
        rect.top > 0 &&
        rect.left > 0 &&
        rect.bottom < (window.innerHeight || document.documentElement.clientHeight) && /*or $(window).height() */
        rect.right < (window.innerWidth || document.documentElement.clientWidth) /*or $(window).width() */
    );
}

// Login
function attemptUserLogin(){
    var loginUser = $('#login-user').val().trim();
    var loginPass = $('#login-pass').val().trim();
    var $loginError = $('#login-error');
    $loginError.hide();

    if(loginUser === '' || loginPass === ''){
        $loginError.text('Please fill both fields');
        $loginError.show();
        return false;
    }

    $.post( // shorthand jQuery $.ajax type:POST func call
        "includes/process_login.php", 
        "action=login&user="+loginUser+"&pass="+loginPass,
        function(response){ // if successful, hide form, show links. else show error
            if (response == 1){
                // refresh window on success
                window.location.reload();
            }
            else if (response == 2){
                $loginError.text('Username or password is incorrect!');
                $loginError.show();
            }
            else if (response == 3){
                $loginError.text('Please fill both fields');
                $loginError.show();
            }
            else {
                $loginError.html(response);
                $loginError.show();
            }
        }
    );
    return false;
}

// Cookie Code from http://www.quirksmode.org/js/cookies.html
function createCookie(name,value,days) {
    var expires;
    if (days) {
        var date = new Date();
        date.setTime(date.getTime()+(days*24*60*60*1000));
        expires = "; expires="+date.toGMTString();
    }
    else expires = "";
    document.cookie = name+"="+value+expires+"; path=/";
}

function readCookie(name) {
    var nameEQ = name + "=";
    var ca = document.cookie.split(';');
    for(var i=0;i < ca.length;i++) {
        var c = ca[i];
        while (c.charAt(0)==' ') c = c.substring(1,c.length);
        if (c.indexOf(nameEQ) === 0) return c.substring(nameEQ.length,c.length);
    }
    return null;
}

function deleteCookie(name) {
    createCookie(name,"",-1);
}