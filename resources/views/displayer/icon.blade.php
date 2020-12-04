@include('labuilder::displayer.labeltempl')
<div class="col-md-{{$size[1]}}">
    <input type="hidden" class="form-control {{$class}}" placeholder="{{$placeholder?:'请选择'.$label}}" value="{{$value}}" name="{{$name}}"
        id="{{$id}}" {!! $attr !!}><span id="{{$id}}-selected" class="m-l-10 label label-default"></span>
    @include('labuilder::displayer.helptempl')
</div>