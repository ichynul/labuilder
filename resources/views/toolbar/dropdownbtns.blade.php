<div id="{{$id}}-div" class="btn-group {{$groupClass}}" role="group" {!! $groupAttr !!}>
        <a class="btn {{$class}} btn-{{$name}} dropdown-toggle" id="{{$id}}" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false" {!! $attr !!}>
                @if($icon)<i class="mdi {{$icon}}"></i> @endif {!! $label !!}
                <span class="caret"></span>
        </a>
        <ul class="dropdown-menu">
                @foreach($items as $item)
                <li>
                        <a data-key="{{$item['key']}}" class="{{$item['class']}}" data-url="{{$item['url']}}" href="javascript:;" {!! $item['attr'] !!}>
                                @if($item['icon'])<i class="mdi {{$item['icon']}}"></i>@endif{!! $item['label'] !!}
                        </a>
                </li>
                @endforeach
        </ul>
</div>
