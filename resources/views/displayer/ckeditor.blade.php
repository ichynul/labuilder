@include('labuilder::displayer.labeltempl')
<div class="col-md-{{$size[1]}}">
    @if($readonly)
    <div class="field-show editor-readonly">{!! $value !!}</div>
    @else
    <textarea class="form-control hidden {{$class}}" placeholder="请输入{{$label}}" name="{{$name}}" id="{{$id}}"{!! $attr !!}>
        {!! $value !!}
    </textarea>
    @endif
    @include('labuilder::displayer.helptempl')
</div>
