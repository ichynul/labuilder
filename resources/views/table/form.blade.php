@if($tablink)
{!! $tablink->render() !!}
@endif
<div id="{{{$id}}}" class="row form-wrapper">
    <form action="{$action}}" method="{{$method?:'POST'}}" enctype="application/x-www-form-urlencoded" class="{{$class}}" {!! $attr !!}>
        @foreach($rows as $row)
        <div class="form-group col-md-{{$row->getColSize()}} {{$row->getClass()}} row-{{$row->getName()}}-div {{$row->getErrorClass()}}" {!! $row->getAttrWithStyle() !!}>
            {!! $row->render() !!}
        </div>
        @endforeach
        <div class="col-md-12">
            <div id="help-block" class="help-block hidden has-error text-center">
                <label class="error-label control-label"></label>
            </div>
        </div>
        <div class="col-md-12 m-b-10"></div>
    </form>
    <script>
        if (!window.__forms__) {
            window.__forms__ = [];
        }

        window.__forms__['{{$id}}'] = {
            ajax: '{{$ajax}}' == '1',
            searchFor: '{{$searchFor}}',
            export: 0,
            action: '{{$action}}',
            formSubmit: function () {
                lightyear.loading('hide');
                var that = this;
                if (!that.export && that.ajax) {
                    lightyear.loading('show');

                    var postdata = $('#{{$id}} form').serialize();

                    $.ajax({
                        url: '{{$action}}' || location.href,
                        data: postdata,
                        type: "{{$method?:'POST'}}",
                        dataType: 'html',
                        success: function (data) {
                            setTimeout(function () {
                                lightyear.loading('hide');
                            }, 500);
                            $('#' + that.searchFor).replaceWith(data);
                        },
                        error: function () {
                            lightyear.loading('hide');
                            lightyear.notify('网络错误', 'danger');
                        }
                    });

                    return false;
                }

                return true;
            },
            exportPost: function (url, file_type, ajax) {
                this.export = 1;

                var form = $('#{{$id}} form');

                var values = [];
                $("#{{$searchFor}} input.table-row:checked").each(function (i, e) {
                    values.push($(e).val());
                });

                var __file_type__ = document.createElement("input");
                __file_type__.type = "hidden";
                __file_type__.id = "__file_type__";
                __file_type__.name = '__file_type__';
                __file_type__.value = file_type || '';
                form.append(__file_type__);

                var __ids__ = document.createElement("input");
                __ids__.type = "hidden";
                __ids__.id = "__ids__";
                __ids__.name = '__ids__';
                __ids__.value = values.join(',');
                form.append(__ids__);

                if(ajax)
                {
                    var loading = layer.open({
                        type: 1,
                        closeBtn: 0, //不显示关闭按钮
                        anim: 2,
                        shadeClose: false, //开启遮罩关闭
                        content: '<p style="padding:10px 20px;color:green;"><img src="/vendor/ichynul/labuilder/builder/js/layer/theme/default/loading-1.gif">生成数据中...</p>'
                    });

                    var postdata = $('#{{$id}} form').serialize();
                    $.ajax({
                        url: url,
                        data: postdata,
                        type: 'GET',
                        dataType: 'json',
                        success: function (data) {
                            layer.close(loading);
                            if(data.code)
                            {
                                var filename = data.data.replace(/.+?([^\/]+)$/, '$1');
                                layer.open({
                                    type: 1,
                                    title: '文件下载',
                                    shadeClose: false,
                                    area: ['400px','150px'],
                                    content: '<div class="alert alert-success " role="alert" style="widht:94%;margin:2%;"><p>文件已生成，点击下载：</p><a onclick="layer.closeAll();" target="_blank" href="' + data.data + '">' + filename + '</a></div>',
                                });
                            }
                            else
                            {
                                layer.alert('错误-' + data.msg);
                            }
                        },
                        error:function(){
                            layer.close(loading);
                            lightyear.notify('网络错误', 'danger');
                        }
                    });
                }
                else
                {
                    lightyear.loading('show');
                    form.attr('action', url);
                    form.trigger('submit');
                    form.attr('action', this.action);
                    setTimeout(function () {
                        lightyear.loading('hide');
                    }, 2000);
                }

                $(__ids__).remove();
                $(__file_type__).remove();
                this.export = 0;
            }
        };
    </script>
</div>