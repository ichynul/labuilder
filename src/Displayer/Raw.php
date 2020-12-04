<?php

namespace Ichynul\Labuilder\Displayer;

class Raw extends Field
{
    protected $view = 'raw';

    protected $inline = false;

    /**
     * Undocumented function
     *
     * @param boolean $val
     * @return $this
     */
    public function inline($val)
    {
        $this->inline = $val;
        return $this;
    }

    public function customVars()
    {
        return [
            'inline' => $this->inline,
        ];
    }
}
