<div class="row">
    <div id="dropzone-elm" style="width: 220px; margin: 0 auto;" class="dropzone"></div>
</div>

<script type="text/javascript">

   function initDropzone()
   {
        $("#dropzone-elm").dropzone({
            url: "{{$uploadUrl}}",
            method: "post",  // 也可用put
            paramName: "file", // 默认为file
            maxFiles: 1,// 一次性上传的文件数量上限
            maxFilesize: '{{$fileSize}}', // 文件大小，单位：MB
            acceptedFiles: "{{$acceptedExts}}", // 上传的类型
            addRemoveLinks: false,
            parallelUploads: 1,// 一次上传的文件数量
            dictDefaultMessage: '拖动文件至此或者点击上传',
            dictMaxFilesExceeded: "您最多只能上传1个文件！",
            dictResponseError: '文件上传失败!',
            dictInvalidFileType: "不允许上传的文件类型",
            dictFallbackMessage: "浏览器不受支持",
            dictFileTooBig: "文件过大上传文件最大支持{{$fileSize}}MB.",
            dictRemoveLinks: "删除",
            dictCancelUpload: "取消",
            init: function () {
                this.on("addedfile", function (file) {
                    // 上传文件时触发的事件
                });
                this.on("success", function (file, data) {
                    if (data.status == '200') {
                        location.href = '{{$successUrl}}?fileurl=' + encodeURI(data.picurl);
                    }
                    else {
                        parent.lightyear.notify('上传失败-' + data.info, 'danger');
                    }
                    // 上传成功触发的事件
                });
                this.on("error", function (file, data) {
                    // 上传失败触发的事件
                    parent.lightyear.notify('出现错误-' + data, 'danger');
                });

                this.on("sending", function(file, xhr, formData) {
                    formData.append("_token", window.__token__);
                });
            },
        });
        Dropzone.autoDiscover = false;
   }
</script>
