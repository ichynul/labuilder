@if($inline)
<span class="the-field {{$class}}" {!! $attr !!}>{{$value}}</span>
    @if($subed)
    <span class="shwo-more">{{$more}}</span>
    <span class="hidden">{{$full}}</span>
    @endif
@else
@include('labuilder::displayer.labeltempl')
<div class="col-md-{{$size[1]}} field-show">
    <span class="the-field {{$class}}" {!! $attr !!}>{{$value}}</span>
    @if($subed)
    <span class="shwo-more">{{$more}}</span>
    <span class="hidden">{{$full}}</span>
    @endif
    @include('labuilder::displayer.helptempl')
</div>
@endif