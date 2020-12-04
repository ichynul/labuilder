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

            var index = parent.layer.getFrameIndex(window.name); //获取窗口索引
            parent.layer.close(index);
        } else {
            document.write(msg);
        }
    </script>
</head>
<body>

</body>
</html>