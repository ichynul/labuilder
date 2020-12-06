多个文件上传

主要方法

```php
//设置是否可以上传
public function canUpload($val){}

//是否显示文件路径输入框，可以直接手动修改
public function showInput($val){}

//是否显示[选择]按钮从已上传的文件中选择
public function showChooseBtn($val){}

//文件总数量限制
public function limit($val){}

//缩略图小尺寸[50 x 50]
public function smallSize(){}

//缩略图中尺寸[120 x 120]
public function mediumSize(){}

//缩略图大尺寸[240 x 240]
public function bigSize(){}

//缩略图巨大尺寸[480 x 480]
public function largeSize(){}

//自定缩略图中尺寸[w x h]
public function thumbSize($w, $h){}

//参数设置
public function jsOptions($options){}
```