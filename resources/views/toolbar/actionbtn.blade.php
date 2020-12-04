@if($useLayer)
<a class="btn {{$class}} action-{{$name}}" id="{{$id}}" data-id="{{$dataId}}" href="javascript:;" data-url="{{$href}}" onclick="layerOpen(this);return false;" {!! $attr !!}>
        @if($icon)<i class="mdi {{$icon}}"></i> @endif {!! $label !!}
</a>
@else
<a class="btn {{$class}} action-{{$name}}" id="{{$id}}" data-id="{{$dataId}}" href="{{$href}}" {!! $attr !!}>
        @if($icon)<i class="mdi {{$icon}}"></i>@endif{!! $label !!}
</a>
@endif
