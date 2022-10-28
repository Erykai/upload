<?php
require "config.php";
require "vendor/autoload.php";

use Erykai\Upload\Upload;

//$upload = new Upload("https://web.com/image.jpg");
//$upload = new Upload("https://web.com/image.jpg", "cover");
//$upload = new Upload($_POST['cover']);
//$upload = new Upload($_POST['cover'], 'cover');
//$_FILES
$upload = new Upload();
$upload->save();
print_r($upload->response());