<?php
include_once './includes/functions.php';

// This had to be manually set up based on the json in the emojiSorted var in emoji.js
$tabs = array(
    "people" => array("People", ":smile:"),
    "nature" => array("Nature", ":whale:"),
    "food_drink" => array("Food and drink", ":doughnut:"),
    "celebration" => array("Celebration", ":tada:"),
    "activity" => array("Activity", ":lifter:"),
    "travel_places" => array("Travel and places", ":rocket:"),
    "objects_symbols" => array("Objects and symbols", ":high_heel:"),
);
?>

<!-- Emoji tabs -->
<ul class="nav nav-tabs emoji-tabs" role="tablist">
    <?php foreach ($tabs as $tabCatagory => $tabInfo) {
        echo '
        <li role="presentation">
            <a class="emoji-tabs__title" href="forum#'.$tabCatagory.'" title="'.$tabInfo[0].'" aria-controls="'.$tabCatagory.'" role="tab" data-toggle="tab">'.
                smilify($tabInfo[1], NULL).'
            </a>
        </li>';
    } ?>
    <li role="presentation">
        <a class="emoji-tabs__title--ucdtc" href="#ucdtc-smilies" title="UCDTC's Home grown oldies" aria-controls="ucdtc-smilies" role="tab" data-toggle="tab">
            <img src="images/emoji/normal-smilies/pstar.gif" alt="Pstar" title="Pstar">
        </a>
    </li>
</ul>

<!-- Emojoi tab panes -->
<div class="tab-content">
    <?php
    // Sort each catagory by the catagory order var
    foreach ($tabs as $tabCatagory => $tabInfo) {
        echo '
        <div role="tabpanel" class="tab-pane" id="'.$tabCatagory.'">
            <ul class="emoji-tab__list">
                <!-- filed in by javascript -->
                Emoji in the '.$tabCatagory.' category failed to load...
            </ul>
        </div>';
    }
    ?>

    <div role="tabpanel" class="tab-pane" id="ucdtc-smilies">
       <ul class="emoji-tab__list">
            <?php 
            foreach ($ucdtcSmilies  as $emojiArray) {
                // So that people who don't know the code can't get it by clicking it
                $shortname = ($emojiArray[0] == ':tramp:' || $emojiArray[0] == ':ptramp:')? ':best:' : $emojiArray[0];

                echo '
                <li class="emoji-icon" data-shortname="'.$shortname.'">
                    <img src="images/emoji/'.$emojiArray[1].'" alt="'.$emojiArray[2].'" title="'.$emojiArray[2].'">
                </li>';
            }
            ?>
       </ul>
    </div>
</div>