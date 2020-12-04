<?php

namespace Ichynul\Labuilder\Displayer;

use Ichynul\Labuilder\Traits\HasOptions;

class Radio extends Field
{
    use HasOptions;

    protected $view = 'radio';

    protected $class = 'lyear-radio radio-default';

    protected $inline = true;

    protected $checked = '';

    /**
     * Undocumented function
     *
     * @param boolean $val
     * @return $this
     */
    public function inline($val = true)
    {
        $this->inline = $val;
        return $this;
    }

    public function render()
    {
        $vars = $this->commonVars();

        if (!($this->value === '' || $this->value === null)) {
            $this->checked = $this->value;
        } else {
            $this->checked = $this->default;
        }

        $vars = array_merge($vars, [
            'inline' => $this->inline ? 'radio-inline' : '',
            'checked' => '-' .$this->checked,
            'options' => $this->options,
        ]);

        $viewshow = $this->getViewInstance($vars);

        return $viewshow->render();
    }
}
