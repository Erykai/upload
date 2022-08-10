<?php
require "test/config.php";
require "vendor/autoload.php";

use Erykai\Upload\Upload;

$upload = new Upload();

if (!$upload->save()) {
    echo $upload->error();
    return false;
}
print_r($upload->response());