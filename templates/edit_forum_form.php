<form class="form-horizontal post-form" action="forum.ajax.php" method="post">
    <div class="form-group forum-post-inputs row">
        <div class="col-sm-3 col-md-2">
            <input type="text" class="form-control forum-user" placeholder="Mr. Smith" name="forumUser" value="<?= $forumUser ?>" readonly>
            <input type="hidden" name="forumId" value="<?= $forumId ?>">
            <input type="hidden" name="postId" value="<?= $postId ?>">
        </div>
        <div class="col-sm-9 col-md-10">
            <textarea class="form-control forum-message" rows="2" placeholder="What's on your mind?" name="forumMessage" autofocus><?= $forumMessage ?></textarea>
        </div>
    </div>
    <!-- Buttons -->
    <div class="form-group row">
        <div class="col-sm-3 col-md-2">
            <button type="submit" class="form-control btn btn-primary btn-post" name="action" value="editPost">
                Update
            </button>
            
        </div>
        <div class="col-sm-9 col-md-10">
            <button type="button" class="btn btn-default btn-toggle-emoji" data-toggle="collapse" data-target="#emoji-picker" aria-expanded="false" aria-controls="emoji-picker">:)</button>
            <a href="forum/delete/<?= $postId ?>" class="btn btn-danger">
                Delete Post
            </a>
            <!-- <button type="button" class="btn btn-default" data-toggle="collapse" data-target="#jquery-uploader" aria-expanded="false" aria-controls="jquery-uploader"><i class="glyphicon glyphicon-plus "></i> <span class="btn-toggle-upload"></span></button> -->
            <button type="button" class="btn btn-default pull-right" style="margin-left:4px;" data-toggle="collapse" data-target="#formatting-help" aria-expanded="false" aria-controls="formatting-help"><span class="btn-toggle-formatting"></span></button>
        </div>
    </div>
</form>

<!-- Dropdowns for emoji, file uploader and help-->
<div class="row">
    <div class="col-xs-12 well collapse" id="emoji-picker">
        <?php include 'templates/emoji_picker.php'; ?>
    </div>
    <div class="col-xs-12 collapse well" id="jquery-uploader">
        <!-- The file upload form used as target for the file upload widget -->
    </div>
    <div class="collapse" id="formatting-help">
        <?php include 'templates/formatting_help.php'; ?>
    </div>
</div>