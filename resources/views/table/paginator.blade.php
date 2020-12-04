{!! $summaryHtml !!}
<ul class="pagination {{$paginatorClass}}">
@foreach($links as $link)
@if($link['url'])
    @if($link['active'])
    <li class="active"><span>{!! $link['label'] !!}</span></li>
    @else
    <li><a href="{!! $link['url'] !!}">{!! $link['label'] !!}</a></li>
    @endif
@else
<li class="disabled"><span>{!! $link['label'] !!}</span></li>
@endif
@endforeach
</ul>
{!! $inputHtml !!}
