<?php

namespace Ichynul\Labuilder\Common;

use Ichynul\Labuilder\Events\BuilderEvents;
use Ichynul\Labuilder\Inface\Renderable;
use Ichynul\Labuilder\Table\Actionbar;
use Ichynul\Labuilder\Table\FieldsContent;
use Ichynul\Labuilder\Table\MultipleToolbar;
use Ichynul\Labuilder\Table\Paginator;
use Ichynul\Labuilder\Table\TColumn;
use Ichynul\Labuilder\Table\TWrapper;
use Ichynul\Labuilder\Toolbar\DropdownBtns;
use Ichynul\Labuilder\Traits\HasDom;
use Illuminate\Support\Collection;

/**
 * Table class
 */
class Table extends TWrapper implements Renderable
{
    use HasDom;

    protected $view = '';

    protected $id = 'the-table';

    protected $js = [
        '/vendor/ichynul/labuilder/builder/js/jquery-toolbar/jquery.toolbar.min.js',
    ];

    protected $css = [
        '/vendor/ichynul/labuilder/builder/js/jquery-toolbar/jquery-toolbar.min.css',
    ];

    protected $headTextAlign = 'text-center';

    protected $textAlign = 'text-center';

    protected $verticalAlign = 'vertical-middle';

    protected $headers = [];

    protected $list = [];

    protected $cols = [];

    protected $data = [];

    protected $pk = 'id';

    protected $ids = [];

    protected $actionbars = [];

    protected $checked = [];

    protected $useCheckbox = true;

    protected $pageSize = 0;

    protected $emptyText = '';

    /**
     * Undocumented variable
     *
     * @var FieldsContent
     */
    protected $__fields__ = null;

    /**
     * Undocumented variable
     *
     * @var MultipleToolbar
     */
    protected $toolbar = null;

    protected $useToolbar = true;

    /**
     * Undocumented variable
     *
     * @var Actionbar
     */
    protected $actionbar = null;

    protected $useActionbar = true;

    protected $actionRowText = '操作';

    protected $isInitData = false;

    protected $sortable = ['id'];

    protected $sortOrder = '';

    protected $partial = false;

    /**
     * Undocumented variable
     *
     * @var Row
     */
    protected $addTop;

    /**
     * Undocumented variable
     *
     * @var Row
     */
    protected $addBottom;

    /**
     * Undocumented variable
     *
     * @var Paginator
     */
    protected $paginator;

    /**
     * Undocumented variable
     *
     * @var Form
     */
    protected $searchForm = null;

    /**
     * Undocumented variable
     *
     * @var DropdownBtns
     */
    protected $pagesizeDropdown = null;

    protected $usePagesizeDropdown = true;

    public function __construct()
    {
        $this->class = 'table-striped table-hover table-bordered table-condensed table-responsive';
        $this->id = request('__table__', 'the-table');

        $this->emptyText = config('labuilder.table_empty_text');
    }

    /**
     * Undocumented function
     *
     * @param string $name
     * @param \Ichynul\Labuilder\Table\TColumn $col
     * @return $this
     */
    public function addCol($name, $col)
    {
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
     * @param string $val
     * @return $this
     */
    public function tableId($val)
    {
        $this->id = $val;
        return $this;
    }

    /**
     * Undocumented function
     *
     * @return string
     */
    public function getTableId()
    {
        return $this->id;
    }

    /**
     * Undocumented function
     * @param boolean $val
     * @return $this
     */
    public function useCheckbox($val)
    {
        $this->useCheckbox = $val;
        return $this;
    }

    /**
     * Undocumented function
     *
     * @param boolean $val
     * @return $this
     */
    public function partial($val = true)
    {
        $this->partial = $val;
        return $this;
    }

    /**
     * Undocumented function
     *
     * @return array
     */
    public function getJs()
    {
        return $this->js;
    }

    /**
     * Undocumented function
     *
     * @return array
     */
    public function getCss()
    {
        return $this->css;
    }

    /**
     * Undocumented function
     *
     * @return array
     */
    public function getHeaders()
    {
        return $this->headers;
    }

    /**
     * Undocumented function
     * vertical-middle | vertical-mtop | vertical-bottom
     * @param string $val
     * @return $this
     */
    public function verticalAlign($val)
    {
        $this->verticalAlign = $val;
        return $this;
    }

    /**
     * Undocumented function
     * text-left | text-center | text-right
     * @param string $val
     * @return $this
     */
    public function textAlign($val)
    {
        $this->textAlign = $val;
        return $this;
    }

    /**
     * Undocumented function
     * text-left | text-center | text-right
     * @param string $val
     * @return $this
     */
    public function headTextAlign($val)
    {
        $this->headTextAlign = $val;
        return $this;
    }

    /**
     * Undocumented function
     *
     * @param string|array $val
     * @return $this
     */
    public function sortable($val)
    {
        if (!is_array($val)) {
            $val = explode(',', $val);
        }

        $this->sortable = $val;
        return $this;
    }

    /**
     * Undocumented function
     *
     * @param boolean $val
     * @return $this
     */
    public function hasExport($val = true)
    {
        $this->getToolbar()->hasExport($val);

        return $this;
    }

    /**
     * Undocumented function
     *
     * @param array|string $val
     * @return $this
     */
    public function checked($val)
    {
        $this->checked = is_array($val) ? $val : explode(',', $val);
        return $this;
    }

    /**
     * Undocumented function
     *
     * @param string $val
     * @return $this
     */
    public function emptyText($val)
    {
        $this->emptyText = $val;
        return $this;
    }

    /**
     * Undocumented function
     *
     * @param array|Collection $data
     * @return $this
     */
    public function data($data = [])
    {
        $this->data = $data;

        return $this;
    }

    /**
     * Undocumented function
     *
     * @param array $val
     * @return $this
     */
    public function setHeaders($val)
    {
        $this->headers = $val;

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
        if (count($data) > 0 && empty($this->cols)) {
            $cols = [];

            if ($data && $data instanceof Collection) {
                $cols = array_keys($data->toArray()[0]);
            } else {
                $cols = array_keys($data[0]);
            }

            foreach ($cols as $col) {
                $this->show($col, ucfirst($col));
            }
        }
        return $this;
    }

    /**
     * Undocumented function
     *
     * @param string $val
     * @return $this
     */
    public function sortOrder($val)
    {
        $this->sortOrder = $val;
        return $this;
    }

    /**
     * Undocumented function
     *
     * @return array|Collection
     */
    public function getData()
    {
        return $this->data;
    }

    /**
     * Undocumented function
     *
     * @param int $dataTotal
     * @param integer $pageSize
     * @param string $paginatorClass
     * @return $this
     */
    public function paginator($dataTotal, $pageSize = 10, $paginatorClass = '')
    {
        if (!$pageSize) {
            $pageSize = 10;
        }

        $paginator = new Paginator($this->data, $dataTotal, $pageSize, request('__page__', 1));

        if ($dataTotal < 10) {
            $this->usePagesizeDropdown = false;
        }

        if ($paginatorClass) {
            $paginator->paginatorClass($paginatorClass);
        }

        $this->pageSize = $pageSize;

        $this->paginator = $paginator;

        return $this;
    }

    /**
     * Undocumented function
     *
     * @return Paginator
     */
    public function getPaginator()
    {
        return $this->paginator;
    }

    /**
     * 获取一个toolbar
     *
     * @return MultipleToolbar
     */
    public function getToolbar()
    {
        if (empty($this->toolbar)) {
            $this->toolbar = new MultipleToolbar();
            $this->toolbar->extKey('-' . $this->id);
        }

        return $this->toolbar;
    }

    /**
     * Undocumented function
     *
     * @param boolean $val
     * @return $this
     */
    public function useToolbar($val)
    {
        $this->useToolbar = $val;
        return $this;
    }

    /**
     * 获取一个actionbar
     *
     * @return Actionbar
     */
    public function getActionbar()
    {
        if (empty($this->actionbar)) {
            $this->actionbar = new Actionbar();
        }

        return $this->actionbar;
    }

    /**
     * Undocumented function
     *
     * @param boolean $val
     * @return $this
     */
    public function useActionbar($val)
    {
        $this->useActionbar = $val;
        return $this;
    }

    /**
     * Undocumented function
     *
     * @param string $val
     * @return $this
     */
    protected function actionRowText($val)
    {
        $this->actionRowText = $val;
        return $this;
    }

    /**
     * Undocumented function
     *
     * @param array|boolean $items
     * @return DropdownBtns|null
     */
    public function pagesizeDropdown($items)
    {
        if ($items === false) {
            $this->usePagesizeDropdown = false;
            return null;
        }

        if (empty($this->pagesizeDropdown)) {
            $this->pagesizeDropdown = new DropdownBtns('pagesize', '每页显示<b class="pagesize-text">' . $this->pageSize . '</b>条');
        }

        $this->pagesizeDropdown->items($items)->class('btn-xs btn-default')->addGroupClass('dropup pull-right m-r-10');

        return $this->pagesizeDropdown;
    }

    /**
     * 获取一个搜索
     *
     * @return Search
     */
    public function getSearch()
    {
        if (empty($this->searchForm)) {
            $this->searchForm = new Search();
            $this->searchForm->search($this);
        }
        return $this->searchForm;
    }

    /**
     * Undocumented function
     *
     * @return $this
     */
    public function beforRender()
    {
        event(new BuilderEvents('tpext_table_befor_render', $this));

        $this->initData();

        Builder::getInstance()->addJs($this->js);
        Builder::getInstance()->addCss($this->css);

        if ($this->useToolbar) {
            $this->getToolbar()->hasSearch(!empty($this->searchForm))->beforRender();
        }

        if ($this->useActionbar) {
            $this->getActionbar()->beforRender();
        }

        if (empty($this->searchForm)) {
            $this->getSearch();
            $this->searchForm->addClass('form-empty');
        }

        $this->searchForm->beforRender();

        $this->tableScript();

        if ($this->addTop) {
            $this->addTop->beforRender();
        }

        if ($this->addBottom) {
            $this->addBottom->beforRender();
        }

        return $this;
    }

    protected function tableScript()
    {
        $table = $this->getTableId();

        $script = <<<EOT

        $('body').on('dblclick', '#{$table} tr', function(){
            if($(this).find('td a.dbl-click').not('.hidden').length)
            {
                $(this).find('td a.dbl-click').trigger('click');
            }
            else if($(this).find('td a.action-edit').not('.hidden').length)
            {
                $(this).find('td a.action-edit').trigger('click');
            }
            else if($(this).find('td a.action-view').not('.hidden').length)
            {
                $(this).find('td a.action-view').trigger('click');
            }
            return false;
        });

EOT;
        Builder::getInstance()->addScript($script);

        return $script;
    }

    protected function initData()
    {
        event(new BuilderEvents('tpext_table_init_data', $this));

        $this->list = [];

        $pk = $this->pk;

        $actionbar = $this->getActionbar();

        $actionbar->pk($this->pk);

        $cols = array_keys($this->cols);

        foreach ($this->data as $key => $data) {

            if (isset($data[$pk])) {

                $this->ids[$key] = $data[$pk];
            } else {
                $this->ids[$key] = $key;
            }

            foreach ($cols as $col) {

                $colunm = $this->cols[$col];

                if (!$colunm instanceof TColumn) {
                    continue;
                }

                $displayer = $colunm->getDisplayer();

                $displayer->clearScript();

                $displayer
                    ->value('')
                    ->fill($data)
                    ->extKey('-' . $this->id . '-' . $key)
                    ->extNameKey('-' . $key)
                    ->showLabel(false)
                    ->size(0, 0)
                    ->beforRender();

                $this->list[$key][$col] = [
                    'displayer' => $displayer,
                    'value' => $displayer->render(),
                    'attr' => $displayer->getAttrWithStyle(),
                    'wrapper' => $colunm,
                ];
            }

            if ($this->useActionbar && isset($this->ids[$key])) {

                $actionbar->extKey('-' . $this->id . '-' . $key)->rowData($data)->beforRender();

                $this->actionbars[$key] = $actionbar->render();
            }
        }

        $this->isInitData = true;
    }

    /**
     * Undocumented function
     *
     * @return Row
     */
    public function addTop()
    {
        if (empty($this->addTop)) {
            $this->addTop = new Row();
            $this->addTop->class('table-top');
        }

        return $this->addTop;
    }

    /**
     * Undocumented function
     *
     * @return Row
     */
    public function addBottom()
    {
        if (empty($this->addBottom)) {
            $this->addBottom = new Row();
            $this->addBottom->class('table-bottom');
        }

        return $this->addBottom;
    }

    /**
     * Undocumented function
     *
     * @return FieldsContent
     */
    public function createFields()
    {
        $this->__fields__ = new FieldsContent();
        $this->__fields__->setTable($this);
        return $this->__fields__;
    }

    /**
     * Undocumented function
     *
     * @return $this
     */
    public function fieldsEnd()
    {
        $this->__fields__ = null;
        return $this;
    }

    /**
     * Undocumented function
     *
     * @return string|mixed
     */
    public function render()
    {
        if (!$this->isInitData) {
            $this->initData();
        }

        $template = 'labuilder::table';

        $count = count($this->data);
        if (!$this->paginator) {
            $this->pageSize = $count ? $count : 10;
            $this->paginator = new Paginator($this->data, $count, $this->pageSize, 1);
            $this->usePagesizeDropdown = false;
        }

        if ($this->paginator->total() <= 6) {
            $this->usePagesizeDropdown = false;
        }

        $sort = request('__sort__', $this->sortOrder);
        $sortKey = '';
        $sortOrder = '';

        if ($sort) {
            $arr = explode(' ', $sort);
            if (count($arr) == 2) {
                $sortKey = $arr[0];
                $sortOrder = $arr[1];
                if (!empty($this->sortable) && !in_array($sortKey, $this->sortable)) {
                    $this->sortable[] = $sortKey;
                }
            }
        }

        if ($this->usePagesizeDropdown && $this->pageSize && empty($this->pagesizeDropdown)) {
            $items = [
                0 => '默认', 6 => '6', 10 => '10', 14 => '14', 20 => '20', 30 => '30', 40 => '40', 50 => '50', 60 => '60', 90 => '90', 120 => '120',
            ];

            ksort($items);

            $this->pagesizeDropdown($items);
        }

        $vars = [
            'class' => $this->class,
            'attr' => $this->getAttrWithStyle(),
            'headers' => $this->headers,
            'cols' => $this->cols,
            'list' => $this->list,
            'data' => $this->data,
            'emptyText' => $this->emptyText,
            'headTextAlign' => $this->headTextAlign,
            'ids' => $this->ids,
            'sortable' => $this->sortable,
            'sortKey' => $sortKey,
            'sortOrder' => $sortOrder,
            'sort' => $sort,
            'useCheckbox' => $this->useCheckbox && $this->useToolbar,
            'name' => time() . mt_rand(1000, 9999),
            'tdClass' => $this->verticalAlign . ' ' . $this->textAlign,
            'verticalAlign' => $this->verticalAlign,
            'textAlign' => $this->textAlign,
            'id' => $this->id,
            'paginator' => $this->paginator,
            'partial' => $this->partial ? 1 : 0,
            'searchForm' => !$this->partial ? $this->searchForm : null,
            'toolbar' => $this->useToolbar && !$this->partial ? $this->toolbar : null,
            'actionbars' => $this->actionbars,
            'actionRowText' => $this->actionRowText,
            'checked' => $this->checked,
            'pagesizeDropdown' => $this->usePagesizeDropdown ? $this->pagesizeDropdown : null,
            'addTop' => $this->addTop,
            'addBottom' => $this->addBottom,
        ];

        $viewshow = view($template, $vars);

        if ($this->partial) {
            return $viewshow;
        }

        return $viewshow->render();
    }

    public function __toString()
    {
        $this->partial = false;
        return $this->render();
    }

    public function __call($name, $arguments)
    {
        $count = count($arguments);

        if ($count > 0 && static::isDisplayer($name)) {

            $col = new TColumn($arguments[0], $count > 1 ? $arguments[1] : '', $count > 2 ? $arguments[2] : 0);

            $col->setTable($this);

            $displayer = null;

            if ($this->__fields__) {
                $this->__fields__->addCol($col);
            } else {
                $this->cols[$arguments[0]] = $col;
                $this->headers[$arguments[0]] = $col->getLabel();
                $displayer = $col->$name($arguments[0], $col->getLabel());
            }

            $displayer = $col->$name($arguments[0], $col->getLabel());

            return $displayer;
        }

        throw new \UnexpectedValueException('未知调用:' . $name);
    }
}
