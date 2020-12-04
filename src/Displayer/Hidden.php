<?php

namespace Ichynul\Labuilder\Displayer;

class Hidden extends Field
{
    protected $view = 'hidden';

    public function __construct($name)
    {
        $this->name = $name;
    }

    public function created($fieldType = '')
    {
        $this->getWrapper()->addStyle('display:none;');
    }
}
