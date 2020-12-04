<?php

namespace Ichynul\Labuilder\Displayer;

class Year extends Date
{
    protected $format = 'YYYY';

    /**
     * Undocumented function
     * YYYY
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
    public function timespan($val = 'Y')
    {
        $this->timespan = $val;
        return $this;
    }
}
