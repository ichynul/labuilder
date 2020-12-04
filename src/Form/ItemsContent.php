<?php

namespace Ichynul\Labuilder\Form;

use Ichynul\Labuilder\Common\Form;
use Ichynul\Labuilder\Displayer\Field;
use Ichynul\Labuilder\Form\FRow;
use Ichynul\Labuilder\Table\Actionbar;
use Ichynul\Labuilder\Traits\HasDom;
use Illuminate\Support\Collection;

class ItemsContent extends FWrapper
{
    use HasDom;

    protected $view = 'itemscontent';

    protected $headers = [];

    protected $cols = [];

    protected $data = [];

    protected $list = [];

    protected $script = [];

    protected $pk = 'id';

    protected $ids = [];

    protected $emptyText = "<span>暂无相关数据~</span>";

    protected $isInitData = false;

    protected $actionRowText = '操作';

    protected $cnaDelete = true;

    protected $canAdd = true;

    protected $name = '';

    protected $template = [];

    /**
     * Undocumented variable
     *
     * @var Actionbar
     */
    protected $actionbar = null;

    /**
     * Undocumented variable
     *
     * @var Form
     */
    protected $form;

    public function __construct()
    {
        $this->class = 'table-striped table-hover table-bordered table-condensed table-responsive';
    }

    /**
     * Undocumented function
     *
     * @param string $val
     * @return void
     */
    public function name($val)
    {
        $this->name = $val;
    }

    /**
     * Undocumented function
     *
     * @param string $val
     * @return $this
     */
    public function actionRowText($val)
    {
        $this->actionRowText = $val;
        return $this;
    }

    /**
     * Undocumented function
     *
     * @param string $name
     * @param FRow|Field|Fillable $row
     * @return $this
     */
    public function addCol($name, $col)
    {
        $this->headers[$name] = $col->getLabel();
        $this->cols[$name] = $col;
        return $this;
    }

    /**
     * Undocumented function
     *
     * @return array
     */
    public function getCols()
    {
        return $this->cols;
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
     * @param boolean $val
     * @return $this
     */
    public function cnaDelete($val)
    {
        $this->cnaDelete = $val;
        return $this;
    }

    /**
     * Undocumented function
     *
     * @return boolean
     */
    public function hasAction()
    {
        return $this->cnaDelete;
    }

    /**
     * Undocumented function
     *
     * @param boolean $val
     * @return $this
     */
    public function canAdd($val)
    {
        $this->canAdd = $val;
        return $this;
    }

    /**
     * Undocumented function
     * 主键, 默认 为 'id'
     * @param string $val
     * @return $this
     */
    public function pk($val)
    {
        $this->pk = $val;
        return $this;
    }

    /**
     * Undocumented function
     *
     * @param array|Collection $data
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
     * @return array
     */
    public function getData()
    {
        return $this->data;
    }

    /**
     * Undocumented function
     *
     * @param boolean $val
     * @return $this
     */
    public function readonly($val = true)
    {
        foreach ($this->cols as $col) {
            $col->getDisplayer()->readonly($val);
        }

        return $this;
    }

    /**
     * Undocumented function
     *
     * @return $this
     */
    public function beforRender()
    {
        $this->initData();

        return $this;
    }

    protected function initData()
    {
        $this->list = [];

        $pk = $this->pk;

        $cols = array_keys($this->cols);

        foreach ($this->data as $key => $data) {

            if (isset($data[$pk])) {

                $this->ids[$key] = $data[$pk];
            } else {
                $this->ids[$key] = $key;
            }

            foreach ($cols as $col) {

                $colunm = $this->cols[$col];

                if (!$colunm instanceof FRow) {
                    continue;
                }

                $displayer = $colunm->getDisplayer();

                $displayer->clearScript();

                $displayer
                    ->value('')
                    ->fill($data)
                    ->extKey($key . $this->name)
                    ->arrayName([$this->name . '[' . $this->ids[$key] . '][', ']'])
                    ->showLabel(false)
                    ->size(12, 12)
                    ->addClass('item-field ' . ($displayer->isRequired() ? ' item-field-required' : ''))
                    ->addAttr('data-label="' . $colunm->getLabel() . '"')
                    ->beforRender();

                $this->list[$key][$col] = [
                    'displayer' => $displayer,
                    'value' => $displayer->render(),
                    'attr' => $displayer->getAttrWithStyle(),
                    'wrapper' => $colunm,
                    '__can_delete__' => isset($data['__can_delete__']) ? $data['__can_delete__'] : 1,
                ];
            }
        }

        foreach ($this->cols as $key => $colunm) {
            if (!$colunm instanceof FRow) {
                continue;
            }
            $displayer = $colunm->getDisplayer();

            $displayer->clearScript();

            $isRequired = $displayer->isRequired();

            if ($isRequired) {
                $this->headers[$key] = $displayer->getLabel() . '<strong title="必填" class="field-required">*</strong>';
            }

            $displayer->required(false);

            $displayer
                ->extKey('')
                ->arrayName([$this->name . '[' . '__new__' . '][', ']'])
                ->showLabel(false)
                ->value('')
                ->size(12, 12)
                ->addClass('item-field ' . ($isRequired ? ' item-field-required' : ''))
                ->addAttr('data-label="' . $colunm->getLabel() . '"')
                ->beforRender();

            $displayer->extKey('-no-init-script'); //模板的id改了，避免被初始化，添加以后再初始化

            $this->template[] = [
                'value' => $displayer->render(),
                'attr' => $displayer->getAttrWithStyle(),
                'wrapper' => $colunm,
            ];

            $script = $displayer->getScript();

            $this->script = array_merge($this->script, $script);
        }

        $this->isInitData = true;
    }

    public function render()
    {
        $template = 'labuilder::form.' . $this->view;

        $vars = [
            'name' => $this->name,
            'class' => $this->class,
            'attr' => $this->getAttrWithStyle(),
            'headers' => $this->headers,
            'cols' => $this->cols,
            'list' => $this->list,
            'data' => $this->data,
            'emptyText' => $this->emptyText,
            'ids' => $this->ids,
            'cnaDelete' => $this->cnaDelete,
            'actionRowText' => $this->actionRowText,
            'canAdd' => $this->canAdd,
            'script' => implode('', array_unique($this->script)),
            'template' => $this->template,
        ];

        $viewshow = view($template, $vars);

        return $viewshow->render();
    }

    public function __call($name, $arguments)
    {
        $count = count($arguments);

        if ($count > 0 && static::isDisplayer($name)) {

            $col = new FRow($arguments[0], $count > 1 ? $arguments[1] : '', $count > 2 ? $arguments[2] : 1);

            $this->headers[$arguments[0]] = $col->getLabel();
            $this->cols[$arguments[0]] = $col;

            return $col->$name($arguments[0], $col->getLabel());
        }

        throw new \UnexpectedValueException('未知调用:' . $name);
    }
}
