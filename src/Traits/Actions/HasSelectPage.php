<?php

namespace Ichynul\Labuilder\Traits\Actions;

/**
 * 下拉数据源
 */

trait HasSelectPage
{
    /**
     * 默认查询条件
     *
     * @var array
     */
    protected $selectScope = [];//如 [['enable', 'eq', 1]]

    /**
     * 模糊查询字段，如 'name|title'
     *
     * @var string
     */
    protected $selectSearch = '';

    /**
     * 默认 Text 字段 如 '{id}#{nickname}'
     *
     * @var string
     */
    protected $selectTextField = '';

    /**
     * 默认 id 字段
     *
     * @var string
     */
    protected $selectIdField = '';

    /**
     * 查询时的字段，优化查询速度
     *
     * @var string
     */
    protected $selectFields = '*';

    /**
     * 排序
     *
     * @var string
     */
    protected $selectOrder = '';

    /**
     * 分页大小
     *
     * @var string
     */
    protected $selectPagesize = 20;

    public function selectPage()
    {
        if (!$this->dataModel) {
            return response()->json(
                [
                    'data' => $this->buildDataList(),
                    'has_more' => 0,
                ]
            );
        }

        $selected = request('selected', '');
        $where = [];
        $list = [];

        if (!$this->selectTextField && $this->selectSearch) {
            $this->selectTextField = explode('|', $this->selectSearch)[0];
        }

        $idField = request('idField', '_');
        $textField = request('textField', '_');

        if (empty($idField) || $idField == '_') {
            $idField = $this->selectIdField ?: $this->getPk();
        }
        if (empty($textField) || $textField == '_') {
            $textField = 'text';
        }

        if ($textField == 'text') {
            $textField = $this->selectTextField;
        }

        if ($textField && (strpos($textField, '{') === false || strpos($textField, '}') === false)) {
            $textField = '{' . $textField . '}';
        }

        $sortOrder = $this->selectOrder ?: ($this->sortOrder ?: $idField . ' desc');
        $pagesize = $this->selectPagesize ?: ($this->pagesize ?: 20);

        $hasMore = 1;
        if ($selected != '') { //初始化已选中的
            if (is_numeric($selected)) {
                $where[] = [$idField, 'eq', $selected];
            } else {
                $arr = array_filter(explode(',', $selected));
                if (count($arr)) {
                    $where[] = [$idField, 'in', $selected];
                } else {
                    $where[] = [$idField, 'eq', $selected];
                }
            }
            $list = $this->dataModel->where($where)->order($sortOrder)->field($this->selectFields)->select();
            $hasMore = 0;
        } else {
            $q = request('q', '');
            $page = request('page/d', 1);

            $page = $page < 1 ? 1 : $page;
            $WhereOr = [];

            $where = $this->selectScope;

            if ($q) {
                if ($this->selectSearch) {
                    $where[] = [$this->selectSearch, 'like', '%' . $q . '%'];
                }
                if (is_numeric($q)) {
                    $WhereOr[] = [$idField, 'eq', $q];
                }
            }

            $list = $this->dataModel->where($where)->whereOr($WhereOr)->order($sortOrder)->limit(($page - 1) * $pagesize, $pagesize)->field($this->selectFields)->select();

            $hasMore = count($list) == $pagesize ? 1 : 0;
        }

        $data = [];
        if ($textField && $textField != 'text') {
            $keys = [];
            $replace = null;
            $n = 0;
            foreach ($list as $li) {
                $li = $li->toArray();
                $replace = [];
                foreach ($li as $key => $val) {
                    if ($n < 1) {
                        $keys[] = '{' . $key . '}';
                    }
                    $replace[] = $val;
                }
                $li['__id__'] = $li[$idField];
                $li['__text__'] = str_replace($keys, $replace, $textField);
                $n += 1;
                $data[] = $li;
            }
        } else {
            foreach ($list as $li) {
                $li = $li->toArray();
                $li['text'] = implode(',', array_slice(array_values($li), 0, 4));
                $li['__id__'] = $li[$idField];
                $li['__text__'] = $li['text'];
                $data[] = $li;
            }
        }
        return response()->json(
            [
                'data' => $data,
                'has_more' => $hasMore,
                'textField' => $textField,
            ]
        );
    }
}
