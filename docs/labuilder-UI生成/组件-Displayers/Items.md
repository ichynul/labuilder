数据条目　　

一般用于一对多的数据录入。

主要方法：
```php
//包含多个组件
public function with(...$fields){}

//设置内容组件的值(数组),同fill,overWrite=true
public function value($val){}

//设置内容组件的值(数组),overWrite是否覆盖。数据结构实例:　[1 => ['id'=>1, 'name' => '小明'], 2 => ['id'=>2, 'name' => '小红']]
public function fill($data = [], $overWrite = false){}

//条目是否可[删除]
public function cnaDelete($val){}

//条目是否可[添加]
public function canAdd($val){}

//条目只读，不可[删除]或[添加]
public function canNotAddOrDelete(){}

//把数据库结果集转换格式，然后调用fill
//[['id'=>5, 'name' => '小刚'],['id'=>6, 'name' => '小芳']]
//　转换为　
//[5 => ['id'=>5, 'name' => '小刚'], 6 => ['id'=>6, 'name' => '小芳']]
public function dataWithId($data, $idField = 'id', $overWrite = false)
```
### 主要用法

- 1 数据录入
```php
$attrList = $isEdit ? ShopGoodsAttr::where(['goods_id' => $data['id']])->order('sort')->select() : [];

$form->items('attr_list', '产品属性')->dataWithId($attrList)->with(
     $form->text('name', '名称')->placeholder('属性名称，如生产日期')->maxlength(55)->required()->getWrapper()->addStyle('width:200px;'),
     $form->text('sort', '排序')->placeholder('规格名称，如颜色')->default(1)->required()->getWrapper()->addStyle('width:80px;'),
     $form->text('value', '属性值')->required()->getWrapper()->addStyle('min-width:70%;')
)->help('【属性】不影响价格，仅展示');
```

- 2 数据展示，针对数据量比较少的情况，比如在订单详情页显示订单日志，日志数量一般不会太多，把相关日志一次性显示为列表。
```php
$logList = model\ShopOrderAction::where(['order_id' => $data['id']])->order('id desc')->select();

$form->items('log_list', '操作日志')->dataWithId($logList)->with(
     $form->show('action_note', '操作备注'),
     $form->show('status_desc', '描述'),
     $form->match('order_status', '订单状态')->options(OrderModel::$order_status_types),
     $form->match('pay_status', '支付状态')->options(OrderModel::$pay_status_types),
     $form->match('shipping_status', '物流状态')->options(OrderModel::$shipping_status_types),
     $form->show('create_time', '时间')
)->canNotAddOrDelete();
```