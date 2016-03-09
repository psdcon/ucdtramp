<?php
include_once 'includes/functions.php';

$jsonFile = 'js/youtube.json';
if (isset($_POST['action']) && $_POST['action'] == 'saveJson'){
    $json = $_POST['json'];
    file_put_contents($jsonFile, $json);
    die();
}

if (isset($_GET['updateVideoList'])){
    $title = "Youtube Update";
    addHeader();
    ?>

    <h3>Update Youtube Vids</h3>
    <p>
        The videos are being loading using Youtube's API. Progress is reported below, the count is 1000+. If it stays on loading for more than a few seconds something is broken...
    </p>
    <p>
        When new videos go up and the script can't recognise the competition name, open <code>js/youtubeapi.js</code> and add the competition name to the <code>competitions</code> array.
    </p>
    <p>
        <a href="youtubevids" class="btn btn-default">Back to List</a>
    </p>

    <pre class="status">Loading....</pre>
    <div class="html-container"></div>

    <script src="js/youtubeapi.js"></script>
    <script src="https://apis.google.com/js/client.js?onload=googleApiClientReady"></script>

    <?php
    addFooter();
}
else {
    $title = "Youtube Vids";
    $description = "Trampoline routines grouped by person";
    addHeader();
    ?>
    <h3>Youtube Vids</h3>
    <p>
        Below is a list of all the videos on our <a href="https://www.youtube.com/channel/UCjU7L7uekUY4T-7cGbPE_HQ">Youtube channel</a> grouped by name. 
        At the bottom you'll find synchro vids and a mess of a mess of a list of everything else on the channel.
    </p>

    <?php
    if ($loggedIn){
        echo '
        <p>
            Yo Committee Member, <br>
            When new videos get uploaded to the Youtube channel, click this button. 
            Wait for that page to load all the videos from youtube so it can save them to the server. Only takes a minute. <br><br>
            <a class="btn btn-primary" href="youtubevids.php?updateVideoList">Update List</a>
        </p>
        ';
    }

    $json = json_decode(file_get_contents($jsonFile), true);
    if ($json == null){
    	echo "JSON Decode failed. Error in youtube.json file";
    }
    // Move the synchro and msc vids to the bottom of the page
    $synchroVids = $json['Synchro'];
    $mscVids = $json['Msc'];
    unset($json['Synchro']);
    unset($json['Msc']);
    $json['Synchro'] = $synchroVids;
    $json['Everything Else'] = $mscVids;

    function name2IdLink($str){
        return preg_replace('/[ \']/im', '', $str);
    }

    $nameCount = 0; $listOfNames='';

    foreach ($json as $name => $vidsArray) {
        if ($vidsArray[0]['year'] == '2006')
            continue;
        
        $listOfNames .= '<a href="youtubevids#'.name2IdLink($name).'">'.$name.'</a>';
    }

    echo '
    <style>
        #allDeNames a {
            width: 126px;
            float:left;
        }
    </style>
    <button class="btn btn-default" type="button" data-toggle="collapse" data-target="#allDeNames" aria-expanded="false" aria-controls="allDeNames">
        Show Names List
    </button>
    <div class="collapse well clearfix" id="allDeNames">
        '.$listOfNames.'
    </div>';


    // Go through each person
    foreach ($json as $name => $vidsArray) {
        if ($vidsArray[0]['year'] == '2006')
            continue;

        $prevYear = '';
        $html = '<div>';
        // Make the html to each video, grouping by year
        foreach ($vidsArray as $vidIndex => $vid) {
            if ($prevYear != $vid['year']){
                $html .= '
                    </div>
                    <div class="col-md-6">
                        <strong>'.$vid['year'].'</strong> <br>
                ';
            }
            $prevYear = $vid['year'];
            $html .= $vid['comp'].': <a target="_blank" href="https://www.youtube.com/watch?v='.$vid['urlId'].'">'.$vid['title'].'</a><br>';
        }
        $html .= '</div>';
        // Spit out the final html
        echo '
        <div class="person" id="'.name2IdLink($name).'">
            <h4>'.$name.' <small>'.count($vidsArray).' videos</small></h4>
            <div class="row">
                <div class="col-md-2">
                    <a target="_blank" href="https://www.youtube.com/watch?v='.$vidsArray[0]['urlId'].'">
                        <img style="max-width:100%;" src="'.$vidsArray[0]['thumb'].'" alt="'.$name.'s youtube thumbnail">
                    </a>
                </div>
                <div class="col-md-10">
                    <div class="row">
                        '.$html.'
                    </div>
                </div>
            </div>
        </div>
        ';
    }
    addFooter();
}
?>