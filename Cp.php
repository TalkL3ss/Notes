<?php

//site ind

$myParam = htmlspecialchars($_REQUEST["site"]) ;

echo "\r\n--Download Site: $myParam ---\r\n\r\n";

// Create a stream

$opts = array(

  'http'=>array(

    'method'=>"GET",

    'header'=>"Accept-language: en-us\r\n" .

              "Cookie: null\r\n"

  )

);

$context = stream_context_create($opts);

// Open the file using the HTTP headers set above

$file = file_get_contents($myParam , false, $context);

echo "<div id='copytext'>";

$b64enc = base64_encode ( $file );

echo chunk_split  ($b64enc, 50, "\r\n") ;

echo "</div>";

echo "\r\n\r\n<script language='javascript'>

	function ClipBoard() {

		var NeedToDL = document.querySelector('#copytext');

		var range = document.createRange(); 

		range.selectNode(NeedToDL);

		window.getSelection().addRange(range);

		document.execCommand('copy');

		NeedToDL.innerText = NeedToDL.innerText + String.fromCharCode(13) + \"Copied!!!\";	

	}

</script>";

echo '<script type="text/javascript">

		var interval = setInterval(function() {

		if(document.readyState === \'complete\') {

		clearInterval(interval);

		ClipBoard();

		}    

	}, 100);

</script>';

?>
