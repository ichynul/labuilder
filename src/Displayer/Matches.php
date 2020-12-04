<?php

namespace Ichynul\Labuilder\Displayer;

use Ichynul\Labuilder\Traits\HasOptions;

class Matches extends Raw
{
    use HasOptions;

    public function renderValue()
    {
        $values = explode(',', $this->value);
        $texts = [];

        foreach ($values as $value) {
            if (isset($this->options[$value])) {
                $texts[] = $this->options[$value];
            }
        }

        $this->value = implode(',', $texts);

        return parent::renderValue();
    }
}
