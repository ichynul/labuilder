@include('labuilder::displayer.labeltempl')
<div class="col-md-{{$size[1]}} checkbox-div">
    <input type="hidden" name="{{$name}}" id="{{$id}}" {!! $attr !!}>
    <label class="lyear-switch {{$class}}">
        <input type="checkbox" data-on="{{$pair[0]}}" data-off="{{$pair[1]}}" class="switch-box" id="{{$id}}-box" @if($checked)checked @endif {!! $attr !!}>
        <span></span>
    </label>
    @include('labuilder::displayer.helptempl')
</div>