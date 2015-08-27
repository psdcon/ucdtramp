<?php
require_once ('includes/functions.php');

$mem_query = mysqli_query($db, "SELECT * FROM members_db");
	while($mem=mysqli_fetch_array($mem_query)){
		$new = date('m/d/Y', $mem['dob']);
		var_dump(date_parse($new));
		echo '<br>';
	}
	
echo mysqli_error($db);
?>

</head>
<body>

<div>

 <div id="loading">
  <h1>Christmas Light Smashfest 2008: Prototype</h1>
  <h2>Rendering...</h2>
 </div>

 <div id="lights">
  <!-- lights go here -->
 </div>

 <div style="position:absolute;bottom:3px;left:3px">
  <a href="?size=pico">pico</a> | <a href="?size=tiny">tiny</a> | <a href="?size=small">small</a> | <a href="?size=medium">medium</a> | <a href="?size=large">large</a>
 </div>

</div>

</body>
</html>