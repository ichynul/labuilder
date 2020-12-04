<?php

namespace Ichynul\Labuilder\Displayer;

use Ichynul\Labuilder\Logic\Url;

/**
 * MultipleFile class
 * @method $this  image()
 * @method $this  office()
 * @method $this  video()
 * @method $this  audio()
 * @method $this  pkg()
 */
class MultipleFile extends Field
{
    protected $view = 'multiplefile';

    protected $js = [
        '/vendor/ichynul/labuilder/builder/js/webuploader/webuploader.min.js',
        '/vendor/ichynul/labuilder/builder/js/magnific-popup/jquery.magnific-popup.min.js',
    ];

    protected $css = [
        '/vendor/ichynul/labuilder/builder/js/webuploader/webuploader.css',
        '/vendor/ichynul/labuilder/builder/js/magnific-popup/magnific-popup.min.css',
        '/vendor/ichynul/labuilder/builder/css/uploadfiles.css',
    ];

    protected $canUpload = true;

    protected $showInput = true;

    protected $showChooseBtn = true;

    protected $files = [];

    protected $jsOptions = [
        'resize' => false,
        'duplicate' => true,
        'ext' => [
            //
            'jpg', 'jpeg', 'gif', 'wbmp', 'webpg', 'png', 'bmp',
            //
            "flv", "swf", "mkv", "avi", "rm", "rmvb", "mpeg", "mpg", "ogv", "mov", "wmv", "mp4", "webm",
            //
            "ogg", "mp3", "wav", "mid",
            //
            "rar", "zip", "tar", "gz", "7z", "bz2", "cab", "iso",
            //
            "doc", "docx", "xls", "xlsx", "ppt", "pptx", "pdf", "txt", "md",
            //
            "xml", "json",
        ],
        'multiple' => true,
        'mimeTypes' => '*/*',
        'swf_url' => '/vendor/ichynul/labuilder/builder/js/webuploader/Uploader.swf',
        'fileSingleSizeLimit' => 5 * 1024 * 1024,
        'fileNumLimit' => 5,
        'fileSizeLimit' => 0,
        'thumbnailWidth' => 80,
        'thumbnailHeight' => 80,
    ];

    protected $extTypes = [
        'image' => ['jpg', 'jpeg', 'gif', 'wbmp', 'webpg', 'png', 'bmp'],
        'office' => ["doc", "docx", "xls", "xlsx", "ppt", "pptx", "pdf"],
        'video' => ["flv", "swf", "mkv", "avi", "rm", "rmvb", "mpeg", "mpg", "ogv", "mov", "wmv", "mp4", "webm"],
        'audio' => ["ogg", "mp3", "wav", "mid"],
        'pkg' => ["rar", "zip", "tar", "gz", "7z", "bz2", "cab", "iso"],
    ];

    /**
     * Undocumented function
     *
     * @param array $val
     * @return $this
     */
    function
default($val = []) {
        $this->default = $val;
        return $this;
    }

    /**
     * Undocumented function
     *
     * @param boolean $val
     * @return $this
     */
    public function canUpload($val)
    {
        $this->canUpload = $val;
        return $this;
    }

    /**
     * Undocumented function
     *
     * @param boolean $val
     * @return $this
     */
    public function showInput($val)
    {
        $this->showInput = $val;
        return $this;
    }

    /**
     * Undocumented function
     *
     * @param boolean $val
     * @return $this
     */
    public function showChooseBtn($val)
    {
        $this->showChooseBtn = $val;
        return $this;
    }

    /**
     * Undocumented function
     * fileNumLimit
     * @param int $val
     * @return $this
     */
    public function limit($val)
    {
        $this->jsOptions['fileNumLimit'] = $val;
        return $this;
    }

    /**
     * Undocumented function
     *
     * @return $this
     */
    public function smallSize()
    {
        $this->jsOptions['thumbnailWidth'] = 50;
        $this->jsOptions['thumbnailHeight'] = 50;

        return $this;
    }

    /**
     * Undocumented function
     *
     * @return $this
     */
    public function mediumSize()
    {
        $this->jsOptions['thumbnailWidth'] = 120;
        $this->jsOptions['thumbnailHeight'] = 120;

        return $this;
    }

    /**
     * Undocumented function
     *
     * @return $this
     */
    public function bigSize()
    {
        $this->jsOptions['thumbnailWidth'] = 240;
        $this->jsOptions['thumbnailHeight'] = 240;

        return $this;
    }

    /**
     * Undocumented function
     *
     * @return $this
     */
    public function largeSize()
    {
        $this->jsOptions['thumbnailWidth'] = 480;
        $this->jsOptions['thumbnailHeight'] = 480;

        return $this;
    }

    /**
     * Undocumented function
     *
     * @param integer $w
     * @param integer $h
     * @return $this
     */
    public function thumbSize($w, $h)
    {
        $this->jsOptions['thumbnailWidth'] = $w;
        $this->jsOptions['thumbnailHeight'] = $h;

        return $this;
    }

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

    public function render()
    {
        $this->canUpload = !$this->readonly && $this->canUpload && empty($this->extKey);

        if (!$this->canUpload) {
            if (empty($this->default)) {
                $this->default = '/vendor/ichynul/labuilder/builder/images/ext/0.png';
            }
        }

        if ($this->canUpload && (!isset($this->jsOptions['upload_url']) || empty($this->jsOptions['upload_url']))) {
            $token = session('uploadtoken') ? session('uploadtoken') : md5('uploadtoken' . time() . uniqid());

            request()->session()->put('uploadtoken', $token);

            $this->jsOptions['upload_url'] = Url::adminUrl('uploads/upfiles', ['type' => 'webuploader', 'token' => $token]);
        }

        $vars = $this->commonVars();

        if (!empty($this->value)) {
            $this->files = is_array($this->value) ? $this->value : explode(',', $this->value);
        } else if (!empty($this->default)) {
            $this->files = is_array($this->default) ? $this->default : explode(',', $this->default);
        }

        $this->files = array_filter($this->files, 'strlen');

        $this->jsOptions['canUpload'] = $this->canUpload;

        $vars = array_merge($vars, [
            'jsOptions' => $this->jsOptions,
            'canUpload' => $this->canUpload,
            'showInput' => $this->showInput,
            'showChooseBtn' => $this->showChooseBtn,
            'files' => $this->files,
        ]);

        $viewshow = $this->getViewInstance($vars);

        return $viewshow->render();
    }

    /**
     * Undocumented function
     *
     * @param string|array $types ['jpg', 'jpeg', 'gif'] or 'jpg,jpeg,gif'
     * @return void
     */
    public function extTypes($types)
    {
        $this->jsOptions['ext'] = is_string($types) ? explode(',', $types) : $types;
        return $this;
    }

    public function __call($name, $arguments)
    {
        if (isset($this->extTypes[$name])) {
            $this->jsOptions['ext'] = $this->extTypes[$name];
            return $this;
        }

        throw new \UnexpectedValueException('未知调用:' . $name);
    }
}
