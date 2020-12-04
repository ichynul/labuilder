<?php

namespace Ichynul\Labuilder\Displayer;

class TimeRange extends DateTimeRange
{
    protected $format = 'HH:mm:ss';

    protected $befor = '';

    protected $timePicker = true;

    /**
     * Undocumented function
     * HH:mm:ss
     * @param string $val
     * @return $this
     */
    public function format($val)
    {
        $this->format = $val;
        return $this;
    }
}
