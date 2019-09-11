# random-ids
Generate a non-repetitive random ID.

Sometimes you don't want people to guess the rules of some IDs,then you can use random-ids.

At present,we support file and redis.

Basic example:
```php
<?php
require_once('../vendor/autoload.php');
$demo = new Demo();
//you can use RedisRandomIds for redis.
$randomIds = new RandomIds\FileRandomIds();
echo $randomIds->getId();
```
Default example:
```php
<?php
require_once('../vendor/autoload.php');
//Specify the folder where random data is stored. If not specified, the data will be stored in the vendor....
$path = './demo_data';
//you can use RedisRandomIds for redis.
$randomIds = new RandomIds\FileRandomIds($path);
//once
$lastId = $randomIds->getId();
//next time
//Enter the last acquired id value and automatically generate the next order of magnitude random table if the data file is lost.
$id = $randomIds->getId($lastId);
echo $id;
```

# 随机生成ID
随机生成不重复的ID值.

有时竞争对手会根据ID去猜出你的项目有多少个用户，已经发表了多少文章，每天大约有多少新增用户之类。如果你不想被人猜到这些，
而又希望保留容易记忆和传播的数字ID，你可以使用这个类库来生成一个随机数代替你的ID。

另外，你也可以用这个库来生成一些抽奖号码之类的随机值。

基本实例:
```php
<?php
require_once('../vendor/autoload.php');
$demo = new Demo();
$randomIds = new RandomIds\FileRandomIds();
echo $randomIds->getId();
```
常用实例:
```php
<?php
require_once('../vendor/autoload.php');
//你可以指定随机数据表的保存位置，这样管理会比较方便，如果你有多个类型的ID需要生成，可以分别把随机数据表保存在不同的文件夹下
$path = './demo_data';
$randomIds = new RandomIds\FileRandomIds($path);
//once
$lastId = $randomIds->getId();
//next time
//万一之前生成的随机数据表丢失，系统将自动生成比$lastId高一个数量级的新数据表
//如果只是正常数据用完，无需使用lastId,系统在取出最后一个ID的同时生成下一组数据表
$id = $randomIds->getId($lastId);
echo $id;
```
