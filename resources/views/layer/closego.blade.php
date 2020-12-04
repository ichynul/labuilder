<!DOCTYPE html>
<html lang="zh">

<head>
    <script>
        var success = '{{$success}}' == '1';
        var msg = '{{$msg}}';
        var url = '{{$url}}';

        if (parent && parent.layer) {
            if (success) {
                parent.lightyear.notify(msg, 'success');
            } else {
                parent.lightyear.notify(msg, 'danger');
            }

            setTimeout(function () {
                parent.location.replace(url);
            }, 2000);

            var index = parent.layer.getFrameIndex(window.name); //获取窗口索引
            parent.layer.close(index);
        } else {
            document.write(msg);

            setTimeout(function () {
                location.replace(url);
            }, 2000);
        }
    </script>
</head>
<body>

</body>
</html>