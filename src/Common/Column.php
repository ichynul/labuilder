<?php

namespace Ichynul\Labuilder\Common;

use Ichynul\Labuilder\Common\Form;
use Ichynul\Labuilder\Common\Table;
use Ichynul\Labuilder\Events\BuilderEvents;
use Ichynul\Labuilder\Inface\Renderable;
use Ichynul\Labuilder\Traits\HasDom;
use Ichynul\Labuilder\tree\JSTree;
use Ichynul\Labuilder\tree\ZTree;

class Column
{
    use HasDom;

    public $size = 12;

    protected $elms = [];

    public function __construct($size = 12)
    {
        $this->size = $size;
    }

    /**
     * 获取一个form
     *
     * @return Form
     */

    public function form()
    {
        $form = new Form();
        event(new BuilderEvents('tpext_create_builder', $form));
        $this->elms[] = $form;
        return $form;
    }

    /**
     * 获取一个表格
     *
     * @return Table
     */
    public function table()
    {
        $table = new Table();
        event(new BuilderEvents('tpext_create_builder', $table));
        $this->elms[] = $table;
        return $table;
    }

    /**
     * 获取一个Toolbar
     *
     * @return Toolbar
     */
    public function toolbar()
    {
        $toolbar = new Toolbar();
        event(new BuilderEvents('tpext_create_builder', $toolbar));
        $this->elms[] = $toolbar;
        return $toolbar;
    }

    /**
     * 获取一个ZTree
     *
     * @return ZTree
     */
    public function tree()
    {
        return $this->zTree();
    }

    /**
     * 获取一个ZTree
     *
     * @return ZTree
     */
    public function zTree()
    {
        $tree = new ZTree();
        event(new BuilderEvents('tpext_create_builder', $tree));
        $this->elms[] = $tree;
        return $tree;
    }

    /**
     * 获取一个jsTree
     *
     * @return JSTree
     */
    public function jsTree()
    {
        $tree = new JSTree();
        event(new BuilderEvents('tpext_create_builder', $tree));
        $this->elms[] = $tree;
        return $tree;
    }

    /**
     * 获取一个自定义内容
     *
     * @return Content
     */
    public function content()
    {
        $content = new Content();
        event(new BuilderEvents('tpext_create_builder', $content));
        $this->elms[] = $content;
        return $content;
    }

    /**
     * 获取一个 tab
     *
     * @return Tab
     */
    public function tab()
    {
        $tab = new Tab();
        event(new BuilderEvents('tpext_create_builder', $tab));
        $this->elms[] = $tab;
        return $tab;
    }

    /**
     * 获取一新行
     *
     * @return Row
     */
    public function row()
    {
        $row = new Row();
        event(new BuilderEvents('tpext_create_builder', $row));
        $this->elms[] = $row;
        return $row;
    }

    /**
     * Undocumented function
     *
     * @param Renderable $rendable
     * @return $this
     */
    public function append($rendable)
    {
        $this->elms[] = $rendable;
        return $this;
    }

    /**
     * Undocumented function
     *
     * @return array
     */
    public function getElms()
    {
        return $this->elms;
    }

    /**
     * Undocumented function
     *
     * @return integer
     */
    public function getSize()
    {
        return $this->size;
    }

    /**
     * Undocumented function
     *
     * @return $this
     */
    public function beforRender()
    {
        foreach ($this->elms as $elm) {
            $elm->beforRender();
        }

        return $this;
    }
}
