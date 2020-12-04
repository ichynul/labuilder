@include('labuilder::displayer.labeltempl')
<div class="col-md-{{$size[1]}} fields-div">
    {!! $fields_content->render() !!}
    @include('labuilder::displayer.helptempl')
</div>