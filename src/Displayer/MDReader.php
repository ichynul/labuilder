<?php

namespace Ichynul\Labuilder\Displayer;

use Ichynul\Labuilder\Logic\Url;

class MDReader extends Field
{
    protected $view = 'mdeditor';

    protected $minify = false;

    protected $js = [
        '/labuilder/builder-mdeditor/lib/marked.min.js',
        '/labuilder/builder-mdeditor/lib/prettify.min.js',
        '/labuilder/builder-mdeditor/lib/raphael.min.js',
        '/labuilder/builder-mdeditor/lib/underscore.min.js',
        '/labuilder/builder-mdeditor/lib/sequence-diagram.min.js',
        '/labuilder/builder-mdeditor/lib/flowchart.min.js',
        '/labuilder/builder-mdeditor/lib/jquery.flowchart.min.js',
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
        'htmlDecode' => "style,script,iframe", // you can filter tags decode
        'emoji' => true,
        'taskList' => true,
        'tex' => true, // 默认不解析
        'flowChart' => true, // 默认不解析
        'sequenceDiagram' => true, // 默认不解析
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

        var mdreader = editormd.markdownToHTML("{$inputId}-div", {$configs});

EOT;
        $this->script[] = $script;

        return $script;
    }

    public function beforRender()
    {
        $this->editorScript();

        return parent::beforRender();
    }
}
