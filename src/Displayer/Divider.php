<?php

namespace Ichynul\Labuilder\Displayer;

class Divider extends Field
{
    protected $view = 'divider';

    public function created($fieldType = '')
    {
        parent::created($fieldType);

        $this->value = $this->label ? $this->label : $this->name;

        $this->name = 'divider' . mt_rand(100, 999);

        $this->label = '';
    }
}
