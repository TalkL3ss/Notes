<?php

$strSite = htmlspecialchars($_REQUEST["site"]) ;

$prefixHost = $_SERVER["HTTP_HOST"];

$prefixHost = strpos($prefixHost, ":") ? implode(":", explode(":", $_SERVER["HTTP_HOST"], -1)) : $prefixHost;

define("MyProxy", "http" . (isset($_SERVER["HTTPS"]) ? "s" : "") . "://" . $prefixHost . $prefixPort . $_SERVER["SCRIPT_NAME"] . "?");

if (empty($strSite)) {

    die("<!DOCTYPE html><html><body><form action='./Proxy.php' method='Post'><label for='site'>First name:</label><input type='text' id='site' name='site'><br><br>  <input type='submit' value='Submit'></form></body></html>");

}

$strFileType = substr($strSite,-4);

if (strpos($strSite, '.css') !== false)

{

	$contype = 'text/css';} elseif (strpos($strSite, '.js') !== false)

{

		$contype = 'application/javascript';

} elseif ($strFileType == '.png')

{

	$contype = 'image/png';

} elseif ($strFileType == '.ico')

{

	$contype = 'image/x-icon';

} elseif (strpos($strSite, '.jsp') == true)

{

	$contype = 'text/html\;charset=UTF-8';

} elseif (strpos($strSite, '.exe') == true)

{

	$contype = 'application/octet-stream';

} elseif (strpos($strSite, '.zip') == true)

{

	$contype = 'application/octet-stream';

}

elseif ((strpos($strSite, '.svg') == true) || (strpos($strSite, '.svgz') == true))

{

	$contype = 'image/svg+xml';

}

 else {

	$contype = 'text/html';

}

header('Content-Type: ' . $contype);

header('Access-Control-Allow-Origin: *' );

header('Vary: Origin' );

//header('X-Content-Type-Options: nosniff');

$opts = array(

  'http'=>array(

    'method'=>"GET",

    'header'=>"Accept-language: en-us\r\n" .

              "Cookie: null\r\n".

	"Content-Type: $contype\r\n"

  ), 
	'ssl' => array(
                        'verify_peer'      => false,
                        'verify_peer_name' => false,
                        )

);

$context = stream_context_create($opts);

$file = file_get_contents($strSite , false, $context);

Function FixSite($text,$root,$RPort)

{

	$replaceThis = Array(

		"src=\"//" => "src=\"" . $RPort ."://",

		"src=\"/" => "src=\"" . $root,

		"href=\"//" => "href=\"" . $RPort ."://",

		"href=\"/" => "href=\"" . $root,

		"src='//" => "src='" . $RPort ."://",

		"src='/" => "src='" . $root,

		"href='//" => "href='" . $RPort ."://",

		"href='/" => "href='" . $root,

		"\$.post('/" => "\$.post('" . $root,

		"url: \"http" => "url: \"" . $root . "http",

		);

    //$text = urlencode($text);

	$text = str_replace(array_keys($replaceThis), $replaceThis, $text);

	return $text;

}

$RPort = parse_url($strSite, PHP_URL_SCHEME);

$myURL = MyProxy . "site=";

$RUrl = parse_url($strSite, PHP_URL_HOST);

$siteAddr = $myURL . $RPort .'://'. $RUrl . '/';

$fileName = basename(parse_url($strSite,PHP_URL_PATH));

$FileExt = pathinfo(parse_url($strSite,PHP_URL_PATH), PATHINFO_EXTENSION);

if ((strpos($strSite, '.exe') == true)||(strpos($strSite, '.zip') == true))

{

	header('Content-Description: File Transfer');

    header('Content-Type: application/octet-stream');

    header('Content-Disposition: attachment; filename="'.$fileName.'"');

    header('Expires: 0');

    header('Cache-Control: must-revalidate');

    header('Pragma: public');

    //header('Content-Length: ' . filesize(FixSite($file,$siteAddr,$RPort)));

    flush(); // Flush system output buffer

}

 else {

	$contype = 'text/html';

}

echo FixSite($file,$siteAddr,$RPort);

#echo FixSite($file,$siteAddr,$RPort) ;

?>
