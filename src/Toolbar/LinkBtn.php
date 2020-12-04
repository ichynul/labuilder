<?php

namespace Ichynul\Labuilder\Toolbar;

use Ichynul\Labuilder\Common\Builder;

class LinkBtn extends Bar
{
    protected $view = 'linkbtn';

    protected $postChecked = '';

    protected $openChecked = '';

    protected $confirm = true;

    /**
     * Undocumented function
     *
     * @return string
     */
    public function getId()
    {
        return 'btn-' . $this->name . preg_replace('/[^\w\-]/', '', $this->extKey);
    }

    /**
     * Undocumented function
     *
     * @param string $url
     * @param boolean|string $confirm
     * @return $this
     */
    public function postChecked($url, $confirm = true)
    {
        $this->postChecked = $url;
        $this->confirm = $confirm ? $confirm : 0;

        return $this;
    }

    /**
     * Undocumented function
     *
     * @param string $url
     * @return $this
     */
    public function openChecked($url)
    {
        $this->openChecked = $url;

        return $this;
    }

    protected function postCheckedScript()
    {
        $script = '';
        $inputId = $this->getId();

        $script = <<<EOT

        labuilder.postChecked('{$inputId}', '{$this->postChecked}', '{$this->confirm}');

EOT;
        $this->script[] = $script;

        return $script;
    }

    protected function openCheckedScript()
    {
        $script = '';
        $inputId = $this->getId();

        $script = <<<EOT

        labuilder.openChecked('{$inputId}', '{$this->openChecked}');

EOT;
        $this->script[] = $script;

        return $script;
    }

    public function beforRender()
    {
        if ($this->postChecked) {

            if (Builder::checkUrl($this->postChecked)) {
                $this->postCheckedScript();
            } else {
                $this->addClass('hidden disabled');
            }
        } else if ($this->openChecked) {

            if (Builder::checkUrl($this->openChecked)) {
                $this->openCheckedScript();
            } else {
                $this->addClass('hidden disabled');
            }
        }

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

        $viewshow = $this->getViewInstance($vars);

        return $viewshow->render();
    }
}
