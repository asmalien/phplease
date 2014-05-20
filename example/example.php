<?php

require_once('../lib/phplease.php');
require_once('config.php');

# Check if we already have the access tokens
$access_token_file = 'phplease.json';
if(file_exists($access_token_file)) {
    $tokens = json_decode(file_get_contents($access_token_file), true);
    $client = new PHPleaseAuth(XREL_CONSUMER_KEY, XREL_CONSUMER_SECRET, $tokens['oauth_token'], $tokens['oauth_token_secret']);
} else {
    $client = new PHPleaseAuth();
}

# Create an instance of PHPlease
$xrel = new PHPlease($client);

# Returns information about a single release.
$response = $xrel->info('f638d1cfec8d');

# Returns the latest releases.
$response = $xrel->latest();

# Returns scene releases from the given category.
$response = $xrel->browse_category('xxx', 'HDTV');

# Returns all releases associated with a given ext info.
$response = $xrel->product_releases('e5262b5349a');

# Returns a list of public, predefined release filters.
$response = $xrel->filters();

# Returns a list upcoming movies and their releases.
$response = $xrel->upcoming();

# Returns information about a product info entry.
$response = $xrel->product('15938d83baa3');

# Browse P2P/non-scene releases.
$response = $xrel->p2p_releases('15938d83baa3');

# Returns a list of available P2P release categories and their IDs.
$response = $xrel->p2p_categories();

# Returns information about a single P2P/non-scene release.
$response = $xrel->p2p_release('6dbb52db2be6');

# Returns information about the currently active user.
$response = $xrel->authd_user();

# Shows how many calls the user or the IP address has left before none will be answered.
$response = $xrel->rate_limit_status();

# Returns a list of all the current user's favorite lists.
$response = $xrel->favs_lists();

# Returns a favorite list's entries.
$response = $xrel->favs_list_entries(9819);

# Print out the result
print_r($response);
