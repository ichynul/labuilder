@include('labuilder::displayer.labeltempl')
<div class="col-md-{{$size[1]}} checkbox-div" id="{{$id}}">
    @if($checkallBtn)
    <label class="{{$inline}} {{$class}}">
        <input type="checkbox" class="checkall" id="checkall-{{$name}}" @if($checkall)checked @endif
        data-check="check-{{$name}}"><span>{!! $checkallBtn !!}</span>
    </label>
    @endif
    @foreach($options as $key => $option)
    <label class="{{$inline}} {{$class}}">
        <input type="checkbox" value="{{$key}}" class="check-{{$name}}" @if(in_array('-'.$key, $checked))checked @endif
            id="{{$id}}-{{$key}}" name="{{$name}}[]" {!! $attr !!}><span>{!! $option !!}</span>
    </label>
    @endforeach
    @include('labuilder::displayer.helptempl')
</div>
