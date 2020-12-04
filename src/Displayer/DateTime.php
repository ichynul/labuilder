<?php

namespace Ichynul\Labuilder\Displayer;

class DateTime extends Text
{
    protected $js = [
        '/vendor/ichynul/labuilder/builder/js/moment/moment.min.js',
        '/vendor/ichynul/labuilder/builder/js/moment/locale/zh-cn.js',
        '/vendor/ichynul/labuilder/builder/js/bootstrap-datetimepicker/bootstrap-datetimepicker.min.js',
        '/vendor/ichynul/labuilder/builder/js/bootstrap-datetimepicker/locale/zh-cn.js',
    ];

    protected $css = [
        '/vendor/ichynul/labuilder/builder/js/bootstrap-datetimepicker/bootstrap-datetimepicker.min.css',
    ];

    protected $size = [2, 3];

    protected $format = 'YYYY-MM-DD HH:mm:ss';

    protected $befor = '<span class="input-group-addon"><i class="mdi mdi-calendar-clock"></i></span>';

    protected $timespan = '';

    protected $jsOptions = [
        'useCurrent' => false,
        'locale' => 'zh-cn',
        'showTodayButton' => false,
        'showClear' => true,
        'showClose' => true,
        'sideBySide' => true,
        'inline' => false,
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

    protected function dateTimeScript()
    {
        $inputId = $this->getId();

        $str = preg_replace('/\W/', '', $this->name);

        $this->jsOptions['format'] = $this->format;

        $locale = isset($this->jsOptions['locale']) ? $this->jsOptions['locale'] : 'zh-cn';

        unset($this->jsOptions['locale']);

        $configs = json_encode($this->jsOptions);

        $configs = substr($configs, 1, strlen($configs) - 2);

        $script = <<<EOT
        var locale{$str} = moment.locale('{$locale}');

        $('#{$inputId}').datetimepicker({
            "locale" : locale{$str},
            {$configs}
        });

EOT;
        $this->script[] = $script;

        return $script;
    }

    /**
     * Undocumented function
     * YYYY-MM-DD HH:mm:ss
     * @param string $val
     * @return $this
     */
    public function format($val)
    {
        $this->format = $val;
        return $this;
    }

    /**
     * Undocumented function
     *
     * @param string $val
     * @return $this
     */
    public function timespan($val = 'Y-m-d H:i:s')
    {
        $this->timespan = $val;
        return $this;
    }

    public function beforRender()
    {
        $this->dateTimeScript();

        return parent::beforRender();
    }

    /**
     * Undocumented function
     *
     * @return string
     */
    public function renderValue()
    {
        if ($this->timespan && is_numeric($this->value)) {
            $this->value = date($this->timespan, $this->value);
        }

        return parent::renderValue();
    }
}
