<?php
require_once('../includes/db.php');

function tags_to_links($tags_string){global $db;
	$tag_array = explode( ',', $tags_string ); //split tags into array so each skill is an element
	//echo'Array length is '.count($tag_array).'<br>';
	for($i=0;$i<count($tag_array);$i++){ //do for each skill in array
		if(is_numeric($tag_array[$i][0])){
			for($j=0;$j<4;$j++){ //find how far offset the full stop is (stored in $j)
				if($tag_array[$i][$j]=='.')
					break; //exit when full stop is found
			}
			if($j==1) // skill id is 1 digit long
				$skill_id = $tag_array[$i][0];
			elseif($j==2) // skill id is 2 digits long
				$skill_id = $tag_array[$i][0].$tag_array[$i][1];
			elseif($j==3) // skill id is 3 digits long
				$skill_id = $tag_array[$i][0].$tag_array[$i][1].$tag_array[$i][2];
			
			// find the skill in the db based on its id. Make a browse link with the skills name is the anchor text
			$skill_name_query = mysqli_query($db, "SELECT * FROM skills WHERE id='$skill_id' LIMIT 1");
			while($skill_name = mysqli_fetch_array($skill_name_query)){
				echo '<a href="#" onClick="return loadPage(\'browse.php?level='.$skill_name['level'].'&skill_id='.$skill_name['id'].'\',\'browse\')">'.$skill_name['name'].'</a><br>';
				if($skill_name['name']=''); //Skill starts with number but no corresponding id in db
					echo $tag_array[$i+1].' - Skill not in db<br>';
			}
		}
		else {
			echo $tag_array[$i].'<br>';
		}
	}
}

$skill_query = mysqli_query($db, "SELECT * FROM skills WHERE id=".$_GET['skill_id']." ORDER BY id ASC");
while($skill = mysqli_fetch_array($skill_query)){ ?>
	<ol class="breadcrumb">
        <li><a href="#browse/all">Browse</a></li>
        <li><a href="#browse/<?=$skill['level']?>"><?=ucfirst($skill['level'])?></a></li>
        <li class="active"><?=$skill['name']?></li>
    </ol>

    <div class="panel panel-primary">
        <div class="panel-heading">
            <h4 style="float:left;"><?=$skill['name']?></h4>
            <p style="float:right;position:relative;top:.5em;">
                Tariff: <?=$skill['tariff']?>
                <a href="#" class="btn btn-default" role="button" onClick="return loadPage('edit.php?edit_id=<?=($skill['id'])?>','edit')">
                    <span class="glyphicon glyphicon-pencil"></span> Edit
                </a>
            </p>
            <div style="clear:both"></div>
        </div>
        <ul class="list-group">
            <li class="list-group-item">
                <h3>Long Description</h3>
                <p>
                    <?=$skill['long_desc']?>
                </p>
            </li>
            <li class="list-group-item">
                <h3>Coaching Points</h3>
                <p>
                    <?=$skill['coaching_points']?>
                </p>
            </li>
            <li class="list-group-item">
                <h3>Prerequisite Skills</h3>
                <p class="well well-sm">
                    <?=tags_to_links($skill['prereq'])?>
                </p>
            </li>
            <li class="list-group-item">
                <h3>Lateral Progressions</h3>
                <p class="well well-sm">
                    <?=tags_to_links($skill['lateral_prog'])?>
                </p>
            </li>
            <li class="list-group-item">
                <h3>Linear Progressions</h3>
                <p class="well well-sm">
                    <?=tags_to_links($skill['linear_prog'])?>
                </p>
            </li>
            <li class="list-group-item">
                <h3>Video notes</h3>
                <p class="well well-sm">
                    <?=$skill['vid']?>
                </p>
            </li>
        </ul>
    </div> 
<?php
} ?>