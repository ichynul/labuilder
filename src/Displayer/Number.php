<?php

namespace Ichynul\Labuilder\Displayer;

class Number extends Field
{
    protected $view = 'number';

    protected $rules = 'number';

    protected $size = [2, 3];

    protected $min = 0;

    protected $max = 9999999;

    protected $js = [
        '/vendor/ichynul/labuilder/builder/js/bootstrap-touchspin/jquery.bootstrap-touchspin.min.js',
    ];

    protected $css = [
        '/vendor/ichynul/labuilder/builder/js/bootstrap-touchspin/jquery.bootstrap-touchspin.css',
    ];

    protected $placeholder = '';

    protected $jsOptions = [
        //'postfix' => '%',
        //'prefix' => '¥',
        'step' => 1,
        'verticalbuttons' => true,
        'initval' => 0,
    ];

    /**
     * Undocumented function
     *
     * @param int $val
     * @return $this
     */
    public function min($val)
    {
        $this->min = $val;
        return $this;
    }

    /**
     * Undocumented function
     *
     * @param int $val
     * @return $this
     */
    public function max($val)
    {
        $this->max = $val;
        return $this;
    }

    /**
     * Undocumented function
     *
     * @param string $val
     * @return $this
     */
    public function placeholder($val)
    {
        $this->placeholder = $val;
        return $this;
    }

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

    protected function numberScript()
    {
        $inputId = $this->getId();

        $this->jsOptions = array_merge($this->jsOptions, [
            'min' => $this->min,
            'max' => $this->max,
        ]);

        $configs = json_encode($this->jsOptions);

        $configs = substr($configs, 1, strlen($configs) - 2);

        $script = <<<EOT

        $('#{$inputId}').TouchSpin({
            {$configs}
        });

EOT;
        $this->script[] = $script;

        return $script;
    }

    public function beforRender()
    {
        $this->numberScript();

        return parent::beforRender();
    }

    public function customVars()
    {
        return [
            'placeholder' => $this->placeholder,
        ];
    }
}
