<?php

namespace Ichynul\Labuilder\Displayer;

use Ichynul\Labuilder\Traits\HasOptions;

class Select extends Radio
{
    use HasOptions;

    protected $view = 'select';

    protected $class = '';

    protected $js = [
        '/vendor/ichynul/labuilder/builder/js/select2/select2.min.js',
        '/vendor/ichynul/labuilder/builder/js/select2/i18n/zh-CN.js',
    ];

    protected $css = [
        '/vendor/ichynul/labuilder/builder/js/select2/select2.min.css',
    ];

    protected $attr = 'size="1"';

    protected $group = false;

    protected $select2 = true;

    protected $prevSelect = null;

    protected $jsOptions = [
        'placeholder' => '',
        'allowClear' => true,
        'minimumInputLength' => 0,
        'language' => 'zh-CN',
    ];

    /**
     * Undocumented function
     *
     * @param boolean $show
     * @return $this
     */
    public function select2($use)
    {
        $this->select2 = $use;
        return $this;
    }

    /**
     * Undocumented function
     *
     * @param string $url
     * @param string $textField text|name
     * @param string $idField
     * @param integer $delay
     * @param boolean $loadmore
     * @return $this
     */
    public function dataUrl($url, $textField = '', $idField = '', $delay = 250, $loadmore = true)
    {
        $this->jsOptions['ajax'] = [
            'url' => $url,
            'id' => $idField,
            'text' => $textField,
            'delay' => $delay,
            'loadmore' => $loadmore,
        ];

        return $this;
    }

    /**
     * Undocumented function
     *
     * @param [type] $val
     * @return $this
     */
    public function placeholder($val)
    {
        $this->jsOptions['placeholder'] = $val;
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

    /**
     * Undocumented function
     *
     * @param string $prev
     * @return string
     */
    protected function select2Script()
    {
        $script = '';
        $selectId = $this->getId();

        if (empty($this->jsOptions['placeholder'])) {
            $this->jsOptions['placeholder'] = '请选择' . $this->getlabel();
        }

        if (isset($this->jsOptions['ajax'])) {
            $ajax = $this->jsOptions['ajax'];
            unset($this->jsOptions['ajax']);
            $url = $ajax['url'];
            $id = $ajax['id'] ?: '_';
            $text = $ajax['text'] ?: '_';
            $delay = !empty($ajax['delay']) ? $ajax['delay'] : 250;
            $loadmore = $ajax['loadmore'];

            $configs = json_encode($this->jsOptions);

            $configs = substr($configs, 1, strlen($configs) - 2);

            $prev_id = isset($this->jsOptions['prev_id']) ? $this->jsOptions['prev_id'] : '';

            $key = preg_replace('/\W/', '', $selectId);

            $script = <<<EOT

        var init{$key} = function()
        {
            $('#{$selectId}').addClass('select2-use-ajax').select2({
            {$configs},
            ajax: {
                url: '{$url}',
                dataType: 'json',
                delay: {$delay},
                data: function (params) {
                    var prev_val = '{$prev_id}' ? $('#{$prev_id}').val() : '';
                    return {
                        q: params.term,
                        page: params.page || 1,
                        prev_val : prev_val,
                        ele_id : '{$selectId}',
                        prev_ele_id : '{$prev_id}',
                        idField : '{$id}' == '_' ? null : '{$id}',
                        textField : '{$text}' == '_' ? null : '{$text}'
                    };
                },
                processResults: function (data, params) {
                    params.page = params.page || 1;
                    var list = data.data ? data.data : data;
                    return {
                        results: $.map(list, function (d) {
                                d.id = d.__id__ || d['{$id}'] || d.id;
                                d.text = d.__text__ || d['{$text}'] || d.text;
                                return d;
                                }),
                        pagination: {
                        more: {$loadmore} ? data.has_more : 0
                        }
                    };
                },
                cache: true
            },
            escapeMarkup: function (markup) {
                return markup;
            }
            });
        }

        var selected = $('#{$selectId}').data('selected');
        if(selected)
        {
            $.ajax({
                url: '{$url}',
                data: {selected : selected},
                type: 'POST',
                dataType: 'json',
                success: function (data) {
                    var list = (data.data ? data.data : data) || [];
                    var d = null;
                    for(var i in list)
                    {
                        d = list[i];
                        $('#{$selectId}').append('<option selected value="' + (d.__id__ || d['{$id}'] || d.id) + '">' + (d.__text__ || d['{$text}'] || d.text) + '</option>');
                    }
                    init{$key}();
                },
                error:function(){
                    $('#{$selectId}').data('selected','');
                    init{$key}();
                }
            });
        }
        else
        {
            init{$key}();
        }

EOT;
            $this->jsOptions['ajax'] = $ajax;
        } else {
            $configs = json_encode($this->jsOptions);

            $configs = substr($configs, 1, strlen($configs) - 2);

            $script = <<<EOT
            $('#{$selectId}').addClass('select2-no-ajax').select2({
                {$configs}
            });

EOT;
        }

        if (empty($prev)) {
            $this->script[] = $script;
        }

        return $script;
    }

    /*
    $form->select('province', '省份', 4)->dataUrl(url('province'))->withNext(
    $form->select('city', '城市', 4)->dataUrl(url('city'))->withNext(
    $form->select('area', '区域', 4)->dataUrl(url('area'))
    )
    );
     */

    /**
     * Undocumented function
     *
     * @param Select $nextSelect
     * @return $this
     */
    public function withNext($nextSelect)
    {
        $nextSelect->withPrev($this);
        return $this;
    }

    /**
     * Undocumented function
     *
     * @param Select $prevSelect
     * @return $this
     */
    public function withPrev($prevSelect)
    {
        $this->prevSelect = $prevSelect;

        return $this;
    }

    protected function withPrevScript()
    {
        $selectId = $this->getId();

        $prevId = $this->prevSelect->getId();

        $script = <<<EOT
        $(document).off('change', '#{$prevId}');
        $(document).on('change', "#{$prevId}", function () {
            $('#{$selectId}').find('option').remove();
            $('#{$selectId}').find('optgroup').remove();
            $('#{$selectId}').empty().append('<option value=""></option>').trigger('change');
        });

EOT;
        $this->script[] = $script;

        $this->jsOptions(['prev_id' => $prevId]);

        return $this;
    }

    protected function isGroup()
    {
        foreach ($this->options as $option) {

            if (isset($option['options']) && isset($option['label'])) {
                $this->group = true;
                break;
            }
        }

        return $this->group;
    }

    public function beforRender()
    {
        if ($this->select2) {
            if ($this->prevSelect) {
                $this->withPrevScript();
            }
            $this->select2Script();
        }

        return parent::beforRender();
    }

    public function render()
    {
        $vars = $this->commonVars();

        if (!($this->value === '' || $this->value === null)) {
            $this->checked = $this->value;
        } else {
            $this->checked = $this->default;
        }

        $this->isGroup();

        if (!$this->group && !isset($this->options[''])) {
            $this->options = ['' => $this->jsOptions['placeholder']] + $this->options;
        }

        $vars = array_merge($vars, [
            'checked' => '-' . $this->checked,
            'dataSelected' => $this->checked,
            'select2' => $this->select2,
            'group' => $this->group,
            'options' => $this->options,
        ]);

        $viewshow = $this->getViewInstance($vars);

        return $viewshow->render();
    }
}
