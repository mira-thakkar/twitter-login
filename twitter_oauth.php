<?php
require_once 'twitteroauth/autoload.php';

use twitter_login\twitteroauth\TwitterOAuth;
session_start();

if(!empty($_GET['oauth_verifier']) && !empty($_SESSION['oauth_token']) && !empty($_SESSION['oauth_token_secret'])){
    // verification successful
} else {
    // Something's missing, go back to login page
    header('Location: login.php');
}

// TwitterOAuth instance, with two new parameters oauth_token and oauth_token secret
$twitteroauth = new TwitterOAuth('app_key', 'app_secret', $_SESSION['oauth_token'], $_SESSION['oauth_token_secret']);

// Let's request the access token
$access_token = $twitteroauth->oauth("oauth/access_token", array("oauth_verifier" => $_REQUEST['oauth_verifier']));

// Save it in a session variable
$_SESSION['access_token'] = $access_token;

//create TwitterOAuth instance with access_token to get user information
$twitteroauth=new TwitterOAuth('app_key','app_secret',$_SESSION['access_token']['oauth_token'],$_SESSION['access_token']['oauth_token_secret']);

//  get the user's info
$user_info = $twitteroauth->get('account/verify_credentials');

// Print user's info
echo '<div style="text-align:center">';
echo '<img src="'.$user_info->profile_image_url.'" width="50px" height="50px"><br>';
echo "Name: ".$user_info->name."<br>";
echo "Username: ".$user_info->screen_name."<br>";
echo "Twitter_id: ".$user_info->id."<br>";
echo "Location: ".$user_info->location."<br>";
echo "Created at: ".$user_info->created_at."<br>";


//get friends list
$parameters =  array('user_id' => $user_info->id);
$friends=$twitteroauth->get('friends/list',$parameters);

$frnds=$friends->users;
foreach ($frnds as $frnd) {
print_r($frnd);
echo "<br><br>";
}

//upload photo on twitter
$media1 = $twitteroauth->upload('media/upload', array('media' => 'image.jpg'));

$parameters = array(
    'status' => 'hello twitter',
    'media_ids' => $media1->media_id_string
);
$result = $twitteroauth->post('statuses/update', $parameters);


?>