<?php

namespace Ichynul\Labuilder\Displayer;

use Ichynul\Labuilder\Traits\HasOptions;

class Match extends Raw
{
    use HasOptions;

    public function renderValue()
    {
        if (isset($this->options[$this->value])) {
            $this->value = $this->options[$this->value];
        } else if (isset($this->options['__default__'])) {
            $this->value = $this->options['__default__'];
        }

        return parent::renderValue();
    }
}
