<?php

namespace Ichynul\Labuilder\Displayer;

use Ichynul\Labuilder\Logic\Url;

class CKEditor extends Field
{
    protected $view = 'ckeditor';

    protected $minify = false;

    protected $js = [
        '/labuilder/builder-ckeditor/ckeditor.js',
    ];

    protected $jsOptions = [
        'language' => 'zh-cn',
        'uiColor' => '#eeeeee',
        'height' => 600,
        'image_previewText' => ' ',
    ];

    /**
     * Undocumented function
     *
     * @param array $options
     * @return $this
     */
    public function jsOptions($options)
    {
        $this->jsOptions = array_merge($this->jsOptions, $options);
        return $this;
    }

    protected function editorScript()
    {
        // 配置可放在config.js中
        // 成功返回格式{"uploaded":1,"fileName":"图片名称","url":"图片访问路径"}
        // 失败返回格式{"uploaded":0,"error":{"message":"失败原因"}}

        if (!isset($this->jsOptions['filebrowserImageUploadUrl']) || empty($this->jsOptions['filebrowserImageUploadUrl'])) {
            $token = session('uploadtoken') ? session('uploadtoken') : md5('uploadtoken' . time() . uniqid());

            request()->session()->put('uploadtoken', $token);

            $this->jsOptions['filebrowserImageUploadUrl'] = Url::adminUrl('/uploads/upfiles', ['type' => 'ckeditor', 'token' => $token]);
        }

        $configs = json_encode($this->jsOptions);

        // 配置可放在config.js中

        $script = <<<EOT

        CKEDITOR.replace('{$this->name}', {$configs});

EOT;
        $this->script[] = $script;

        return $script;
    }

    public function beforRender()
    {
        if (!$this->readonly) {
            $this->editorScript();
        }

        return parent::beforRender();
    }
}
