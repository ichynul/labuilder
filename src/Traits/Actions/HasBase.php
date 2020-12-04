<?php

namespace Ichynul\Labuilder\Traits\Actions;

use Ichynul\Labuilder\Common\Builder;
use Ichynul\Labuilder\Common\Form;
use Ichynul\Labuilder\Common\Search;
use Ichynul\Labuilder\Common\Table;
use Ichynul\Labuilder\Logic\Filter;

/**
 * 基础
 */

trait HasBase
{
    /**
     * 数据模型
     *
     * @var \Illuminate\Database\Eloquent\Model
     */
    protected $dataModel;
    /**
     * 页面标题
     *
     * @var string
     */
    protected $pageTitle = 'Page';
    protected $addText = '添加';
    protected $editText = '编辑';
    protected $viewText = '查看';
    protected $indexText = '列表';
    protected $pagesize = 14;
    protected $sortOrder = 'id desc';
    protected $enableField = 'enable';
    protected $pk = 'id';
    protected $isExporting = false;

    /**
     * Undocumented variable
     *
     * @var Form
     */
    protected $form;
    /**
     * Undocumented variable
     *
     * @var Search
     */
    protected $search;

    /**
     * Undocumented variable
     *
     * @var Table
     */
    protected $table;

    /**
     * 不允许删除的
     *
     * @var array
     */
    protected $delNotAllowed = [];
    /**
     * 允许行内编辑的字段，留空则不限制
     *
     * @var array
     */
    protected $postAllowFields = [];

    /** 初始化页面，覆盖默认
     *public function initialize()
     *{
     *   $this->dataModel = new MyModel;
     *   $this->pageTitle = 'A Page';
     *   $this->addText = '添加';
     *   $this->editText = '编辑';
     *   $this->indexText = '列表';
     *   $this->pagesize = 14;
     *   $this->sortOrder = 'id desc';
     *
     *   $this->delNotAllowed = [1, 3, 4];
     *
     *   $this->postAllowFields = ['name', 'phone'];
     *}
     */

    /*******辅助方法******/

    /**
     * Undocumented function
     *
     * @param boolean|int $isEdit 0:create,1:edit,2:show
     * @param array $data
     * @return void
     */
    protected function buildForm($isEdit, &$data = [])
    {
        $form = $this->form;
    }

    /**
     * Undocumented function
     *
     * @param array $data
     * @return void
     */
    protected function buildTable(&$data = [])
    {
        $table = $this->table;
    }

    /**
     * 构建搜索 范例
     *
     * @return void
     */
    protected function buildSearch()
    {
        $search = $this->search;
    }

    protected function doSave($data, $id = 0)
    {
        $res = 0;

        if ($id) {
            $model = $this->dataModel->findOrFail($id);
            $res = $model->update($data);
        } else {
            $res = $this->dataModel->fill($data)->save();
        }

        if (!$res) {
            return response()->json(
                ['code' => 0, 'msg' => '保存失败']
            );
        }

        return $this->builder()->layer()->closeRefresh(1, '保存成功');
    }

    protected function filterWhere()
    {
        $this->search = new Search();

        $this->buildSearch();

        $logic = new Filter;

        $searchData = request()->post();

        $where = $logic->getQuery($this->search, $searchData);

        return $where;
    }

    /**
     * Undocumented function
     *
     * @return array|\Illuminate\Support\Collection
     */
    protected function buildDataList()
    {
        $page = intval(request('__page__', 1));
        $page = $page < 1 ? 1 : $page;
        $sortOrder = request('__sort__', $this->sortOrder ? $this->sortOrder : $this->getPk() . ' desc');

        $where = $this->filterWhere();

        $table = $this->table;

        $pagesize = intval(request('__pagesize__', 0));

        $this->pagesize = $pagesize ?: $this->pagesize;

        $sortOrderArr = explode(' ', $sortOrder);
        if (count($sortOrderArr) < 2) {
            $sortOrderArr[] = 'asc';
        }

        $data = $this->dataModel->where($where)->offset(($page - 1) * $this->pagesize)->orderBy($sortOrderArr[0], $sortOrderArr[1])->limit($this->pagesize)->get();

        $this->buildTable($data);
        $table->fill($data);
        $table->paginator($this->dataModel->where($where)->count(), $this->pagesize);
        $table->sortOrder($sortOrder);

        return $data;
    }

    /**
     * Undocumented function
     *
     * @param string $title
     * @param string $desc
     * @param string $type index/add/edit/view
     * @return Builder
     */
    protected function builder($title = '', $desc = '', $type = '')
    {
        $builder = Builder::getInstance($title, $desc);

        $this->creating($builder, $type);

        return $builder;
    }

    /**
     * Undocumented function
     *
     * @param Builder $builder
     * @param string $type index/add/edit/view
     * @return void
     */
    protected function creating($builder, $type = '')
    {
        //其他用户自定义初始化
    }

    /**
     * Undocumented function
     *
     * @return string
     */
    protected function getPk()
    {
        if (empty($this->pk)) {
            if ($this->dataModel) {
                $this->pk = $this->dataModel->getKeyName();
                $this->pk = !empty($this->pk) && is_string($this->pk) ? $this->pk : 'id';
            } else {
                $this->pk = 'id';
            }
        }

        return $this->pk;
    }
}
