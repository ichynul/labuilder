@foreach($rows as $row)
<div class="form-group col-md-{{$row->getColSize()}} {{$row->getClass()}} row-{{$row->getName()}}-div {{$row->getErrorClass()}}" {!! $row->getAttrWithStyle() !!}>
  {!! $row->render() !!}
</div>
@endforeach