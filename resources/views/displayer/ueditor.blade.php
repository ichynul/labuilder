@include('labuilder::displayer.labeltempl')
<div class="col-md-{{$size[1]}}">
    @if($readonly)
    <div class="field-show editor-readonly">{!! $value !!}</div>
    @else
    <script name="{{$name}}" id="{{$id}}" type="text/plain">{{$value}}</script>
    <script type="text/javascript">
        window.uploadUrl = '{{$uploadUrl}}'
    </script>
    @endif
    @include('labuilder::displayer.helptempl')
</div>