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
$requestMethod = 'GET';
$url = 'https://api.twitter.com/1.1/search/tweets.json';
$getfield = '?q=#DiaLibertadPrensa';

$twitter = new TwitterAPIExchange($settings);
$response = $twitter->setGetfield($getfield)
    ->buildOauth($url, $requestMethod)
    ->performRequest();

var_dump($response);
?>
