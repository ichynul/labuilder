<?php

namespace Ichynul\Labuilder\Toolbar;

use Ichynul\Labuilder\Common\Builder;

class MultipleActions extends DropdownBtns
{
    /**
     * Undocumented function
     *
     * @param array $confirms
     * @return $this
     */
    public function postChecked($confirms)
    {
        $this->postChecked = true;
        $this->confirms = $confirms;

        return $this;
    }

    protected function postCheckedScript()
    {
        $confirms = [];
        $actions = [];

        foreach ($this->items as $key => $item) {
            if (is_string($item)) {
                $item = ['label' => $item];
            }
            if (!isset($item['url'])) {
                $item['url'] = $key;
            }
            if (stripos($item['url'], '/') === false) {
                $item['url'] = url($item['url']);
            }
            if (!Builder::checkUrl($item['url'])) {
                continue;
            }
            $confirms[$item['url']] = isset($item['confirm']) ? $item['confirm'] : '1';
            $actions[$key] = $item;
        }

        $this->items = $actions;

        $script = '';
        $inputId = $this->getId();

        $confirms = json_encode($confirms, JSON_UNESCAPED_UNICODE);

        $script = <<<EOT

        labuilder.postActionsChecked('{$inputId}', {$confirms});

EOT;
        $this->script[] = $script;

        return $script;
    }

    public function beforRender()
    {
        $this->postCheckedScript();
        return parent::beforRender();
    }
}
