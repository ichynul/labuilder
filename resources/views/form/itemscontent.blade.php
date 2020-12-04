<div class="table-wrapper">
  <table id="items-{$name}" class="table form-items {{$class}}" {!! $attr !!}>
    @if($headers)
    <thead>
      <tr>
        @foreach($headers as $header)
        <th class="text-center vertical-middle item-label">
          {!! $header !!}
        </th>
        @endforeach
        @if($cnaDelete)
        <th style="min-width: 50px;" class="text-center vertical-middle">{!! $actionRowText !!}</th>
        @endif
      </tr>
    </thead>
    @endif
    <tbody>
      @foreach($list as $row)
      <tr class="table-row-id" data-id="{{$ids[$key]}}">
        @foreach($row as $col)
        <td class="{{$col['wrapper']->getClass()}} vertical-middle" {!! $col['wrapper']->getAttrWithStyle() !!}>
          {!! $col['value'] !!}
        </td>
        @endforeach
        @if($cnaDelete)
        <td class="row-__action__ vertical-middle text-center">
          <input type="hidden" name="{{$name}}[{$ids[$key]}][__del__]" value="0" />
          @if($col->__can_delete__)
          <span class="btn btn-xs btn-danger action-delete" id="bar-delete-{{$key}}" data-id="{{$ids[$key]}}" title="删除">
            <i class="mdi mdi-delete"></i>
          </span>
          @else
          <span class="btn btn-xs btn-danger disabled" title="不允许删除"><i class="mdi mdi-delete"></i></span>
          @endif
        </td>
        @endif
      </tr>
      @endforeach
      @if($canAdd)
      <tr>
        @foreach($cols as $col)
        <td><input type="text" class="form-control" style="visibility: hidden;" /></td>
        @endforeach
        <td class="row-__action__ vertical-middle text-center">
          <span id="items-{{$name}}-add" class="btn btn-xs btn-success" title="新加一条"><i
              class="mdi mdi-plus-circle-outline"></i></span>
        </td>
      </tr>
      @endif
    </tbody>
  </table>
  @if($canAdd)
  <table style="display: none;" id="items-{$name}-temple" class="table table-items {{$class}}" {!! $attr !!}>
    <tbody>
      <tr class="table-row-id">
        @foreach($template as $col)
        <td class="{{$col['wrapper']->getClass()}} text-center vertical-middle" {$col['wrapper']->getAttrWithStyle() !!}>
          {!! $col['value'] !!}
        </td>
        @endforeach
        <td class="row-__action__ vertical-middle text-center">
          <input type="hidden" value="-1" />
          <span class="btn btn-xs btn-danger action-delete" id="bar-delete-0" data-id="new-0" title="删除">
            <i class="mdi mdi-delete"></i></span>
        </td>
      </tr>
    </tbody>
  </table>
  <textarea style="display: none;" id="items-{{$name}}-script">{!! $script !!}</textarea>
  @endif
  @if(empty($list))
  <div class="items-empty-text">
    {!! $emptyText !!}
  </div>
  @endif
</div>
