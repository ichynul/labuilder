<?php

namespace Ichynul\Labuilder\Common;

use Ichynul\Labuilder\Inface\Renderable;
use Ichynul\Labuilder\Toolbar\Bar;
use Ichynul\Labuilder\Toolbar\BWrapper;
use Ichynul\Labuilder\Traits\HasDom;

class Toolbar extends BWrapper implements Renderable
{
    use HasDom;

    protected $view = '';

    protected $elms = [];

    protected $__elm__;

    protected $extKey = '';

    protected $elmsRight = [];

    protected $elmsLeft = [];

    /**
     * Undocumented function
     *
     * @param string $val
     * @return $this
     */
    public function extKey($val)
    {
        $this->extKey = $val;
        return $this;
    }

    /**
     * Undocumented function
     *
     * @return Bar
     */
    public function getCurrent()
    {
        return $this->__elm__;
    }

    /**
     * Undocumented function
     *
     * @return array
     */
    public function getElms()
    {
        return $this->elms;
    }

    /**
     * Undocumented function
     *
     * @return boolean
     */
    public function isEmpty()
    {
        return empty($this->elms);
    }

    /**
     * Undocumented function
     *
     * @return $this
     */
    public function clear()
    {
        $this->__elm__ = null;
        $this->elms = [];
        $this->elmsRight = [];
        $this->elmsLeft = [];

        return $this;
    }

    /**
     * Undocumented function
     *
     * @return $this
     */
    public function beforRender()
    {
        $this->elmsLeft = $this->elmsRight = [];

        foreach ($this->elms as $elm) {

            if ($this->extKey) {
                $elm->extKey($this->extKey);
            }

            if ($elm->isPullRight()) {
                $this->elmsRight[] = $elm;
            } else {
                $this->elmsLeft[] = $elm;
            }

            $elm->beforRender();
        }

        return $this;
    }

    public function render()
    {
        $template = 'labuilder::toolbar';

        $vars = [
            'elms' => $this->elms,
            'elmsLeft' => $this->elmsLeft,
            'elmsRight' => $this->elmsRight,
            'class' => $this->class,
            'attr' => $this->getAttrWithStyle(),
        ];

        $viewshow = view($template, $vars);

        return $viewshow->render();
    }

    public function __toString()
    {
        return $this->render();
    }

    public function __call($name, $arguments)
    {
        $count = count($arguments);

        if ($count > 0 && static::isDisplayer($name)) {

            $class = static::$displayerMap[$name];

            $this->__elm__ = new $class($arguments[0], $count > 1 ? $arguments[1] : '');

            $this->__elm__->created();

            $this->elms[] = $this->__elm__;

            return $this->__elm__;
        }

        throw new \UnexpectedValueException('未知调用:' . $name);
    }
}
