<?php

namespace Ichynul\Labuilder\Displayer;

use Ichynul\Labuilder\Logic\Url;

class WangEditor extends Field
{
    protected $view = 'wangeditor';

    protected $minify = false;

    protected $js = [
        '/vendor/ichynul/labuilder/builder/js/wangEditor/wangEditor.min.js',
    ];

    protected $jsOptions = [
        'uploadImgMaxSize' => 20 * 1024 * 1024,
        'uploadImgMaxLength' => 10,
        'uploadImgTimeout' => 30000,
        'uploadFileName' => 'file',
        'zIndex' => 99
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
        $inputId = $this->getId();

        if (!isset($this->jsOptions['uploadImgServer']) || empty($this->jsOptions['uploadImgServer'])) {
            $token = session('uploadtoken') ? session('uploadtoken') : md5('uploadtoken' . time() . uniqid());

            request()->session()->put('uploadtoken', $token);

            $this->jsOptions['uploadImgServer'] = Url::adminUrl('/uploads/upfiles', ['type' => 'wangeditor', 'token' => $token]);
        }

        $this->jsOptions['uploadImgParams'] = [];

        $configs = json_encode($this->jsOptions);

        $script = <<<EOT

        var E = window.wangEditor;
        var editor = new E('#{$inputId}-div');

        editor.customConfig = {$configs};

        editor.customConfig.uploadImgParams = {_token: window.__token__};

        editor.customConfig.uploadImgHooks = {
            customInsert: function (insertImg, result, editor) {
                // 图片上传并返回结果，自定义插入图片的事件（而不是编辑器自动插入图片！！！）
                // insertImg 是插入图片的函数，editor 是编辑器对象，result 是服务器端返回的结果
                // 举例：假如上传图片成功后，服务器端返回的是 {url:'....'} 这种格式，即可这样插入图片：
                var url = result.url;
                insertImg(url);
                // result 必须是一个 JSON 格式字符串！！！否则报错
            }
        };
        // 设置内容
        editor.customConfig.onchange = function (html) {
            $('#{$inputId}').val(html);
        }
        editor.create();

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
