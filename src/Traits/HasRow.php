<?php

namespace Ichynul\Labuilder\Traits;

trait HasRow
{
    protected $name = '';

    protected $label = '';

    protected $cloSize = 12;

    protected $errorClass = '';

    /**
     * Displayer
     *
     * @var \Ichynul\Labuilder\Displayer\Field
     */
    protected $displayer;

     /**
     * Undocumented function
     * @example 1 [int] 4 => class="col-md-4"
     * @example 2 [string] '4 xls-4' => class="col-md-4 xls-4"
     *
     * @param int|string $val
     * @return $this
     */
    public function cloSize($val)
    {
        $this->size = $val;
        return $this;
    }

    /**
     * Undocumented function
     *
     * @return int|string
     */
    public function getColSize()
    {
        return $this->cloSize;
    }

    /**
     * Undocumented function
     *
     * @return string
     */
    public function getName()
    {
        return $this->name;
    }

    /**
     * Undocumented function
     *
     * @return string
     */
    public function getLabel()
    {
        return $this->label;
    }

    /**
     * Undocumented function
     *
     * @param string $val
     * @return $this
     */
    public function errorClass($val)
    {
        $this->errorClass = $val;
        return $this;
    }

    /**
     * Undocumented function
     *
     * @return string
     */
    public function getErrorClass()
    {
        return $this->errorClass;
    }

    /**
     * Undocumented function
     *
     * @return \Ichynul\Labuilder\Displayer\Field
     */
    public function getDisplayer()
    {
        return $this->displayer;
    }

    /**
     * Undocumented function
     *
     * @return mixed
     */
    public function render()
    {
        return $this->displayer->render();
    }

    public function __toString()
    {
        return $this->render();
    }

    /**
     * Undocumented function
     *
     * @param string $class
     * @param array $arguments
     * @return void
     */
    public function createDisplayer($class, $arguments)
    {
        $displayer = new $class($arguments[0], $arguments[1]);
        $displayer->setWrapper($this);
        $displayer->created($class);

        $this->displayer = $displayer;

        static::addUsing($displayer);

        return $displayer;
    }

    /**
     * Undocumented function
     *
     * @return $this
     */
    public function beforRender()
    {
        $this->displayer->beforRender();
        return $this;
    }
}
