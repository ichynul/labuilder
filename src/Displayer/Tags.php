<?php

namespace Ichynul\Labuilder\Displayer;

class Tags extends Field
{
    protected $view = 'tags';

    protected $js = [
        '/vendor/ichynul/labuilder/builder/js/jquery-tags-input/jquery.tagsinput.min.js',
    ];

    protected $css = [
        '/vendor/ichynul/labuilder/builder/js/jquery-tags-input/jquery.tagsinput.min.css',
    ];

    protected $placeholder = '';

    protected $jsOptions = [
        'height' => '33px',
        'width' => '100%',
        'defaultText' => '',
        'removeWithBackspace' => true,
        'delimiter' => [','],
    ];

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

    protected function tagsScript()
    {
        $inputId = $this->getId();

        $this->jsOptions['defaultText'] = empty($this->placeholder) ? $this->label : $this->placeholder;

        $configs = json_encode($this->jsOptions);

        $configs = substr($configs, 1, strlen($configs) - 2);

        $script = <<<EOT
        $('#{$inputId}').tagsInput({
			{$configs}
		});

EOT;
        $this->script[] = $script;

        return $script;
    }

    public function beforRender()
    {
        if(!$this->readonly)
        {
            $this->tagsScript();
        }

        return parent::beforRender();
    }

    public function customVars()
    {
        return [
            'placeholder' => $this->placeholder,
        ];
    }
}
