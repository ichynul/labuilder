多个组件组合为一个，多用于排版布局。

主要方法：

```php
//with 包含多个其他组件
public function with(...$fields){}

//设置　内部组件的值(数组)　同: fill,overWrite=ture
public function value($val){}

//设置　内部组件的值(数组)，overWrite是否覆盖.
public function fill($data = [], $overWrite = false){}
```

#### 用法

有两种写法:
- 1 使用with;
```php
$form->fields('', '基本信息', 7)->with(
    $form->text('name', '名称')->required()->maxlength(55),
    $form->text('spu', 'spu码')->maxlength(100)
    //其他组件以,分割
);
```

- 2 使用fieldsEnd;
```php
$form->fields('', '基本信息', 7);
//写包含的组件
$form->text('name', '名称')->required()->maxlength(55);
$form->text('spu', 'spu码')->maxlength(100);
//其他组件

$form->fieldsEnd();//结束
```

#### 功能

- 功能1 : 页面布局，左边`col-md-7` + 右边`col-md-5`,把页码整体做一个基本的划分。　　
```php
$form->fields('', '', 7)->size(0, 12)->showLabel(false);
$form->text('name', '名称')->required()->maxlength(55);
$form->text('spu', 'spu码')->maxlength(100);
$form->tags('keyword', '关键字')->maxlength(255);
$form->textarea('description', '摘要')->maxlength(255);
$form->wangEditor('content', '产品详情')->required();
$form->fieldsEnd();

$form->fields('', '', 5)->size(0, 12)->showLabel(false);
$form->image('logo', '封面图')->required()->mediumSize();
$form->text('market_sale', '市场价', 4);
$form->text('cost_price', '成本价', 4);
$form->number('sort', '排序')->default(0);
$form->number('weight', '重量')->default(1000)->help('单位:克');
$form->fieldsEnd();
```

- 功能2 : 把一些相关的字段组合到一起.

```php
$form->fields('省/市/区');
$form->select('province', ' ', 4)->size(0, 12)->showLabel(false)->dataUrl(url('api/areacity/province'), 'ext_name')->withNext(
     $form->select('city', ' ', 4)->size(0, 12)->showLabel(false)->dataUrl(url('api/areacity/city'), 'ext_name')->withNext(
          $form->select('area', ' ', 4)->size(0, 12)->showLabel(false)->dataUrl(url('api/areacity/area'), 'ext_name'))
);
$form->fieldsEnd();
```
- 功能3 : `table`中使用，把相关字段合并到同一列，避免字段过多时表格显示不便。

```php
$table->fields('consignee', '收货人/电话')->with(
     $table->show('consignee', '收货人'),
     $table->show('mobile', '电话')->default('--')
);

$table->show('pay_money', '支付金额');

$table->fields('pay_status', '支付状态/时间')->with(
     $table->match('pay_status', '支付状态')->options(OrderModel::$pay_status_types),
     $table->show('pay_time', '支付时间')->default('--')
);
```
显示：
|  收货人/电话   |　支付金额 | 支付状态/时间 |
|  :----:  |   :----:  | :----:  |
| 小明<br>13612345678  | 100.00 | 已支付<br>2020-08-29 12:31:24 |

ps : `table`中使用 `fields`组合多个字段时，表头只显示`fields`的label，但里面的各个字段还是需要单独设置其label，因为导出数据时，字段是分开的。