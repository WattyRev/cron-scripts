<?php 
// Clean out the tmp folder
$files = glob('tmp/*');
foreach($files as $file){ 
    if(is_file($file)) {
        unlink($file);
    }
}

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
$json = file_get_contents('https://www.reddit.com/r/AdviceAnimals+Seattle+aww+gaming+pics/top.json?sort=top&t=day');

// Parse the JSON string to an object, then grab the property that contains the actual results
$data = json_decode($json)->data->children;

// Filter the data
$data = array_filter($data, "filter_items");

// Clean up the data
$data = array_map("parse_items", $data);

// Define a directory where the images will be stored
define('DIRECTORY', 'tmp');

// Save the images locally and send to flickr
foreach($data as $item) {
    // Save the file locally
    $content = file_get_contents($item->url);
    $fileName = end(explode("/", $item->url));
    file_put_contents(DIRECTORY . "/$fileName", $content);
    
    // Use IFTTT to upload the image to flickr
    $url = "https://maker.ifttt.com/trigger/redditImageSaved/with/key/c1lyYFkke78adLBh823q-T?value1=" . urlencode($fileName) . "&value2=" . urlencode($item->title);
    $curl_handle=curl_init();
    curl_setopt($curl_handle, CURLOPT_URL, $url);
    curl_setopt($curl_handle, CURLOPT_CONNECTTIMEOUT, 2);
    curl_setopt($curl_handle, CURLOPT_RETURNTRANSFER, 1);
    $query = curl_exec($curl_handle);
    curl_close($curl_handle);
}
?>