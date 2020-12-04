<?php

namespace Ichynul\Labuilder\Traits\Actions;

use Ichynul\Labuilder\Displayer;
use Ichynul\Labuilder\Logic\Export;
use Ichynul\Labuilder\Table\TColumn;

/**
 * 导出
 */

trait HasExport
{
    public function export()
    {
        $this->isExporting = true;
        $this->table = $this->builder()->table();
        $sortOrder = request('__sort__', $this->sortOrder ? $this->sortOrder : $this->getPk() . ' desc');

        $__ids__ = request('__ids__');

        if (!empty($__ids__)) {
            $where = [[$this->getPk(), 'in', array_filter(explode(',', $__ids__))]];
        } else {
            $where = $this->filterWhere();
        }

        if ($this->dataModel) {

            $sortOrderArr = explode(' ', $sortOrder);
            if (count($sortOrderArr) < 2) {
                $sortOrderArr[] = 'asc';
            }

            $data = $this->dataModel->where($where)->orderBy($sortOrderArr[0], $sortOrderArr[1])->get();

            // TODO 性能优化

            $this->buildTable($data);
        } else {
            $data = $this->buildDataList();
        }

        $cols = $this->table->getCols();

        $displayers = $this->getDisplayers($cols);

        $__file_type__ = request('__file_type__', '');

        $logic = new Export;

        if ($__file_type__ == 'xls' || $__file_type__ == 'xlsx') {
            return $logic->toExcel($this->pageTitle, $data, $displayers, $__file_type__);
        } else if ($__file_type__ == 'csv') {
            return $logic->toCsv($this->pageTitle, $data, $displayers);
        } else {
            return $this->exportTo($data, $displayers, $__file_type__);
        }
    }

    /**
     * Undocumented function
     *
     * @param string $fileType 其他类型的导出
     * @return mixed
     */
    protected function exportTo($data, $displayers, $__file_type__)
    {
        $logic = new Export;
        return $logic->toCsv($this->pageTitle, $data, $displayers);
    }

    private function getDisplayers($cols, $displayers = [])
    {
        $displayer = null;

        foreach ($cols as $col) {

            $displayer = $col->getDisplayer();

            if ($displayer instanceof displayer\Fields) {
                $content = $displayer->getContent();
                $displayers = $this->getDisplayers($content->getCols(), $displayers);
                continue;
            }

            if (!$col instanceof TColumn) {
                continue;
            }

            if ($displayer instanceof displayer\Checkbox || $displayer instanceof displayer\MultipleSelect) {

                $displayer = (new displayer\Matches($displayer->getName(), $col->getLabel()))->options($displayer->getOptions());
            } else if ($displayer instanceof displayer\Radio) {

                $displayer = (new displayer\Match($displayer->getName(), $col->getLabel()))->options($displayer->getOptions());
            } else if ($displayer instanceof displayer\SwitchBtn) {

                $pair = $displayer->getPair();
                $options = [$pair[0] => '是', $pair[1] => '否'];
                $displayer = (new displayer\Match($displayer->getName(), $col->getLabel()))->options($options);
            }
            $displayers[] = $displayer;
        }

        return $displayers;
    }
}
