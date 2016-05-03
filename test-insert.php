<?php
ini_set('display_errors', 1);
//mysql_query("set names 'utf8'");
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
$requestMethod = 'GET';
$url = 'https://api.twitter.com/1.1/search/tweets.json';
$getfield = '?q=#YaNoPuedoCreerEn-filter:retweets&result_type=recent&true&count=100';
//$getfield = '?q=#YaNoPuedoCreerEn';

$twitter = new TwitterAPIExchange($settings);
$response = $twitter->setGetfield($getfield)
    ->buildOauth($url, $requestMethod)
    ->performRequest();
	$cont = 0;
	$parsed_json = json_decode($response);
	foreach($parsed_json->statuses as $k) { 
		$usuario[] = $k->user;
		foreach($usuario as $q) {
			$idUser = $q->id;
		}
		$cont++;
		$tweetDate = date('Y-m-d H:i:s', strtotime($k->created_at));
		//echo "ID:".$k->id." Tweet: ".$k->text."<br>";
		$insert = "insert into ".$table." (id, message, date_social,  date_current, FB_post_id, voto_id, user_id, social_type) ".
				  "VALUES ('', '$k->text', '$tweetDate', now(), '$k->id', '$k->id_str', '$idUser', '$socialType')"; 
		mysqli_query($conn, $insert);
		echo $insert. "<br>";
		
	}
//print_r($response);
?>
