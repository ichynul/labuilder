<?php

namespace Ichynul\Labuilder\Displayer;

use Illuminate\Database\Eloquent\Model;
use Ichynul\Labuilder\Common\Form;
use Ichynul\Labuilder\Common\Search;
use Ichynul\Labuilder\Common\Table;
use Ichynul\Labuilder\Form\FieldsContent as FormFileds;
use Ichynul\Labuilder\Table\FieldsContent as TableFileds;
use Ichynul\Labuilder\Table\TColumn;

class Fields extends Field
{
    protected $view = 'fields';

    protected $data = [];

    /**
     * Undocumented variable
     *
     * @var Form|Search|Table
     */
    protected $widget;

    /**
     * Undocumented variable
     *
     * @var FormFileds|TableFileds
     */
    protected $__fields_content__;

    public function created($fieldType = '')
    {
        parent::created($fieldType);

        if ($this->getWrapper() instanceof TColumn) {
            $this->widget = $this->getWrapper()->getTable();
        } else {
            $this->widget = $this->getWrapper()->getForm();
        }

        $this->__fields_content__ = $this->widget->createFields();

        if (empty($this->name)) {
            $this->name = 'fields' . mt_rand(100, 999);
        }

        return $this;
    }

    /**
     * Undocumented function
     *
     * @param mixed ...$fields
     * @return $this
     */
    public function with(...$fields)
    {
        $this->widget->fieldsEnd();
        return $this;
    }

    /**
     * Undocumented function
     *
     * @return FormFileds|TableFileds
     */
    public function getContent()
    {
        return $this->__fields_content__;
    }

    /**
     * Undocumented function
     *
     * @param array|Model $data
     * @return $this
     */
    public function value($val)
    {
        return $this->fill($val, true);
    }

    /**
     * Undocumented function
     *
     * @param string $val
     * @return $this
     */
    public function extKey($val)
    {
        $this->__fields_content__->extKey($val);
        return parent::extKey($val);
    }

    /**
     * Undocumented function
     *
     * @param array|Model $data
     * @param boolean $overWrite
     * @return $this
     */
    public function fill($data = [], $overWrite = false)
    {
        if (!$overWrite && !empty($this->data)) {
            return $this;
        }

        if (!empty($this->name) && isset($data[$this->name]) &&
            (is_array($data[$this->name]) || $data[$this->name] instanceof Model)) {
            $this->data = $data[$this->name];
        } else {
            $this->data = $data;
        }

        $this->__fields_content__->fill($this->data);

        return $this;
    }

    /**
     * Undocumented function
     *
     * @return $this
     */
    public function clearScript()
    {
        $this->__fields_content__->clearScript();
        return parent::clearScript();
    }

    /**
     * Undocumented function
     *
     * @param boolean $val
     * @return $this
     */
    public function readonly($val = true)
    {
        $this->__fields_content__->readonly($val);
        return $this;
    }

    /**
     * Undocumented function
     *
     * @return array
     */
    public function getData()
    {
        return $this->data;
    }

    public function beforRender()
    {
        $this->__fields_content__->beforRender();
        parent::beforRender();
        return $this;
    }

    public function customVars()
    {
        return [
            'fields_content' => $this->__fields_content__,
        ];
    }
}
