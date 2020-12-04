<?php

namespace Ichynul\Labuilder\Traits\Actions;

/**
 * 左侧树形结构
 */

trait HasTree
{
    /**
     * 默认查询条件
     *
     * @var array
     */
    protected $treeScope = [];//如 [['enable', 'eq', 1]]

    /**
     * js树类型，ztree/jstree
     *
     * @var string
     */
    protected $treeType = 'ztree';

    /**
     * 树 Text 字段 如 'name'
     *
     * @var string
     */
    protected $treeTextField = '';

    /**
     * 树 id 字段
     *
     * @var string
     */
    protected $treeIdField = 'id';

    /**
     * 树 上级id字段 如 parend_id pid
     *
     * @var string
     */
    protected $treeParentIdField = 'parent_id';

    /**
     * 树字段
     *
     * @var string
     */
    protected $treeKey = '';

    /**
     * 树数据模型
     *
     * @var \Illuminate\Database\Eloquent\Model
     */
    protected $treeModel;
}
