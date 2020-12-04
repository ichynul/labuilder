@if($options)
<div id="{{$id}}" class="{{$class}}" {!! $attr !!}>
    <ul class="nav nav-tabs">
        @foreach($options as $key => $option)
        <li class="nav-item tab-{{$key}}">
            <a data-val="{{$key}}" href="#tabkey_{{$key}}">{!! $option !!}</a>
        </li>
        @endforeach
    </ul>
</div>
@endif