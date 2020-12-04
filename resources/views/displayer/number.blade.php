@include('labuilder::displayer.labeltempl')
<div class="col-md-{{$size[1]}}">
    <input type="text" class="form-control {{$class}}" placeholder="{{$placeholder?:'请输入'.$label}}" value="{{$value}}"
        name="{{$name}}" id="{{$id}}" {!! $attr !!}>
    @include('labuilder::displayer.helptempl')
</div>