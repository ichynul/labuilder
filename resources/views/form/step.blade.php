<div id="{{$id}}" class="{{$class}}" {!! $attr !!}>
    @if($rows)
    <div class="col-md-{{$size[0]}}" style="visibility: hidden;"></div>
    <ul class="nav-step col-md-{{$size[1]}} {{$class}}">
        @foreach($labels as $label)
        <li class="nav-step-item {$label->active}">
            @if($mode == 'anchor')
            <a data-toggle="tab" href="#{{$id}}-{{$key}}">
                <h6>{!! $label->content !!}</h6>
                <p class="m-0">{!! $label->description ?: $label->content !!}</p>
            </a>
            @else
            <span>{!! $label->content !!}</span>
            <a data-toggle="tab" href="#{$id}-{$key}"></a>
            @endif
        </li>
        @endforeach
    </ul>
    <div class="nav-step-content">
        @foreach($rows as $row)
        <div class="nav-step-pane hidden {{$row.active}}" id="{{$id}}-{{$key}}">
            {!! $row.content->render() !!}
        </div>
        @endforeach
    </div>
    <div class="col-md-5" style="visibility: hidden;"></div>
    <div class="nav-step-button col-md-2">
        <button class="btn btn-secondary disabled" data-wizard="prev" type="button">上一步</button>
        <button class="btn btn-secondary" data-wizard="next" type="button">下一步</button>
        <button class="btn btn-primary hidden" data-wizard="finish" type="submit">完&nbsp;&nbsp;成</button>
    </div>
    @endif
</div>