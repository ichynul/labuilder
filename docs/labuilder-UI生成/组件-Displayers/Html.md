输出Html

主要方法：
```php
//渲染一个模板文件，和thinkphp框架类似`Controller`,模板存放位置也遵循
public function fetch($template = '', $vars = [], $config = []){}

//渲染一个字符串(模板)
public function display($content = '', $vars = [], $config = []){}
```
### 实例
```php

$form->html('tree', 'tree');//填充数据里面有`tree`这个字段

$form->html('tree', 'tree')->vale($treeHtml);//直接显示一段html

$form->html('tree', 'tree')->fetch('tree',　['data' => $data]);//渲染模板

$form->html('tree', 'tree')->display('<p>{$name}</p>',　['name' => '小明']);//渲染带变量的字符串

```