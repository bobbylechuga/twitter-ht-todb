<?php
//ini_set('display_errors', 1);
require_once('twitter-api-php/TwitterAPIExchange.php');
require_once('conf.inc.php');

$conn = new mysqli($servername, $username, $password, $dbname);
$table = "votos";
$socialType = 0; 
if ($conn->connect_error) {
	die("Connection fallida: " . $conn->connect_error);
} 

$settings = array(
        'oauth_access_token' => "$access_token",
        'oauth_access_token_secret' => "$access_token_secret",
        'consumer_key' => "$consumer_key",
        'consumer_secret' => "$consumer_secret"
);

$url = 'https://api.twitter.com/1.1/search/tweets.json';
$requestMethod = 'GET';

//$getfield = '?q=#FelizMartes+AND+#testhht';
//$tagPermanente = "#FelizMartes";
$hastags = array ("#MarchaCones", "#TodosSomosMagnificos", "#libertadcondicional"); 
$arrlength = count($hastags);

for($x = 0; $x < $arrlength; $x++) {
    //$strUrl = "?q=".$tagPermanente."+AND+".$hastags[$x];
	//echo $url."?q=".$tagPermanente."+AND+".$hastags[$x]."<br>";
	$strUrl = '?q='.$hastags[$x].'-filter:retweets&result_type=recent&true&count=100';
	//echo $strUrl;
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
		$cont++;
		$now = date('Y-m-d H:i:s');
		$tweetDate = date('Y-m-d H:i:s', strtotime($k->created_at));
		
		//$tweetDate = $k->created_at;
		//echo "ID:".$k->id." Tweet: ".$k->text."<br>";
		$tweet = utf8_decode ($k->text); 
		$insert = "insert into ".$table." (id, message, date_social,  date_current, FB_post_id, voto_id, user_id, etiqueta_id, social_type) ".
				  "VALUES ('', '$tweet', '$tweetDate', '$now', '$k->id', '$k->id_str', '$idUser', $x, '$socialType')"; 
		
		mysqli_query($conn, $insert); 
		//echo $insert. "<br>";
	} 
}

//print_r($response);
?>
