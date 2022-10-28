# upload

[![Maintainer](http://img.shields.io/badge/maintainer-@alexdeovidal-blue.svg?style=flat-square)](https://instagram.com/alexdeovidal)
[![Source Code](http://img.shields.io/badge/source-erykai/upload-blue.svg?style=flat-square)](https://github.com/erykai/upload)
[![PHP from Packagist](https://img.shields.io/packagist/php-v/erykai/upload.svg?style=flat-square)](https://packagist.org/packages/erykai/upload)
[![Latest Version](https://img.shields.io/github/release/erykai/upload.svg?style=flat-square)](https://github.com/erykai/upload/releases)
[![Software License](https://img.shields.io/badge/license-MIT-brightgreen.svg?style=flat-square)](LICENSE)
[![Quality Score](https://img.shields.io/scrutinizer/g/erykai/upload.svg?style=flat-square)](https://scrutinizer-ci.com/g/erykai/upload)
[![Total Downloads](https://img.shields.io/packagist/dt/erykai/upload.svg?style=flat-square)](https://packagist.org/packages/erykai/upload)

Upload media, files, images and upload url

## Installation

Composer:

```bash
"erykai/upload": "1.2.*"
```

Terminal

```bash
composer require erykai/upload
```

Create config.php

```php
//define name folder uploads system
const UPLOAD_DIR = 'storage';
//define mimetypes accepts
const UPLOAD_MIMETYPE = [
    'image/jpeg',
    'image/gif',
    'image/png',
    'image/svg+xml',
    'audio/mpeg',
    'video/mp4',
    'application/msword',
    'application/vnd.openxmlformats-officedocument.wordprocessingml.document',
    'application/pdf'
];
```

Upload $_FILES

```php
require "config.php";
require "vendor/autoload.php";

use Erykai\Upload\Upload;
$upload = new Upload();
$upload->save();
print_r($upload->response());
```

Upload $_POST url

```php
require "config.php";
require "vendor/autoload.php";

use Erykai\Upload\Upload;
$upload = new Upload($_POST['cover'], 'cover');
$upload->save();
print_r($upload->response());
```

Upload url

```php
require "config.php";
require "vendor/autoload.php";

use Erykai\Upload\Upload;
$upload = new Upload('https://web.com/pdf.pdf', 'document');
$upload->save();
print_r($upload->response());
```

Create object and delete

```php
if($upload->save()){
        $user = new stdClass();
            foreach ($upload->response()->data as $key => $value) {
                $user->$key = $value;
                $file = true;
            }
        }   
        //case delete
        if($file){
           $upload->delete();
           print_r($upload->response());
        }
```

Delete image
 
```php
$upload->delete("storage/image/2022/08/10/imagem.jpg");
print_r($upload->response());
```

## Contribution

All contributions will be analyzed, if you make more than one change, make the commit one by one.

## Support

If you find faults send an email reporting to webav.com.br@gmail.com.

## Credits

- [Alex de O. Vidal](https://github.com/alexdeovidal) (Developer)
- [All contributions](https://github.com/erykai/upload/contributors) (Contributors)

## License

The MIT License (MIT). Please see [License](https://github.com/erykai/upload/LICENSE) for more information.
