<?php

namespace Ichynul\Labuilder\Table;

use Ichynul\Labuilder\Traits\HasDom;
use Illuminate\Pagination\LengthAwarePaginator;

class Paginator extends LengthAwarePaginator
{
    use HasDom;

    protected $paginatorClass = 'pagination-sm';

    protected $summary = true;

    /**
     * Undocumented function
     *
     * @return string
     */
    public function getClass()
    {
        if (!$this->class) {
            $this->class = 'text-center';
        }

        return $this->class;
    }

    /**
     * Undocumented function
     *
     * @param string $val
     * @return $this
     */
    public function paginatorClass($val)
    {
        $this->paginatorClass = $val;
        return $this;
    }

    /**
     * Undocumented function
     *
     * @param boolean $val
     * @return $this
     */
    public function summary($val)
    {
        $this->summary = $val;
        return $this;
    }

    /**
     * Undocumented function
     *
     * @return mixed
     */
    public function render($view = null, $data = [])
    {
        if (!$this->total) {
            return '';
        }

        $summaryHtml = '';
        $inputHtml = '';

        if ($this->summary) {
            $a = $this->firstItem();
            $b = $this->lastItem();
            if ($this->total != $this->items->count()) {
                $summaryHtml = "<span class='pagination-summary'>共{$this->total}条记录，当前显示{$a}~{$b}条记录</span>" ;
                if ($this->lastPage > 1) {
                    $inputHtml = "<ul class='pagination pagination-sm'><li><a data-last='{$this->lastPage}' class='goto-page'>&nbsp;&nbsp;输入&nbsp;&nbsp;</a></li></ul>";
                }
            } else {
                $summaryHtml = "<span class='pagination-summary'>共{$this->total}条记录</span>";
            }
        }

        $template = 'labuilder::table.paginator';

        $vars = [
            'summaryHtml' => $summaryHtml,
            'inputHtml' => $inputHtml,
            'links' => $this->linkCollection()->toArray(),
            'paginatorClass' => $this->paginatorClass,
            'attr' => $this->getAttrWithStyle(),
        ];

        $viewshow = view($template, $vars);

        return $viewshow->render();
    }
}
