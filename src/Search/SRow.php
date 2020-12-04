<?php

namespace Ichynul\Labuilder\Search;

use Ichynul\Labuilder\Common\Search;
use Ichynul\Labuilder\Inface\Renderable;
use Ichynul\Labuilder\Traits\HasDom;
use Ichynul\Labuilder\Traits\HasRow;

class SRow extends SWrapper implements Renderable
{
    use HasDom;
    use HasRow;

    protected $filter = '';

    /**
     * Undocumented variable
     *
     * @var Search
     */
    protected $form;

    public function __construct($name, $label = '', $colSize = 2, $filter = '')
    {
        $this->name = trim($name);
        if (empty($label) && !empty($this->name)) {
            $label = __(ucfirst($this->name));
        }

        $this->label = $label;
        $this->cloSize = $colSize;
        $this->filter = $filter;

        return $this;
    }

    /**
     * Undocumented function
     *
     * @param mixed $filter
     * @return $this
     */
    public function filter($filter)
    {
        $this->filter = $filter;
        return $this;
    }

    /**
     * Undocumented function
     *
     * @return mixed
     */
    public function getFilter()
    {
        return $this->filter ?: 'eq';
    }

    /**
     * Undocumented function
     *
     * @param Search $val
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
     * @return Search
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
