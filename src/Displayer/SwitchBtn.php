<?php

namespace Ichynul\Labuilder\Displayer;

class SwitchBtn extends Field
{
    protected $view = 'switchbtn';

    protected $class = 'switch-outline switch-primary';

    protected $checked = '';

    protected $pair = [1, 0];

    /**
     * Undocumented function
     * @example 1 (1, 0) / ('yes', 'no') / ('on', 'off') etc...
     * @param array $val
     * @return $this
     */
    public function pair($on = 1, $off = 0)
    {
        $this->pair = [$on, $off];

        return $this;
    }

    /**
     * Undocumented function
     *
     * @return array
     */
    public function getPair()
    {
        return $this->pair;
    }

    protected function boxScript()
    {
        $inputId = $this->getId();

        $script = <<<EOT

        $('#{$inputId}').next('label').find('input').on('change', function(){
            $('#{$inputId}').val($(this).is(':checked') ? '{$this->pair[0]}' : '{$this->pair[1]}');
        });

        $('#{$inputId}').val($('#{$inputId}').next('label').find('input').is(':checked') ? '{$this->pair[0]}' : '{$this->pair[1]}');

EOT;
        $this->script[] = $script;

        return $script;
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
            'checked' => $this->checked,
            'pair' => $this->pair,
        ]);

        $viewshow = $this->getViewInstance($vars);

        return $viewshow->render();
    }

    public function beforRender()
    {
        $this->boxScript();

        return parent::beforRender();
    }
}
