<?php
ini_set('display_errors', 1);
require_once('twitter-api-php/TwitterAPIExchange.php');
require_once('conf.inc.php');

$settings = array(
        'oauth_access_token' => "$access_token",
        'oauth_access_token_secret' => "$access_token_secret",
        'consumer_key' => "$consumer_key",
        'consumer_secret' => "$consumer_secret"
);

$url = 'https://api.twitter.com/1.1/search/tweets.json';
$requestMethod = 'GET';

//$getfield = '?q=#FelizMartes+AND+#testhht';
$tagPermanente = "#FelizMartes";
$hastags = array ("#testhht", "#testhht1", "#testhht2"); 
$arrlength = count($hastags);

for($x = 0; $x < $arrlength; $x++) {
    $strUrl = "?q=".$tagPermanente."+AND+".$hastags[$x];
	echo $url."?q=".$tagPermanente."+AND+".$hastags[$x]."<br>";
	$twitter = new TwitterAPIExchange($settings);
    $response = $twitter->setGetfield($strUrl)
    ->buildOauth($url, $requestMethod)
    ->performRequest();

	$parsed_json = json_decode($response);

	foreach($parsed_json->statuses as $k) { 
		$usuario[] = $k->user;
		foreach($usuario as $q) {
			$idUser = $q->id;
		}
		echo "<b>IDUsuario: ".$idUser." - IdTweet: ".$k->id." - Tweet:".$k->text." | ".$k->created_at."</b><br>";
	} 
}

//print_r($response);
?>
