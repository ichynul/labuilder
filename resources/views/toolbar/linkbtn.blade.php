@if($useLayer)
<a class="btn {{$class}} btn-{{$name}}" id="{{$id}}" href="javascript:;" data-url="{{$href}}" onclick="layerOpen(this);return false;" {!! $attr !!}>
        @if($icon)<i class="mdi {{$icon}}"></i>@endif{!! $label !!}
</a>
@else
<a class="btn {{$class}} btn-{{$name}}" id="{{$id}}" href="{{$href}}" {!! $attr !!}>
        @if($icon)<i class="mdi {{$icon}}"></i>@endif{!! $label !!}
</a>
@endif
