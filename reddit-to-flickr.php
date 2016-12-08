<?php 
$json = file_get_contents('https://www.reddit.com/r/all/top.json');
$data = json_decode($json);
var_dump($data);
?>