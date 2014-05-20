<?php

require_once('../lib/phplease.php');
require_once('config.php');

# Check if we already have the access tokens
$access_token_file = 'phplease.json';
if(file_exists($access_token_file)) {
    header('Location: ' . XREL_CALLBACK_URL . 'example.php');
} else {
    session_start();
    $oauth_verifier = $_GET['oauth_verifier'];

    if(!empty($oauth_verifier) && $_SESSION['oauth_token'] && $_SESSION['oauth_token_secret']) {
    	$phplease = new PHPleaseAuth(XREL_CONSUMER_KEY, XREL_CONSUMER_SECRET, $_SESSION['oauth_token'], $_SESSION['oauth_token_secret']);

        # Step 3: Request the access token
    	$access_token = $phplease->access_token($oauth_verifier);

        # Step 4: Save the access token
    	file_put_contents($access_token_file, json_encode($access_token));

    	header('Location: ' . XREL_CALLBACK_URL . 'example.php');
    }
}
