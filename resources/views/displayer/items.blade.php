@include('labuilder::displayer.labeltempl')
<div class="col-md-{{$size[1]}} items-div">
    {!! $items_content->render() !!}
    @include('labuilder::displayer.helptempl')
</div>