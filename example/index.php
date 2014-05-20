<?php

require_once('../lib/phplease.php');
require_once('config.php');

# Check if we already have the access tokens
$access_token_file = 'phplease.json';
if(file_exists($access_token_file)) {
    header('Location: ' . XREL_CALLBACK_URL . 'example.php');
} else {
    # No access tokens found, start the auth process
    session_start();
    $client = new PHPleaseAuth(XREL_CONSUMER_KEY, XREL_CONSUMER_SECRET);

    # Step 1: Get a request token
    $response = $client->request_token(XREL_CALLBACK_URL . 'authorize.php');
    $_SESSION['oauth_token'] = $response['oauth_token'];
    $_SESSION['oauth_token_secret'] = $response['oauth_token_secret'];

    # Step 2: Redirect to the provider
    header('Location: ' . $client->authorize_url($response['oauth_token']));
}
