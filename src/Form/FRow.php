<?php

namespace Ichynul\Labuilder\Form;

use Ichynul\Labuilder\Common\Form;
use Ichynul\Labuilder\Inface\Renderable;
use Ichynul\Labuilder\Traits\HasDom;
use Ichynul\Labuilder\Traits\HasRow;

class FRow extends FWrapper implements Renderable
{
    use HasDom;
    use HasRow;

    /**
     * Undocumented variable
     *
     * @var Form
     */
    protected $form;

    public function __construct($name, $label = '', $colSize = 12)
    {
        $this->name = trim($name);
        if (empty($label) && !empty($this->name)) {
            $label = __(ucfirst($this->name));
        }

        $this->label = $label;
        $this->cloSize = $colSize;

        return $this;
    }

    /**
     * Undocumented function
     *
     * @param Form $val
     * @return $this
     */
    public function setForm($val)
    {
        $this->form = $val;
        return $this;
    }

    /**
     * Undocumented function
     *
     * @return Form
     */
    public function getForm()
    {
        return $this->form;
    }

    /**
     * Undocumented function
     *
     * @param array $data
     * @return $this
     */
    public function fill($data = [])
    {
        $this->displayer->fill($data);
        return $this;
    }

    public function __call($name, $arguments)
    {
        if (static::isDisplayer($name)) {

            $class = static::$displayerMap[$name];

            return $this->createDisplayer($class, $arguments);
        }

        throw new \UnexpectedValueException('未知调用:' . $name);
    }
}
