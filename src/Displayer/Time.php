<?php

namespace Ichynul\Labuilder\Displayer;

class Time extends DateTime
{
    protected $format = 'HH:mm:ss';

    protected $befor = '<span class="input-group-addon"><i class="mdi mdi-clock"></i></span>';

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

    /**
     * Undocumented function
     *
     * @param string $val
     * @return $this
     */
    public function timespan($val = 'H:i:s')
    {
        $this->timespan = $val;
        return $this;
    }
}
