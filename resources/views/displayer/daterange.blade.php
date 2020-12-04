@include('labuilder::displayer.labeltempl')
<div class="col-md-{{$size[1]}}">
    <div class="input-group col-md-12" id="{{$id}}-piker">
        {!! $befor !!}
        <input type="hidden" value="{{$value}}" name="{{$name}}" id="{{$id}}" {!! $attr !!}>
        <input class="form-control {{$class}}" type="text" id="{{$id}}-start" placeholder="从" {!! $attr !!}>
        <span class="input-group-addon"><i class="mdi mdi-minus"></i></span>
        <input class="form-control {{$class}}" type="text" id="{{$id}}-end" placeholder="至" {!! $attr !!}>
        {!! $after !!}
    </div>
    @include('labuilder::displayer.helptempl')
</div>