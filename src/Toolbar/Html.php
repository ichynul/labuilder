<?php

namespace Ichynul\Labuilder\Toolbar;

class Html extends Bar
{
    protected $view = 'html';

    public function __construct($html)
    {
        $this->label = $html;
    }

     /**
     * Undocumented function
     *
     * @return mixed
     */
    public function render()
    {
        $vars = $this->commonVars();

        $viewshow = $this->getViewInstance($vars);

        return $viewshow->render();
    }
}
