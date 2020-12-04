<?php

namespace Ichynul\Labuilder\Displayer;

class Date extends DateTime
{
    protected $format = 'YYYY-MM-DD';

    protected $befor = '<span class="input-group-addon"><i class="mdi mdi-calendar-range"></i></span>';

    /**
     * Undocumented function
     * YYYY-MM-DD
     * @param string $val
     * @return $this
     */
    public function format($val)
    {
        $this->format = $val;
        return $this;
    }

    /**
     * Undocumented function
     *
     * @param string $val
     * @return $this
     */
    public function timespan($val = 'Y-m-d')
    {
        $this->timespan = $val;
        return $this;
    }
}
