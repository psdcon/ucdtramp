<?php
require_once ('db.php');


################################################
#            EMOJIONE CODE
# include the PHP library (if not autoloaded)
require('includes/emojione/autoload.php');

$emojione = new Emojione\Client(new Emojione\Ruleset());

// Can't go svg because clicking the svg object doesnt propagate the link
# default is PNG but you may also use SVG
$emojione->imageType = 'png';
# default is ignore ASCII smileys like :) but you can easily turn them on
$emojione->ascii = true;
# use sprite instead of individual images
// $emojione->sprites = true;
# change the default paths for svg
$emojione->imagePathSVGSprites = 'images/emoji/emojione/emojione.sprites.svg';
################################################


$userPosition = '';
$loggedIn = false;
if(isset($_COOKIE['user'])){ // checks for users cookie
    $cookieuser = $_COOKIE['user']; $cookiepass = $_COOKIE['pass']; // Store cookie info
    $dbuser = mysqli_query($db, "SELECT * FROM committee_users WHERE user = '$cookieuser'") or die(mysqli_error());

    while($info = mysqli_fetch_array($dbuser)){
        if ($cookiepass == $info['pass']){
            $loggedIn = true;
            $userPosition = $info['position'];
        }
    }
}
// Theme stuff
// $theme = false;
// $theme = "starwars";
// $theme = "christmas";

function addHeader() {
    // These variables are used in the header.php file.
    // They're given values before addheader function call in all other php files
    global $title,
           $description,
           $userPosition,
           $loggedIn,
           $theme;

    $title = ($title == 'UCD Trampoline Club')? $title: 'UCDTC '.$title;
    include('includes/header.php');
}

function addFooter() {
    global $db, // db connection is closed in footer
           $theme;
    include('includes/footer.php');
}

function nicetime($date){ // makes nice date on forum posts
    $periods         = array("second", "minute", "hour", "day", "week", "month", "year", "decade");
    $lengths         = array("60","60","24","7","4.35","12","10");

    $now             = time();
    $unix_date       = ($date);

       // check validity of date
    if(empty($unix_date)) {
        return "Bad date";
    }

    $difference = $now - $unix_date;
    $tense = "ago";

    for($j = 0; $difference >= $lengths[$j] && $j < count($lengths)-1; $j++) {
        $difference /= $lengths[$j];
    }
    $difference = round($difference);

    if($periods[$j]=='second'){return "less than a min ago";}
    if($difference != 1) {$periods[$j].= "s";}
    return "$difference $periods[$j] {$tense}";
}

// Both ip functions taken from old site for backwards compatability
function encode_ip($dotquad_ip){
    if($dotquad_ip=="::1"){
        return '0.0.0.0' ;
    }
    $ip_sep = explode('.', $dotquad_ip);
    return sprintf('%02x%02x%02x%02x', $ip_sep[0], $ip_sep[1], $ip_sep[2], $ip_sep[3]);
}
function decode_ip($int_ip){
    if($int_ip==NULL){
        return '0.0.0.0' ;
    }
    $hexipbang = explode('.', chunk_split($int_ip, 2, '.'));
    return hexdec($hexipbang[0]). '.'.hexdec($hexipbang[1]).'.'.hexdec($hexipbang[2]).'.'.hexdec($hexipbang[3]);
}

function seconds_to_time($secs){
    $dt = new DateTime('@' . $secs, new DateTimeZone('UTC'));
    $time = array('days'    => $dt->format('z'),
                 'hours'   => $dt->format('G'),
                 'minutes' => $dt->format('i'),
                 'seconds' => $dt->format('s'));
    $str = ($time['days'] == 0)? '': $time['days'].'d ';
    $str .= ($time['hours'] == 0)? '': $time['hours'].'hr';
    $str .= ($time['hours'] > 1)? 's ': '';
    if ($time['days'] == 0)
        $str .= $time['minutes'].'min ';
    return $str;
}

// Turn all recognised URLs into links/image/youtube embed
// Uses regular expressions found on stackoverflow. User regexr.com to figure them out
function URL2link($text){
    // Replace youtube.com and youtu.be links with video in a scaling div with class="forum-vid-container"
    $youtube_regex='(https?\:\/\/)?(www\.youtube\.com/watch\?v=|youtu\.?be)/?(\S{11})';
    $youtube_embed='<div class="embed-bounding-box"><div class="embed-responsive"><iframe class="embed-responsive-item" src="https://www.youtube.com/embed/$3" allowfullscreen></iframe></div></div>';
    $text = preg_replace('#'.$youtube_regex.'#i',$youtube_embed, $text);

    // Show img when a jpg, gif or png are posted
    // $img_regex='((https?:)([/|.|\w|])*\.(jpg|gif|png|svg))';
    $img_regex='((https?:)([/.\w-])*\.(jpg|gif|png|svg))';
    $img_replace='<a class="post-image" href="$1" target="_blank"><img src="$1"></a>';
    $text = preg_replace('#'.$img_regex.'#i',$img_replace, $text);

    // If url wasnt already recognised as a youtube link or an image, make it a clickable link
    $URL_reg='((([A-Za-z]{3,9}:(?:\/\/)?)(?:[-;:&=\+\$,\w]+@)?[A-Za-z0-9.-]+|(?:www.|[-;:&=\+\$,\w]+@)[A-Za-z0-9.-]+)((?:\/[\+~%\/.\w-_]*)?\??(?:[-\+=&;%@.\w_]*)#?(?:[\w]*))?)';
    if(!preg_match('!src="'.$URL_reg.'!i',$text)){ //if a url is not already part of a src (preceded by src=") then make it an anchor
        $text = preg_replace('!'.$URL_reg.'!i', '<a target="_blank" href="$1">$1</a>', $text);
        // TODO CHeck for https? and add if not there
    }

    return $text;
}

// Other faces/gifs (not emoji)
$ucdtcSmilies = array(
    // Order: smiley shortname, image path, alt/description
    array(':osmile:', 'normal-smilies/osmile.gif', 'smile'),
    array(':obiggrin:', 'normal-smilies/obiggrin.gif', 'biggrin'),
    array(':olol:', 'normal-smilies/olol.gif', 'lol'),
    array(':omrgreen:', 'normal-smilies/omrgreen.gif', 'mrgreen'),
    array(':owink:', 'normal-smilies/owink.gif', 'wink'),
    array(':ocool:', 'normal-smilies/ocool.gif', 'cool'),
    array(':orazz_alt:', 'normal-smilies/orazz_alt.gif', 'razzle dazzle alt'),
    array(':orazz_old:', 'normal-smilies/orazz_old.gif', 'razzle dazzle old'),
    array(':orazz:', 'normal-smilies/orazz.gif', 'razzle dazzle'),
    array(':oeek:', 'normal-smilies/oeek.gif', 'eek'),
    array(':orolleyes:', 'normal-smilies/orolleyes.gif', 'rolleyes'),
    array(':oredface:', 'normal-smilies/oredface.gif', 'redface'),
    array(':osurprised:', 'normal-smilies/osurprised.gif', 'surprised'),
    array(':oneutral:', 'normal-smilies/oneutral.gif', 'neutral'),
    array(':oconfused:', 'normal-smilies/oconfused.gif', 'confused'),
    array(':ofrown:', 'normal-smilies/ofrown.gif', 'frown'),
    array(':osad:', 'normal-smilies/osad.gif', 'sad'),
    array(':ocry:', 'normal-smilies/ocry.gif', 'cry'),
    array(':omad:', 'normal-smilies/omad.gif', 'mad'),
    array(':oevil:', 'normal-smilies/oevil.gif', 'evil'),
    array(':otwisted:', 'normal-smilies/otwisted.gif', 'absolutely bleedin twisted'),
    array(':oflamed:', 'normal-smilies/flamed.gif', 'a boy who\'s sad because he has red hair'),
    array(':ocamera:', 'normal-smilies/ocamera.gif', 'camera'),
    array(':oarrow:', 'normal-smilies/oarrow.gif', 'arrow'),
    array(':oquestion:', 'normal-smilies/oquestion.gif', 'question'),
    array(':oexclaim:', 'normal-smilies/oexclaim.gif', 'exclaim'),
    array(':oidea:', 'normal-smilies/oidea.gif', 'idea'),
    array(':tramp:', 'normal-smilies/tramp.gif', 'The best smiley ever'),
    // Purples
    array(':psmile:', 'normal-smilies/psmile.gif', 'Purple smile'),
    array(':pbiggrin:', 'normal-smilies/pbiggrin.gif', 'Purple biggrin'),
    array(':prazz:', 'normal-smilies/prazz.gif', 'Purple razz'),
    array(':pevil:', 'normal-smilies/pevil.gif', 'Purple evil'),
    array(':pneutral:', 'normal-smilies/pneutral.gif', 'Purple neutral'),
    array(':peh:', 'normal-smilies/peh.gif', 'Purple eh'),
    array(':pconfused:', 'normal-smilies/pconfused.gif', 'Purple confused'),
    array(':psad:', 'normal-smilies/psad.gif', 'Purple sad'),
    array(':psurprised:', 'normal-smilies/psurprised.gif', 'Purple surprised'),
    array(':pwince:', 'normal-smilies/pmad.gif', 'Purple mad'),
    array(':pheart:', 'normal-smilies/pheart.gif', 'Purple heart'),
    array(':pstar:', 'normal-smilies/pstar.gif', 'Purple star'),
    array(':ptramp:', 'normal-smilies/ptramp.gif', 'The best smiley ever in purple'),
    // Msc
    array(':flappers:', 'normal-smilies/oflappers.jpg', 'Flappers'),
    array(':best:', 'normal-smilies/me.jpg', 'Me'),
    array(':bosco:', 'normal-smilies/bosco.jpg', 'Bosco'),
    array(':ofrog:', 'normal-smilies/ofrog.gif', 'Ofrog'),
    array(':ofox:', 'normal-smilies/ofox.gif', 'Ofox'),
    array(':bdaycake:', 'normal-smilies/ocake.gif', 'Cake'),
    array(':tim:', 'normal-smilies/tim.jpg', 'Tim'),
    array(':hearttim:', 'normal-smilies/hearttim.png', 'Hearttim'),
    array(':whydontyouloveusvincent:', 'normal-smilies/whydontyouloveusvincent.jpg', 'Why dont you love us vincent'),
    array(':lolol:', 'normal-smilies/LOL.gif', 'Hiiiiiiiiiiiilarious'),
);
$ucdtcSmiliesHalloween = array(
    // Halloween smilies
    array(':bat.:', 'holloween-smilies/bat.gif', 'Bat'),
    array(':cat.:', 'holloween-smilies/cat.gif', 'Cat'),
    array(':demon.:', 'holloween-smilies/demon.gif', 'Demon'),
    array(':ghost.:', 'holloween-smilies/ghost.gif', 'Ghost'),
    array(':monster.:', 'holloween-smilies/monster.gif', 'Monster'),
    array(':monsterII.:', 'holloween-smilies/monsterII.gif', 'MonsterII'),
    array(':mummy.:', 'holloween-smilies/mummy.gif', 'Mummy'),
    array(':pumpkin.:', 'holloween-smilies/pumpkin.gif', 'Pumpkin'),
    array(':skull.:', 'holloween-smilies/skull.gif', 'Skull'),
);
$ucdtcSmiliesXmas = array(
    // Xmas smilies
    array(':angel.:', 'xmas-smilies/angel.gif', 'Angel'),
    array(':ball.:', 'xmas-smilies/ball.gif', 'Ball'),
    array(':biggrin.:', 'xmas-smilies/biggrin.gif', 'Big grin'),
    array(':confused.:', 'xmas-smilies/confused.gif', 'Confused'),
    array(':cool.:', 'xmas-smilies/cool.gif', 'Cool'),
    array(':cry.:', 'xmas-smilies/cry.gif', 'Cry'),
    array(':eek.:', 'xmas-smilies/eek.gif', 'Eek'),
    array(':evil.:', 'xmas-smilies/evil.gif', 'Evil'),
    array(':exclaim.:', 'xmas-smilies/exclaim.gif', 'Exclaim'),
    array(':frown.:', 'xmas-smilies/frown.gif', 'Frown'),
    array(':idea.:', 'xmas-smilies/idea.gif', 'Idea'),
    array(':lol.:', 'xmas-smilies/lol.gif', 'Lol'),
    array(':mad.:', 'xmas-smilies/mad.gif', 'Mad'),
    array(':mrgreen.:', 'xmas-smilies/mrgreen.gif', 'Mrgreen'),
    array(':neutral.:', 'xmas-smilies/neutral.gif', 'Neutral'),
    array(':question.:', 'xmas-smilies/question.gif', 'Question'),
    array(':raindeer.:', 'xmas-smilies/raindeer.gif', 'Raindeer'),
    array(':razz.:', 'xmas-smilies/razz.gif', 'Razz'),
    array(':redface.:', 'xmas-smilies/redface.gif', 'Redface'),
    array(':rolleyes.:', 'xmas-smilies/rolleyes.gif', 'Rolleyes'),
    array(':rudolph.:', 'xmas-smilies/rudolph.gif', 'Rudolph'),
    array(':sad.:', 'xmas-smilies/sad.gif', 'Sad'),
    array(':santy.:', 'xmas-smilies/santy.gif', 'Santy'),
    array(':smile.:', 'xmas-smilies/smile.gif', 'Smile'),
    array(':snowflake.:', 'xmas-smilies/snowflake.gif', 'Snowflake'),
    array(':snowman.:', 'xmas-smilies/snowman.gif', 'Snowman'),
    array(':surprised.:', 'xmas-smilies/surprised.gif', 'Surprised'),
    array(':tramp.:', 'xmas-smilies/tramp.gif', 'Tramp'),
    array(':tree.:', 'xmas-smilies/tree.gif', 'Tree'),
    array(':twisted.:', 'xmas-smilies/twisted.gif', 'Twisted'),
    array(':wink.:', 'xmas-smilies/wink.gif', 'Wink'),
    array(':2muchpunch.:', 'xmas-smilies/2muchpunch.gif', 'A bit too much punch'),
);

// Change smilies to images
function smilify($text, $poster) {
    global $ucdtcSmilies;
    global $emojione;

    // Formatting text to html first
    // Colour codes
    $text = preg_replace('/:red:/i', '<span style="color:#FF0000">', $text);
    $text = preg_replace('/:blue:/i', '<span style="color:#0000FF">', $text);
    $text = preg_replace('/:green:/i', '<span style="color:#32CD32">', $text);
    $text = preg_replace('/:pink:/i', '<span style="color:#FF1493">', $text);
    $text = preg_replace('/:purple:/i', '<span style="color:#C71585">', $text);
    $text = preg_replace('/:orange:/i', '<span style="color:#FF4500">', $text);
    $text = preg_replace('/:gray:/i', '<span style="color:#808080">', $text);
    $text = preg_replace('/:silver:/i', '<span style="color:#COCOCO">', $text);
    $text = preg_replace('/:thesecretcolour:/i', '<span class="forum-secret-color">', $text);
    $text = preg_replace('/:endcolour:/i', '</span>', $text);
    $text = preg_replace('/:endc:/i', '</span>', $text);

    // Formatting codes
    $text = preg_replace('/\*\*(.*)\*\*/Uis', '<strong>$1</strong>', $text); // U regex modifier makes match ungreedy so it stops at the first match
    $text = preg_replace('/\*(.*)\*/Uis', '<em>$1</em>', $text);
    $text = preg_replace('/__(.*)__/is', '<u>$1</u>', $text);
    $text = preg_replace('/--(.*)--/is', '<strike>$1</strike>', $text);
    $text = preg_replace('/\^\^(.*)\^\^/is', '<span class="forum-big-text">$1</span>', $text);

    // Site-wide replacements incl news, forum and polls
    $text = preg_replace('/Deirdre/i', 'BJ', $text);
    $text = preg_replace('/D eirdre/i', 'BJ', $text);
    $text = preg_replace('/D.eirdre/i', 'BJ', $text);
    $text = preg_replace('/deirdre/i', 'BJ', $text);
    $text = preg_replace('/deirdre/i', 'BJ', $text);

    $text = preg_replace('/Cormac H/i', 'Norman', $text);

    $text = preg_replace('/Hannah the Hun/i', 'Attilla the Hun', $text);


    // Change colour of Orla's name
    //$colors = ['#FF0000','#0000FF','#32CD32','#FF1493','#C71585','#FF4500','#COCOCO'];
    //$text = preg_replace('/Orla/i', '<span style="color:'.$colors[date("N", time())-1].'">Orla</span>', $text);

    // Hiding Committee Profiles
    $glasgowsMessage = "I'm a stupid moron with an ugly face and a big butt and my butt smells and I, uh, like to kiss my own butt";
    // $text = preg_replace('/rosemanbolhand/i', $glasgowsMessage, $text);
    $text = preg_replace('/colmwillbringshameonhisfamily/i', $glasgowsMessage, $text);
    $text = preg_replace('/nicoletrinidadandtobago/i', $glasgowsMessage, $text);
    $text = preg_replace('/colecolemanthecoalman/i', $glasgowsMessage, $text);
    $text = preg_replace('/longfordmorelikeshortford/i', $glasgowsMessage, $text);
    $text = preg_replace('/mariannedoesntacuallysuckexceptshedoes/i', $glasgowsMessage, $text);
    $text = preg_replace('/https:\/\/ucdtramp.com\/page\/justletgoalreadyoldman/i', $glasgowsMessage, $text);

    $text = preg_replace('/:colmstar:/i', '<a href="page/colmwillbringshameonhisfamily"><img title="not your averge joe soap" class="forum-original-emoji" src="images/emoji/normal-smilies/pstar.gif" alt="Purple star"></a>', $text);

    // ForumUser-only replacements, not site-wide
    if($poster == 'Sinead'){$text = preg_replace('/Sinead/i', 'Flaps', $text);}

    if($poster == 'Jordan'){$text = preg_replace('/Jordan/i', 'Obama', $text);}
    if($poster == 'J o r d a n'){$text = preg_replace('/J o r d a n/i', 'Obama', $text);}
    if($poster == 'J_o_r_d_a_n'){$text = preg_replace('/J_o_r_d_a_n/i', 'Obama', $text);}


    if($poster == 'Colm'){$text = preg_replace('/C:pstar:o:pstar:l:pstar:m/i', 'Obama', $text);}

    // Smiley face image replacements from the array defined above
    for ($i = 0; $i < count($ucdtcSmilies); $i++){
        $original[] = "/".preg_quote($ucdtcSmilies[$i][0])."/i";
        $replacement[] = '<img class="forum-original-emoji" src="images/emoji/'.$ucdtcSmilies[$i][1].'" alt="'.$ucdtcSmilies[$i][2].'"/>';
    }
    $text = preg_replace($original, $replacement, $text);

    //
    return $emojione->shortnameToImage($text);
}
