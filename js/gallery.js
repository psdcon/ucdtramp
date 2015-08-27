var Gallery = {
    init: function(){
        // init important vars
        this.currentImageDbIndex = 0;
        this.eventName = $('#gallery-event-name').val();
        // get relevant vars
        this.$hideBtn = $('.btn-showHide-form');
        this.$eventLink = $('.swipebox-link');
        this.$commentsPannel = $('.comments-pannel');
        this.$commentsContainer = $('.comments-container');
        this.$commentLoader = $('.comments-loading');

        // Lightbox plugin
        // http://brutaldesign.github.io/swipebox/
        Gallery.$eventLink.swipebox({
            hideBarsDelay : 3000,
            beforeOpen: function() {Gallery.commentsPannelShow();}, // called before opening
            afterClose: function() {Gallery.commentsPannelHide();}, // called after closing
            loopAtEnd: true,
        });

        this.bindUIActions();
    },
    bindUIActions: function(){
        this.$hideBtn.on('click', this.showHide);
        $('.comment-form').on('submit', this.commentPost);
    },
    // Image related functions
    // Changes url when photo is opened/changed. Called in jquery.swipebox.js, line 602
    urlMagic: function(swipeboxImageIndex){
        this.currentImageDbIndex = Gallery.$eventLink.eq(swipeboxImageIndex).data('photoid');
        // Update pannel with current image number and load comments. 1 is added so that it doesnt say 0 of xx
        $('#current-image').text(swipeboxImageIndex + 1);
        this.commentsLoad();

        // Change the url by appending the current photo index for sharing. 1 is added so that url is in sync with comment pannel count
        var newUrl = 'gallery/' + this.eventName + '/' + (swipeboxImageIndex + 1);
        history.replaceState(null, null, newUrl);
    },
    openImage: function(index){
        // 1 was added for consistency with comment pannel
        Gallery.$eventLink.eq(index-1).trigger('click');
    },
    // Comment related functions
    showHide : function () {
        var btnDefaultText = 'Write Comment';
        var btnAltText = 'Hide';

        if ($(this).text() == btnDefaultText) { 
            // Show form elements
            $('.comment-form-inputs').addClass('showForm');
            $('.btn-comment').addClass('showForm');
            // Shrink button to 30% width
            $(this).addClass('showForm');
            // Change text to hide
            $(this).text(btnAltText);
        } else {
            // Hide the form elements
            $('.comment-form-inputs').removeClass('showForm');
            $('.btn-comment').removeClass('showForm');
            // Make the button 100% width again
            $(this).removeClass('showForm');
            // Change button text
            $(this).text(btnDefaultText);
        }
    },
    commentsPannelShow: function() {
        this.$commentsPannel.addClass('show');
    },
    commentsPannelHide: function() {
        this.$commentsPannel.removeClass('show');
        // history.replaceState(null, null, 'gallery/' + this.eventName);
    },
    comment2HTML:function(comment){
        var special = (comment.special !== '')? '<div>'+comment.special+'</div>': '';
        return ''+
        '<div>'+
            '<strong>'+comment.sender+'</strong>'+
            '<time style="float:right;" datetime="'+comment.htmlDatetime+'" title="'+ comment.readableTime+'">'+
                comment.niceTime+
            '</time>'+
        '</div>'+
        special+
        '<div style="padding-top:.4em;">'+
            comment.message+
        '</div>';
    },
    // ajax that returns the comments for append
    commentsLoad: function() {
        // Gallery.$commentsContainer.fadeOut(); // Hide comments from previous photo
        // Gallery.$commentLoader.show(); // Show loading.. text

        $.ajax({
            type: "POST",
            url: "gallery.ajax.php",
            data: "action=getComments" +
                "&photoid=" + Gallery.currentImageDbIndex + 
                "&eventname=" + Gallery.eventName,
            dataType: "json",
            success: function (response) {
                if(response.empty){
                    // Show no comments
                    Gallery.$commentsContainer.text('No comments :(');
                }
                else if(response.comments){
                    var commentsHtml = '';
                    for (var i = 0; i < response.comments.length; i++) {
                        commentsHtml += Gallery.comment2HTML(response.comments[i]);
                    }
                    var $newComment = Gallery.$commentsContainer.html(commentsHtml);
                    $newComment.find('time').timeago(); // TODO: Test if this works
                }
                else{
                    // error
                    console.log(response);
                }
            },
            error: function(jqXHR, textStatus, errorThrown) { 
                // error, probably with json parse meaning php error
                console.log(jqXHR.responseText);
            }
        });
    },
    commentPost: function() {
        var senderVal = $('.comment-user').val();
        var messageVal = $('.comment-message').val();

        if (senderVal === '' || messageVal === '') {
            alert("Fill the text boxes");
            return false;
        }
        $.ajax({
            type: "POST",
            url: "gallery.ajax.php",
            data: "action=addComment"+
                "&sender=" + senderVal + 
                "&message=" + messageVal + 
                "&photoid=" + Gallery.currentImageDbIndex + 
                "&eventname=" + Gallery.eventName,
            dataType: "json",
            success: function (response) {
                if(response.newComment){
                    // Empty textarea
                    $('.comment-message').val('');
                    // Add comment to pannel
                    Gallery.$commentsContainer.html(Gallery.comment2HTML(response.newComment));
                }
                else{
                    // error
                    console.log(response);
                }
            },
            error: function(jqXHR, textStatus, errorThrown) { 
                // error, probably with json parse meaning php error
                console.log(jqXHR.responseText);
            }
        });

        return false;
    }
};

Gallery.init();
