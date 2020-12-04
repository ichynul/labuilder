<div id="{{{$id}}}" class="row form-wrapper">
    <form action="{{$action}}" method="{{$method?:'POST'}}" enctype="application/x-www-form-urlencoded" class="{{$class}}" { !! $attr !!}>
        @foreach($rows as $row)
        @if($row instanceof Ichynul\Labuilder\Common\Tab)
        <div class="col-md-12">
            {!! $row->render() !!}
        </div>
        @elseif($row instanceof Ichynul\Labuilder\Form\step)
        <div class="col-md-12">
            {!! $row->render() !!}
        </div>
        @else
        <div class="form-group col-md-{{$row->getColSize()}} {{$row->getClass()}} row-{{$row->getName()}}-div{{$row->getErrorClass()}}" {!! $row->getAttrWithStyle() !!}>
            {!! $row->render() !!}
        </div>
        @endif
        @endforeach
        <div class="col-md-12">
            <div id="help-block" class="help-block hidden has-error text-center">
                <label class="error-label control-label"></label>
            </div>
        </div>
        <input type="hidden" name="_token" value="{{$__token__??''}}" />
        <div class="col-md-12 m-b-10"></div>
    </form>
    <script>
        if (!window.__forms__) {
            window.__forms__ = [];
        }

        window.__forms__['{{$id}}'] = {
            ajax: '{{$ajax}}' == '1',
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
                            if (/^\{.+\}$/.test(data)) {
                                data = JSON.parse(data);
                                if (data.status || data.code) {
                                    if (data.layer_close) {
                                        that.closeLayer(data.msg || data.message || '操作成功！', 'success');
                                    } else if (data.layer_close_refresh) {
                                        that.closeLayerRefresh(data.msg || data.message || '操作成功！', 'success');
                                    } else if (data.layer_close_go) {
                                        that.closeLayerGo(data.msg || data.message || '操作成功！', data
                                            .layer_close_go, 'success');
                                    }
                                    else if (data.url) {
                                        lightyear.notify(data.msg || data.message || '操作成功！', 'success');
                                        setTimeout(function () {
                                            location.replace(data.url);
                                        }, data.wait * 1000 || 2000);
                                    }
                                    else {
                                        lightyear.notify(data.msg || data.message || '操作成功！',
                                            'success');
                                    }
                                } else {
                                    if (data.layer_close) {
                                        that.closeLayer(data.msg || data.message || '操作失败！', 'danger');
                                    } else if (data.layer_close_refresh) {
                                        that.closeLayerRefresh(data.msg || data.message || '操作失败！', 'danger');
                                    } else if (data.layer_close_go) {
                                        that.closeLayerGo(data.msg || data.message || '操作失败！', data
                                            .layer_close_go, 'danger');
                                    }
                                    else if (data.url) {
                                        lightyear.notify(data.msg || data.message || '操作失败', 'danger');
                                        setTimeout(function () {
                                            location.replace(data.url);
                                        }, data.wait * 1000 || 2000);
                                    }
                                    else {
                                        lightyear.notify(data.msg || data.message || '操作失败', 'danger');
                                    }
                                }
                                if (data.script || (data.data && data.data.script)) {
                                    var script = data.script || data.data.script;
                                    if ($('#script-div').size()) {
                                        $('#script-div').html(script);
                                    } else {
                                        $('body').append(
                                            '<div class="hidden" id="script-div">' + script + '</div>');
                                    }
                                }
                            }
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
            closeLayer: function (msg, style) {
                if (parent) {
                    parent.lightyear.notify(msg, style);
                    if (parent.layer) {
                        var index = parent.layer.getFrameIndex(window.name); //获取窗口索引
                        parent.layer.close(index);
                    }
                } else {
                    lightyear.notify(msg, style);
                }
            },
            closeLayerRefresh: function (msg, style) {
                if (parent) {
                    parent.lightyear.notify(msg, style);
                    if (parent.$('.search-refresh').size()) {
                        parent.$('.search-refresh').trigger('click');
                    } else {
                        parent.location.reload();
                    }
                    if (parent.layer) {
                        var index = parent.layer.getFrameIndex(window.name); //获取窗口索引
                        parent.layer.close(index);
                    }
                } else {
                    lightyear.notify(msg, style);
                }
            },
            closeLayerGo: function (msg, url, style) {
                if (parent) {
                    parent.lightyear.notify(msg, style);
                    if (parent.layer) {
                        parent.location.replace(url);
                        var index = parent.layer.getFrameIndex(window.name); //获取窗口索引
                        parent.layer.close(index);
                    }
                } else {
                    lightyear.notify(msg, style);
                }
            }
        };
    </script>
</div>
