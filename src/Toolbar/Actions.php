<?php

namespace Ichynul\Labuilder\Toolbar;

use Illuminate\Database\Eloquent\Model;
use Ichynul\Labuilder\Common\Builder;

class Actions extends DropdownBtns
{
    protected $mapClass = [];

    protected $mapData = [];

    protected $data = [];

    protected $dataId = 0;

    protected $initPostRowidScript = false;

    /**
     * Undocumented function
     *
     * @param string $val
     * @return $this
     */
    public function dataId($val)
    {
        $this->dataId = $val;
        $this->addGroupAttr('data-id="' . $val . '"');
        return $this;
    }

    /**
     * Undocumented function
     *
     * @param array|Model $data
     * @return $this
     */
    public function parseUrl($data)
    {
        $this->data = $data;

        $data = $this->data instanceof Model ? $this->data->toArray() : $this->data;

        $keys = ['__data.pk__'];
        $replace = [$this->dataId];
        foreach ($data as $key => $val) {
            $keys[] = '__data.' . $key . '__';
            $replace[] = $val;
        }

        $this->__href__ = str_replace($keys, $replace, $this->href);

        foreach ($this->items as $key => &$item) {
            if (is_string($item)) {
                $item = ['label' => $item];
            }
            if (!isset($item['url'])) {
                $item['url'] = $key;
            }
            if (stripos($item['url'], '/') === false) {
                $item['url'] = url($item['url']);
            }
            $item['url'] = str_replace($keys, $replace, $item['url']);
        }

        return $this;
    }

    /**
     * Undocumented function
     *
     * @param [type] $data
     * @return $this
     */
    public function parseMapClass($data)
    {
        foreach ($this->items as &$item) {
            if (!isset($item['mapClass'])) {
                continue;
            }
            foreach ($item['mapClass'] as $class => $check) {
                if (isset($data[$check]) && $data[$check]) {
                    $item['class'] = isset($item['class']) ? $item['class'] . ' ' . $class : $class;
                }
            }
        }

        return $this;
    }

    /**
     * Undocumented function
     *
     * @param array $mapData
     * @return $this
     */
    public function mapClass($mapData)
    {
        if (!empty($mapData)) {
            foreach ($this->items as $key => &$item) {
                if (isset($mapData[$key])) {
                    $item['mapClass'] = $mapData[$key];
                }
            }
        }

        return $this;
    }

    protected function postRowidScript()
    {
        if ($this->initPostRowidScript) {
            return '';
        }

        $confirms = [];
        $actions = [];

        foreach ($this->items as $key => $item) {
            if (!Builder::checkUrl($item['url'])) {
                continue;
            }
            $confirms[$item['url']] = isset($item['confirm']) ? $item['confirm'] : '1';
            $actions[$key] = $item;
        }

        $this->items = $actions;

        $script = '';
        $class = 'dropdown-actions';

        $this->addGroupClass($class);

        $confirms = json_encode($confirms, JSON_UNESCAPED_UNICODE);

        $script = <<<EOT

        labuilder.postActionsRowid('{$class}', {$confirms});

EOT;
        $this->initPostRowidScript = true;

        $this->script[] = $script;

        return $script;
    }

    public function beforRender()
    {
        $this->postRowidScript();

        $this->parseMapClass($this->data);

        return parent::beforRender();
    }
}
