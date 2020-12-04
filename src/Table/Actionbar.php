<?php

namespace Ichynul\Labuilder\Table;

use Ichynul\Labuilder\Common\Toolbar;
use Ichynul\Labuilder\Logic\Url;

class Actionbar extends Toolbar
{
    protected $pk;

    protected $rowId;

    protected $rowData;

    protected $mapClass = [];

    /**
     * Undocumented function
     *
     * @param boolean $val
     * @param array|string $size
     * @return $this
     */
    public function useLayerAll($val, $size = [])
    {
        foreach ($this->elms as $elm) {
            $elm->useLayer($val, $size);
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
        if (empty($this->elms)) {
            $this->buttons();
        }

        foreach ($this->elms as $elm) {

            if ($this->extKey) {
                $elm->extKey($this->extKey);
            }

            if ($this->rowId) {
                $elm->dataId($this->rowId);
            }

            if ($this->rowData) {
                $elm->parseUrl($this->rowData);
            }

            if ($this->mapClass) {
                $elm->mapClass($this->mapClass);
            }
        }

        return parent::beforRender();
    }

    /**
     * Undocumented function
     *
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
     * @param array $data
     * @return $this
     */
    public function rowData($data)
    {
        if (isset($data[$this->pk])) {
            $this->rowId = $data[$this->pk];
        }

        $this->rowData = $data;

        return $this;
    }

    /**
     * Undocumented function
     *
     * @param array $data
     * @return $this
     */
    public function mapClass($data)
    {
        $this->mapClass = $data;
        return $this;
    }

    /**
     * Undocumented function
     *
     * @return $this
     */
    public function buttons()
    {
        $this->btnEdit();
        $this->btnDestroy();

        return $this;
    }

    /**
     * Undocumented function
     *
     * @param string $url
     * @param string $label
     * @param string $class
     * @param string $icon
     * @param string $attr
     * @return $this
     */
    public function btnEdit($url = '', $label = '', $class = 'btn-primary', $icon = 'mdi-lead-pencil', $attr = 'title="编辑"')
    {
        if (empty($url)) {
            $url = Url::action('__data.pk__' . '/edit');
        }
        $this->actionBtn('edit', $label)->href($url)->icon($icon)->addClass($class)->addAttr($attr);
        return $this;
    }

    /**
     * Undocumented function
     *
     * @param string $url
     * @param string $label
     * @param string $class
     * @param string $icon
     * @param string $attr
     * @return $this
     */
    public function btnShow($url = '', $label = '', $class = 'btn-info', $icon = 'mdi-eye-outline', $attr = 'title="查看"')
    {
        if (empty($url)) {
            $url = Url::action('', ['id' => '__data.pk__']);
        }
        $this->actionBtn('show', $label)->href($url)->icon($icon)->addClass($class)->addAttr($attr);
        return $this;
    }

    /**
     * Undocumented function
     *
     * @param string $postUrl
     * @param string $label
     * @param string $class
     * @param string $icon
     * @param string $attr
     * @param boolean|string $confirm
     * @return $this
     */
    public function btnDestroy($postUrl = '', $label = '', $class = 'btn-danger', $icon = 'mdi-delete', $attr = 'title="删除"', $confirm = true)
    {
        if (empty($postUrl)) {
            $postUrl = Url::action('destroy');
        }
        $this->actionBtn('destroy', $label)->postRowid($postUrl, $confirm)->icon($icon)->addClass($class)->addAttr($attr);
        return $this;
    }

    /**
     * Undocumented function
     *
     * @param string $postUrl
     * @param string $label
     * @param string $class
     * @param string $icon
     * @param string $attr
     * @param boolean|string $confirm
     * @return $this
     */
    public function btnDisable($postUrl = '', $label = '', $class = 'btn-warning', $icon = 'mdi-block-helper', $attr = 'title="禁用"', $confirm = true)
    {
        if (empty($postUrl)) {
            $postUrl = Url::action('enable', ['state' => 0]);
        }
        $this->actionBtn('disable', $label)->postRowid($postUrl, $confirm)->icon($icon)->addClass($class)->addAttr($attr);
        return $this;
    }

    /**
     * Undocumented function
     *
     * @param string $postUrl
     * @param string $label
     * @param string $class
     * @param string $icon
     * @param string $attr
     * @param boolean|string $confirm
     * @return $this
     */
    public function btnEnable($postUrl = '', $label = '', $class = 'btn-success', $icon = 'mdi-check', $attr = 'title="启用"', $confirm = true)
    {
        if (empty($postUrl)) {
            $postUrl = Url::action('enable', ['state' => 1]);
        }
        $this->actionBtn('enable', $label)->postRowid($postUrl, $confirm)->icon($icon)->addClass($class)->addAttr($attr);
        return $this;
    }

    /**
     * Undocumented function
     *
     * @param string $enableTitle
     * @param string $disableTitle
     * @return $this
     */
    public function btnEnableAndDisable($enableTitle = '启用', $disableTitle = '禁用')
    {
        $this->btnEnable()->getCurrent()->attr('title="' . $enableTitle . '"');
        $this->btnDisable()->getCurrent()->attr('title="' . $disableTitle . '"');

        return $this;
    }

    /**
     * Undocumented function
     *
     * @param string $name
     * @param string $url
     * @param string $label
     * @param string $class
     * @param string $icon
     * @param string $attr
     * @return $this
     */
    public function btnLink($name = '', $url, $label = '', $class = 'btn-secondary', $icon = '', $attr = '')
    {
        if (!$name) {
            $name = preg_replace('/.+?\/(\w+)(\.\w+)?$/', '$1', $url, -1, $count);

            if (!$count) {
                $name = mt_rand(10, 99);
            }
        }

        $this->actionBtn($name, $label)->href($url)->icon($icon)->addClass($class)->addAttr($attr);

        return $this;
    }

    /**
     * Undocumented function
     *
     * @param string $name
     * @param string $postUrl
     * @param string $label
     * @param string $class
     * @param string $icon
     * @param string $attr
     * @param boolean|string $confirm
     * @return $this
     *
     */
    public function btnPostRowid($name = '', $postUrl, $label = '', $class = 'btn-secondary', $icon = 'mdi-checkbox-marked-outline', $attr = '', $confirm = true)
    {
        if (!$name) {
            $name = preg_replace('/.+?\/(\w+)(\.\w+)?$/', '$1', $postUrl, -1, $count);

            if (!$count) {
                $name = mt_rand(10, 99);
            }
        }

        $this->actionBtn($name, $label)->postRowid($postUrl, $confirm)->icon($icon)->addClass($class)->addAttr($attr);

        return $this;
    }

    /**
     * Undocumented function
     *
     * @param array $items
     * @param string $label
     * @param string $class
     * @param string $icon
     * @param string $attr
     * @return $this
     */
    public function btnActions($items, $label = '操作', $class = 'btn-secondary', $icon = '', $attr = '')
    {
        $this->actions('actions', $label)->items($items)->addClass($class)->icon($icon)->addAttr($attr);
        return $this;
    }
}
