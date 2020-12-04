<?php

namespace Ichynul\Labuilder\Traits\Actions;

/**
 * 列表
 */

trait HasIndex
{
    use HasExport;
    use HasSelectPage;
    use HasTree;

    public function index()
    {
        $builder = $this->builder($this->pageTitle, $this->indexText, 'index');

        $table = null;

        if ($this->treeModel && $this->treeKey) {

            $tree = null;

            if ($this->treeType == 'ztree') {
                $tree = $builder->ztree('1 left-tree');
            } else {
                $tree = $builder->jsTree('1 left-tree');
            }

            $tree->fill($this->treeModel->where($this->treeScope)->select(), $this->treeTextField, $this->treeIdField, $this->treeParentIdField);

            $tree->trigger('.row-' . $this->treeKey);

            $table = $builder->table('1 right-table');
        } else {
            $table = $builder->table();
        }

        $table->pk($this->getPk());
        $this->table = $table;
        $this->search = $table->getSearch();

        $this->buildSearch();
        $this->buildDataList();

        if (request()->ajax()) {
            return $this->table->partial()->render();
        }

        return $builder->render();
    }
}
