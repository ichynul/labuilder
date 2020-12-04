<?php

namespace Ichynul\Labuilder\Displayer;

use Ichynul\Labuilder\Logic\Url;

class Tinymce extends Field
{
    protected $view = 'tinymce';

    protected $minify = false;

    protected $js = [
        '/labuilder/builder-tinymce/tinymce.min.js',
    ];

    protected $jsOptions = [
        'language' => 'zh_CN',
        'directionality' => 'ltl',
        'browser_spellcheck' => true,
        'contextmenu' => false,
        'height' => 600,
        'plugins' => [
            "advlist autolink lists link image charmap print preview anchor",
            "searchreplace visualblocks code fullscreen",
            "insertdatetime media table contextmenu paste imagetools wordcount",
            "code",
        ],
        'toolbar' => "insertfile undo redo | styleselect | bold italic | alignleft aligncenter alignright alignjustify | bullist numlist outdent indent | link image | code",
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

        if (!isset($this->jsOptions['images_upload_url']) || empty($this->jsOptions['images_upload_url'])) {
            $token = session('uploadtoken') ? session('uploadtoken') : md5('uploadtoken' . time() . uniqid());

            request()->session()->put('uploadtoken', $token);

            $this->jsOptions['images_upload_url'] = Url::adminUrl('/uploads/upfiles', ['type' => 'tinymce', 'token' => $token]);
        }

        $this->jsOptions['selector'] = "#{$inputId}";

        $configs = json_encode($this->jsOptions);

        $script = <<<EOT

        tinymce.init({$configs});

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
