<?php
require_once('../includes/db.php');

$skills = mysqli_query($db, "SELECT * FROM skills ORDER BY id ASC");
echo "<script> var skillNames = [";
while($skill = mysqli_fetch_array($skills)){
    echo "{id: '".$skill['name']."', text: '".$skill['name']."'},";
}

echo "];</script>";

?>

<h1 class="page-header">Add</h1>
<div class="alert alert-dismissable" id="ajaxReturn" style="display:none;"></div>

<form class="form-horizontal" role="form" onSubmit="addMove(this); return false">
    <div class="form-group">
        <label for="name" class="col-sm-2 control-label">Name</label>
        <div class="col-sm-3">
            <input type="text" class="form-control" id="name" placeholder="Name">
        </div>
        <label for="name" class="col-sm-1 control-label">FIG</label>
        <div class="col-sm-1">
            <input type="text" class="form-control" id="fig" placeholder="- - /">
        </div>
        <label for="name" class="col-sm-1 control-label">Level</label>
        <div class="col-sm-2">
            <select class="form-control" id="level">
                <option selected="selected">Novice</option>
                <option>Intermediate</option>
                <option>Advanced</option>
                <option>Elite</option>
                <option>Misc</option>
            </select>
        </div>
        <label for="name" class="col-sm-1 control-label">Tariff</label>
        <div class="col-sm-1">
            <input type="number" step="0.1" class="form-control" id="tariff" placeholder="0.0">
        </div>
    </div>
    <div class="form-group">
        <label for="short_desc" class="col-sm-2 control-label">Short Description</label>
        <div class="col-sm-10">
            <input type="text" class="form-control" id="short_desc" placeholder="Jump up...">
        </div>
    </div>
    <div class="form-group">
        <label for="long_desc" class="col-sm-2 control-label">Long Description</label>
        <div class="col-sm-10">
            <textarea class="form-control" rows="3" id="long_desc" placeholder="Jump up and down..."></textarea>
        </div>
    </div>
    <div class="form-group">
        <label for="coaching_points" class="col-sm-2 control-label">Coaching Points</label>
        <div class="col-sm-10">
            <textarea class="form-control" rows="3" id="coaching_points" placeholder="Defy gravity"></textarea>
        </div>
    </div>  
    <div class="form-group">
        <div class="col-sm-2 control-label">
            <label for="prereq">Prerequisite Moves</label>
        </div>
        <div class="col-sm-10">
            <select id="prereq" class="" multiple="multiple"></select>
        </div>
    </div>
    <div class="form-group">
        <div class="col-sm-2 control-label">
            <label for="lateral_prog">Lateral Progressions</label>
        </div>
        <div class="col-sm-10">
            <select id="lateral_prog" class="" multiple="multiple"></select>
        </div>
    </div>
    <div class="form-group">
        <div class="col-sm-2 control-label">
            <label for="linear_prog">Linear Progressions</label>
        </div>
        <div class="col-sm-10">
            <select id="linear_prog" class="" multiple="multiple"></select>
        </div>
    </div>
    <div class="form-group">
        <div class="col-sm-2 control-label">
            <label for="vids">Video Notes</label>
        </div>
        <div class="col-sm-10">
            <textarea class="form-control" rows="3" id="vids">Speak clearly and make uncomfortable prolonged eyecontact with the camera...</textarea>
        </div>
    </div>

    <div class="form-group">
        <div class="col-sm-offset-2 col-sm-10">
            <button type="submit" class="btn btn-default" id="save">Save</button>
        </div>
    </div>
</form>

<script>

    // Set up select2 inputs
    var select2Options = {
        placeholder: "Select some skills",
        data: skillNames,
        tags: true,
        tokenSeparators: [',']
    };
    $("#prereq").select2(select2Options);
    $("#lateral_prog").select2(select2Options);
    $("#linear_prog").select2(select2Options);

    function addMove(thisform) {
        //if(mustFill(thisform)){
        $('#save').text('Saving...');
        $.ajax({
            type: "POST",
            url: "includes/moves.db.php",
            data: "action=Add&name=" + $('#name').val() +
                "&fig=" + $('#fig').val() +
                "&level=" + $('#level').val() +
                "&tariff=" + $('#tariff').val() +
                "&short_desc=" + $('#short_desc').val() +
                "&long_desc=" + $('#long_desc').val() +
                "&coaching_points=" + $('#coaching_points').val() +
                "&prereq=" + JSON.stringify($('#prereq').val()) +
                "&lateral_prog=" + JSON.stringify($('#lateral_prog').val()) +
                "&linear_prog=" + JSON.stringify($('#linear_prog').val()) +
                "&vids=" + $('#vids').val(),
            dataType: "text",
            success: function (data) {
                if (data.charAt(0) == '1') {
                    $('input').val('');
                    $('textarea').val('');
                    $('#prereq').val('');
                    $('#lateral_prog').val('');
                    $('#linear_prog').val('');

                    $('#ajaxReturn').removeClass('alert-danger'); //Just in case
                    $('#ajaxReturn').addClass('alert-success').html('<strong>Success!</strong> ' + data.substring(1));
                } else {
                    $('#ajaxReturn').removeClass('alert-success'); //Just in case
                    $('#ajaxReturn').addClass('alert-danger').html('<strong>Warning!</strong> ' + data);
                }
                $('#ajaxReturn').prepend('<button type="button" class="close" data-dismiss="alert" aria-hidden="true">&times;</button>').show();
                window.scrollTo(0, 0);
                console.log(data);
                $('#save').text('Save');
            }
        });
        //}
    }
</script>