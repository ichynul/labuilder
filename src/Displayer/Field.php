<?php

namespace Ichynul\Labuilder\Displayer;

use Ichynul\Labuilder\Common\Builder;
use Ichynul\Labuilder\Common\Wrapper;
use Ichynul\Labuilder\Events\BuilderEvents;
use Ichynul\Labuilder\Form\Fillable;
use Ichynul\Labuilder\Traits\HasDom;
use Illuminate\Database\Eloquent\Model;
use Ichynul\Labuilder\Logic\Url;

/**
 * Field class
 */
class Field implements Fillable
{
    use HasDom;

    protected $extKey = '';

    protected $extNameKey = '';

    protected $name = '';

    protected $label = '';

    protected $js = [];

    protected $css = [];

    protected $stylesheet = '';

    protected $script = [];

    protected $view = 'field';

    protected $value = '';

    protected $default = '';

    protected $icon = '';

    protected $autoPost = '';

    protected $autoPostRefresh = false;

    protected $showLabel = true;

    protected $labelClass = '';

    protected $labelArrt = '';

    protected $errorClass = '';

    protected $error = '';

    protected $size = [2, 8];

    protected $help = '';

    protected $readonly = '';

    protected $disabled = '';

    protected $wrapper = null;

    protected $useDefauleFieldClass = true;

    protected static $helptempl = 'labuilder::displayer.helptempl';

    protected static $labeltempl = 'labuilder::displayer.labeltempl';

    protected $mapClass = [];

    protected $required = false;

    protected $minify = true;

    protected $arrayName = false;

    protected $to = '';

    protected $data = [];

    public function __construct($name, $label = '')
    {
        $this->name = trim($name);
        if (empty($label) && !empty($this->name)) {
            $label = __(ucfirst($this->name));
        }

        $this->label = $label;
    }

    /**
     * Undocumented function
     *
     * @param string $fieldType
     * @return $this
     */
    public function created($fieldType = '')
    {
        $fieldType = $fieldType ? $fieldType : get_called_class();

        $defaultClass = Wrapper::hasDefaultFieldClass($fieldType);

        if (!empty($defaultClass)) {
            $this->class = $defaultClass;
        }

        event(new BuilderEvents('tpext_displayer_created', $this));

        return $this;
    }

    /**
     * Undocumented function
     *
     * @return string
     */
    public function getId()
    {
        return 'form-' . preg_replace('/\W/', '', $this->name) . $this->extKey;
    }

    /**
     * Undocumented function
     *
     * @return string
     */
    public function getName()
    {
        if ($this->arrayName) {
            return $this->arrayName[0] . $this->name . $this->arrayName[1];
        }

        return $this->name . $this->extNameKey;
    }

    /**
     * Undocumented function
     *
     * @param array $val
     * @return $this
     */
    public function arrayName($val)
    {
        $this->arrayName = $val;
        return $this;
    }

    /**
     * Undocumented function
     *
     * @return string
     */
    public function getLabel()
    {
        return $this->label;
    }

    /**
     * Undocumented function
     *
     * @param string $val
     * @return $this
     */
    public function extKey($val)
    {
        $this->extKey = $val;
        return $this;
    }

    /**
     * Undocumented function
     *
     * @param string $val
     * @return $this
     */
    public function extNameKey($val)
    {
        $this->extNameKey = $val;
        return $this;
    }

    /**
     * Undocumented function
     *
     * @param string $url
     * @param boolean $refresh
     * @return $this
     */
    public function autoPost($url = '', $refresh = false)
    {
        if (empty($url)) {
            $url = Url::action('autopost');
        }
        $this->autoPost = $url;
        $this->autoPostRefresh = $refresh;
        return $this;
    }

    /**
     * Undocumented function
     *
     * @param string $val
     * @return $this
     */
    public function value($val)
    {
        if (is_array($val)) {
            $val = implode(',', $val);
        }
        $this->value = $val;
        return $this;
    }

    /**
     * Undocumented function
     *
     * @param string $val
     * @return $this
     */
    public function to($val)
    {
        $this->to = $val;
        return $this;
    }

    /**
     * Undocumented function
     *
     * @param string|int|mixed $val
     * @return $this
     */
    function
default($val = '') {
        $this->default = $val;
        return $this;
    }

    /**
     * Undocumented function
     *
     * @param string $val
     * @return $this
     */
    public function name($val)
    {
        $this->name = $val;
        return $this;
    }

    /**
     * Undocumented function
     *
     * @param string $val
     * @return $this
     */
    public function label($val)
    {
        $this->label = $val;
        return $this;
    }

    /**
     * Undocumented function
     *
     * @param string $val
     * @return $this
     */
    public function labelClass($val)
    {
        $this->labelClass = $val;
        return $this;
    }

    /**
     * Undocumented function
     *
     * @param string $val
     * @return $this
     */
    public function labelAttr($val)
    {
        $this->labelAttr = $val;
        return $this;
    }

    /**
     * Undocumented function
     *
     * @param array $val
     * @return $this
     */
    public function size($label = 2, $element = 8)
    {
        $this->size = [$label, $element];
        return $this;
    }

    /**
     * Undocumented function
     * @example 1 [int] 4 => class="col-md-4"
     * @example 2 [string] '4 xls-4' => class="col-md-4 xls-4"
     *
     * @param int|string $val
     * @return $this
     */
    public function cloSize($val)
    {
        $this->getWrapper()->cloSize($val);
        return $this;
    }

    /**
     * Undocumented function
     *
     * @param string $val
     * @return $this
     */
    public function help($val)
    {
        $this->help = $val;
        return $this;
    }

    /**
     * Undocumented function
     *
     * @param string $val
     * @return $this
     */
    public function error($val)
    {
        $this->error = $val;
        $this->errorClass($val ? 'has-error' : '');
        return $this;
    }

    /**
     * Undocumented function
     *
     * @param string $val
     * @return $this
     */
    public function errorClass($val)
    {
        $this->errorClass = $val;
        $this->wrapper->errorClass($val);
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
        $this->readonly = $val;
        return $this;
    }

    /**
     * Undocumented function
     *
     * @param boolean $val
     * @return $this
     */
    public function disabled($val = true)
    {
        $this->disabled = $val;
        return $this;
    }

    /**
     * Undocumented function
     *
     * @param boolean $val
     * @return $this
     */
    public function required($val = true)
    {
        $this->required = $val;
        return $this;
    }

    /**
     * Undocumented function
     *
     * @param boolean $val
     * @return $this
     */
    public function showLabel($val)
    {
        $this->showLabel = $val;
        return $this;
    }

    /**
     * Undocumented function
     *
     * @return boolean
     */
    public function isShowLabel()
    {
        return $this->showLabel;
    }

    /**
     * Undocumented function
     *
     * @return array
     */
    public function getSize()
    {
        return $this->size;
    }

    /**
     * Undocumented function
     *
     * @return boolean
     */
    public function isRequired()
    {
        return $this->required;
    }

    /**
     * Undocumented function
     *
     * @return boolean
     */
    public function canMinify()
    {
        return $this->minify;
    }

    /**
     * Undocumented function
     *
     * @param boolean $val
     * @return $this
     */
    public function useDefauleFieldClass($val)
    {
        $this->useDefauleFieldClass = $val;
        return $this;
    }

    /**
     * Undocumented function
     *
     * @param \Ichynul\Labuilder\Form\FRow|\Ichynul\Labuilder\Search\SRow|\Ichynul\Labuilder\Table\TColumn $wrapper
     * @return $this
     */
    public function setWrapper($wrapper)
    {
        $this->wrapper = $wrapper;
        return $this;
    }

    /**
     * Undocumented function
     *
     * @return \Ichynul\Labuilder\Form\FRow|\Ichynul\Labuilder\Search\SRow|\Ichynul\Labuilder\Table\TColumn
     */
    public function getWrapper()
    {
        return $this->wrapper;
    }

    /**
     * Undocumented function
     *
     * @return string
     */
    public function getLabelClass()
    {
        return $this->labelClass;
    }

    /**
     * Undocumented function
     *
     * @return string
     */
    public function getLabelAttr()
    {
        return $this->labelArrt;
    }

    /**
     * Undocumented function
     *
     * @return string
     */
    public function getError()
    {
        return $this->error;
    }

    /**
     * Undocumented function
     *
     * @return string
     */
    protected function getValue()
    {
        return $this->value;
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
    public function getCss()
    {
        return $this->css;
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
     * @return $this
     */
    public function clearScript()
    {
        $this->script = [];
        return $this;
    }

    /**
     * Undocumented function
     *
     * @param integer $labelMin
     * @return $this
     */
    public function fullSize($labelMin = 3)
    {
        if (empty($this->size) || (is_numeric($this->size[0]) && is_numeric($this->size[1]))) {

            $this->size = [$labelMin, 12 - $labelMin];
        }

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
        if (!empty($this->name) && isset($data[$this->name])) {
            $value = $data[$this->name];

            if (is_array($value)) {
                $value = implode(',', $value);
            }

            $this->value = $value;
        }

        $this->data = $data;

        return $this;
    }

    /**
     * Undocumented function
     *
     * @param string $event
     * @return $this
     */
    public function trigger($event)
    {
        $fieldId = $this->getId();

        $script = <<<EOT

        $('#{$fieldId}').trigger('{$event}');

EOT;
        $this->script[] = $script;
        return $this;
    }

    /**
     * Undocumented function
     *
     * @param string $script
     * @return $this
     */
    public function addScript($script)
    {
        $this->script[] = $script;
        return $this;
    }

    /**
     * Undocumented function
     *
     * @param array|string|int $values
     * @param string $class
     * @param string $field default current field
     * @param string $logic in_array|not_in_array|eq|gt|lt|egt|elt|strpos|not_strpos
     * @return $this
     */
    public function mapClass($values, $class, $field = '', $logic = 'in_array')
    {
        if (empty($field)) {
            $field = $this->name;
        }

        if (!is_array($values)) {
            $values = [$values];
        }

        $this->mapClass[] = [$values, $class, $field, $logic];
        return $this;
    }

    /**
     * 弃用，使用mapClass
     *
     * @param array|string|int $values
     * @param string $class
     * @param string $field
     * @param string $logic
     * @return $this
     */
    public function mapClassWhen($values, $class, $field = '', $logic = 'in_array')
    {
        return $this->mapClass($values, $class, $field, $logic);
    }

    /**
     * Undocumented function
     *
     * @param array $groupArr
     * @return $this
     */
    public function mapClassGroup($groupArr)
    {
        foreach ($groupArr as $g) {
            $values = $g[0];
            $class = $g[1];
            $field = isset($g[2]) ? $g[2] : '';
            $logic = isset($g[3]) ? $g[3] : '';
            $this->mapClass($values, $class, $field, $logic);
        }

        return $this;
    }

    /**
     * 弃用，使用mapClassGroup
     *
     * @param array $groupArr
     * @return $this
     */
    public function mapClassWhenGroup($groupArr)
    {
        return $this->mapClassGroup($groupArr);
    }

    /**
     * Undocumented function
     *
     * @return mixed
     */
    public function render()
    {
        $vars = $this->commonVars();

        $viewshow = $this->getViewInstance($vars);

        return $viewshow->render();
    }

    public function __toString()
    {
        return $this->render();
    }

    protected function getViewInstance($vars = [])
    {
        $template = 'labuilder::displayer.' . $this->view;

        $viewshow = view($template, $vars);

        return $viewshow;
    }

    protected function autoPostScript()
    {
        $class = 'row-' . $this->name . '-td';

        $refresh = $this->autoPostRefresh ? 1 : 0;

        $script = <<<EOT

        labuilder.autoPost('{$class}', '{$this->autoPost}' ,{$refresh});

EOT;
        $this->script[] = $script;

        return $script;
    }

    public function beforRender()
    {
        if (!empty($this->js)) {
            if ($this->minify) {
                Builder::getInstance()->addJs($this->js);
            } else {
                Builder::getInstance()->customJs($this->js);
            }
        }

        if (!empty($this->css)) {
            Builder::getInstance()->addCss($this->css);
        }

        if ($this->autoPost) {

            if (Builder::checkUrl($this->autoPost)) {
                $this->autoPostScript();
            } else {
                $this->readonly();
            }
        }

        if (!empty($this->script)) {
            Builder::getInstance()->addScript($this->script);
        }

        if (!empty($this->stylesheet)) {
            Builder::getInstance()->addStyleSheet($this->stylesheet);
        }

        event(new BuilderEvents('tpext_displayer_befor_render', $this));

        return $this;
    }

    /**
     * Undocumented function
     *
     * @return string
     */
    public function renderValue()
    {
        if (is_array($this->default)) {
            $this->default = implode(',', $this->default);
        } else if ($this->default === true) {
            $this->default = 1;
        } else if ($this->default === false) {
            $this->default = 0;
        }

        if ($this->value === true) {
            $this->value = 1;
        } else if ($this->value === false) {
            $this->value = 0;
        }

        $value = !($this->value === '' || $this->value === null) ? $this->value : $this->default;

        if (!empty($this->to)) {
            $value = $this->parshToValue($value);
        }

        return $value;
    }

    protected function parshToValue($value)
    {
        $data = $this->data instanceof Model ? $this->data->toArray() : $this->data;

        $keys = ['{val}'];
        $replace = [$value];

        foreach ($data as $key => $val) {
            $keys[] = '{' . $key . '}';
            $replace[] = $val;
        }

        return str_replace($keys, $replace, $this->to);
    }

    protected function parshMapClass()
    {
        $mapClass = [];

        if (!empty($this->mapClass)) {

            foreach ($this->mapClass as $mp) {
                $values = $mp[0];
                $class = $mp[1];
                $field = $mp[2];
                $logic = $mp[3]; //in_array|not_in_array|eq|gt|lt|egt|elt|strpos|not_strpos
                $val = '';
                if (!isset($this->data[$field])) {
                    continue;
                }
                $val = $this->data[$field];
                $match = false;
                if ($logic == 'not_in_array') {
                    $match = !in_array($val, $values);
                } else if ($logic == 'eq') {
                    $match = $val == $values[0];
                } else if ($logic == 'gt') {
                    $match = is_numeric($values[0]) && $val > $values[0];
                } else if ($logic == 'lt') {
                    $match = is_numeric($values[0]) && $val < $values[0];
                } else if ($logic == 'egt') {
                    $match = is_numeric($values[0]) && $val >= $values[0];
                } else if ($logic == 'elt') {
                    $match = is_numeric($values[0]) && $val <= $values[0];
                } else if ($logic == 'strpos') {
                    $match = strpos($values[0], $val);
                } else if ($logic == 'not_strpos') {
                    $match = !strpos($values[0], $val);
                } else //default in_array
                {
                    $match = in_array($val, $values);
                }
                if ($match) {
                    $mapClass[] = $class;
                }
            }
        }

        if (count($mapClass)) {

            return ' ' . implode(' ', array_unique($mapClass));
        }

        return '';
    }

    /**
     * Undocumented function
     *
     * @return array
     */
    public function commonVars()
    {
        $mapClass = $this->parshMapClass();

        $value = $this->renderValue();

        $extendAttr = ($this->isRequired() ? ' required="true"' : '') . ($this->disabled ? ' disabled' : '') . ($this->readonly ? ' readonly onclick="return false;"' : '');

        $this->size = [$this->size[0] . ' col-xs-12', $this->size[1] . ' col-xs-12'];

        $vars = [
            'id' => $this->getId(),
            'label' => $this->label,
            'name' => $this->getName(),
            'requiredStyle' => $this->required ? '' : 'style="display: none;"',
            'extKey' => $this->extKey,
            'extNameKey' => $this->extNameKey,
            'value' => $value,
            'class' => ' row-' . $this->name . $this->getClass() . $mapClass,
            'attr' => $this->getAttrWithStyle() . $extendAttr,
            'error' => $this->error,
            'size' => $this->size,
            'labelClass' => $this->size[0] < 12 ? $this->labelClass . ' control-label' : $this->labelClass . ' full-label',
            'labelAttr' => empty($this->labelAttr) ? '' : ' ' . $this->labelAttr,
            'help' => $this->help,
            'showLabel' => $this->showLabel,
            'helptempl' => static::$helptempl,
            'labeltempl' => static::$labeltempl,
            'readonly' => $this->readonly,
            'disabled' => $this->disabled,
        ];

        $customVars = $this->customVars();

        if (!empty($customVars)) {
            $vars = array_merge($vars, $customVars);
        }

        return $vars;
    }

    /**
     * Undocumented function
     *
     * @return array
     */
    public function customVars()
    {
        return [];
    }
}
