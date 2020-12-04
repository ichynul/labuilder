<?php

namespace Ichynul\Labuilder\Toolbar;

use Illuminate\Database\Eloquent\Model;
use Ichynul\Labuilder\Common\Builder;

class ActionBtn extends Bar
{
    protected $view = 'actionbtn';

    protected $mapClass = [];

    protected $postRowid = '';

    protected $extClass = '';

    protected $data = [];

    protected $dataId = 0;

    protected $confirm = true;

    protected $initPostRowidScript = false;

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

        return $this;
    }

    /**
     * Undocumented function
     *
     * @param array $data
     * @return $this
     */
    public function parseMapClass($data)
    {
        $this->extClass = '';
        foreach ($this->mapClass as $class => $check) {
            if (isset($data[$check]) && $data[$check]) {
                $this->extClass .= ' ' . $class;
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
        if (!empty($mapData) && isset($mapData[$this->name])) {
            $this->mapClass = $mapData[$this->name];
        }

        return $this;
    }

    /**
     * Undocumented function
     *
     * @param string $val
     * @return $this
     */
    public function dataId($val)
    {
        $this->dataId = $val;
        return $this;
    }

    /**
     * Undocumented function
     *
     * @param string $url
     * @param boolean|string $confirm
     * @return $this
     */
    public function postRowid($url, $confirm = true)
    {
        $this->postRowid = $url;
        $this->confirm = $confirm ? $confirm : 0;

        return $this;
    }

    protected function postRowidScript()
    {
        if ($this->initPostRowidScript) {
            return '';
        }
        $script = '';
        $class = 'action-' . $this->name;

        $script = <<<EOT

        labuilder.postRowid('{$class}', '{$this->postRowid}', '{$this->confirm}');

EOT;
        $this->script[] = $script;

        $this->initPostRowidScript = true;

        return $script;
    }

    public function beforRender()
    {
        if ($this->postRowid) {

            if (Builder::checkUrl($this->postRowid)) {
                $this->postRowidScript();
            } else {
                $this->addClass('hidden disabled');
            }
        }

        $this->parseMapClass($this->data);

        return parent::beforRender();
    }

    /**
     * Undocumented function
     *
     * @return mixed
     */
    public function render()
    {
        $vars = $this->commonVars();

        $vars = array_merge($vars, [
            'class' => $vars['class'] . $this->extClass,
            'dataId' => $this->dataId,
        ]);

        $viewshow = $this->getViewInstance($vars);

        return $viewshow->render();
    }
}
