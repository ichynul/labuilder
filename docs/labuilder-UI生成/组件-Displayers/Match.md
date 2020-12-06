匹配

`HasOptions`　trait 为`Checkbox` `Radio` `Select` `MultipleSelect` `Match` `Matches` 共有。

相当于只读的`radio`。

```php
$table->match('type', '类型')->options([
    1 => '<label class="label label-success">增加</label>', 
    2 => '<label class="label label-danger">支出</label>'
])->value(1);

//输出 ：增加
```

```php
$table->match('type', '类型')->options([
    1 => '男', 
    2 => '女',
    3 => '未知'
])->value(2);

//输出 ：女
```

```php
use \app\common\model\GenderType;
```

数据库表: tp_gender_type ,模型　\app\common\model\GenderType;

| id |name| key |
| ---- | ---- | ---- |
| 1  |  男 | m　 |
| 2  |  女 | f　 |
| 3  |  未知 | n　 |

```php
//指定text字段
$table->match('gender','性别')->optionsData(GenderType::select(), 'name')->value(3);//默认主键`id`作为key
//输出 ：未知
```

```php
//指定text/key字段
$table->match('gender','性别')->optionsData(GenderType::select(), 'name', 'key')->value('m');
//输出 ：男
```