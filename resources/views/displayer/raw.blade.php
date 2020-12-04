@if($inline)
<span class="the-field {{$class}}" {!! $attr !!}>{!! $value !!}</span>
@else
@include('labuilder::displayer.labeltempl')
<div class="col-md-{{$size[1]}} field-raw">
    <span class="the-field {{$class}}" {!! $attr !!}>{!! $value !!}</span>
    @include('labuilder::displayer.helptempl')
</div>
@endif