<?php

namespace Ichynul\Labuilder\Form;

use Ichynul\Labuilder\Common\Form;
use Ichynul\Labuilder\Displayer\Field;
use Ichynul\Labuilder\Inface\Renderable;
use Illuminate\Database\Eloquent\Model;

class FieldsContent extends FWrapper implements Renderable
{
    protected $view = 'fieldscontent';

    protected $rows = [];

    protected $data = [];

    /**
     * Undocumented variable
     *
     * @var Form
     */
    protected $form;

    /**
     * Undocumented function
     *
     * @return $this
     */
    public function beforRender()
    {
        foreach ($this->rows as $row) {
            $row->fill($this->data);
            if (!$row instanceof FRow) {
                $row->beforRender();
                continue;
            }

            $displayer = $row->getDisplayer();

            if ($displayer->isRequired()) {
                $this->form->addJqValidatorRule($displayer->getName(), 'required', true);
            }

            $row->beforRender();
        }
        return $this;
    }

    /**
     * Undocumented function
     *
     * @param FRow|Field|Fillable $row
     * @return $this
     */
    public function addRow($row)
    {
        $this->rows[] = $row;
        return $this;
    }

    /**
     * Undocumented function
     *
     * @return array
     */
    public function getRows()
    {
        return $this->rows;
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
     * @param array|Model $data
     * @return $this
     */
    public function fill($data = [])
    {
        $this->data = $data;
        return $this;
    }

    /**
     * Undocumented function
     *
     * @param boolean $val
     * @return $this
     */
    public function readonly($val = true)
    {
        foreach ($this->rows as $row) {
            $row->getDisplayer()->readonly($val);
        }
        return $this;
    }

    /**
     * Undocumented function
     *
     * @param string $val
     * @return $this
     */
    public function extKey($val)
    {
        foreach ($this->rows as $row) {
            if (!$row instanceof FRow) {
                continue;
            }

            $row->getDisplayer()->extKey($val);
        }

        return $this;
    }

    /**
     * Undocumented function
     *
     * @return array|Model
     */
    public function getData()
    {
        return $this->data;
    }

    public function render()
    {
        $template = 'labuilder::form.' . $this->view;

        $vars = [
            'rows' => $this->rows,
        ];

        $viewshow = view($template, $vars);

        return $viewshow->render();
    }

    public function __call($name, $arguments)
    {
        $count = count($arguments);

        if ($count > 0 && static::isDisplayer($name)) {

            $row = new FRow($arguments[0], $count > 1 ? $arguments[1] : '', $count > 2 ? $arguments[2] : ($name == 'button' ? 1 : 12));

            $this->rows[] = $row;

            return $row->$name($arguments[0], $row->getLabel());
        }

        throw new \UnexpectedValueException('未知调用:' . $name);
    }
}
