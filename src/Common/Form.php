<?php

namespace Ichynul\Labuilder\Common;

use Ichynul\Labuilder\Common\Builder;
use Ichynul\Labuilder\Events\BuilderEvents;
use Ichynul\Labuilder\Form\FieldsContent;
use Ichynul\Labuilder\Form\Fillable;
use Ichynul\Labuilder\Form\FRow;
use Ichynul\Labuilder\Form\FWrapper;
use Ichynul\Labuilder\Form\ItemsContent;
use Ichynul\Labuilder\Form\Step;
use Ichynul\Labuilder\Inface\Renderable;
use Ichynul\Labuilder\Traits\HasDom;
use Illuminate\Database\Eloquent\Model;

/**
 * Form class
 */
class Form extends FWrapper implements Renderable
{
    use HasDom;

    protected $view = '';

    protected $action = '';

    protected $id = 'the-form';

    protected $method = 'post';

    protected $rows = [];

    protected $data = [];

    protected $botttomButtonsCalled = false;

    protected $ajax = true;

    protected $defaultDisplayerSize = null;

    protected $defaultDisplayerCloSize = 12;

    protected $validator = [];

    protected $butonsSizeClass = 'btn-sm';

    protected $readonly = false;

    protected $partial = false;

    /**
     * Undocumented variable
     *
     * @var Tab
     */
    protected $tab = null;

    /**
     * Undocumented variable
     *
     * @var Step
     */
    protected $step = null;

    /**
     * Undocumented variable
     *
     * @var FieldsContent
     */
    protected $__tabs_content__ = null;

    /**
     * Undocumented variable
     *
     * @var FieldsContent
     */
    protected $__fields__ = null;

    /**
     * Undocumented variable
     *
     * @var array
     */
    protected $__fields__bag__ = [];

    /**
     * Undocumented variable
     *
     * @var ItemsContent
     */
    protected $__items__ = null;

    public function __construct()
    {
        $this->class = 'form-horizontal';
    }

    /**
     * Undocumented function
     *
     * @param FRow|Fillable $row
     * @return $this
     */
    public function addRow($row)
    {
        $this->rows[] = $row;
        return $this;
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
     *
     * @param boolean $val
     * @return $this
     */
    public function readonly($val = true)
    {
        if ($val) {
            foreach ($this->rows as $row) {

                if ($row instanceof Tab || $row instanceof Step) {
                    $row->readonly($val);
                    continue;
                }

                if (!$row instanceof FRow) {
                    continue;
                }

                $row->getDisplayer()->readonly($val);
            }
        }

        $this->readonly = $val;

        return $this;
    }

    /**
     * Undocumented function
     *
     * @param boolean $val
     * @return $this
     */
    public function ajax($val)
    {
        $this->ajax = $val;
        return $this;
    }

    /**
     * Undocumented function
     *
     * @param string $val
     * @return $this
     */
    public function formId($val)
    {
        $this->id = $val;
        return $this;
    }

    /**
     * Undocumented function
     *
     * @return string
     */
    public function getFormId()
    {
        return $this->id;
    }

    /**
     * Undocumented function
     *
     * @param string $val
     * @return $this
     */
    public function action($val)
    {
        $this->action = $val;
        return $this;
    }

    /**
     * Undocumented function
     *
     * @param string $val
     * @return $this
     */
    public function method($val)
    {
        $this->method = $val;
        return $this;
    }

    /**
     * Undocumented function
     * btn-lg btn-sm btn-xs
     * @param string $val
     * @return $this
     */
    public function butonsSizeClass($val)
    {
        $this->butonsSizeClass = $val;
        return $this;
    }

    /**
     * Undocumented function
     *
     * @param boolean $val
     * @return $this
     */
    public function partial($val = true)
    {
        $this->partial = $val;
        return $this;
    }

    /**
     * Undocumented function
     *
     * @return Tab
     */
    public function getTab()
    {
        return $this->tab;
    }

    /**
     * Undocumented function
     *
     * @param string $label
     * @param boolean $active
     * @param string $name
     * @return Tab
     */
    public function tab($label, $active = false, $name = '')
    {
        if (empty($this->tab)) {
            $this->tab = new Tab();
            $this->rows[] = $this->tab;
        }

        $this->__tabs_content__ = $this->tab->addFieldsContent($label, $active, $name);
        $this->__tabs_content__->setForm($this);
        return $this->tab;
    }

    /**
     * Undocumented function
     *
     * @return Step
     */
    public function getStep()
    {
        return $this->step;
    }

    /**
     * Undocumented function
     *
     * @param string $label
     * @param string $description
     * @param boolean $active
     * @param string $name
     * @return Step
     */
    public function step($label, $description = '', $active = false, $name = '')
    {
        if (empty($this->step)) {
            $this->step = new Step();
            $this->rows[] = $this->step;
        }

        $this->__tabs_content__ = $this->step->addFieldsContent($label, $description, $active, $name);
        $this->__tabs_content__->setForm($this);
        return $this->step;
    }

    /**
     * Undocumented function
     *
     * @return FieldsContent
     */
    public function createFields()
    {
        if ($this->__fields__) {
            $this->__fields__bag__[] = $this->__fields__;
        }
        $this->__fields__ = new FieldsContent();
        $this->__fields__->setForm($this);
        return $this->__fields__;
    }

    /**
     * Undocumented function
     *
     * @return ItemsContent
     */
    public function createItems()
    {
        $this->__items__ = new ItemsContent();
        $this->__items__->setForm($this);
        return $this->__items__;
    }

    /**
     * Undocumented function
     *
     * @return $this
     */
    public function fieldsEnd()
    {
        $this->__fields__ = array_pop($this->__fields__bag__);
        return $this;
    }

    /**
     * Undocumented function
     *
     * @return $this
     */
    public function itemsEnd()
    {
        $this->__items__ = null;
        return $this;
    }

    /**
     * Undocumented function
     *
     * @return $this
     */
    public function tabEnd()
    {
        $this->__tabs_content__ = null;

        return $this;
    }

    /**
     * Undocumented function
     *
     * @return $this
     */
    public function stepEnd()
    {
        $this->__tabs_content__ = null;

        return $this;
    }

    /**
     * Undocumented function
     *
     * @return $this
     */
    public function allContentsEnd()
    {
        $this->__fields__ = null;
        $this->__tabs_content__ = null;
        $this->__items__content = null;
        return $this;
    }

    /**
     * Undocumented function
     *
     * @return FieldsContent
     */
    public function getTabsContent()
    {
        return $this->__tabs_content__;
    }

    /**
     * Undocumented function
     *
     * @param integer $label
     * @param integer $element
     * @return $this
     */
    public function defaultDisplayerSize($label = 2, $element = 8)
    {
        $this->defaultDisplayerSize = [$label, $element];
        return $this;
    }

    /**
     * Undocumented function
     *
     * @param integer $size
     * @return $this
     */
    public function defaultDisplayerCloSize($size = 12)
    {
        $this->defaultDisplayerCloSize = $size;
        return $this;
    }

    /**
     * Undocumented function
     *
     * @param array|Model $data
     * @return $this
     */
    public function fill($data = [])
    {
        $this->data = $data;

        return $this;
    }

    public function addJqValidatorRule($name, $rule, $val = true)
    {
        $this->validator[$name][$rule] = $val;
    }

    /**
     * Undocumented function
     *
     * @return array|Model
     */
    public function getData()
    {
        return $this->data;
    }

    /**
     * Undocumented function
     *
     * @param boolean $create
     * @return $this
     */
    public function bottomButtons($create = true)
    {
        if ($create) {
            if ($this->readonly) {
                $this->bottomOffset(5);
                $this->btnLayerClose('返&nbsp;&nbsp;回', 2);
            } else {
                $this->btnSubmit();
                $this->html('', '', '2 col-xs-2')->showLabel(false);
                $this->btnReset();
            }
        }

        $this->botttomButtonsCalled = true;
        return $this;
    }

    public function bottomOffset($offset = 4)
    {
        $this->allContentsEnd();
        $this->html('', '', 12)->value('<hr/>')->showLabel(false)->size(0, 12);
        $this->html('', '', $offset)->showLabel(false);
    }

    /**
     * Undocumented function
     *
     * @param string $label
     * @param integer $size
     * @param string $class
     * @return $this
     */
    public function btnSubmit($label = '提&nbsp;&nbsp;交', $size = '1 col-xs-2', $class = 'btn-success')
    {
        $this->bottomOffset();
        $this->button('submit', $label, $size)->class($class . ' ' . $this->butonsSizeClass);
        $this->botttomButtonsCalled = true;
        return $this;
    }

    /**
     * Undocumented function
     *
     * @param string $label
     * @param integer $size
     * @param string $class
     * @return $this
     */
    public function btnReset($label = '重&nbsp;&nbsp;置', $size = '1 col-xs-2', $class = 'btn-warning')
    {
        $this->button('reset', $label, $size)->class($class . ' ' . $this->butonsSizeClass);
        return $this;
    }

    /**
     * Undocumented function
     *
     * @param string $label
     * @param integer $size
     * @param string $class
     * @param string $attr
     * @return $this
     */
    public function btnBack($label = '返&nbsp;&nbsp;回', $size = '1 col-xs-2', $class = 'btn-default btn-go-back', $attr = 'onclick="history.go(-1);')
    {
        $this->button('button', $label, $size)->class($class . ' ' . $this->butonsSizeClass)->attr($attr);
        return $this;
    }

    /**
     * Undocumented function
     *
     * @param string $label
     * @param integer $size
     * @param string $class
     * @return $this
     */
    public function btnLayerClose($label = '返&nbsp;&nbsp;回', $size = '1 col-xs-2', $class = 'btn-default')
    {
        $this->button('button', $label, $size)->class($class . ' btn-close-layer' . ' ' . $this->butonsSizeClass);
        return $this;
    }

    /**
     * Undocumented function
     *
     * @return $this
     */
    public function beforRender()
    {
        event(new BuilderEvents('tpext_form_befor_render', $this));

        if (!$this->botttomButtonsCalled && empty($this->step)) {
            $this->bottomButtons(true);
        }
        foreach ($this->rows as $row) {
            $row->fill($this->data);

            if (!$row instanceof FRow) {
                $row->beforRender();
                continue;
            }

            $displayer = $row->getDisplayer();

            if ($displayer->isRequired()) {
                $this->validator[$displayer->getName()]['required'] = true;
            }

            $row->beforRender();
        }

        $this->validatorScript();

        if (!in_array(strtolower($this->method), ['get', 'post'])) {
            $this->hidden('_method')->value($this->method);
            $this->method = 'post';
        }

        Builder::getInstance()->getCsrfToken();

        return $this;
    }

    protected function validatorScript()
    {
        $form = $this->getFormId();

        $rules = json_encode($this->validator);

        $script = <<<EOT

        window.focus();

        $(document).bind('keyup', function(event) {
            if (event.keyCode === 0x1B) {
                var index = layer.msg('关闭当前弹窗？', {
                    time: 2000,
                    btn: ['确定', '取消'],
                    yes: function (params) {
                        layer.close(index);
                        var index2 = parent.layer.getFrameIndex(window.name);
                        parent.layer.close(index2);
                    }
                });
                return false; //阻止系统默认esc事件
            }
        });

        $('#{$form} form').validate({
            ignore: ".ignore",    // 插件默认不验证隐藏元素,这里可以自定义一个不验证的class,即验证隐藏元素,不验证class为.ignore的元素
            focusInvalid: false,  // 禁用无效元素的聚焦
            rules: {$rules},
            errorPlacement: function errorPlacement(error, element) {
                $('#help-block').removeClass('hidden');
                var parent = $(element).closest('div.form-group');
                if($(element).hasClass('item-field'))
                {
                    $('#help-block .error-label').html(parent.find('.control-label,.full-label').text() + $(element).data('label') + '* 这是必填字段');
                    $(element).closest('td').addClass('has-error');
                    return;
                }
                parent.addClass('has-error');
                $('#help-block .error-label').html(parent.find('.control-label,.full-label').text() + '* ' + error.text());
            },
            highlight: function(element) {
                var el = $(element);
                if (el.hasClass('js-tags-input')) {
                    el.next('.tagsinput').addClass('is-invalid');  // tags插件所隐藏的输入框没法实时验证，比较尴尬
                }
            },
            unhighlight: function(element) {
                $(element).next('.tagsinput').removeClass('is-invalid');
                if($(element).hasClass('item-field'))
                {
                    $(element).closest('td').removeClass('has-error');
                }
                $(element).closest('div.form-group').removeClass('has-error');
                if($('.form-group.has-error').size() == 0 && $('.item-field.has-error').size() == 0)
                {
                    $('#help-block .error-label').html('');
                }
            },
            submitHandler: function(form) {
                return window.__forms__['{$form}'].formSubmit();
            }
        });

EOT;
        Builder::getInstance()->addScript($script);

        return $script;
    }

    /**
     * Undocumented function
     *
     * @return string|mixed
     */
    public function render()
    {
        $template = 'labuilder::form';

        $vars = [
            'rows' => $this->rows,
            'action' => $this->action,
            'method' => strtoupper($this->method),
            'class' => $this->class,
            'attr' => $this->getAttrWithStyle(),
            'id' => $this->getFormId(),
            'ajax' => $this->ajax ? 1 : 0,
        ];

        $viewshow = view($template, $vars);

        if ($this->partial) {
            return $viewshow;
        }

        return $viewshow->render();
    }

    public function __toString()
    {
        $this->partial = false;
        return $this->render();
    }

    public function __call($name, $arguments)
    {
        $count = count($arguments);

        if ($count > 0 && static::isDisplayer($name)) {

            $row = new FRow($arguments[0], $count > 1 ? $arguments[1] : '', $count > 2 ? $arguments[2] : ($name == 'button' ? 1 : $this->defaultDisplayerCloSize));

            if ($this->__fields__) {

                $this->__fields__->addRow($row);
            } else if ($this->__items__) {

                $row->class('text-center');
                $this->__items__->addCol($arguments[0], $row);
            } else if ($this->__tabs_content__) {

                $this->__tabs_content__->addRow($row);
            } else {

                $this->rows[] = $row;
            }

            $row->setForm($this);

            $displayer = $row->$name($arguments[0], $row->getLabel());

            if ($this->defaultDisplayerSize) {
                $displayer->size($this->defaultDisplayerSize[0], $this->defaultDisplayerSize[1]);
            }

            if ($name == 'button') {
                $displayer->extKey('-' . $this->id . mt_rand(10, 99));
            }

            return $displayer;
        }

        throw new \UnexpectedValueException('未知调用:' . $name);
    }
}
