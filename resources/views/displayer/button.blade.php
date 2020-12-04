@include('labuilder::displayer.labeltempl')
<div class="col-md-{{$size[1]}} button-div">
    <button type="{{$type}}" class="btn {{$class}}" {!! $attr !!} id="{{$id}}">{!! $label !!}</button>
    @include('labuilder::displayer.helptempl')
</div>