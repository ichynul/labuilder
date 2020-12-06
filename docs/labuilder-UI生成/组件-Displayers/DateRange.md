日期区间选择


主要方法：

```php
//修改格式，默认为yyyy-mm-dd
public function format($val){}

//时间戳格式化，若value值为时间戳数字格式
public function timespan($val = 'Y-m-d'){}

//设置分隔符
public function separator($val = ','){}
```