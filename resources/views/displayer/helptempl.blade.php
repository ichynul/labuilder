@if($error)
<small class="help-block">{{$error}}</small>
@endif
@if($help)
<div class="help-block">
    <i class="mdi mdi-information-outline"></i>&nbsp;{!! $help !!}
</div>
@endif