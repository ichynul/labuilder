@include('labuilder::displayer.labeltempl')
<div class="col-md-{{$size[1]}}" title="{{$placeholder?:'请输入'.$label.',回车键分割'}}">
    <input type="text" class="form-control {{$class}}" placeholder="{{$placeholder?:'请输入'.$label}}" value="{{$value}}"
        name="{{$name}}" id="{{$id}}" {!! $attr !!}>
    @include('labuilder::displayer.helptempl')
</div>