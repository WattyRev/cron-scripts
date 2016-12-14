<?php 
/**
 * Instructions found here https://davidwalsh.name/flickr-php
 */

// Start the session since phpFlickr uses it but does not start it itself
session_start();

// Require the phpFlickr API
require_once('phpFlickr-master/phpFlickr.php');

// Create new phpFlickr object: new phpFlickr('[API Key]','[API Secret]')
$flickr = new phpFlickr('0ac7111d2344c5c20ef6dc4e0797a77c','d0e6ed5ef123d946', true);

// Authenticate;  need the "IF" statement or an infinite redirect will occur
if(empty($_GET['frob'])) {
    $flickr->auth('write'); // redirects if none; write access to upload a photo
}
else {
    // Get the FROB token, refresh the page;  without a refresh, there will be "Invalid FROB" error
    $flickr->auth_getToken($_GET['frob']);
    exit();
}