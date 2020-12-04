<?php

namespace Ichynul\Labuilder\Displayer;

class RangeSlider extends Text
{
    protected $js = [
        '/vendor/ichynul/labuilder/builder/js/ion-rangeslider/ion.rangeSlider.min.js',
    ];

    protected $css = [
        '/vendor/ichynul/labuilder/builder/js/ion-rangeslider/ion.rangeSlider.min.css',
    ];

    protected $jsOptions = [
        'type' => 'single',
        'min' => 0,
        'max' => 100,
    ];

    protected $checked = [];

    /**
     * Undocumented function
     *
     * @param array $options
     * @return $this
     */
    public function jsOptions($options)
    {
        $this->jsOptions = array_merge($this->jsOptions, $options);
        return $this;
    }

    protected function rangeScript()
    {
        $inputId = $this->getId();

        if (!empty($this->value)) {
            $this->checked = is_array($this->value) ? $this->value : explode(';', $this->value);
        } else if (!empty($this->default)) {
            $this->checked = is_array($this->default) ? $this->default : explode(';', $this->default);
        }

        if (count($this->checked) > 0) {
            $this->jsOptions['from'] = $this->checked[0];

            if (count($this->checked) > 1) {
                $this->jsOptions['to'] = $this->checked[1];
                $this->jsOptions['type'] = 'double';
            }
        }

        if ($this->disabled) {
            $this->jsOptions['disable'] = true;
        }

        $configs = json_encode($this->jsOptions);

        $script = <<<EOT

        $('#{$inputId}').ionRangeSlider({$configs});

EOT;
        $this->script[] = $script;

        return $script;
    }

    public function beforRender()
    {
        $this->rangeScript();

        return parent::beforRender();
    }
}
