@include('labuilder::displayer.labeltempl')
<div class="col-md-{{$size[1]}}">
    <textarea class="form-control {{$class}}" placeholder="{{$placeholder?:'请输入'.$label}}" name="{{$name}}" id="{{$id}}"
        {!! $attr !!}>{{$value}}</textarea>
    @include('labuilder::displayer.helptempl')
</div>