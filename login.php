<?php
require_once 'twitteroauth/autoload.php';

use twitter_login\twitteroauth\TwitterOAuth;
session_start();

// The TwitterOAuth instance
$twitteroauth = new TwitterOAuth('app_key', 'app_secret');

// Requesting authentication tokens, the parameter is the URL we will be redirected to. For localhost specify ip instead of loaclhost
 $request_token = $twitteroauth->oauth('oauth/request_token', array('oauth_callback' => 'http://127.0.0.1/twitter_login/hash_handle.php'));

// Saving oauth token into the session
$_SESSION['oauth_token'] = $request_token['oauth_token'];
$_SESSION['oauth_token_secret'] = $request_token['oauth_token_secret'];
 

if($_SESSION['oauth_token']){
    //generate the authorization URL and redirect

	$url = $twitteroauth->url('oauth/authorize', array('oauth_token' => $request_token['oauth_token']));
    header('Location: '. $url);
} else {

    // If there is any error.
    die('Something wrong happened.');
}

?>