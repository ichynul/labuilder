<!DOCTYPE html>
<html lang="zh">

<head>
    <script>
        var success = '{{$success}}' == '1';
        var msg = '{{$msg}}';

        if (parent && parent.layer) {
            if (success) {
                parent.lightyear.notify(msg, 'success');
            } else {
                parent.lightyear.notify(msg, 'danger');
            }
            if (parent.$('.btn-refresh,.search-refresh').size()) {
                parent.$('.btn-refresh,.search-refresh').eq(0).trigger('click');
            } else {
                setTimeout(function () {
                    parent.replace.reload(parent.location.href);
                }, 2000);
            }

            var index = parent.layer.getFrameIndex(window.name); //获取窗口索引
            parent.layer.close(index);
        } else {
            document.write(msg);
            setTimeout(function () {
                location.replace(location.href);
            }, 2000);
        }
    </script>
</head>
<body>

</body>
</html>