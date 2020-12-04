<?php

namespace Ichynul\Labuilder\Displayer;

class Rate extends Text
{
    protected $rules = 'number|regex:^([1-9]?\d|100)$';

    protected $after = '<span class="input-group-addon">%</span>';

    protected $size = [2, 3];
}
