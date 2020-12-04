@include('labuilder::displayer.labeltempl')
<div class="col-md-{{$size[1]}}">
    <div class="input-group col-md-12">
        {!! $befor !!}
        <input type="text" class="form-control {{$class}}" placeholder="{{$placeholder?:'请输入'.$label}}"
            value="{{$value}}" name="{{$name}}" id="{{$id}}" {!! $attr !!}>
        {!! $after !!}
    </div>
    @include('labuilder::displayer.helptempl')
</div>