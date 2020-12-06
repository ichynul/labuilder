所有`displayer`的基类。

直接输出value(支持html)，一般不直接使用。

`form`中使用：无`label`, 不支持`col-md-n`大小控制。

##### 主要通用方法
|  方法    |  说明    |  备注  |
| :-- | :-- | :-- |
|`class($val)`|设置field的class||
|`labelClass($val)` | 设置label的class ||
|`attr($val)` | 设置field的属性 ||
|`addClass($val)` |添加field的class ||
|`addAttr($val)` |添加field的属性 ||
|`addStyle($val)` |  添加field的style ||
|`labelAttr($val)` | 添加label的属性 ||
|`size($label, $elemetm)` | 设置大小(label,field)|默认: 2, 8|
|`help($text)` | 添加帮助信息 ||
|`readonly($val =true)` | 只读 ||
|`disabled($val =true)` | 禁用 ||
|`required($val =true)` | 必填 |主要是前端js验证，不涉及后端|
|`showLabel($val =true)` | 是否显示label ||
|`default($val)` | 默认值 ||
|`value($val)` | 设置值 ||
|`to($tpl)` | 简单的转换 ||
|`mapClass()` | 样式匹配 ||
|`mapClassGroup($GroupArr)` | 样式组匹配 ||

>to
支持模板变量：{字段名}  {val}

如 
```php
$table->show('name','姓名')->to('{val}#{mobile}')`;//{val}代表当前字段`name`值，{mobile}为这条记录中的`mobile`字段值。

$table->raw('link','链接')->to('<a href="{val}">{val}</a>')`;//渲染html要用`raw`或`field`
```
`mapClass($values, $class, $field = '', $logic = 'in_array')` 样式匹配 

`mapClassGroup([[$values1, $class1, $field1 = '', $logic1 = 'in_array'], [$values2, $class2, $field2 = '', $logic2 = 'in_array']]])` 批量样式匹配  

如 
```php
$table->match('open', '状态')->options(['0' => '关闭', '1' => '开启'])->mapClass(1, 'hidden');

$table->match('pay_status', '支付状态')
    ->options(['0' => '未支付', '1' => '已支付', '2' =>'已关闭'])
    ->mapClassGroup([[1, 'success'], [2, 'danger']]);
```
css 样式：
```css
span.the-field.default {
    color: #8b95a5;
}
span.the-field.primary {
    color: #33cabb;
}
span.the-field.success {
    color: #72b754;
}
span.the-field.info {
    color: #48b0f7;
}
span.the-field.warning {
    color: #faa64b;
}
span.the-field.danger {
    color: #f96868;
}
span.the-field.dark {
    color: #465161;
}
span.the-field.secondary {
    color: #e4e7ea;
}
span.the-field.purple {
    color: #926dde;
}
span.the-field.pink {
    color: #f96197;
}
span.the-field.cyan {
    color: #57c7d4;
}
span.the-field.yellow {
    color: #fcc525;
}
```