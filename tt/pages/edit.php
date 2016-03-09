<?php
require_once('../includes/db.php');
?>

<ol class="breadcrumb">
    <li><a href="#edit/all">Edit</a></li>
    <li class="active" id="crumb">All</li>
</ol>
<hr>

<div class="btn-group level-buttons" style="padding-bottom:1.5em;">
    <button type="button" class="btn btn-default active">All</button>
    <button type="button" class="btn btn-default">Novice</button>
    <button type="button" class="btn btn-default">Intermediate</button>
    <button type="button" class="btn btn-default">Advanced</button>
    <button type="button" class="btn btn-default">Elite</button>
</div>

<?php
for($i=0;$i<4;$i++){
    if($i==0)     $level='novice';
    elseif($i==1) $level='intermediate';
    elseif($i==2) $level='advanced';
    elseif($i==3) $level='elite';
    
    $skills = mysqli_query($db, "SELECT * FROM skills WHERE level='".$level."' ORDER BY id ASC");
    echo '<div class="panel panel-primary skills '.$level.'">
            <div class="panel-heading">'.ucfirst($level).'</div>
            <table class="table table-hover">
                <thead>
                    <tr>
                        <th></th>
                        <th style="width:35%">Name</th>
                        <th style="width:62%">Short description</th>
                    </tr>
                </thead>

                <tbody>';
    
    while($skill = mysqli_fetch_array($skills)){
        echo '<tr ng-click="go(\'/edit/'.$level.'/'.$skill['id'].'\')">
                <td></td>
                <td>'.$skill['name'].'</td>
                <td>'.$skill['short_desc'].'</rd>
            </tr>';
    }
	
	echo '    </tbody>
            </table>
        </div>';
} ?>

<script>
    // Add click handler to level buttons
    $('.level-buttons').children().click(changeLevel);

    function changeLevelByName(name){
        var levels = ['all','novice','intermediate','advanced','elite'];
        var elIndex = levels.indexOf(name);
        $('.level-buttons').children().eq(elIndex).trigger('click');
    }

    function changeLevel() {
        $('.level-buttons .active').removeClass('active');
        $(this).addClass('active');

        var levelText = $(this).text();
        $('#crumb').text(levelText);
        levelText = levelText.toLowerCase();

        if (levelText == 'all') {
            $('.skills').slideDown();
        } else {
            if (levelText != 'novice') {
                $('.novice').slideUp();
            }
            if (levelText != 'intermediate') {
                $('.intermediate').slideUp();
            }
            if (levelText != 'advanced') {
                $('.advanced').slideUp();
            }
            if (levelText != 'elite') {
                $('.elite').slideUp();
            }
            $('.' + levelText).slideDown();
        }
    }
    
</script>