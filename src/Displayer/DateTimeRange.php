<?php

namespace Ichynul\Labuilder\Displayer;

class DateTimeRange extends Text
{
    protected $js = [
        '/vendor/ichynul/labuilder/builder/js/moment/moment.min.js',
        '/vendor/ichynul/labuilder/builder/js/moment/locale/zh-cn.js',
        '/vendor/ichynul/labuilder/builder/js/bootstrap-daterangepicker/daterangepicker.min.js',
    ];

    protected $css = [
        '/vendor/ichynul/labuilder/builder/js/bootstrap-daterangepicker/daterangepicker.css',
    ];

    protected $size = [2, 4];

    protected $format = 'YYYY-MM-DD HH:mm:ss';

    protected $befor = '';

    protected $timePicker = true;

    protected $separator = ',';

    protected $timespan = '';

    protected $jsOptions = [
        'opens' => 'right',
        'showDropdowns' => true,
        'timePicker24Hour' => true, //设置小时为24小时制
        'locale' => [
        ],
    ];

    /**
     * Undocumented function
     *
     * @param string $val
     * @return void
     */
    public function startDate($val)
    {
        $this->jsOptions['startDate'] = $val;
        return $this;
    }

    /**
     * Undocumented function
     *
     * @param string $val
     * @return void
     */
    public function endDate($val)
    {
        $this->jsOptions['endDate'] = $val;
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

    protected function dateTimeRangeScript()
    {
        $inputId = $this->getId();

        $this->jsOptions['timePicker'] = $this->timePicker;

        $this->jsOptions['locale'] = array_merge(
            $this->jsOptions['locale'],
            [
                'format' => $this->format,
                'separator' => $this->separator,
            ]);

        $configs = json_encode($this->jsOptions);

        $configs = substr($configs, 1, strlen($configs) - 2);

        $script = <<<EOT

        $('#{$inputId}').daterangepicker({
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
     * ','
     * @param string $val
     * @return $this
     */
    public function separator($val = ',')
    {
        $this->separator = $val;
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

    /**
     * Undocumented function
     *
     * @return string
     */
    public function renderValue()
    {
        if ($this->timespan && is_numeric($this->value)) {
            $arr = explode($this->separator, $this->value);
            if (isset($arr[0]) && is_numeric($arr[0])) {
                $arr[0] = date($this->timespan, $arr[0]);
            }
            if (isset($arr[1]) && is_numeric($arr[1])) {
                $arr[1] = date($this->timespan, $arr[1]);
            }
            $this->value = implode($this->separator, $arr);
        }

        return parent::renderValue();
    }

    public function beforRender()
    {
        $this->dateTimeRangeScript();

        return parent::beforRender();
    }
}
