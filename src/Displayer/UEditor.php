<?php

namespace Ichynul\Labuilder\Displayer;

class UEditor extends Field
{
    protected $view = 'ueditor';

    protected $minify = false;

    protected $js = [
        '/labuilder/builder-ueditor/ueditor.config.js',
    ];

    protected $configJsPath = '/labuilder/builder-ueditor/ueditor.all.min.js';

    protected $uploadUrl = '';

    /**
     * Undocumented function
     *
     * @param string $val
     * @return $this
     */
    public function configJsPath($val)
    {
        $this->configJsPath = $val;
        return $this;
    }

    /**
     * Undocumented function
     *
     * @param string $val
     * @return $this
     */
    public function uploadUrl($val)
    {
        $this->uploadUrl = $val;
        return $this;
    }

    protected function editorScript()
    {
        $inputId = $this->getId();

        $script = <<<EOT

        var ue = UE.getEditor('{$inputId}');

EOT;
        $this->script[] = $script;

        return $script;
    }

    public function beforRender()
    {
        $this->js[] = $this->configJsPath;

        if (!$this->readonly) {
            $this->editorScript();
        }

        return parent::beforRender();
    }

    public function render()
    {
        if (empty($this->uploadUrl)) {

            $token = session('uploadtoken') ? session('uploadtoken') : md5('uploadtoken' . time() . uniqid());

            request()->session()->put('uploadtoken', $token);

            $this->uploadUrl = url('/admin/attachments', ['token' => $token]);
        }

        $vars = $this->commonVars();

        $vars = array_merge($vars, [
            'uploadUrl' => $this->uploadUrl,
        ]);

        $viewshow = $this->getViewInstance($vars);

        return $viewshow->render();
    }
}
