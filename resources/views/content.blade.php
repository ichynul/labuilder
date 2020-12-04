@extends('labuilder::layout')

@section('style')
@foreach($css as $item)
<link href="{{$item}}" rel="stylesheet">
@endforeach
<style>
  {!! $stylesheet !!}

</style>
@endsection

@section('content')
<div class="panel panel-default content">
  @if($title)
  <div class="panel-heading content-header">
    <h4 class="panel-title">
      {{$title}}
      @if($desc)
      <small> - {{$desc}}</small>
      @endif
    </h4>
  </div>
  @endif
  <div class="panel-body">
    @foreach($rows as $row)
        {!! $row->render() !!}
    @endforeach
  </div>
</div>
@endsection

@section('script')
@foreach($js as $item)
  <script type="text/javascript" src="{{$item}}" charset="utf-8"></script>
@endforeach
<script type="text/javascript">
    $(function(){

      {!! $script !!}

    });
</script>
<div class="hidden" id="script-div"></div>
@endsection
