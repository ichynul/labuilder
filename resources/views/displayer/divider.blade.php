@include('labuilder::displayer.labeltempl')
<div class="col-md-{{$size[1]}}">
    <div class='divider'>{!! $value?:'-' !!}</div>
    @include('labuilder::displayer.helptempl')
</div>