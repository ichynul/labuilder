Tap和Step是`form`内置的用于分割组件的方法。

tab实例：
```php
$form->tab('基本信息');//tab 1

$form->image('avatar', '头像')->thumbSize(50, 50);
$form->text('username', '账号')->required()->maxlength(20);
$form->text('nickname', '昵称')->required()->maxlength(20);
$form->text('mobile', '手机号')->maxlength(11);
$form->text('email', '邮件')->maxlength(60);
//
$form->tab('其他信息');//tab 2

$form->textarea('remark', '备注')->maxlength(255);
$form->switchBtn('status', '状态')->default(1);

if ($isEdit) {
     $form->show('last_login_ip', '最近登录IP')->default('-');

     if (session('admin_id') == 1) {
          $form->show('openid', 'openid')->default('-');
     }

     $form->show('last_login_time', '最近登录时间');
     $form->show('create_time', '注册时间');
     $form->show('update_time', '修改时间');
}
```
step实例：

不常用也不实用，用法跟`tab`差不多