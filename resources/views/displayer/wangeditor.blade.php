@include('labuilder::displayer.labeltempl')
<div class="col-md-{{$size[1]}}">
    @if($readonly)
    <div class="field-show editor-readonly">{!! $value !!}</div>
    @else
    <div id="{{$id}}-div" style="width: 100%; height: 100%;">
        <p>{!! $value !!}</p>
    </div>
    <textarea class="form-control hidden {{$class}}" placeholder="请输入{{$label}}" name="{{$name}}" id="{{$id}}"{!! $attr !!}>
        {!! $value !!}
    </textarea>
    @endif
    @include('labuilder::displayer.helptempl')
</div>
