@include('labuilder::displayer.labeltempl')
<div class="col-md-{{$size[1]}} radio-div" id="{{$id}}">
    @foreach($options as $key => $option)
    <label class="{{$inline}} {{$class}}">
        <input type="radio" value="{{$key}}" class="check-{{$name}}" @if('-'.$key == $checked)checked @endif
            id="{{$id}}-{{$key}}" name="{{$name}}" {!! $attr !!}><span>{!! $option !!}</span>
    </label>
    @endforeach
    @include('labuilder::displayer.helptempl')
</div>
