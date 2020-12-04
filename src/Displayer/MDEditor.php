<?php

namespace Ichynul\Labuilder\Displayer;

use Ichynul\Labuilder\Logic\Url;

class MDEditor extends Field
{
    protected $view = 'mdeditor';

    protected $minify = false;

    protected $js = [
        '/labuilder/builder-mdeditor/editormd.min.js',
    ];

    protected $css = [
        '/labuilder/builder-mdeditor/css/editormd.min.css',
    ];

    /*模板样式里面有一个css会影响editor.md的图标,这里重设下*/
    protected $stylesheet =
        '.editormd .divider {
            width: auto;
        }
        .editormd .divider:before,
        .editormd .divider:after {
            margin: 0px;

        }
        '
    ;

    protected $jsOptions = [
        'height' => 500,
        'width' => 1200,
        'path' => "/labuilder/builder-mdeditor/lib/",
        'codeFold' => true,
        'htmlDecode' => 'iframe|on*', // 开启标签
        'imageUpload' => true,
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

    /**
     * Undocumented function
     *
     * @param boolean $val
     * @return $this
     */
    public function readonly($val = true)
    {
        $this->readonly = $this->canUpload = !$val;
        return $this;
    }

    protected function editorScript()
    {
        $inputId = $this->getId();

        /**
         * 上传的后台只需要返回一个 JSON 数据，结构如下：
         * {
         *      success : 0 | 1,           // 0 表示上传失败，1 表示上传成功
         *      message : "提示的信息，上传成功或上传失败及错误信息等。",
         *      url     : "图片地址"        // 上传成功时才返回
         *  }
         */
        if (!isset($this->jsOptions['imageUploadURL']) || empty($this->jsOptions['imageUploadURL'])) {
            $token = session('uploadtoken') ? session('uploadtoken') : md5('uploadtoken' . time() . uniqid());

            request()->session()->put('uploadtoken', $token);

            $this->jsOptions['imageUploadURL'] = Url::adminUrl('/uploads/upfiles', ['type' => 'editormd', 'token' => $token]);
        }

        $configs = json_encode($this->jsOptions);

        $script = <<<EOT

        var mdeditor = editormd("{$inputId}-div", {$configs});

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
