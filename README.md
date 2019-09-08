# random-ids
Generate a non-repetitive random ID.
Basic example:
```
<?php
$path='your/path';//save a random ids array.At first,all ids are 7-bit number
$randomIds=new willcn\random-ids($path);
$id=$randomIds->getId();//return a id number from array and remove it.
```
If ids array file lost:
```
<?php
$path='your/path';
$randomIds=new willcn\random-ids($path);
$number=1;//save a new random ids array.All ids more than $number*1000000
$randomIds->create($number);//If you do not delete the file yourself, you do not need to perform this step.New ids will auto created.
```
