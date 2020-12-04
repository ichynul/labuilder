<?php

namespace Ichynul\Labuilder\Form;

use Ichynul\Labuilder\Common\Builder;
use Ichynul\Labuilder\Form\FieldsContent;
use Ichynul\Labuilder\Inface\Renderable;
use Ichynul\Labuilder\Traits\HasDom;
use Illuminate\Database\Eloquent\Model;

class Step implements Renderable
{
    use HasDom;

    private $view = '';

    protected $navigateable = true;

    protected $size = [2, 8];

    protected $rows = [];

    protected $labels = [];

    protected $active = '';

    protected $id = '';

    protected $mode = 'dots';

    /**
     * Undocumented variable
     *
     * @var array
     */
    protected $__fields_contents__ = [];

    public function getId()
    {
        if (empty($this->id)) {
            $this->id = 'step-' . mt_rand(1000, 9999);
        }

        return $this->id;
    }

    /**
     * Undocumented function
     *
     * @param string $label
     * @param boolean $isActive
     * @param string $name
     * @return FieldsContent
     */
    public function addFieldsContent($label, $description = '', $isActive = false, $name = '')
    {
        if (empty($name)) {
            $name = (count($this->labels) + 1);
        }

        if (count($this->labels) == 0) {
            $this->active = $name;
        }

        if ($isActive) {
            $this->actives[$name] = $name;
        }

        $content = new FieldsContent();
        $this->__fields_contents__[] = $content;

        $this->rows[$name] = ['content' => $content, 'description' => $description, 'active' => ''];
        $this->labels[$name] = ['content' => $label, 'active' => ''];

        return $content;
    }

    /**
     * Undocumented function
     *
     * @param array|Model $data
     * @return $this
     */
    public function fill($data = [])
    {
        foreach ($this->__fields_contents__ as $content) {
            $content->fill($data);
        }
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
        foreach ($this->__fields_contents__ as $content) {
            $content->readonly($val);
        }
        return $this;
    }

    /**
     * Undocumented function
     *
     * @param boolean $val
     * @return $this
     */
    public function navigateable($val)
    {
        $this->navigateable = $val;
        return $this;
    }

    /**
     * Undocumented function
     *
     * @param integer $left
     * @param integer $width
     * @return $this
     */
    public function size($left = 2, $width = 8)
    {
        $this->size = [$left, $width];
        return $this;
    }

    /**
     * Undocumented function
     *
     * @return $this
     */
    public function anchor()
    {
        $this->mode = 'anchor';
        return $this;
    }

    /**
     * Undocumented function
     *
     * @return $this
     */
    public function dots()
    {
        $this->mode = 'dots';
        return $this;
    }

    /**
     * Undocumented function
     *
     * @param string $val
     * @return $this
     */
    function class ($val)
    {
        $this->class = $val;
        return $this;
    }

    /**
     * Undocumented function
     *
     * @param string $val
     * @return $this
     */
    public function addClass($val)
    {
        $this->class .= ' ' . $val;
        return $this;
    }

    /**
     * Undocumented function
     *
     * @param string $val
     * @return $this
     */
    public function active($val)
    {
        $names = array_keys($this->labels);

        if (in_array($val, $names)) {
            $this->active = $val;
        }

        return $this;
    }

    protected function stepScript()
    {
        $id = $this->getId();
        $navigateable = $this->navigateable ? 'true' : 'false';

        $script = <<<EOT

        $('#{$id}').bootstrapWizard({
            'tabClass': 'nav-step',
            'nextSelector': '[data-wizard="next"]',
            'previousSelector': '[data-wizard="prev"]',
            'finishSelector': '[data-wizard="finish"]',
            'onTabClick': function(e, t, i) {
                return {$navigateable};
            },
            'onTabShow': function(e, t, i) {
                t.children(":gt(" + i + ").complete").removeClass("complete");
                t.children(":lt(" + i + "):not(.complete)").addClass("complete");
            },
            'onFinish': function(e, t, i) {
                // 点击完成后处理提交
            }
        });

EOT;
        Builder::getInstance()->addScript($script);

        return $script;
    }

    /**
     * Undocumented function
     *
     * @return string
     */
    public function getClass()
    {
        return empty($this->class) ? '' : ' ' . $this->class;
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
     * @return $this
     */
    public function beforRender()
    {
        foreach ($this->rows as $row) {
            $row['content']->beforRender();
        }

        Builder::getInstance()->addJs('/vendor/ichynul/labuilder/builder/js/jquery.bootstrap.wizard.min.js');

        $this->stepScript();

        return $this;
    }

    /**
     * Undocumented function
     *
     * @return mixed
     */
    public function render($partial = false)
    {
        $this->view = 'labuilder::form.step';

        $names = array_keys($this->labels);

        foreach ($names as $name) {
            if ($name == $this->active) {
                $this->labels[$name]['active'] = 'active';
                $this->rows[$name]['active'] = 'active';

                break;
            } else {
                $this->labels[$name]['active'] = 'complete';
                $this->rows[$name]['active'] = 'complete';
            }
        }

        $vars = [
            'labels' => $this->labels,
            'rows' => $this->rows,
            'active' => $this->active,
            'id' => $this->getId(),
            'class' => ($this->mode == 'anchor' ? 'step-anchor' : 'step-dots') . ' ' . $this->class,
            'mode' => $this->mode,
            'size' => $this->size,
            'attr' => $this->getAttrWithStyle(),
        ];

        $viewshow = view($this->view, $vars);

        return $viewshow->render();
    }
}
