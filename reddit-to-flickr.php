<?php 
// Filter reddit results for those that link to an image
function filter_items($item) {
    $url = $item->data->url;
    $supported_image = array(
        'gif',
        'jpg',
        'jpeg',
        'png'
    );
    $ext = strtolower(pathinfo($url, PATHINFO_EXTENSION));
    if (in_array($ext, $supported_image)) {
        return true;
    }
    return false;
};

// Clean up reddit results to a flat array with only data I care about
function parse_items($item) {
    $parsed = (object) array(
        'title' => $item->data->title,
        'url' => $item->data->url
    );
    return $parsed;
};

// Retrieve the data from Reddit
$json = file_get_contents('https://www.reddit.com/r/all/top.json');

// Parse the JSON string to an object, then grab the property that contains the actual results
$data = json_decode($json)->data->children;

// Filter the data
$data = array_filter($data, "filter_items");

// Clean up the data
$data = array_map("parse_items", $data);

// Save the images locally
// foreach($data as $item) {
//     copy($item->url, 'tmp/file.jpeg');
// }
var_dump($data);

?>