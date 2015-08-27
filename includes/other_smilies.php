<?php
				//Setting alt value to emoji so that css selector img[alt="emoji"] will fix their height at 1.2em
echo "array( <br>  
	array( 1, ':)', 'emoji/blush.png', 'emoji'),<br>
	array( 2, ':D', 'emoji/grinning.png', 'emoji'),<br>
	array( 3, ':p', 'emoji/stuck_out_tongue.png', 'emoji'),<br>
	array( 4, ':P', 'emoji/stuck_out_tongue.png', 'emoji'),<br>
	array( 5, ';)', 'emoji/wink.png', 'emoji'),<br>";
$i=6;

if ($handle = opendir('../images/smilies')) {
    /* This is the correct way to loop over the directory. */
    while (false !== ($entry = readdir($handle))) {
		if ($entry != "." && $entry != ".." && $entry != "emoji" && $entry != "holloween-smilies" && $entry != "xmas-smilies") {			
			$noext = file_ext_strip($entry);
			echo "array( ".$i.", ':".$noext.":', '".$entry."', '".ucfirst($noext)."'),<br>";
			//echo ":".$noext.": - : ".$noext." : ";
			
			$i++;
		}
	}
    closedir($handle);
	
	//The last line is important because you can't have a trailing , on the last array item
	echo "array( ".($i) .", ':(', 'emoji/worried.png', 'emoji')<br>	);";
}

function file_ext_strip($filename){
    return preg_replace('/\.[^.]*$/', '', $filename);
}

?>