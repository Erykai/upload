<?php
require "test/config.php";
require "vendor/autoload.php";

use Erykai\Upload\Upload;

$upload = new Upload();
$upload->save();
print_r($upload->response());