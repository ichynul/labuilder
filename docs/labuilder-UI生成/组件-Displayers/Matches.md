多个值匹配

`HasOptions`　trait 为`Checkbox` `Radio` `Select` `MultipleSelect` `Match` `Matches` 共有。


相当于只读的`checkbox`。

```php
$table->matches('hobbies', '爱好')->options([
    1 => '唱歌', 
    2 => '跳舞',
    3 => '爬山',
    4 => '游泳'
])->value('2,4');

//输出 ：跳舞、游泳
```

数据库表: tp_hobby_type ,模型　\app\common\model\HobbyType;

| id |name| hoby |
| ---- | ---- | ---- |
| 1  |  唱歌 | sing　 |
| 2  |  跳舞 | dance　 |
| 3  |  爬山 | climb　 |
| 4  |  游泳 | swim　 |

```php
use \app\common\model\HobbyType;
```

```php
//指定text字段
$table->matches('hobbies','爱好')->optionsData(HobbyType::select(), 'name')->value('1,3,4');//默认主键`id`作为key
//输出 ：唱歌、爬山、游泳
```

```php
//指定text/key字段
$table->matches('hobbies','爱好')->optionsData(HobbyType::select(), 'name', 'hoby')->value('dance,climb');
//输出 ：跳舞、爬山
```