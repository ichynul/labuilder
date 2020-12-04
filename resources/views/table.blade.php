@if($searchForm)
{!! $searchForm->render() !!}
@endif

@if($toolbar)
<div class="row m-b-10 m-l-10">
    {!! $toolbar->render() !!}
</div>
@endif

<div id="{{$id}}" class="row">
    @if ($addTop)
    {!! $addTop->render() !!}
    @endif
    <div class="table-wrapper">
        <table class="table {{$class}}" {!! $attr !!}>
            @if($headers)
            <thead>
                <tr>
                    @if($useCheckbox)
                    <th class="table-checkbox">
                        <label class="lyear-checkbox checkbox-primary">
                            <input type="checkbox" id="checkall-{{$name}}" data-check="check-{{$name}}" class="checkall
                                table-row-checkall"><span></span>
                        </label>
                    </th>
                    @endif
                    @foreach($headers as $key => $header)
                    <th class="{{$headTextAlign}}">
                        {!! $header !!}
                        @if(in_array($key, $sortable))
                            @if($key == $sortKey)
                                @if($sortOrder == 'asc')
                                <a data-key="{{$key}}" href="javascript:;" class="sortable mdi mdi-sort-ascending"></a>
                                @else
                                <a data-key="{{$key}}" href="javascript:;" class="sortable mdi mdi-sort-descending"></a>
                                @endif
                            @else
                                <a data-key="{{$key}}" href="javascript:;" class="sortable mdi mdi-sort"></a>
                            @endif
                        @endif
                    </th>
                    @endforeach
                    @if($actionbars)
                    <th class="{!! $headTextAlign !!}">{!! $actionRowText !!}</th>
                    @endif
                </tr>
            </thead>
            @endif
            <tbody>
                @foreach($list as $key => $row)
                <tr class="table-row-id" data-id="{{$ids[$key]}}">
                    @if($useCheckbox)
                    <td class="table-checkbox {{$tdClass}}">
                        <label class="lyear-checkbox checkbox-primary">
                            <input type="checkbox" id="{{$name}}-{{$key}}" value="{{$ids[$key]}}" @if(in_array($ids[$key], $checked)) checked @endif class="check-{{$name}} table-row"><span></span>
                        </label>
                    </td>
                    @endif
                    @foreach($row as $co => $col)
                    <td class="col-md-{{$col['wrapper']->getColSize()}} {{$tdClass}} {{$col['wrapper']->getClass()}} row-{{$col['wrapper']->getName()}}-td" {!! $col['wrapper']->getAttrWithStyle() !!}>
                        {!! $col['value'] !!}
                    </td>
                    @endforeach
                    @if($actionbars)
                    <td class="text-center row-__action__ {{$verticalAlign}}">
                        {!! $actionbars[$key] !!}
                    </td>
                    @endif
                </tr>
                @endforeach
            </tbody>
        </table>
    </div>
    @if(empty($list))
    <div class="table-empty-text">
        {!! $emptyText !!}
    </div>
    @endif
    @if($paginator)
    <nav class="pagination-navi {{$paginator->getClass()}}" {!! $paginator->getAttrWithStyle() !!}>
        {!! $paginator->render() !!}
        @if($pagesizeDropdown)
        {!! $pagesizeDropdown->render() !!}
        @endif
    </nav>
    @endif
    @if($addBottom)
    {!! $addBottom->render() !!}
    @endif
    <script>
        var partial = '{{$partial}}' == 1;

        document.getElementById('form-__sort__-{{$id}}').value = '{{$sort}}';

        if (partial) {
            $('#form-__page__-{{$id}}').val('{{$paginator->currentPage()}}');

            $('#{{$id}} input.checkall').each(function (i, e) {
                var checkall = $(e);
                var checkboxes = $('.' + checkall.data('check'));
                var count = checkboxes.size();

                checkall.on('change', function () {
                    checkboxes.prop('checked', checkall.is(':checked'));
                });

                checkboxes.on('change', function () {
                    var ss = 0;
                    checkboxes.each(function (ii, ee) {
                        if ($(ee).is(':checked')) {
                            ss += 1;
                        }
                    });
                    checkall.prop('checked', ss == count);
                });
            });

            $('#{{$id}} input.table-row-checkall').trigger('change');
            if (window.renderFiles) {
                window.renderFiles('#{{$id}} ');
            }
            $("table .form-control.readonly").attr('readonly', 'readonly');
            $("table .form-control.disabled").attr('disabled', 'disabled');
            $("table .form-control.not-readonly").removeAttr('readonly');
            $("table .form-control.not-disabled").removeAttr('disabled')
        }
    </script>
</div>