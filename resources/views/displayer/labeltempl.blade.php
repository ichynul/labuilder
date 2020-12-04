@if($showLabel)
<label class="col-md-{{$size[0]}} {{$labelClass}}" {!! $labelAttr !!} for="{{$id}}">
    <strong title="必填" class="field-required" {!! $requiredStyle !!}> *</strong>
    {!! $label !!}
</label>
@endif