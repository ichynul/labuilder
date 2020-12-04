<?php

namespace Ichynul\Labuilder\Common;

use Ichynul\Labuilder\Traits\HasDom;
use Ichynul\Labuilder\tree\JSTree;
use Ichynul\Labuilder\tree\ZTree;

class Row
{
    use HasDom;

    protected $cols = [];

    /**
     * Undocumented function
     *
     * @param integer $size
     * @return Column
     */
    public function column($size = 12)
    {
        $col = new Column($size);
        $this->cols[] = $col;
        return $col;
    }

    /**
     * Undocumented function
     *
     * @param integer $size
     * @return Form
     */
    public function form($size = 12)
    {
        return $this->column($size)->form();
    }

    /**
     * Undocumented function
     *
     * @param integer $size
     * @return Table
     */
    public function table($size = 12)
    {
        return $this->column($size)->table();
    }

    /**
     * 获取一个工具栏
     *
     * @return Toolbar
     */
    public function toolbar($size = 12)
    {
        return $this->column($size)->table();
    }

    /**
     * 默认获取一个ZTree树
     *
     * @return ZTree
     */
    public function tree($size = 12)
    {
        return $this->column($size)->tree();
    }

    /**
     * 获取一个ZTree树
     *
     * @return ZTree
     */
    public function zTree($size = 12)
    {
        return $this->column($size)->zTree();
    }

    /**
     * 获取一个JSTree树
     *
     * @return JSTree
     */
    public function jsTree($size = 12)
    {
        return $this->column($size)->jsTree();
    }

    /**
     * Undocumented function
     *
     * @return Content
     */
    public function content($size = 12)
    {
        return $this->column($size)->content();
    }

    /**
     * Undocumented function
     *
     * @return Tab
     */
    public function tab($size = 12)
    {
        return $this->column($size)->tab();
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
     * @return $this
     */
    public function beforRender()
    {
        foreach ($this->cols as $col) {
            $col->beforRender();
        }

        return $this;
    }

    public function render()
    {
        $template = 'labuilder::row';

        $vars = [
            'cols' => $this->cols,
            'class' => $this->class,
            'attr' => $this->getAttrWithStyle(),
        ];

        $viewshow = view($template, $vars);

        return $viewshow->render();
    }

    public function __toString()
    {
        return $this->render();
    }
}
