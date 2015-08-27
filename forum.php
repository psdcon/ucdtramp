<?php
include_once('includes/functions.php');

if (isset($_GET['forum'])){
	$forum_query = mysqli_query($db, "SELECT * FROM forums WHERE id='".$_GET['forum']."'");
} else {
	$forum_query = mysqli_query($db, "SELECT * FROM forums WHERE id='1'");
}
$forum_details = mysqli_fetch_array($forum_query); //select posts from specified forum, default to public forum

// Check that the requested forum exists and that the user has permission to view it.
if(!$forum_details || (!$loggedin && $forum_details['perms']>0)){
	header("Location: http://www.ucdtramp.com/page/404#cont");
} else {
	$forum = $forum_details['id'];
}

// Delete post by changing the forum it appears in to 0
if (isset($_GET['delete']) && $userpos=='webmaster'){
	$message_id = $_GET['delete'];
	
	mysqli_query($db, "UPDATE forum_posts 
				 SET forum='0' 
				 WHERE forum='".$forum_details['id']."' 
				 AND id='".mysqli_real_escape_string($db,$message_id)."'
				 LIMIT 1");
	if (mysqli_error($db))
		exit(mysqli_error($db));
	else
		header('Location:http://www.ucdtramp.com/forum/'.$forum.'');
}

$start = (isset($_GET['start']))?$_GET['start']:0;

		$all_posts = mysqli_query ($db, "SELECT * FROM forum_posts WHERE forum='$forum'");
		$num_posts = mysqli_num_rows($all_posts);
		$num_pages = ceil($num_posts/$forum_details['posts_per_page']);
		
		$memos = mysqli_query($db, "SELECT * FROM forum_posts 
									WHERE forum='".$forum_details['id']."' AND parent_id='0' 
									ORDER BY id DESC 
									LIMIT ".$start.", ".$forum_details['posts_per_page']);
		
		$autoGenMsgDB = mysqli_query($db, "SELECT * FROM forum_posts 
											WHERE forum='1' ORDER BY id DESC 
											LIMIT ".$start.", ".$forum_details['posts_per_page']);

$autoGenMsgTimer = time() - 86400/2; //60sec*60mins*24hours = seconds in 24hours
$autoGenMsgLastPost=mysqli_fetch_array($autoGenMsgDB);

$dayLaterTime=$autoGenMsgLastPost['post_time']+86400/2;
if($autoGenMsgTimer > $autoGenMsgLastPost['post_time']  && $forum_details['posts_per_page'] > $start && $forum_details['id']==1)
{	
	/*$unused_photos = mysqli_query($db, "SELECT * FROM  `photo_punishment` WHERE  `used` <2");
	$selected_photo=mt_rand(0,mysqli_num_rows($unused_photos)-1); // select a random number
	//offset by this number. id's wouldnt work cause these will change as more are added
	$punish_photo=mysqli_query($db, "SELECT * FROM `photo_punishment` WHERE `used` <2 ORDER BY `id` LIMIT 1 OFFSET ".$selected_photo." ");
	while($punish=mysqli_fetch_array($punish_photo)){
		$autoMessage="************************** AUTOMATICALLY GENERATED MESSAGE **************************

Pay attention to me or I will show you more Cian...
		
		http://www.ucdtramp.com/images/punishment_photos/".$punish['img'];
		mysqli_query($db, "UPDATE photo_punishment SET used=used+1 WHERE id='".$punish['id']."'");
	}*/

$pickup = array("Do you live on a chicken farm?  'Cause you sure know how to raise a cock.",
"Are you a drill sergeant? Because you have my privates standing at attention.",
"You're just like my little toe, because I'm going to bang you on every piece of furniture in my home.",
"Do you mix concrete for a living? Because you're making me hard.",
"If you're feeling down, I can feel you up.",
"My dick just died. Would you mind if I buried it in your ass?",
" Are your legs made of Nutella? Because I'd love to spread them!",
"There will only be 7 planets left after I destroy Uranus.",
"I'm no weather man, but you can expect more than a few inches tonight.",
"Do you work at Subway? Because you just gave me a footlong.",
"I may not go down in history, but I'll go down on you.",
"That shirt's very becoming on you. If I were on you, I'd be coming too.",
"Do you know the difference between my dick and a chicken wing? No? Well, let's go on a picnic and find out!",
" Forget that! Playing doctor is for kids! Let's play gynecologist.",
"Are you a termite? Cause you're about to have a mouth full of wood.",
"Your face reminds me of a wrench, every time I think of it my nuts tighten up.",
"Excuse me, but would you like an orally stimulated orgasm?",
"Do you run track? Cause I heard you Relay want this dick.",
"Are you from the ghetto? Cause I'm about to ghetto hold of dat ass.",
"You know what I like in a girl? My dick.",
"Are you a doctor? cause you just cured my erectile dysfunction.",
"Your legs are like an Oreo Cookie - I wanna split them and eat all the good stuff in the middle.",
"Have you ever kissed a rabbit between the ears? [Pull your pockets inside out] Would you like to?",
"I lost my virginity. Can I have yours?",
"Hey babe, how about a pizza and a f**k? [No] What's wrong, don't you like pizza?",
"Hey, you wanna do a 68? You go down on me, and I'll owe you one.",
"Someone vacuum my lap, I think this girl needs a clean place to sit.",
"If I could rearrange the alphabet, I'd put 'U' between 'F' and 'CK'",
"F**k me if I'm wrong, but isn't your name Laura?",
"Hey Baby! I'd like to use your thighs as earmuffs.",
"You might not be the best looking girl here, but beauty is only a light switch away.",
"Are you from Iraq? 'Cause I like the way you Baghdad ass up.",
"Your breasts remind me of Mount Rushmore - my face should be among them.",
"Hey baby, I think you just made my two by four into a four by eight.",
"Is that a keg in your pants? Because I'd love to tap that ass.",
"Hey cutie, wanna go halves on a baby?",
"You can call me cake, cause I'll go straight to your ass.",
"Are you hungry? Cause omelette you suck this dick.",
"Do you like pudding? Cause I'll be pudding this dick in your ass.",
"Can I read your t-shirt in braille?",
"Do you know your ABC's? Cause I wanna give you the 4th letter of the alphabet.",
"Are you an archaeologist? Because I've got a bone for you to examine.",
"I'll give you a nickel if you tickle my pickle.",
"You are so selfish! You're going to have that body the rest of your life and I just want it for one night.",
"Just remember: To you, I am a virgin.",
"What's the speed limit of sex? [what?] 68. Because at 69 YOU have to turn around!",
"I'm an astronaut and my next mission is to explore Uranus.",
"I'm like a Rubik's Cube, the more you play with me the harder I get!",
"What's the difference between a Ferrari and an erection? I don't have a Ferrari.",
"Hi, do you want to have my children? [No] OK, can we just practice then?",
"I'm afraid of the dark... Will you sleep with me tonight?",
"I love my bed but I'd rather be in yours.",
"Baby, I'm like a firefighter, I find 'em hot and leave 'em wet!",
"I spent over a grand on Viagra today, only to come here and see you and find out that I don't need it after all.",
"Brrr! My hands are cold. Can I warm them in your heaving breasts?",
"I'm hung like a tic tac. Wanna freshen your breath?",
"Do you come here often or wait till you get home?",
"Do you have a mirror in your pocket? (Why?) Because I can see myself in your pants.",
"Do you wash your panties with Windex? Because I can really see myself in them.",
"Do you need a stud in your life? Cause I got the STD and all I need is U.",
"Why pay for a bra, when I would gladly hold your boobs up all day for free?",
"You smell like trash. May I take you out?",
"If I had AIDS, would you have sex with me? [No] Well, I don't, so let's go.",
"Excuse me, but do you give head to strangers? [No] Well then, allow me to introduce myself.",
"I wanna floss with your pubic hair.",
"I want to melt in your mouth, not in your hand.",
"If your right leg was Christmas and your left leg was Easter, would you let me come for dinner between the holidays?",
"That dress looks great on you...as a matter of fact, so would I.",
"So, come back to my place, and if you don't like it I swear I'll give you a full refund.",
"Miss, If you've lost your virginity, can I have the box it came in?",
"Let's have a party and invite your pants to come on down.",
"Do you have any Italian in you? Would you like some?",
"Hey baby, let's play house, you can be the door and I'll slam you all night long!",
"Hi, my name is &quot;Milk.&quot; I'll do your body good.",
"I think I could fall madly in bed with you.",
"Let's play carpenter. First we'll get hammered, then I'll nail you.",
"We're like hot chocolate and marshmallows... You're hot and I wanna be on top of you.",
"Wanna go on an 'ate' with me? I'll give you the 'D' later.",
"You're so hot, even my pants are falling for you!",
"Are you from the Philippines? Because I wanna phil you with my penis.",
"Do you like Ramen Noodles? Cuz I'll be Rammin' my noodle in you later.",
"Are you spaghetti cause I want you to meat my balls.",
"Do you like whales? Cause we can go hump back at my place.",
"Baby I last longer than a white crayon.",
"Do you like to draw? Cause I put the D in Raw.",
"We should play strip poker. You can strip, and I'll poke you.",
"You remind me of the movie &quot;Scarface&quot; cause I want you to say hello to my little friend.",
"Do you like Adele? Cause I can tell you wanna be rolling in the D.",
"Girl, you should sell hotdogs, because you already know how to make a wiener stand.",
"I had a wet dream about you last night. Would you like to make it a reality?",
"&quot;Do you like cherries?&quot; [No.] &quot;Ok, can I have yours?&quot; ",
"Do you know what winks and screws like a tiger? [No] Wink.",
"Hey baby, wanna play lion? You go kneel down right there and I'll throw you my meat.",
"<em>[Excuse me, do you have the time?]</em> &quot;Yes, do you have the energy?&quot;",
"At the office copy machine &quot;Reproducing eh?&quot; &quot;Can I help?&quot;",
"Do you have a phone in your back pocket? Because your booty is calling me.",
"(Use index finger to call someone over then say) I made you come with one finger, imagine what I could do with my whole hand.",
"Hi, wanna f**k? [No] Mind lying down while I do?",
"I know a great way to burn off the calories in that pastry you just ate.",
"I miss my teddy bear. Would you sleep with me?",
"Is your name daisy? Because I have a sudden urge to plant you right here!",
" Does your ass&nbsp;have Allstate insurance? [No, why?] Well do you want it&nbsp;to be in good hands? ",
"Let me insert my plug into your socket and we can generate some electricity.",
"You have been very naughty. Go to my room!",
"Do you like Wendy's? Cause you're gonna love Wendy's nuts slap yo face!",
"Don't ever change. Just get naked.",
"Are those jeans Guess? Cause guess who wants to be inside them...",
"Do you like bacon? Wanna strip?",
" Hey there, I just took some Cialis and I have 18 hours left.",
"I must expel some seminal fluid. May I use your body?",
"Hold out two fingers and say: &quot;Why should a woman masturbate with these two fingers?&quot; (I don't know.) &quot;'Cause they're mine sweetheart.&quot;",
"I wanna put my thingy into your thingy.",
"Excuse me, I am about to go masturbate and needed a name to go with the face.",
"I would absolutely love to swap bodily fluids with you.",
"Let's go to my place and do the things I'll tell everyone we did anyway.",
"I'm gonna have sex with you tonight so, you might as well be there.",
"I'm not Asian but I'll still eat your cat.",
"Are you the lottery lady on TV, because I'm picturing you holding up my balls.",
"Damn girl I'd love to kiss those beautiful, luscious lips. And the ones on your face.",
"I have a job for you, but it blows!",
"Do you have a shovel? Cause I'm diggin' that ass!",
"The things I would do if I got a few roofies in you.",
"Damn, are you my new boss, because you just gave me a raise.",
"You're so hot you could make a deceased man's dick rise from the dead!",
"As long as I have a face, you'll have a place to sit.",
"You must be yogurt because I want to spoon you.",
"Do you like tapes and CD's? Cause I'm gonna tape this dick to your forehead so you CD's nuts.",
"Do you work at the wood store? Cause I could've sworn you gave me wood before.",
"Do you like soda? Because I'd mount-and-do you. (Mountain Dew)",
"Is it hot in here, or are your boobs just huge.",
"I'm peanut butter, you're jelly, let's have sex.",
"If it's true that we are what we eat, then I could be you by tomorrow morning.",
"Remember my name, because you'll be screaming it later!",
"Nice shoes, wanna f**k?",
"Nice socks. Can I try them on after we have sex?",
"Nice tits. Mind if I squeeze them?",
"Oh, you're a bird watcher. [Pull out your dong] Well, would you take this for a swallow?",
"Are you an elevator? Cause I wanna go down on you.",
"Is your name Osteoporosis? Because you're giving me a serious bone condition",
"Is your name winter? Because you'll be coming soon.",
"Do you like jalape&ntilde;os? Cause in a minute I'll be jalape&ntilde;o pussy.",
"Are you a shark? Cause I've got some swimmers for you to swallow.",
"Are you jewish? Cause the way you're looking at me, I'm beginning to think Jewish this dick was in your mouth.",
"Do you work for Papa Johns? Cause you're a fine pizza ass.",
"Girl are you a witch? Cause you know how to make something stand without even touching it",
"Are you from China? Cause I'm China get in your pants.",
"Do you like Pizza Hut? Cause I'll stuff your crust.",
"Since we've been told to reduce waste these days, what you say we use these condoms in my pocket before they expire.",
"[Take an ice cube to the bar, smash it, and say] &quot;Now that I've broken the ice, will you sleep with me?&quot;",
"The only reason I would kick you out of bed would be to f**k you on the floor.",
"The word of the day is &quot;legs.&quot; Let's go back to my place and spread the word.",
"We're going to dance to one song, then go back to my apartment and f**k.",
"What can I do to make you sleep with me?",
"Let's go back to my room and do some math: Add a bed, subtract our clothes, divide your legs, and multiply.",
"I wish you were a screen door, so I could slam you all day long!",
"Do you like yoga? Cause Yoganna love this dick.",
"Your place or mine? Tell you what? I'll flip a coin. Head at my place, tail at yours.",
"I'd like to get between your legs and eat my way straight to your heart...",
"Hey! Wanna play war? I'll lay on the ground and you blow the f**k outta me!",
"If we were both squirrels, would you let me bust a nut in your hole?",
"My dick's been feeling a little dead lately. Wanna give it some mouth-to-mouth?",
"If I told you I had a 2 inch dick would you f**k me? [No] Good, because mine is 8 inches.",
"Do you like apples? [Yes/No] How about I take you home and f**k the sh*t out of you. How do like them apples?",
"Do you like jewels? [Yes/No] well, suck my dick, it's a gem.",
"They say sex is a killer... Do you want to die happy? ",
"First, I'd like to kiss you passionately on the lips, then, I'll move up to your belly button.",
"Your lips are kinda wrinkled. Mind if I press them?",
"I have a big headache. I hear the best cure for headaches is sex. What say we go upstairs and work out a remedy.",
"So, Is it safe to say I'm gonna score?",
"I just checked my schedule and I can have you pregnant by Christmas.",
"I'm like Domino's Pizza. If I don't come in 30 minutes, the next one is free.",
"Do you like my belt buckle? (any response is okay ) It would look better against your forehead!",
"Do you wanna come to the Marines, or would your rather have a Marine come into you?",
"Are you gay? [No] Wow, me neither, let's have sex.",
"If I washed my dick, would you suck it? [No] Oh, so you like to suck dirty dicks.",
"Nice f**king weather. Want to?",
"That outfit would look great in a crumpled heap next to my bed.",
"We're out of bleach. Do you want to go in the janitor's closet and make out?&nbsp;&nbsp; &nbsp; ",
"There are 206 bones in the human body. How would you like one more?",
"Those are nice jeans, do you think I could get in them?",
"Wanna play carnival? You sit on my face and I guess how much you weigh.",
"What do you like for breakfast?",
"Which is easier? You getting into those tight pants or getting you out of them?",
"Why don't you come over here, sit on my lap and we'll talk about the first thing that pops up?",
"Why don't you surprise your roommate and not come home tonight?",
"You have some nice jewelry. It would look great on my nightstand.",
"Are those lumberjack pants your wearing? They are giving me a wood.",
"You remind me of a championship bass, I don't know whether to mount you or eat you.",
"Hey baby, as long as I have a face, you'll have a place to sit.",
"Hey baby there's a party in my pants and you are invited!",
"Can I walk through your bushes and climb your mountains?",
"Hey I'm looking for treasure, Can I look around your chest?",
"I'm a freelance gynecologist. How long has it been since your last checkup?",
"Do you take Visa?",
"Excuse me, I just shit in my pants. Can I get in yours?",
"You are the reason that god invented boners.",
"With great penis, comes great responsibility.",
"If I flip a coin, what are my chances of getting head?",
"If you're feeling down, I can fill you up.",
"There are so many things you can do with the human mouth... why waste it on talking?",
"How do you like your eggs? Poached, scrambled or fertilized?",
"You smell... We should go take a shower together.",
"Would you like a hotdog to go with those buns?",
"You're like my own personal brand of heroin.",
"This may seem corny, but you make me really horny.",
"I'm a burglar and I'm gonna smash your backdoor in.",
"Do you wanna do something that rhymes with 'Truck'?",
"I have a rare disease that will kill me unless I have sex within the next 30 minutes. Don't let me die!",
"I bet my tongue can beat up your tongue.",
"Yeah, it's big and if you pet it, it spits",
"Let us let only latex stand between our love.",
"Do you wanna see why my nickname is 'tri-pod'?",
"There are a lot of fish in the sea, but you're the only one I'd like to mount.",
"I heard your ankles were having a party... want to invite your pants down?",
"Are you a virgin? [No] Prove it!",
"You bring a whole new meaning to the word, &quot;edible.&quot;",
"I don't know what you think of me, but I hope it's X-rated.",
"Want to play lion tamer? You could get on all fours and I'll put my head in your mouth.",
"If I was a watermelon, would you spit or swallow my seed?",
"Do you like chicken? Sorry, I haven't got any, how about a cock?",
"I think that we might be related. Let me check for the family birthmark on your chest.",
"Are you from Ireland? 'Cuz my dick's-a-Dublin!",
"[Look down at your crotch] It's not just going to suck itself.",
"I'm a writer, you're a writer, how about we get naked together and put some poetry in motion?",
"Are you from Africa? Cause I wanna know Kenya suck this dick?",
"[Hold up a screw] Wanna screw?",
"Do you want to come over to my place and feed your beaver some wood?",
"[What are you doing?] I'm taking off my shoes. [Why?] So I can take off my pants.",
"How about you be my story and I'll be your climax!",
"&quot;I have this magic watch that can actually talk to me. Seriously, it's saying something right now. It says that you're not wearing any underwear, is that true?.&quot; [No.] &quot;Oh wait, my watch is an hour fast!",
"Is your name Dora? Cause I'll let you explore this dick.",
"I like your hair, your eyes, your smile... I like every bone in your body... Especially mine!",
"Do you sleep on your stomach? [No] Can I?",
"Lets play &quot;Titanic.&quot; When I say &quot;Iceburg!&quot; you do down.",
"Do you believe guys think with their dick? (Yeah.) Well, in that case, will you blow my mind?",
"Smile. It is the second best thing you can do with your lips.",
"Don't you think most people who use pick-up lines are dipsticks? (Yes.) In that case, mind if I check your oil level?",
"Your shirt has to go, but you can stay.",
"Would you like to actively engage in mock procreation?",
"I'm easy. Are you?",
"Would you like to try an Australian kiss? It is just like a French kiss, but down under.",
"Could you do me a favor? Could you get on your knees and smile like a donut?",
"This is a condom. If we put it on, we can have sex.",
"I WANT SEX! Sorry, the doctor said that would help...",
"Do you believe in free love? [No] Then how much do you cost?",
"Hey baby, I'll f**k you so well the NEIGHBORS will be having a cigarette when we're done.",
"Want to make a porno? We don't have to tape it.",
"Let's not mess with nature. We are here to make babies. So, let's get to it.",
"Gee, that's a nice set of legs, what time do they open?",
"I don't know you, and you don't know me, but who's to say it's wrong if we sleep together?",
"Is it that cold out or are you just smuggling tic-tac's in your bra?",
"I just popped a Viagra. So, we've got about 30 minutes to get back to your place.",
"I think that pick-up lines are for people with to much time on their hands. Let's just f**k.",
"You have a beautiful voice. I bet it would sound even better muffled by my penis.",
"If you can dance, you have my hand, but if you can sing, you have my heart. I hope to God you can't sing because I just wanna f**k you.",
"That's a nice shirt. Can I talk you out of it?",
"Hi, I'm gay. Do you think you can convert me?",
" I'm the finger down your spine when all the lights go out.",
"If I'm a pain in your ass... We can just add more lubricants.",
"Life is short. Let's f**k and see if there is anything after that.",
"Let me eat you for an hour. If you don't want to have sex after that, we won't.",
"All those curves, and me with no brakes.",
"[Give the person a bottle of wine or tequila] Drink this, and then call me when you're ready.",
"Hi, will you help me find my lost puppy? I think he went into this cheap motel room across the street.",
"I'm trying to determine after years of therapy and lots of testing, whether or not I'm allergic to sex.",
"[Walk into her chest] &quot;If they weren't sooo large, it wouldn't have happened!&quot;",
"How much will $20 get me?",
"Roses are red, violets are blue, I suck at pick up lines... nice tits.");	
	
//	$selected_pickup=mt_rand(0,count($pickup)); // select a random number
//	$autoMessage="Pickup line #".$selected_pickup." - ".$pickup[$selected_pickup];
	
//	$insert_post = mysqli_query($db, "INSERT INTO forum_posts (forum,sender,post_time,message,length1,length2) 
//	VALUES('1','Pickup Line Generator','".time()."','".$autoMessage."', '1', '1')");
//	echo mysqli_error($db);
}

if($forum=='2') // used on commapps.php page to show how many posts user hasnt seen
	mysqli_query($db, "UPDATE committee_users SET commforum='".time()."' WHERE user='".$_COOKIE["user"]."'" );
											
$client_ip = $_SERVER['REMOTE_ADDR'];

$title=$forum_details['title'];
$description="The best part";
addheader();
?>
<script src="http://www.ucdtramp.com/js/forum.js"></script>

<?php if($forum == '404'){ ?>
	<style>
	.whitebox{background:rgba(100,0,255,0.7);color:#DDD;}
	.nicetime{color:#BBB;}
	</style>
	<div class="whitebox" style="text-align:center;padding:1em;">
    	<h1>Welcome to the 404 Forum</h1>
    	I realised I needed a better way to answer questions and suggestions about the site so I made this place. You can comment on it in the usual way or posting via the bug box. It's purple so you know it's not the regular forum. The music that you might be hearing, I cant get it out of my head, now it's in yours too.
	    <audio src="http://www.ucdtramp.com/files/ZeldaTP_Menu_Select_Screen.mp3" loop autoplay hidden="true"></audio>
    </div>
<?php } ?>

<div id="forum_header">
	<button id="postbutton" tabindex="1"><i class="fa fa-comment-o"></i> Post</button>
    
    <button style="float:right;" onClick="$('.reply').slideToggle();">Hide Replies</button>
    <button style="float:right;" id="secretsbutton">Secrets</button>
    <?php if($forum != '404'){ echo '<a style="float:right;" href="http://www.ucdtramp.com/forum/404" title="404 Forum"><img style="padding-right:.5em;height:1.3em;vertical-align:middle;" src="http://www.ucdtramp.com/images/msc/bug.png"></a>';} ?>
</div>
<hr>
<div class="whitebox" id="writepost" style="display:none;position:relative;">
  <form onsubmit="return ajaxPost(this);">
        <div id="namesubmit">
          <input type="text" style="width:100%;" name="eggs" id="eggs" placeholder="Name" value="<?php if(isset($_COOKIE['user'])){echo $_COOKIE['user'];} else if(isset($_COOKIE['Milk'])){echo $_COOKIE['Milk'];}?>" tabindex="2">
          <button type="submit" style="width:100%;" id="postsubmit" title="Push me, I know you want to ;)" tabindex="4">Submit</button></div>
        <div id="posttextarea">
          <textarea placeholder="Put your message here :D" name="bacon" id="bacon" tabindex="3"></textarea>
          <input type="hidden" id="sausage" name="sausage"><input type="hidden" name="forumid" id="forumid" value="<?=$forum?>"></div>
  </form>
</div>

<div class="whitebox" id="secrets" style="display:none;position:relative;">
    <div style="width:60%;float:left;padding-right:2em;">
    	<a style="color:blue" href="http://www.emoji-cheat-sheet.com/" target="_blank">Emoji Smiley Codes</a> - Type any one of these 900-something codes into your post for the smiley to appear!<br>
        <a style="color:blue" href="http://www.ucdtramp.com/forum_stats.php">Forum Stats</a> - See the most frequently used words, the person with the most posts and the most active forum day!<br>

    <h3>Special Codes</h3>
    <u>Text</u> - :underline:Text:endul:<br>
    <strong>Text</strong> - :bold:Text:endb:<br>
    <span style="font-size:1.5em;">Text</span> - :large:Text:endl:<br>
	<p>Please note that smilies work in both the name and message.</p></div>
    
    <div style="width:40%;float:left;">
    <h3 style="margin-top:0px">Color Codes</h3>
    <span style="color:#FF0000">Text</span> - :red:Text:endc: <br>
    <span style="color:#0000FF">Text</span> - :blue:Text:endc:<br>
    <span style="color:#32CD32">Text</span> - :green:Text:endc:<br>
    <span style="color:#FF1493">Text</span> - :pink:Text:endc:<br>
    <span style="color:#C71585">Text</span> - :purple:Text:endc:<br>
    <span style="color:#FF4500">Text</span> - :orange:Text:endc:<br>
    <span style="color:#808080">Text</span> - :gray:Text:endc:<br>
    <span style="color:#COCOCO">Text</span> - :silver:Text:endc:<br>
    <span style="color:rgba(255,255,255,.35)">Text</span> - :thesecretcodecolour:Text:endc:<br><br>
    </div>
    <hr>
    <div id="oldsmilies">
<h3>Old Site Smilies</h3> Type the code shown without the spaces between the :'s. o for old and p for purple.<br>
    	<?php $string = ":oquestion: - : oquestion :  |  :oredface: - : oredface :  |  :ofox: - : ofox :  |  :orolleyes: - : orolleyes :  |  :omrgreen: - : omrgreen :  |  :whydontyouloveusvincent: - : whydontyouloveusvincent :  |  :flamed: - : flamed :  |  :psurprised: - : psurprised :  |  :orazz_alt: - : orazz_alt :  |  :pevil: - : pevil :  |  :psad: - : psad :  |  :oidea: - : oidea :  |  :ocool: - : ocool :  |  :oneutral: - : oneutral :  |  :ptramp: - (<i>I'm not telling</i>)  |  :tim: - : tim :  |  :oconfused: - : oconfused :  |  :ofrown: - : ofrown :  |  :omad: - : omad :  |  :peh: - : peh :  |  :pheart: - : pheart :  |  :olol: - : olol :  |  :ocake: - : ocake :  |  :oflappers: - : oflappers :  |  :pstar: - : pstar :  |  :osmile: - : osmile :  |  :hearttim: - : hearttim :  |  :orazz_old: - : orazz_old :  |  :prazz: - : prazz :  |  :ocry: - : ocry :  |  :oeek: - : oeek :  |  :pneutral: - : pneutral :  |  :tramp: - (<i>Hahaha</i>)  |  :oarrow: - : oarrow :  |  :osurprised: - : osurprised :  |  :pmad: - : pmad :  |  :pconfused: - : pconfused :  |  :oexclaim: - : oexclaim :  |  :oevil: - : oevil :  |  :psmile: - : psmile :  |  :owink: - : owink :  |  :osad: - : osad :  |  :pbiggrin: - : pbiggrin :  |  :ocamera: - : ocamera :  |  :otwisted: - : otwisted :  |  :me: - : me :  |  :bosco: - : bosco :  |  :orazz: - : orazz :  |  :obiggrin: - : obiggrin :  |  :ofrog: - : ofrog : ";
		echo smilify($string,NULL);
		?>
    </div>
</div>

<div id="placeholder"></div> <!--new ajax post are inserted after this empty placeholder div-->

<?php 
//start of loop!

while($memo=mysqli_fetch_array($memos,MYSQL_ASSOC)) 
{

$datetime = date('"D, d M y H:i:s O"',$memo['post_time']);
$nicetime = nicetime($memo['post_time']);


?>
<div class="whitebox">
  <div class="details"> <!--top bar with name, time and other details. bottom border-->
    <span class="name"><?=smilify(html_entity_decode($memo['sender']),html_entity_decode($memo['sender']));?></span>
    <span class="time">
    	<span class="nicetime"><?=$nicetime?></span>
    	<span class="datetime"><?=$datetime?></span>
    </span>
    <span class="ip">
<?php if($userpos=='webmaster'){ //Decode ip and show it top right
   echo decode_ip($memo['ipaddress']).' <a title="Delete" style="color:black" href="http://www.ucdtramp.com/forum/'.$forum.'/'.$memo['id'].'"><i class="fa fa-trash-o"></i></a>';} ?>
	  <button onClick="$('#replybox_<?=$memo['id']?>').slideToggle();">Reply</button>
    </span>
  </div>
  <div class="msg"><?=smilify(URL_to_link(nl2br(html_entity_decode($memo['message']))), html_entity_decode($memo['sender']) ); 
      if(isset($_COOKIE['ForumLikes']) && strpos($_COOKIE['ForumLikes'], $memo['id']) !== FALSE){ //Post has been liked
		  echo '<a class="like" title="Approved"><img class="likeimg" src="http://www.ucdtramp.com/images/msc/like_used.png" alt="Like">';
	  }else{
		  echo '<a class="like" title="Approve Post" onclick="updateCount(\''.$memo['id'].'\',this);"> 
          <img class="likeimg" src="http://www.ucdtramp.com/images/msc/like.png" alt="Like">';
	  } ?>
          <span id="message_count_text_id<?=$memo['id']?>"><script>getCount('<?=$memo['id']?>');</script></span>
      </a> 
   </div>
   <?php
   if($replies = mysqli_query($db, "SELECT * FROM forum_posts WHERE parent_id='".$memo['id']."' AND forum='$forum' ")){
		while($reply=mysqli_fetch_array($replies)){?>
        	<div class="reply">
              <i onMouseOver="$(this).next().slideToggle();"><?=smilify(html_entity_decode($reply['sender']),html_entity_decode($reply['sender']));?></i>
              <span style="display:none;color:#666;font-size:.8em;"> - <?=nicetime($reply['post_time'])?></span>  
			  <span> - <?=smilify(URL_to_link(nl2br(html_entity_decode($reply['message']))), html_entity_decode($reply['sender']) )?>              
				  <?php if(isset($_COOKIE['ForumLikes']) && strpos($_COOKIE['ForumLikes'], $reply['id']) !== FALSE){ //Post has been liked
                      echo '<a class="like" title="Approved"><i class="fa fa-check likeimg" style="color:gray;font-size:1.1em"></i>';
                  }else{
                      echo '<a class="like" title="Approve Post" onclick="updateCount(\''.$reply['id'].'\',this);">
                      <i class="fa fa-check likeimg" style="color:limegreen;font-size:1.2em"></i>';
                  } ?>               
                      <span id="message_count_text_id<?=$reply['id']?>"><script>getCount('<?=$reply['id']?>');</script></span>
<?php if($userpos=='webmaster'){ //Decode ip and show it top right
   echo '<div style="float:right;"> '.decode_ip($reply['ipaddress']).' <a title="Delete" style="color:black" href="http://www.ucdtramp.com/forum/'.$forum.'/'.$reply['id'].'"> <i class="fa fa-trash-o"></i> </a></div>';} ?>

                  </a>
              </span> 
		    </div>
   <?php }
   }?>
   <div class="replyform" id="replybox_<?=$memo['id']?>" style="display:none;">
   <form onsubmit="return postReply(this);">
        <div id="namesubmit">
          <input type="text" style="width:100%;" id="replyname" placeholder="Name" value="<?php if(isset($_COOKIE['user'])){echo $_COOKIE['user'];} else if(isset($_COOKIE['Milk'])){echo $_COOKIE['Milk'];}?>" tabindex="2">
          <button type="submit" style="width:100%;" title="Push me, I know you want to ;)" tabindex="4">Post Reply</button></div>
        <div id="posttextarea">
          <textarea placeholder="Put your reply here" id="replymessage" tabindex="3"></textarea>
          <input type="hidden" id="sausage"><input type="hidden" id="parentid" value="<?=$memo['id']?>"></div>
  </form>
   </div>
</div>
<?php 	
			} //end post loop
?>
<hr>

<div class="whitebox" id="jump_page"> <span>Jump to a page:</span>
  <?php
	for($i=0;$i<$num_pages;$i++) 
		{
			$start_page = $i*$forum_details['posts_per_page'];
			echo"<a href='http://www.ucdtramp.com/forum.php?forum=".$forum."&start=".($start_page)."'>".($i+1)."</a>";
		}
	echo '</div>';

addfooter(); 
?>