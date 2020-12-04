<div class="row {{ $class }}" {!! $attr !!}>
    @foreach($cols as $col)
    <div class="col-md-{{ $col->getSize() }} {{ $col->getClass() }}" {!! $col->getAttrWithStyle() !!}>
      @foreach($col->getElms() as $elm)
        {!! $elm->render() !!}
      @endforeach
    </div>
    @endforeach
</div>
