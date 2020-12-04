@if($labels)
<div id="{{$id}}" class="{{$class}}" {!! $attr !!}>
    <ul class="nav nav-tabs">
        @foreach($labels as $label)
        <li class="nav-item {{$label['active']??''}}">
            <a {!! $label['attr'] !!} href="{{$label['href']}}">{!! $label['content'] !!}</a>
        </li>
        @endforeach
    </ul>
    @if($rows)
    <div class="tab-content">
        @foreach($rows as $key => $row)
        <div class="tab-pane fade {{$row['active']??''}}" id="{{$id}}-{{$key}}">
            {!! $row['content']->render() !!}
        </div>
        @endforeach
    </div>
    @endif
</div>
@endif