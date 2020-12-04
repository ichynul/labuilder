@include('labuilder::displayer.labeltempl')
<div class="col-md-{{$size[1]}}">
    <select class="form-control {{$class}}" name="{$name}[]" id="{{$id}}"
        multiple="multiple" data-selected="{$dataSelected}" {!! $attr !!}>
        @if($group)
            @foreach($options as $key => $group)
            <optgroup label="{{$group.label}}">
                @foreach($group['options'] as $key => $option)
                <option value="{{$key}}" @if(in_array($key, $checked))selected @endif>{{$option}}</option>
                @endforeach
            </optgroup>
            @endforeach
        @else
            @foreach($options as $key => $option)
            <option value="{{$key}}" @if(in_array($key, $checked))selected @endif>{{$option}}</option>
            @endforeach
        @endif
    </select>
    @include('labuilder::displayer.helptempl')
</div>