@include('labuilder::displayer.labeltempl')
<div class="col-md-{{$size[1]}} ">
    <div class="js-upload-files" data-name="{{$name}}">
        @if($canUpload)
        <div class="input-group col-md-12">
            @if($showInput)
            <input type="text" class="form-control {{$class}} file-url-input"
                placeholder="请上传{{$label}}" value="{{$value}}" name="{{$name}}" id="{{$id}}" {!! $attr !!}>
            @endif
            <span title="点击上传新文件" class="input-group-addon opt-btn" onclick="$('#picker_{{$name}} .webuploader-element-invisible').trigger('click')"><i class="mdi mdi-folder-open"></i>上传</span>
            @if($showChooseBtn)
            <span title="点击选择已经上传的文件" class="input-group-addon opt-btn" onclick="chooseFile('{{$id}}' ,'{{$name}}')"><i class="mdi mdi mdi-file-find"></i>选择</span>
            @else
            <input type="text" class="form-control {{$class}} file-url-input pull-right" style="visibility: hidden;"
                placeholder="请上传{{$label}}" value="{{$value}}" name="{{$name}}" id="{{$id}}" {!! $attr !!}>
            @endif
            <div style="display: none;"><a id="picker_{{$name}}"></a></div>
        </div>
        @endif
        <ul id="file_list_{{$name}}" class="pull-left list-inline lyear-uploads-pic">
            @foreach($files as $key => $file)
            <li class="pic-item" id="flie{{$key}}">
                <figure>
                    <div>
                        <img style="display: none;" class="preview-img" src="{{$file}}" />
                    </div>
                    <figcaption>
                        <a class="btn btn-xs btn-round btn-square btn-primary
                            btn-link-pic" data-id="{{$key}}" href="{{$file}}"><i class="mdi mdi-eye"></i></a>
                        @if($canUpload)
                        <a class="btn btn-xs btn-round btn-square btn-danger
                            btn-remove-pic" data-id="{{$key}}" data-url="{{$file}}" href="javascript:;"><i class="mdi
                                mdi-delete"></i></a>
                        @endif
                    </figcaption>
                </figure>
            </li>
            @endforeach
        </ul>
    </div>
    <script>
        var jsOptions = JSON.parse('@json($jsOptions)')

        if (!window.uploadConfigs) {
            window.uploadConfigs = [];
        }

        window.uploadConfigs['{{$name}}'] = jsOptions;
    </script>
    @include('labuilder::displayer.helptempl')
</div>