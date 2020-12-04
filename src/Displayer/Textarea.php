<?php

namespace Ichynul\Labuilder\Displayer;

class Textarea extends Field
{
    protected $view = 'textarea';

    protected $maxlength = 0;

    protected $rows = 3;

    protected $placeholder = '';

    protected $js = [
        '/vendor/ichynul/labuilder/builder/js/bootstrap-maxlength/bootstrap-maxlength.min.js',
    ];

    /**
     * Undocumented function
     *
     * @param integer $val
     * @return $this
     */
    public function maxlength($val = 0)
    {
        $this->maxlength = $val;
        return $this;
    }

    /**
     * Undocumented function
     *
     * @param integer $val
     * @return $this
     */
    public function rows($val = 3)
    {
        $this->rows = $val;
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

    public function render()
    {
        if ($this->maxlength > 0) {
            $this->attr .= ' maxlength="' . $this->maxlength . '"';
        }

        $this->addAttr('rows="' . $this->rows . '"');

        $vars = $this->commonVars();

        $vars = array_merge($vars, [
            'placeholder' => $this->placeholder,
        ]);

        $viewshow = $this->getViewInstance($vars);

        return $viewshow->render();
    }
}
