<?php

namespace Ichynul\Labuilder\Common;

use Ichynul\Labuilder\Events\BuilderEvents;
use Ichynul\Labuilder\Inface\Auth;
use Ichynul\Labuilder\Inface\Renderable;
use Ichynul\Labuilder\tree\JSTree;
use Ichynul\Labuilder\tree\ZTree;

class Builder implements Renderable
{
    private $view = 'labuilder::content';

    protected $title = '';

    protected $desc = null;

    protected $csrf_token = '';

    /**
     * Undocumented variable
     *
     * @var array
     */
    protected $rows = [];

    /**
     * Undocumented variable
     *
     * @var Row
     */
    protected $__row__ = null;

    protected $js = [];

    protected $customJs = [];

    protected $css = [];

    protected $customCss = [];

    protected $stylesheet = [];

    protected $script = [];

    protected $notify = [];

    protected $layer;

    /**
     * Undocumented variable
     *
     * @var Auth
     */
    protected static $auth;

    protected static $minify = false;

    protected static $aver = '1.0';

    protected static $instance = null;

    protected function __construct($title, $desc)
    {
        $this->title = $title;
        $this->desc = $desc;
    }

    /**
     * Undocumented function
     *
     * @param string $title
     * @param string $desc
     * @return $this
     */
    public static function getInstance($title = '', $desc = '')
    {
        if (static::$instance == null) {
            static::$instance = new static($title, $desc);

            event(new BuilderEvents('tpext_create_builder', static::$instance));
        } else {
            if ($title) {
                static::$instance->title($title);
            }
            if ($desc) {
                static::$instance->desc($desc);
            }
        }

        return static::$instance;
    }

    /**
     * Undocumented function
     *
     * @param string $val
     * @return $this
     */
    public function title($val)
    {
        $this->title = $val;
        return $this;
    }

    /**
     * Undocumented function
     *
     * @param string $val
     * @return $this
     */
    public function desc($val)
    {
        $this->desc = $val;
        return $this;
    }

    /**
     * Undocumented function
     *
     * @return string
     */
    public function getCsrfToken()
    {
        if (!$this->csrf_token) {
            $token = csrf_token();
            $this->csrf_token = $token;
            view()->share(['__token__' => $token]);
        }

        return $this->csrf_token;
    }

    /**
     * Undocumented function
     *
     * @param array|string $val
     * @return $this
     */
    public function addJs($val)
    {
        if (!is_array($val)) {
            $val = [$val];
        }
        $this->js = array_merge($this->js, $val);
        return $this;
    }

    /**
     * 添加自定义js，不会被minify
     *
     * @param array|string $val
     * @return $this
     */
    public function customJs($val)
    {
        if (!is_array($val)) {
            $val = [$val];
        }
        $this->customJs = array_merge($this->customJs, $val);
        return $this;
    }

    /**
     * Undocumented function
     *
     * @param array|string $val
     * @return $this
     */
    public function addCss($val)
    {
        if (!is_array($val)) {
            $val = [$val];
        }
        $this->css = array_merge($this->css, $val);
        return $this;
    }

    /**
     * Undocumented function添加自定义css，不会被minify
     *
     * @param array|string $val
     * @return $this
     */
    public function customCss($val)
    {
        if (!is_array($val)) {
            $val = [$val];
        }
        $this->customCss = array_merge($this->customCss, $val);
        return $this;
    }

    /**
     * Undocumented function
     *
     * @param array|string $val
     * @return $this
     */
    public function addScript($val)
    {
        if (!is_array($val)) {
            $val = [$val];
        }
        $this->script = array_merge($this->script, $val);
        return $this;
    }

    /**
     * Undocumented function
     *
     * @param array $val
     * @return $this
     */
    public function addStyleSheet($val)
    {
        if (!is_array($val)) {
            $val = [$val];
        }
        $this->stylesheet = array_merge($this->stylesheet, $val);
        return $this;
    }

    /**
     * Undocumented function
     *
     * @return array
     */
    public function getJs()
    {
        return $this->js;
    }

    /**
     * Undocumented function
     *
     * @return array
     */
    public function getScript()
    {
        return $this->script;
    }

    /**
     * Undocumented function
     *
     * @return array
     */
    public function getStyle()
    {
        return $this->style;
    }

    /**
     * Undocumented function
     *
     * @return array
     */
    public function getCss()
    {
        return $this->css;
    }

    /**
     * Undocumented function
     *
     * @return array
     */
    public function getRows()
    {
        return $this->rows;
    }

    /**
     * Undocumented function
     * lightyear.notify('修改成功，页面即将自动跳转~', 'success', 5000, 'mdi mdi-emoticon-happy', 'top', 'center');
     * @param string $msg
     * @param string $type
     * @param integer $delay
     * @param string $icon
     * @param string $from
     * @param string $align
     * @return $this
     */
    public function notify($msg, $type = 'info', $delay = 2000, $icon = '', $from = 'top', $align = 'center')
    {
        $this->notify = [$msg, $type, $delay, $icon, $from, $align];
        return $this;
    }

    /**
     * Undocumented function
     *
     * @return array
     */
    public function getNotify()
    {
        return $this->notify;
    }

    /**
     * Undocumented function
     *
     * @return Row
     */
    public function row()
    {
        $row = new Row();
        $this->rows[] = $row;
        $this->__row__ = $row;
        return $row;
    }

    /**
     * Undocumented function
     *
     * @param integer $size
     * @return Column
     */
    public function column($size = 12)
    {
        if (!$this->__row__) {
            $this->row();
        }

        return $this->__row__->column($size);
    }

    /**
     * 获取一个form
     *
     * @param integer col大小
     * @return Form
     */
    public function form($size = 12)
    {
        return $this->column($size)->form();
    }

    /**
     * 获取一个表格
     *
     * @param integer col大小
     * @return Table
     */
    public function table($size = 12)
    {
        return $this->column($size)->table();
    }

    /**
     * 获取一个工具栏
     *
     * @param integer col大小
     * @return Toolbar
     */
    public function toolbar($size = 12)
    {
        return $this->column($size)->toolbar();
    }

    /**
     * 默认获取一个ZTree树
     *
     * @return ZTree
     */
    public function tree($size = 12)
    {
        return $this->column($size)->tree();
    }

    /**
     * 获取一个ZTree树
     *
     * @return ZTree
     */
    public function zTree($size = 12)
    {
        return $this->column($size)->zTree();
    }

    /**
     * 获取一个JSTree树
     *
     * @return JSTree
     */
    public function jsTree($size = 12)
    {
        return $this->column($size)->jsTree();
    }

    /**
     * 获取一自定义内容
     *
     * @param integer col大小
     * @return Content
     */
    public function content($size = 12)
    {
        return $this->column($size)->content();
    }

    /**
     * 获取一tab内容
     *
     * @param integer col大小
     * @return Tab
     */
    public function tab($size = 12)
    {
        return $this->column($size)->tab();
    }

    /**
     * 获取layer
     *
     * @return Layer
     */
    public function layer()
    {
        if (!$this->layer) {
            $this->layer = new Layer;
        }

        return $this->layer;
    }

    public function commonJs()
    {
        return [
            '/vendor/ichynul/labuilder/lightyearadmin/js/jquery.min.js',
            '/vendor/ichynul/labuilder/lightyearadmin/js/bootstrap.min.js',
            '/vendor/ichynul/labuilder/lightyearadmin/js/jquery.lyear.loading.js',
            '/vendor/ichynul/labuilder/lightyearadmin/js/bootstrap-notify.min.js',
            '/vendor/ichynul/labuilder/lightyearadmin/js/jconfirm/jquery-confirm.min.js',
            '/vendor/ichynul/labuilder/lightyearadmin/js/lightyear.js',
            '/vendor/ichynul/labuilder/lightyearadmin/js/main.min.js',
            '/vendor/ichynul/labuilder/builder/js/jquery-validate/jquery.validate.min.js',
            '/vendor/ichynul/labuilder/builder/js/jquery-validate/messages_zh.min.js',
            '/vendor/ichynul/labuilder/builder/js/layer/layer.js',
            '/vendor/ichynul/labuilder/builder/js/labuilder.js',
        ];
    }

    public function commonCss()
    {
        return [
            '/vendor/ichynul/labuilder/lightyearadmin/css/bootstrap.min.css',
            '/vendor/ichynul/labuilder/lightyearadmin/css/materialdesignicons.min.css',
            '/vendor/ichynul/labuilder/lightyearadmin/css/animate.css',
            '/vendor/ichynul/labuilder/lightyearadmin/css/style.min.css',
            '/vendor/ichynul/labuilder/lightyearadmin/js/jconfirm/jquery-confirm.min.css',
            '/vendor/ichynul/labuilder/builder/css/labuilder.css',
        ];
    }

    public function beforRender()
    {
        $this->addJs($this->commonJs());
        $this->addCss($this->commonCss());

        foreach ($this->rows as $row) {
            $row->beforRender();
        }
    }

    /**
     * Undocumented function
     *
     * @param boolean $val
     * @return void
     */
    public static function minify($val)
    {
        static::$minify = $val;
    }

    /**
     * Undocumented function
     *
     * @return boolean
     */
    public static function isMinify()
    {
        return static::$minify;
    }

    /**
     * Undocumented function
     *
     * @param string $val
     * @return void
     */
    public static function aver($val)
    {
        static::$aver = $val;
    }

    /**
     * Undocumented function
     *
     * @param string|Auth $class
     * @return void
     */
    public static function auth($class)
    {
        static::$auth = $class;
    }

    /**
     * Undocumented function
     *
     * @param [type] $url
     * @return boolean
     */
    public static function checkUrl($url)
    {
        if (!empty(static::$auth)) {

            return static::$auth::checkUrl($url);
        }

        return true;
    }

    /**
     * Undocumented function
     *
     * @return string
     */
    public function render()
    {
        if ($this->layer) {
            return $this->layer->getViewShow();
        }

        $this->beforRender();

        if (!empty($this->notify)) {

            $this->script[] = "lightyear.notify('{$this->notify[0]}', '{$this->notify[1]}', {$this->notify[2]}, '{$this->notify[3]}', '{$this->notify[4]}', '{$this->notify[5]}');";
        }

        if (static::$minify) {
            $this->js = $this->customJs;
            $this->css = $this->customCss;
        } else {
            $this->js = array_merge($this->js, $this->customJs);
            $this->css = array_merge($this->css, $this->customCss);
        }

        foreach ($this->css as &$c) {
            if (strpos($c, '?') == false && strpos($c, 'http') == false) {
                $c .= '?aver=' . static::$aver;
            }
        }

        unset($c);

        foreach ($this->js as &$j) {
            if (strpos($j, '?') == false && strpos($j, 'http') == false) {
                $j .= '?aver=' . static::$aver;
            }
        }

        unset($j);

        $vars = [
            'title' => $this->title ? $this->title : '',
            'desc' => $this->desc,
            'rows' => $this->rows,
            'js' => array_unique($this->js),
            'css' => array_unique($this->css),
            'stylesheet' => implode('', array_unique($this->stylesheet)),
            'script' => implode('', array_unique($this->script)),
            'admin_page_title' => $this->desc,
            'admin_page_position' => $this->title,
            'admin_favicon' => '',
            'admin_page_description' => '',
        ];

        return view($this->view, $vars)->render();
    }

    public function __toString()
    {
        return $this->render();
    }
}
