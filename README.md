# upload
Upload media, files and images

## Installation

Composer:

```bash
"erykai/upload": "1.0.*"
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

Create upload.php

```php
require "config.php";
require "vendor/autoload.php";

use Erykai\Upload\Upload;

$upload = new Upload();

if (!$upload->save()) {
    echo $upload->getError();
    return false;
}
print_r($upload->getResponse());
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
