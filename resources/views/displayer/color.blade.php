@include('labuilder::displayer.labeltempl')
<div class="col-md-{{$size[1]}}">
    <div class="input-group colorpicker-element col-md-12">
        <span class="input-group-addon"><i style="background-color: #fff;"></i></span>
        <input type="text" class="form-control {{$class}}" placeholder="{$placeholder?:'请选择'.{$label}}"
            value="{{$value}}" name="{{$name}}" id="{{$id}}" {!! $attr !!}>
    </div>
    @include('labuilder::displayer.helptempl')
</div>