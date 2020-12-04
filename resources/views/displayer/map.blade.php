@include('labuilder::displayer.text')
<div class="col-md-{{$size[1]}} col-md-offset-{{$size[0]}}">
    @if($maptype != 'yandex')
    <div class="input-group m-t-10 button-div">
        <input type="text" class="form-control input-sm" placeholder="搜索地点" id="search-{{$id}}">
        <span class="input-group-btn">
            <button class="btn btn-default btn-sm" type="button"><i class="mdi mdi-search"></i>搜索</button>
        </span>
    </div>
    @endif
    <div id="map-{{$id}}" {!! $mapStyle !!}></div>
    @include('labuilder::displayer.helptempl')
</div>