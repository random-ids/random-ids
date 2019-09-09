# random-ids
Generate a non-repetitive random ID.
Sometimes you don't want people to guess the rules of some IDs,then you can use random-ids.
Basic example:
```php
<?php
require_once('../vendor/autoload.php');
$demo = new Demo();
$randomIds = new RandomIds\RandomIds();
echo $randomIds->getId();
```
Default example:
```php
<?php
require_once('../vendor/autoload.php');
//Specify the folder where random data is stored. If not specified, the data will be stored in the vendor folder.
$path = './demo_data';
$randomIds = new RandomIds\RandomIds($path);
//once
$lastId = $randomIds->getId();
$cache = new Cache();
$cache->set('lastId', $lastId);
//next time
$lastId = $cache->get('lastId');
//Enter the last acquired id value and automatically generate the next order of magnitude random table if the data file is lost.
$id = $randomIds->getId($lastId);
echo $id;
```
