<?php
require "test/config.php";
require "vendor/autoload.php";

use Erykai\Upload\Upload;

$upload = new Upload();

if (!$upload->save()) {
    echo $upload->getError();
    return false;
}
print_r($upload->getResponse());